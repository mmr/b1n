<?
require(KOEWY_MODULES_DIR."/wacc/lib/consts.php");
require(MODULE_LIB_DIR."/messages.php");
require(KOEWY_LIB_DIR."/utils.php");
require(KOEWY_LIB_DIR."/daemon.php");
require (KOEWY_LIB_DIR."/errors.php");

$menu_opcs  = array('menu'	=> 'menu',
		'produto'	=> 'produto',
		'consumo'	=> 'consumo',
		'baixa'		=> 'baixa',
		'relatorio'	=> 'relatorio',
		'config'	=> 'config'
		);

$menu_opc = $menu_opcs[$sn_inc] ? $menu_opcs[$sn_inc] : 'consumo';

?>
<table>
<tr><td><img src="images/pixel_div.gif"  height="3" width="550"></td></tr>
<tr><td class='text'><?=MSG_DESC_SN?></td></tr>
<tr><td><img src="images/pixel_div.gif"  height="3" width="550"></td></tr>
</table>
<?
/* Include do Menu */
include(INC_PATH_PHP . "/menu.inc.php");

/* Include do JavaScript */
include(INC_PATH_JS  . "/form.js");

/* Include da opcao */
include(INC_PATH_PHP . "/" .  $menu_opc . ".inc.php");
?>
