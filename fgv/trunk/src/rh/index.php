<?
/* $Id: index.php,v 1.13 2002/07/30 13:41:36 binary Exp $ */

switch ($pagina)
{
case "membros":
case "processo_seletivo":
	$inc = $suppagina . "/" . $pagina . "/index.php";
	break;
default:
    $inc = $suppagina . "/default.php";
    break;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" bgcolor="#336699" valign="top" height="<?= ALTURA_PADRAO ?>">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#336699">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=membros">Membros</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=processo_seletivo">Processo Seletivo</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
    </td>
    <td bgcolor="#ffffff" valign="top" height='<?= ALTURA_PADRAO ?>'>
<?
if(isset($inc))
{
    include($inc);
    unset($inc);
}
?>
    </td>
  </tr>
</table>
