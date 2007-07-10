<?
/* $Id: index.php,v 1.11 2002/07/30 13:36:43 binary Exp $ */

switch ($pagina)
{
case "eventos":
case "patrocinadores":
case "palestrantes":
case "tipos_evento":
case "criterios":
case "categorias":
case "logos":
case "brindes":
case "tipos_convidado":
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
      <table border="0" width="100%" cellspacing="0" cellpadding="0"><br>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=brindes">Brindes</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=categorias">Categorias</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=criterios">Critérios</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=eventos">Eventos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=logos">Logos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=palestrantes">Palestrantes</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=patrocinadores">Patrocinadores</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=tipos_convidado">Tipos Convidado</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=tipos_evento">Tipos Evento</a></td>
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
