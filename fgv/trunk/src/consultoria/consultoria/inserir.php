<? /* $Id: inserir.php,v 1.1 2002/04/26 16:00:42 binary Exp $ */ ?>

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
            <input type="hidden" name="tipo_inserir" value="<?= $tipo_inserir ?>" />
            <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
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
              <td bgcolor='#ffffff' class="textb">Cliente</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "cli_id", "cli_nome", "cliente", $dados["cli_id"], array("name" => "cli_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome da Consultoria</td>
              <td bgcolor='#ffffff'><input type="text" name="cst_nome" value="<?= in_html($dados["cst_nome"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("cst_dt_contato"); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Prazo para retorno</td>
              <td bgcolor='#ffffff'><input type="text" name="cst_dt_retorno_u" value="<?= in_html($dados["cst_dt_retorno_u"]) ?>" size="3" /> Dias úteis</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Problema apresentado</td>
              <td bgcolor='#ffffff'><textarea name="cst_texto" cols="65" rows="15"><?= htmlspecialchars($dados["cst_texto"]) ?></textarea></td>

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
