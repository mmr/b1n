<?
if(get_magic_quotes_gpc()||get_magic_quotes_runtime())
	die("Para o correto funcionamento desta aplicação e necessário desligar magic_quote_gpc e magic_quote_runtime do PHP (php.ini)");

define(INC_PATH_PHP, "./include/php");
define(INC_PATH_JS, "./include/js");
define(INC_PATH_CSS, "./include/css");
define(PAG_INC,  30);

require(INC_PATH_PHP . "/sqlLink.inc.php");
$sql = new sqlLink("wacc", INC_PATH_PHP . "/sqlconf.inc.php");
?>
