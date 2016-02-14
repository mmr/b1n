<?
/* $Id: add.php,v 1.3 2002/12/19 05:04:56 binary Exp $ */ 
$colspan = 3;
$title_msg = "Do not forget adding 'http://' in the beginning of external links.";

$reg_config['Name']['extra']['options'] = b1n_regPossibleSyslink($sql);

require(b1n_REGINCPATH . "/add.inc.php");
?>
