#!/usr/bin/perl

#
# $Id: cron.pl,v 1.1 2002/12/19 00:06:05 binary Exp $
#
# By Marcio Ribeiro (marcio@b1n.org)
#

use DBI;
use strict;

sub b1n_connect()
{
    my $b1n_DB_NAME = "flywatch";
    my $b1n_DB_USER = "flywatch";
    my $b1n_DB_PASS = "";
    my $b1n_DB_HOST = "127.0.0.1";

    my $link = 
        DBI->connect(
            "dbi:Pg:dbname=$b1n_DB_NAME; host=$b1n_DB_HOST", 
            "$b1n_DB_USER", "$b1n_DB_PASS", 
            { RaiseError => 1, AutoCommit => 1 }) or die "could not connect -- $DBI::errorstr\n";
    return $link;
}

my $link  = b1n_connect();
my $query = "DELETE FROM \"user\" WHERE usr_expire_dt IS NOT NULL AND usr_expire_dt <= CURRENT_TIMESTAMP";
my $ret = $link->prepare($query)->execute();
$link->disconnect();
exit($ret);
