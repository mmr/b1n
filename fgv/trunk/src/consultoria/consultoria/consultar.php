<? /* $Id: consultar.php,v 1.1 2002/04/26 16:00:37 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="cst_id"      value="<?= in_html($dados["cst_id"]) ?>" />
            <input type="hidden" name="tipo_apagar" value="<?= $tipo_apagar ?>" />
            <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cliente</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cli_id", "cli_nome", "cliente", $dados["cli_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome da Consultoria</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cst_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["cst_dt_contato"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Prazo para retorno</td>
              <td bgcolor='#ffffff'><?= in_html($dados["cst_dt_retorno_u"]) ?> dias úteis</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data de retorno</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["cst_dt_retorno"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Problema apresentado</td>
              <td bgcolor='#ffffff'><textarea name="cst_texto" cols="65" rows="15" disabled><?= htmlspecialchars($dados["cst_texto"]) ?></textarea></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb" colspan="2" align="center">Status da Consultoria: <?= in_html(ucwords($dados["cst_status"])) ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
</center>
