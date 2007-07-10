<? /* $Id: destacar.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */ ?>

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
              <td bgcolor='#ffffff' class='textb'>Título</td>
              <td bgcolor='#ffffff' class='text'><?= in_html($dados["mat_titulo"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Olho</td>
              <td bgcolor='#ffffff' class='text'><?= in_html($dados["mat_olho"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Modo</td>
              <td bgcolor='#ffffff' class='text'><?= ( ( $dados[ 'mat_modo' ] == 0 ) ? "HTML" : "Texto Puro" ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Texto</td>
              <td bgcolor='#ffffff' class='text'>
                <?= in_html( $dados[ 'mat_texto' ] ) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Palavra Chave</td>
              <td bgcolor='#ffffff' class='text'><?= in_html($dados["mat_pal_chave"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fonte</td>
              <td bgcolor='#ffffff' class='text'><?= in_html($dados["mat_fonte"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb' colspan='2'>&nbsp;</td>
            </tr>

<!-- Destaque -->

            <tr>
              <td bgcolor='#ffffff' class='textb'>* Texto do Destaque</td>
              <td bgcolor='#ffffff' class='text'><input type="text" name="mat_des_texto" value="<?= in_html($dados["mat_des_texto"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>* Áreas de Destaque</td>
              <td bgcolor='#ffffff' class='text'><?= gera_select_g($sql, "des_id", "des_nome", "destaque", $dados["destaques"], array("name" => "destaques[]", "multiple" => "", "size" => 10)) ?></td>
            </tr>
            <!--
            <tr>
              <td bgcolor='#ffffff' class='textb'>Imagem do Destaque</td>
              <td bgcolor='#ffffff' class='text'><input type="file" name="arq" size="30" />
                <?
                if( consis_inteiro( $dados[ "mat_id" ] ) && $dados[ "mat_des_arq_f" ] != "" )
                {
                ?>
                    <br />
                    Aquivo Atual: 
                    <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'mat_id' ] . "&tabela=materia&col_id=mat_id&arq_col_r=mat_des_arq_r&arq_col_f=mat_des_arq_f" ?>'><?= $dados[ 'mat_des_arq_f' ] ?></a>
                    <br />
                <?
                }
                ?>
              </td>
            </tr>
            //-->
            <tr>
              <td bgcolor='#ffffff' class='textb'>Imagem do Destaque</td>
              <td bgcolor='#ffffff' class='text'><input type='text' name='mat_des_imagem' value='<?= $dados[ 'mat_des_imagem' ] ?>' /></td>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Entrada do destaque</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data( "mat_des_dt_ent", $dados["mat_des_dt_ent"] ); ?></td>
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
