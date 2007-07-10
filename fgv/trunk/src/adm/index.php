<?
/* $Id: index.php,v 1.14 2002/07/30 13:13:33 binary Exp $ */

switch ($pagina)
{
case "funcionarios_gv":
case "empresas_juniores":
case "fornecedores":
case "ferramentas":
case "projetos_internos":
case "alunos_gv":
case "etiquetas":
    $inc = $suppagina . "/" . $pagina . "/index.php";
    break;
default:
    $inc = $suppagina . "/default.php";
    break;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" bgcolor="#336699" valign="top" height='<?= ALTURA_PADRAO ?>'><BR>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=alunos_gv">Alunos GV</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=empresas_juniores">Empresas Juniores</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=etiquetas">Etiquetas</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=ferramentas">Ferramentas</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=fornecedores">Fornecedores</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=funcionarios_gv">Funcionários GV</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=projetos_internos">Projetos Internos</a></td>
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
