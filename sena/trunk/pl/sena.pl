#!/usr/bin/perl -w

use DBI;
use strict;
use constant b1n_ARQ    => 'sorteios';
use constant b1n_DEBUG  => 1;

sub b1n_connect();

my $i = 0;  # Contador
my @d = (0,0,0,0,0,0); # Dezenas
my $data = ''; # Data do sorteio
my $query;

open(FD, b1n_ARQ);
my @dados = <FD>;
close(FD);

my $sql  = b1n_connect();

foreach (@dados){
  chomp;
  if(/(\d{2})\/(\d{2})\/(\d{4})/){
    if($data ne ''){
      $query = "INSERT INTO sena (data,d1,d2,d3,d4,d5,d6) VALUES ('$data',".$d[0].",".$d[1].",".$d[2].",".$d[3].",".$d[4].",".$d[5].")";
      print "$query\n";
      $sql->prepare($query)->execute() or die("Erro $data\n");
    }
    $data = "$3-$2-$1";
    $i = 0;
  }
  else {
    $d[$i] = $_;
    $i++;
  }
}

if($data ne ''){
  $query = "INSERT INTO sena (data,d1,d2,d3,d4,d5,d6) VALUES ('$data',".$d[0].",".$d[1].",".$d[2].",".$d[3].",".$d[4].",".$d[5].")";
  $sql->prepare($query)->execute() or die("Erro $data\n");
}
$sql->commit();
$sql->disconnect();

sub b1n_connect()
{
  my $b1n_DB_NAME = "sena";
  my $b1n_DB_USER = "sena";
  my $b1n_DB_PASS = "senpass";
  my $b1n_DB_HOST = "/var/www/tmp";

  my $link = 
    DBI->connect(
      "dbi:Pg:dbname=$b1n_DB_NAME; host=$b1n_DB_HOST", 
      "$b1n_DB_USER", "$b1n_DB_PASS", 
      {RaiseError => 1, AutoCommit => 0}) or die "could not connect\n";
  return $link;
}
