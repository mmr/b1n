<?
/* $Id: debug.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function msex_r( $var, $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $endline = "<br>", $tabr = "" )
{
    if( !is_array( $var ) )
    {
        print( "[" . $var . "]");
        return;
    }

    $tabaux = $tab . $tabr;

    print( "Array ( " . $endline );

    foreach( $var as $key => $value )
    {
	print( $tabaux . "[" . $key . "] => " );
	msex_r( $value, $tab, $endline, $tabaux );
	print( $endline );
    }
    print( $tabr . ")" );
}

function pq( $query, $indent = 1 )
{
    if( $query != "" )
        print "<div style='background-color: #7799bb;'><font color='#ffffff'>Query: " . ( $indent ? nl2br( str_replace( " ", "&nbsp;", $query ) ) : $query ) . "</font></div>"; 
}
?>
