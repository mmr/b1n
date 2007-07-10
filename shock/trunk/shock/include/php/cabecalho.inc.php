<html>
<head>
	<title>: ShockNet : <?= ucwords(($sn_inc) .  ($sn_acao ? " - $sn_acao" : "")) ?></title>
	<link rel='stylesheet' href='<?= INC_PATH_CSS ?>/estilo.css'>
</head>
<body bgcolor='#ffffff' text='#000000'>
<?
if($sn_inc && $sn_inc != "menu")
{
	$menu  = implode("",@file(INC_PATH_PHP . "/menu.inc.php"));
	$menu  = str_replace("<br>","&nbsp;&nbsp;&nbsp;",$menu);
	$menu  = str_replace("<?= \$PHP_SELF ?>","$PHP_SELF",$menu);
	$menu .= "<hr>";
	print $menu;
}
?>
<h2>: ShockNet : <?= ucwords(($sn_inc) . ($sn_acao ? " - $sn_acao" : "")) ?></h2>
