<? /* $Id: inserir.php,v 1.9 2002/07/12 18:37:58 binary Exp $ */ ?>

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
              <td bgcolor='#ffffff'><input type="text" name="pal_nome" value="<?= in_html($dados["pal_nome"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Cargo</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["pal_cargo"], array("name" => "pal_cargo")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome do Contato</td>
              <td bgcolor='#ffffff'><input type="text" name="pal_nome_contato" value="<?= in_html($dados["pal_nome_contato"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Cargo do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["cex_id"], array("name" => "cex_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>
                <input type="text" name="pal_ddi" value="<?= in_html($dados["pal_ddi"]) ?>" size="2">
                <input type="text" name="pal_ddd" value="<?= in_html($dados["pal_ddd"]) ?>" size="3">
                <input type="text" name="pal_telefone" value="<?= in_html($dados["pal_telefone"]) ?>" size="9"> ([ DDI 99 ] [ DDD 999 ] 9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'><input type="text" name="pal_ramal" value="<?= in_html($dados["pal_ramal"]) ?>" size="6" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fax</td>
              <td bgcolor='#ffffff'><input type="text" name="pal_fax" value="<?= in_html($dados["pal_fax"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'><input type="text" name="pal_celular" value="<?= in_html($dados["pal_celular"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'><input type="text" name="pal_email" value="<?= in_html($dados["pal_email"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Curr�culo</td>
              <td bgcolor='#ffffff'><textarea name="pal_curriculo" cols="65" rows="15"><?= htmlspecialchars($dados["pal_curriculo"]) ?></textarea></td>
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
