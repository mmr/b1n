#!/usr/bin/perl

#TODO: fechar rede 10.1 somente p/ as ng's, ou algo melhor
#TODO: colocar distinct no view "rules"

use DBI;
use strict;

# DATABASE ###############################################
my $db = "airgate";
my $db_user = "pgsql";
my $db_pass ="";

# configs ################################################
my $ipf_zero = '/sbin/ipf -z -f -';
my $ipf_command = '/sbin/ipf -Fa -f -';
my $ipnat_command = '/sbin/ipnat -C -f -';
my $status_rules  = '/sbin/ipfstat -ionh';
my $status_acc    = '/sbin/ipfstat -ionha';
my $status_nat    = '/sbin/ipnat -lh';

# debug ##################################################
if($ENV{'DEBUG'}) {
    $ipf_zero      = '/bin/cat -n';
    $ipf_command   = '/bin/cat -n';
    $ipnat_command = '/bin/cat -n';
    $status_rules  = 'echo ">> '.$status_rules.'"';
    $status_acc    = 'echo ">> '.$status_acc.'"'; 
    $status_nat    = 'echo ">> '.$status_nat.'"';
}

##########################################################
sub account {
    my $dbh = shift();
    my @out = `$status_acc`;
    my %acc;
    my @a;
    foreach(@out) {
        chomp();
        @a=split(/ /);
        if($a[4] eq 'out') {
            $acc{$a[10]}{'out'}{'bytes'}+=$a[1];
            $acc{$a[10]}{'out'}{'packs'}+=$a[0];
        } elsif($a[4] eq 'in') {
            $acc{$a[8]}{'in'}{'bytes'}+=$a[1];
            $acc{$a[8]}{'in'}{'packs'}+=$a[0];
        } else {
            #TODO: tratar erro bizarro
            printf STDERR "unknown rule, accounting errors may occur\n";
            printf STDERR "$_\n";
        }

    }
    my $key;
    my $sth  = $dbh->prepare("UPDATE account SET ".
        "acc_b_in = acc_b_in + ?, ".
        "acc_b_out = acc_b_out + ?, ".
        "acc_p_in = acc_p_in + ?, ".
        "acc_p_out = acc_p_out + ? ".
        "WHERE acc_address = ? AND acc_date = 'TODAY'");
    my $sth2 = $dbh->prepare("INSERT INTO account (acc_b_in, ".
                "acc_b_out,acc_p_in,acc_p_out,acc_address)
                values(?,?,?,?,?)");

    foreach $key (keys %acc) {
        $sth->execute($acc{$key}{'in'}{'bytes'},
                $acc{$key}{'out'}{'bytes'}, $acc{$key}{'in'}{'packs'}, 
                $acc{$key}{'out'}{'packs'}, $key ) or 
                print $dbh->errstr."\n";
        if(!$sth->rows) {
            $sth2->execute($acc{$key}{'in'}{'bytes'},
                    $acc{$key}{'out'}{'bytes'}, $acc{$key}{'in'}{'packs'}, 
                    $acc{$key}{'out'}{'packs'}, $key ) or 
                    print $dbh->errstr."\n";
        }
    }
    open IPF,"|".$ipf_zero or die $!;
    print IPF account_rules($dbh);
    close IPF;
}

sub connecta() {
    my $dbh = DBI->connect("dbi:Pg:dbname=$db", "$db_user", "$db_pass")
        or die "could not connect -- $DBI::errorstr\n";
    return $dbh;
}

sub nat_rules {
    my @if = ('fxp0', 'fxp1', 'fxp2');
    my $dbh = shift();
    my $sth = $dbh->prepare('SELECT dev_address,dev_nat FROM device WHERE dev_nat IS NOT NULL AND dvt_id = \'WLES\'');
    my $rule = '';
    $sth->execute();
    while( my @row = $sth->fetchrow_array ) {
        for(my $i=0; $i<@if; $i++) {
            my $if = $if[$i];
            $row[0].="/32" if(!($row[0]=~/\//));
            $row[1].="/32" if(!($row[1]=~/\//));
            $rule .= "bimap $if $row[0] -> $row[1]\n";
        }
    }
    return $rule
}

sub account_rules {
    my @if = ('fxp0', 'fxp1', 'fxp2');
    my $dbh = shift();
    my $sth = $dbh->prepare('SELECT dev_address,dev_if FROM device WHERE dvt_id = \'WLES\'');
    my $rule = '';
    $sth->execute();
    while( my @row = $sth->fetchrow_array ) {
        $rule .= "count in  on $row[1] from $row[0] to any\n";
        $rule .= "count out on $row[1] from any     to $row[0]\n";
    }
    return $rule;
}

sub filter_rules {
    my $dbh = shift();
    my $sth = $dbh->prepare('SELECT from_addr, to_addr, from_if, to_if FROM rules');
    my $rule = '';
    $sth->execute();
    while( my @row = $sth->fetchrow_array ) {
	if(! $row[2] =~ /^ng/) {
	    $row[2] = " on ".$row[2];
	} else {
	    $row[2] = "";
	}
	if(! $row[3] =~ /^ng/) {
	    $row[3] = " on ".$row[3];
	} else {
	    $row[3] = "";
	}
	$rule .= "pass in quick $row[2] from $row[0] to $row[1]\n";
	$rule .= "pass in quick $row[3] from $row[1] to $row[0]\n";
    }
    return $rule;
}

# main ###################################################

if($ARGV[0] eq '-a') {
    my $dbh = connecta();
    account($dbh);
    $dbh->disconnect();
} elsif($ARGV[0] eq '-r') {
    my $dbh = connecta();


    #############
    ## account
    account($dbh);

    #############
    ## filters
    open IPF,"|".$ipf_command or die $!;
    print IPF <<EOF
pass in quick on lo
pass out quick on lo
pass out quick proto tcp  all flags S/SA keep state
pass out quick proto tcp  all
pass out quick proto udp  all keep state
pass out quick proto icmp all keep state
pass in  quick from 200.228.204.205 to 200.215.182.212
pass in  quick from 200.246.138.5 to 200.215.182.212
pass in  quick on fxp0 proto tcp from any to 200.215.182.212 port = 1723
pass in  quick on fxp0 proto gre from any to 200.215.182.212
EOF
;
    print IPF account_rules($dbh);
    print IPF filter_rules($dbh);
    print IPF "block in quick all\n";
    close IPF;


    #############
    ## NAT
    open IPNAT,"|".$ipnat_command or die $!;
    print IPNAT "map   fxp1 0.0.0.0/0     -> 192.168.18.42/32\n";
    print IPNAT nat_rules($dbh);
    close IPNAT;
    $dbh->disconnect();

} elsif($ARGV[0] eq '-s') {
    print "** rules\n";
    system($status_rules);
    print "\n** account\n";
    system($status_acc);
    print "\n** NAT\n";
    system($status_nat);
} else {
    print <<EOF;
* AirGate GW control
* Aberium Systems

usage: $0 [-r|-a|-s]
   -r   reset rules
   -a   account packs
   -s   status

EOF
;
}
