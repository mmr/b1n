<?
/* $Id: apagar_cobranca.php,v 1.1 2002/04/26 16:00:37 binary Exp $ */
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
            <input type="hidden" name="subpagina"   value="apagar" />
            <input type="hidden" name="tipo_apagar" value="cobranca" />
            <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
            <input type="hidden" name="ppg_id"      value="<?= $dados["ppg_id"] ?>" />
            <input type="hidden" name="cob_id"      value="<?= $dados["cob_id"] ?>" />
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
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["cob_dt_venc"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>N&ordm; Parcela</td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= in_html($dados["cob_parcela"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nota Fiscal</td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= in_html($dados["cob_nota"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Pagamento Efetuado</td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= ( ( $dados['cob_pago'] == 1 ) ? "Sim" : "Não" ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Protocolo Assinado</td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= ( ( $dados['cob_protocolo'] == 1 ) ? "Sim" : "Não" ) ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&cst_id=" . $dados["cst_id"] . "&status=" . urlencode(CST_PROJETO_EM_ANDAMENTO) ?>'" />
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
