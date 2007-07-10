<?

function execute_command( $sql, $command )
{
    $ret = array( false, "" );

    switch( $command )
    {
    case QUEUE_CMD_CREATE_SECRET;
        $ret = create_secret( $sql );
        break;
    default:
        $ret = array( false, "Command not implemented: '" . $command . "'" );
    }

    return $ret;
}


/* Commands */
function create_secret( $sql )
{
    $ret = array( false, "" );
    $err = array( );
    $output = "";

    // Geting data from DB
    $query = "SELECT dev_address, ppt_login, ppt_passwd FROM pptp NATURAL JOIN device";

    if( $data = $sql->query( $query ) )
    {
        if( is_array( $data ) )
        {
            foreach( $data as $d )
                $output .= $d[ 'dev_address' ] . "\t" . $d[ 'ppt_login' ] . "\t" . $d[ 'ppt_passwd' ] . "\n";

            clearstatcache( );
            if( is_writable( dirname( PPT_FILE ) ) || ( ( file_exists( PPTP_FILE ) && is_writable( PPTP_FILE ) ) ) ) 
            {
                $fp = fopen( PPTP_FILE, "w" );

                if( $fp )
                {
                    set_file_buffer( $fp, 0 );

                    if( ! fputs( $fp, $output ) )
                        array_push( $err, "Can't write to file. Verify if the disk is not full." );

                    fclose( $fp );
                }
                else
                    array_push( $err, "The File Pointer is invalid." );
            }
            else
                array_push( $err, "Can't open file '" . PPTP_FILE . "' for writing. Verify permissions/owner." );
        }
    }
    else
        array_push( $err, "Can't get data from pptp table." );

    if( ! sizeof( $err ) )
        return array( true );

    $tmp = "";
    foreach( $err as $e )
        $tmp .= "\n" . $e; 

    return array( false, $tmp );    
}
?>
