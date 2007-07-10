<?
/* $Id: index.php,v 1.16 2002/08/05 13:30:21 binary Exp $ */

switch ($pagina)
{

case "areas":
case "atividades":
case "aviso_auto":
case "backup":
case "cargos_ej":
case "cargos_ext":
case "classificacoes":
case "feriados":
case "grupos":
case "planos_pagamento":
case "ramos":
case "regioes":
case "setores":
case "status_contato":
case "status_evento":
case "status_task":
case "subatividades":
case "tipos_servico":
case "tipos_task":
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
          <td bgcolor="#336699" valign="top">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=areas">Áreas</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=atividades">Atividades</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=aviso_auto">Avisos Automáticos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=backup">Backup</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=cargos_ej">Cargos EJ</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=cargos_ext">Cargos Externos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=classificacoes">Classificações</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=feriados">Feriados</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=grupos">Grupos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699" valign="top">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=planos_pagamento">Planos Pagamento</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=ramos">Ramos</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=regioes">Regiões</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=setores">Setores</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=status_contato">Status Contato</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=status_evento">Status Cronograma</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=status_task">Status Tasks</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=subatividades">SubAtividades</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=tipos_servico">Tipos Serviço</a></td>
        </tr>
        <tr>
          <td bgcolor="#336699">&nbsp;<a class="lmenu" href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=tipos_task">Tipos Task</a></td>
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
