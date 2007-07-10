<?
/* $Id: debug.inc.php,v 1.8 2003/05/06 01:36:24 mmr Exp $ */

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
        print "<br><div style='background-color: #7799bb;'><font color='#ffffff'>Query: " . ( $indent ? nl2br( str_replace( " ", "&nbsp;", $query ) ) : $query ) . "</font></div>"; 
}
?>
