#!/usr/bin/perl
# $Id: cron.pl,v 1.1.1.1 2002/07/18 22:23:42 binary Exp $

use DBI;
use File::Temp qw/ tempfile /;

use strict;

my $QUEUE_DONE = 1;
my $PPTP_DIR = "/usr/local/etc/mpd";
my $PPTP_FILE  = $PPTP_DIR."/mpd.secret";
my $QUEUE_CMD_CREATE_SECRET =  "pptp_create_secret";

# DATABASE ###############################################
my $db = "airgate2";
my $db_user = "pgsql";
my $db_pass ="";
my $db_host ="127.0.0.1";

sub exec_command {
    my $dbh = shift();
    my $command = shift();
    if($command eq $QUEUE_CMD_CREATE_SECRET) { 
        my ($fh, $filename) = tempfile("tempfileXXXXXX", DIR => $PPTP_DIR);
        my $query = "SELECT dev_address, ppt_login, ppt_passwd FROM pptp NATURAL JOIN device";
        my $sth = $dbh->prepare($query);
        $sth->execute();
        while(my @row = $sth->fetchrow_array ) {
            print($fh $row[1]."\t\"".$row[2]."\"\t".$row[0]."\n");
        }
        close($fh);
        if(!rename($filename, $PPTP_FILE)) {
            print STDERR "$!\n";
            unlink $filename;
            return 0;
        }
        else {
            return 1;
        }
    } else {
        print STDERR "unknown command\n";
    }
}
        
sub connecta() {
    my $dbh = DBI->connect("dbi:Pg:dbname=$db; host=$db_host", 
        "$db_user", "$db_pass", { RaiseError => 1, AutoCommit => 0 })
        or die "could not connect -- $DBI::errorstr\n";
    return $dbh;
}

my $dbh = connecta();

my $query = "SELECT DISTINCT que_command FROM queue WHERE que_done != '" . $QUEUE_DONE . "' GROUP BY que_command";
my $sth = $dbh->prepare($query);
my $sth2;
$sth->execute();

while(my @row = $sth->fetchrow_array) {
    #este update deve acontecer antes da execucao do comando
    $query = "UPDATE queue SET que_done = '$QUEUE_DONE' where que_command=? and que_done != '$QUEUE_DONE' ";
    $sth2 = $dbh->prepare($query);
    $sth2->execute($row[0]) or print STDERR $!;
    $sth2->finish();
    if(exec_command($dbh,$row[0])==1) {
        $dbh->commit();
    } else {
        $dbh->rollback();
        print(STDERR "exec FAILED ($row[0])\n");
    }

}
$dbh->disconnect();
#
