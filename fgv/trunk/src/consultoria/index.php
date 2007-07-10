<?
/* $Id: index.php,v 1.11 2002/08/05 13:30:21 binary Exp $ */

switch ($pagina)
{
case "clientes":
case "consultoria":
case "departamentos":
case "professores":
case "tipos_projeto":
	$inc = $suppagina . "/" . $pagina . "/index.php";
	break;
default:
    $inc = $suppagina . "/default.php";
    break;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" bgcolor="#336699" valign="top" height='<?= ALTURA_PADRAO ?>'><br>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=clientes">Clientes</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=consultoria">Consultoria</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699" valign="top">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=departamentos">Departamentos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=professores">Professores</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=tipos_projeto">Tipos Projeto</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td bgcolor="#ffffff" valign="top" height='<?= ALTURA_PADRAO ?>'><img src="images/trans.gif" width="1" height="20"  />
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
