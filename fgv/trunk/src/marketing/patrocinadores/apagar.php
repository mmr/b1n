<? /* $Id: apagar.php,v 1.9 2002/07/12 19:16:32 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina    ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>" />
            <input type="hidden" name="acao"        value="go">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br />" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_nome_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Cargo do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["cex_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Classifica��o</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cla_id", "cla_nome", "pat_class",  $dados["cla_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Setor de Atua��o</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "set_id", "set_nome", "setor",  $dados["set_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "pat_ddi" ] ) ? " (+" . $dados[ "pat_ddi" ] . ")" : "" ) .
                    ( consis_telefone( $dados[ "pat_ddd" ] ) ? " ("  . $dados[ "pat_ddd" ] . ")" : "" ) .
                    $dados[ "pat_telefone" ] )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_ramal"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fax</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_fax"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_celular"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["pat_email"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Apoiador</td><input type="checkbox" value="1" name="pat_apoiador"<? if($dados["pat_apoiador"] == 1) print " checked"; ?> /></td>
              <td bgcolor='#ffffff'><? $dados["pat_apoiador"] == 1 ? print "Sim" : print "N�o" ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Coment�rios</td>
              <td bgcolor='#ffffff'><textarea name="pat_texto" cols="65" rows="15" disabled><?= htmlspecialchars($dados["pat_texto"]) ?></textarea></td>
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
  <br />
  <br />
</center>
