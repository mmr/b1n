<?
require("./include/php/main.inc.php");

$menu_opcs  = array('menu'	=> 'menu',
		'maquina'	=> 'maquina',
		'produto'	=> 'produto',
		'usuario'	=> 'usuario',
		'consumo'	=> 'consumo',
		'baixa'		=> 'baixa' );

$menu_opc = $menu_opcs[$sn_inc] ? $menu_opcs[$sn_inc] : 'menu';

include(INC_PATH_PHP . "/cabecalho.inc.php");
include(INC_PATH_PHP . "/" .  $menu_opc . ".inc.php");
include(INC_PATH_PHP . "/rodape.inc.php");
?>
