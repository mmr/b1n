<?
/* $Id: inserir_cobranca.php,v 1.1 2002/04/26 16:00:42 binary Exp $ */
?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="inserir" />
            <input type="hidden" name="tipo_inserir" value="cobranca" />
            <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
            <input type="hidden" name="ppg_id"      value="<?= $dados["ppg_id"] ?>" />
            <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
            <input type="hidden" name="cst_status"  value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
            <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Cobrança
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Vencimento</td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<? gera_select_data( "cob_dt_venc", $dados["cob_dt_venc"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>N&ordm; Parcela</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="cob_parcela" value="<?= in_html($dados["cob_parcela"]) ?>" size="3" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nota Fiscal</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="cob_nota" value="<?= in_html($dados["cob_nota"]) ?>" size="4" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb' colspan='2'><input type='checkbox' class='caixa' name='cob_pago' value='1'<? if( $dados['cob_pago'] == 1 ) print ' checked'; ?> />Pagamento Efetuado</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb' colspan='2'><input type='checkbox' class='caixa' name='cob_protocolo' value='1'<? if( $dados['cob_protocolo'] == 1 ) print ' checked'; ?> />Procolo Assinado</td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&cst_id=" . $dados["cst_id"] . "&status=" . urlencode(CST_PROJETO_EM_ANDAMENTO) ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>
  </form>
  <br />
  <br />
</center>
