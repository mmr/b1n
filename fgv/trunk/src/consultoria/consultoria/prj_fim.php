<? /* $Id: prj_fim.php,v 1.1 2002/04/26 16:00:43 binary Exp $ */ ?>
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
    <input type="hidden" name="status"      value="<?= CST_PROJETO_FINALIZADO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROJETO_FINALIZADO ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Projeto Finalizado
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data de T�rmino do Projeto</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("cst_dt_prj_fim", $dados["cst_dt_prj_fim"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Coment�rios</td> 
              <td bgcolor='#ffffff'><textarea name="com_texto" cols="65" rows="15"><?= htmlspecialchars($dados["com_texto"]) ?></textarea></td>
            </tr>
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Upload de Documento ESC</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Descri��o do Arquivo</td>
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
