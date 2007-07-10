<? /* $Id: feedback.php,v 1.2 2002/06/21 13:57:51 binary Exp $ */ ?>
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
    <input type="hidden" name="status"      value="<?= CST_FEEDBACK ?>" />
    <input type="hidden" name="cst_status"  value="<?= $dados["cst_status"] ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Registrar Resposta do Cliente
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' colspan='2'>
                <input type='radio' name='cst_feedback' value='stand by'<?= ( ($dados["cst_feedback"] == "stand by") ? " checked" : "" ) ?>> Ausência de Retorno do Cliente - Permanecer em Stand By
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' colspan='2'>
                <input type='radio' name='cst_feedback' value='negativo'<?= ( ($dados["cst_feedback"] == "negativo") ? " checked" : "" ) ?>> Retorno Negativo do Cliente
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' colspan='2'>
                <input type='radio' name='cst_feedback' value='positivo'<?= ( ($dados["cst_feedback"] == "positivo") ? " checked" : "" ) ?>> Retorno Positivo do Cliente - Gerar Contrato
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Observações</td> 
              <td bgcolor='#ffffff'><textarea name="com_texto" cols="65" rows="15"><?= htmlspecialchars($dados["com_texto"]) ?></textarea></td>
            </tr>
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Upload de Arquivo</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Descrição do Arquivo</td>
              <td bgcolor='#ffffff'><input type="text" name="arq_texto" value="<?= in_html($dados["arq_texto"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Arquivo para Upload</td>
              <td bgcolor='#ffffff' class="textb">
                <input type="file" name="arq" />
                <?
                if( consis_inteiro( $dados[ "arq_id" ] ) && $dados[ "arq_nome_falso" ] != "" )
                {
                ?>
                    <br />
                    Aquivo Atual: 
                    <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'arq_id' ] . "&tabela=arquivo&col_id=arq_id&arq_col_r=arq_nome_real&arq_col_f=arq_nome_falso" ?>'><?= $dados[ 'arq_nome_falso' ] ?></a>
                    <br />
                <?
                }
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;OK&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=consultoria&cst_id=" . $dados["cst_id"] ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</form>
  </table>
  <br />
  <br />
</center>
