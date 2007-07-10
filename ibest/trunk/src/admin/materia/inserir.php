<? /* $Id: inserir.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */ ?>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="acao"        value="go" />
            <input type="hidden" name="mat_id"      value="<?= $dados[ 'mat_id' ] ?>" />
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
              <td bgcolor='#ffffff' class='textb'>* Título</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="mat_titulo" value="<?= in_html($dados["mat_titulo"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Olho</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="mat_olho" value="<?= in_html($dados["mat_olho"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Modo</td>
              <td bgcolor='#ffffff' class='text'>
                <input type="radio" class='caixa' name="mat_modo" value='0'<? if( $dados[ 'mat_modo' ] == 0 ) print ' checked'; ?> /> HTML
                <input type="radio" class='caixa' name="mat_modo" value='1'<? if( $dados[ 'mat_modo' ] == 1 ) print ' checked'; ?> /> Texto Puro
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Texto</td>
              <td bgcolor='#ffffff' class='text'>
                <textarea  name='mat_texto' rows='7' cols='30' wrap='virtual'><?= $dados[ 'mat_texto' ] ?></textarea>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Palavra Chave</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="mat_pal_chave" value="<?= in_html($dados["mat_pal_chave"]) ?>" size="30" /> * separadas por espaço</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fonte</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="mat_fonte" value="<?= in_html($dados["mat_fonte"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Status</td>
              <td bgcolor='#ffffff' class='text'>
              <select name='mat_status'>
                <option <? if( $dados[ 'mat_status' ] == 'Preview' ) print 'selected' ?>>Preview</option>
                <option <? if( $dados[ 'mat_status' ] == 'Ativa' ) print 'selected' ?>>Ativa</option>
                <option <? if( $dados[ 'mat_status' ] == 'Desativa' ) print 'selected' ?>>Desativa</option>
              </td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
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
