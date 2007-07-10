<?  
    define( "LIBPATH", "./lib" );
    define( "INCPATH", "./include" );

    require( LIBPATH . "/utils.inc.php"); 
    require( LIBPATH . "/sql_link.inc.php"); 
    require( LIBPATH . "/debug.inc.php"); 
    require( LIBPATH . "/main.inc.php"); 
    $sql = new sqlLink("airgate", LIBPATH . "/sql_conf.inc.php");
?>
<html>
<head>
    <title>Airgate</title>
</head>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="style/style.css">
<body link="#000000">
<!-- HEADER -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="100%" height="60" bgcolor="#000000"><img src="images/logo_airgate.gif" alt="" width="161" height="49" border="0"></td>
    </tr>
    <tr>
        <td width="100%" height="7" bgcolor="#999933"></td>
    </tr>
</table>
<!-- FIM  HEADER -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="140" valign="top"><br>
                
                 <? include( INCPATH . "/menu.php");  ?>
        
        </td>
        
        <td align="center" valign="top">
<?
    extract_request_var("page", $page, "1");
    extract_request_var("item", $item, "");

    $back_page = $_SERVER[ 'SCRIPT_NAME' ] . "?item=" . $item;

    switch($item) 
    {
    case 'acl':
    case 'company':
    case 'group':
    case 'device':
        include( INCPATH . "/" .$item.".php"); 
        break;                                                
        /*
    default:
        print "<br>default</b>";
        break;
        */
    }

?>

<br><br>
        </td>
    </tr>
</table>
<!-- RODAPE -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="100%" height="5" bgcolor="#999933"><img src="images/trans.gif" alt="" width="1" height="5" border="0"></td>
    </tr>
    <tr>
        <td width="100%" height="20" bgcolor="#000000"><img src="images/trans.gif" alt="" width="1" height="20" border="0"></td>
    </tr>    
</table>
<!-- FIM RODAPE -->
<div align=left>
<hr>
REQUEST DATA DEBUG:<br>
<?=msex_r($_REQUEST)?>
</div>
</body>
</html>
