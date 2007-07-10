<? /* $Id: apagar_atividade.php,v 1.1 2002/04/26 16:00:37 binary Exp $ */ ?>

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
            <input type="hidden" name="tipo_apagar" value="<?= $tipo_apagar ?>" />
            <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
            <input type="hidden" name="atv_id"      value="<?= $dados["atv_id"] ?>" />
            <input type="hidden" name="cst_status"  value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />
            <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo . " - Apagar Atividade" ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ordem</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["atv_ordem"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Atividade</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["atv_desc"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Início</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["atv_dt_ini"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Fim (dias úteis)</td>
              <td bgcolor='#ffffff'><?= in_html($dados["atv_dt_fim_u"]) ?> dias úteis</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Início</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["atv_dt_fim"]); ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&cst_id=" . $dados["cst_id"] . "&status=" . urlencode(CST_PROPOSTA_EM_ANDAMENTO) ?>'" />
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
