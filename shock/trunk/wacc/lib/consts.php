<?

/** considera declaradas e setadas as seguintes constantes:
 * KOEWY_MODULES_DIR Ex.: "/koewy/modules"
 * KOEWY_HTML_DIR    Ex.: "/koewy/html"
 * e a funcao create_error_msg(array())
 */


#errors
define(MODULE_NAME, "wacc");

define(MODULE_DIR,      KOEWY_MODULES_DIR."/".MODULE_NAME);
define(MODULE_HTML_DIR, KOEWY_HTML_DIR."/".MODULE_NAME);
define(MODULE_LIB_DIR,  MODULE_DIR."/lib");
define(MODULE_CONF_DIR, MODULE_DIR."/conf");

if(get_magic_quotes_gpc()||get_magic_quotes_runtime())
	die("Para o correto funcionamento desta aplicação e necessário desligar magic_quote_gpc e magic_quote_runtime do PHP (php.ini)");

define(INC_PATH_PHP, MODULE_LIB_DIR."/php");
define(INC_PATH_JS,  MODULE_LIB_DIR."/js");
define(PAG_INC,  30);

require(KOEWY_LIB_DIR . "/sqlLink.inc.php");
$sql = new sqlLink("wacc", KOEWY_LIB_DIR . "/sqlconf.inc.php");
?>
