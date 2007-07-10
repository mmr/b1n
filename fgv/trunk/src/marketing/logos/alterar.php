<? /* $Id: alterar.php,v 1.2 2002/04/16 12:42:48 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype='multipart/form-data'>
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina    ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>" />
            <input type="hidden" name="acao"        value="go" />
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom">&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="lgo_nome" value="<?= in_html($dados["lgo_nome"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Descrição</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="lgo_desc" value="<?= in_html($dados["lgo_desc"]) ?>" size="30" /></td>
            </tr>

            <tr>
              <td bgcolor='#ffffff' class="textb">Arquivo para Upload</td>
              <td bgcolor='#ffffff' class="textb">
                <input type="file" name="lgo_arq" />
                <?
                if( consis_inteiro( $dados[ 'id' ] ) && $dados[ 'lgo_nome_falso' ] != '' )
                        print "<br />Arquivo Atual:  <a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'id' ] . "&tabela=logo&col_id=lgo_id&arq_col_r=lgo_nome_real&arq_col_f=lgo_nome_falso '>" . $dados[ 'lgo_nome_falso' ] . "</a><br />";
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='textb'>
                <input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />
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
