#!/usr/bin/php

<?  
    define( "LIBPATH", "../web/lib" );
    define( "INCPATH", "." );

    require( LIBPATH . "/utils.inc.php" ); 
    require( LIBPATH . "/sql_link.inc.php" ); 
    require( LIBPATH . "/main.inc.php" ); 

    require( INCPATH . "/func.inc.php" ); 

    $sql = new sqlLink( "airgate", LIBPATH . "/sql_conf.inc.php" );
    $err = array( );

    /* Geting data from DB */
    if( $sql->query( "BEGIN TRANSACTION" ) )
    {
        $query = "SELECT DISTINCT que_command FROM queue WHERE que_done != '" . QUEUE_DONE . "'";

        if( $data = $sql->query( $query ) )
        {
            if( is_array( $data ) )
            {
                foreach( $data as $d )
                {
                    $ret = execute_command( $sql, $d[ 'que_command' ] );

                    if( $ret[ 0 ] )
                    {
                        if( ! $sql->query( "UPDATE queue SET que_done = '1' WHERE que_command = '" . $d[ 'que_command' ] . "'" ) )
                            array_push( $err, "Can't update queue for Id '" . $d[ 'que_id' ] . "'" );
                    }
                    else
                        if( $ret[ 1 ] != "" )
                            array_push( $err, $ret[ 1 ] );
                }
            }
        }
        else
            array_push( $err, "Can't get data from pptp table." );

        if( ! sizeof( $err ) )
        {
            if( $sql->query( "COMMIT TRANSACTION" ) )
            {
                print "\nOperation finished succesfully.\n\n\n";
                return true;
            }
            else
                print "Can't Commit transaction.";
        }
        else
        {
            if( ! $sql->query( "ROLLBACK TRANSACTION" ) )
                array_push( $err, "Can't Rollback transaction." );

            print "\nErrors: ";
            for( $i=0; $i<sizeof( $err )-1; $i++ )
                print "\n" . ($i+1) . " - " . $err[ $i ];
        }
    }
    else
        print "\nCan't begin transaction";

    print "\n";
    return false;
?>
