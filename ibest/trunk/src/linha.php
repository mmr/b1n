<?
/* $Id: linha.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */
/* Linha com exemplo do content managenment */

if ( get_magic_quotes_gpc() || get_magic_quotes_runtime() )
    die( "Para o correto funcionamento desta aplicaçao e necessario desligar magic_quote_gpc e magic_quote_runtime do PHP" );

define( 'INCPATH',      'include' );

require_once( INCPATH . '/debug.inc.php' );             /* biblioteca para debug.           */
require_once( INCPATH . '/sql_link.inc.php' );          /* biblioteca para uso do BD.       */
require_once( INCPATH . '/trata_dados.inc.php' );       /* funcoes de tratamento de dados   */
require_once( INCPATH . '/funcoes.inc.php' );

/* Conecta no banco */
$sql = new sqlLink( "ibest",  INCPATH . "/sql_conf.inc.php" );
?>

<table width=145 border=0 cellspacing=0 cellpadding=0>
    <tr>
        <td height=3></td>
    </tr>
<?
mostraDestaques( $sql, "home" );
?>
</table>
