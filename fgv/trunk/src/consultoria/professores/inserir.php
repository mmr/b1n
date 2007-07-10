<? /* $Id: inserir.php,v 1.1 2002/08/05 13:30:21 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>" />
            <input type="hidden" name="acao"        value="go" />
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
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'><input type="text" name="prf_nome" value="<?= in_html($dados["prf_nome"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Departamento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "dpt_id", "dpt_nome", "departamento", $dados["dpt_id"], array("name" => "dpt_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>
                <input type="text" name="prf_ddi" value="<?= in_html($dados["prf_ddi"]) ?>" size="2">
                <input type="text" name="prf_ddd" value="<?= in_html($dados["prf_ddd"]) ?>" size="3">
                <input type="text" name="prf_telefone" value="<?= in_html($dados["prf_telefone"]) ?>" size="9"> ([ DDI 99 ] [ DDD 999 ] 9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'><input type="text" name="prf_ramal" value="<?= in_html($dados["prf_ramal"]) ?>" size="6" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fax</td>
              <td bgcolor='#ffffff'><input type="text" name="prf_fax" value="<?= in_html($dados["prf_fax"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'><input type="text" name="prf_celular" value="<?= in_html($dados["prf_celular"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'><input type="text" name="prf_email" value="<?= in_html($dados["prf_email"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nascimento</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("prf_dt_nasci", array("dia" => 0, "mes" => "0", "ano" => "0"), 1950, date("Y", time()) - 2); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff'><input type="checkbox" value="1" name="prf_ajuda_ej"<? if($dados["prf_ajuda_ej"] == 1) print " checked"; ?> /></td>
              <td bgcolor='#ffffff' class='textb'>Ajuda EJ</td>
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
