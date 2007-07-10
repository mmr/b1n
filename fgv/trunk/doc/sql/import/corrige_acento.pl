#!/usr/bin/perl

use strict;

foreach $_ ( @ARGV )
{
    if( -r $_ )
    {
        &parseia( $_ );
    }
    else
    {
        print "\n\033[0;31mArquivo '\033[1;31m" . $_ . "\033[0;31m' não existe!\n\033[0m";
    }
}

if( -f $ARGV[ 0 ] )
{
    &parseia;
}
else
{
    &uso;
}

sub parseia( )
{
    my ( $arquivo ) = shift( );
    my ( @tmp, $feito, $i );

    open( FD, $arquivo );
    my @cont = <FD>;    
    close( FD );

    # Pular primeira linha (cabecalho)
    shift( @cont );

    $i = 1;
    $feito = '';
    foreach $_ ( @cont )
    {
        # Tratando acentuacao zoada
        $_ =~ y/þÒ¶Ýß+ÛÚ¾ÓÔ·/çãôíáÉêéóâú/; 
        $feito .= $_;
    }

    open( FD, ">" . $arquivo . ".b1n" );
    print FD $feito;
    close( FD );
}

sub uso( )
{
    print "Usage: " . $0 . " file1 [ file2 ... ]\n\n"
}
