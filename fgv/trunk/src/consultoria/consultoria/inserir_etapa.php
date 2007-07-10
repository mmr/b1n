<? /* $Id: inserir_etapa.php,v 1.1 2002/04/26 16:00:42 binary Exp $ */ ?>

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
            <input type="hidden" name="tipo_inserir" value="<?= $tipo_inserir ?>" />
            <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
            <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
            <input type="hidden" name="cst_status"  value="<?= $dados["cst_status"] ?>" />
            <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Inserir Etapa
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ordem</td>
              <td bgcolor='#ffffff'><input type="text" name="etp_ordem" value="<?= in_html($dados["etp_ordem"]) ?>" size="3" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Etapa</td>
              <td bgcolor='#ffffff'><input type="text" name="etp_desc" value="<?= in_html($dados["etp_desc"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Início</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("etp_dt_ini", $dados["etp_dt_ini"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Fim</td>
              <td bgcolor='#ffffff'><input type="text" name="etp_dt_fim_u" value="<?= in_html($dados["etp_dt_fim_u"]) ?>" size="3" /> Dias úteis</td>
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
