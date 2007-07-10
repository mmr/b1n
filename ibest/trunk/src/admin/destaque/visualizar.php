<? /* $Id: visualizar.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */ ?>

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="acao"        value="go" />
            <input type="hidden" name="mat_id"      value="<?= $dados[ 'mat_id' ] ?>" />
            <input type="hidden" name="des_id"      value="<?= $dados[ 'des_id' ] ?>" />
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
              <td bgcolor='#ffffff' class='textb'>Destaque</td>
              <td bgcolor='#ffffff' class='text'><?= in_html($dados["des_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Matérias</td>
              <td bgcolor='#ffffff' class='text'>
                <?
                if( sizeof( $dados[ 'materias' ][ 'mat_id' ] ) )
                {
                    /*
                    print "<select name='materias' multiple size='10'>";

                    foreach( $dados[ 'materias' ] as $mat )
                        print "<option value='" . $mat[ 'mat_id' ] . "'>" .  $mat[ 'mat_titulo' ] . "</option>";

                    print "</select>";    
                    */
                    print "<li><a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=materia&subpagina=destacar&mat_id=" . $dados[ 'materias' ][ 'mat_id' ][ 0 ] . "'>" . $dados[ 'materias' ][ 'mat_titulo' ][ 0 ] ."</a>";

                    for( $i=1; $i<sizeof( $dados[ 'materias' ][ 'mat_id' ] ); $i++ )
                        print "</li><li><a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=materia&subpagina=destacar&mat_id=" . $dados[ 'materias' ][ 'mat_id' ][ $i ] . "'>" . $dados[ 'materias' ][ 'mat_titulo' ][ $i ] ."</a>";
                }
                else
                    print "Não há matérias destacadas nessa área";
                ?>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <!--<input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />-->
                <input type="button" value="Voltar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
