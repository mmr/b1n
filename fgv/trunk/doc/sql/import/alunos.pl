#!/usr/bin/perl

use strict;

if( -f $ARGV[ 0 ] && $ARGV[ 1 ] )
{
    &parseia;
}
else
{
    &uso;
}

sub parseia( )
{
    my ( $arquivo, $campos, $tabela, $nome_campos, $campos_que_importam ) = ( @ARGV );
    my ( @tmp, $query, $i, $aux, @aux );

    open( FD, $arquivo );
    my @cont = <FD>;    
    close( FD );

    $i = 1;
    my @campos_que_importam = split( " ", $campos_que_importam ); 

    foreach $_ ( @cont )
    {
        @tmp = split( ";", $_ );
        
        # Vendo se tem a quantidade de campos esperada
        if( $#tmp == $campos )
        {
            # Montando a Query
            $query = "INSERT INTO " . $tabela . " ( " . $nome_campos . " ) VALUES ( ";
            foreach $aux ( @campos_que_importam )
            {
                $tmp[ $aux ] =~ s/^['"]//;
                $tmp[ $aux ] =~ s/['"]$//;
                $tmp[ $aux ] =~ s/'/\\'/g;
                $tmp[ $aux ] =~ s/"/\\"/g;

                # Tratamento pra data de nascimento
                if( $aux == 22 && $tmp[ $aux ] ne '' )
                {
                    @aux = split( "\/", $tmp[ $aux ] ); 
                    $tmp[ $aux ] = substr( $aux[ 2 ], 0, 4 ) . "-" . $aux[ 1 ] . "-" . $aux[ 0 ];
                }

                $query .= "'" . $tmp[ $aux ] . "', ";
            }
            $query =~ s/''/NULL/g;
            $query = substr( $query, 0, length( $query ) - 2 ) . " );\n";

            print $query . "\n";
        }
        else
        {
            print "WARNING: Line " . $i . " is broken!\n";
            die( $#tmp );
        }
        $i++;
    }
}

sub uso( )
{
    print "Usage: " . $0 . " file fields table fields_name fields_that_matter.\n\n"
}
