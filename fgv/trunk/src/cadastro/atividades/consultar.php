<? /* $Id: consultar.php,v 1.1 2002/07/30 13:06:54 binary Exp $ */ ?>

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
            <input type="hidden" name="acao"        value="go" />
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom">&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br />" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff' class="text" colspan="3">&nbsp;<?= in_html($dados["tat_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Descri��o</td>
              <td bgcolor='#ffffff' class="text" colspan="3">&nbsp;<?= in_html($dados["tat_desc"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff'  class='textb'>SubAtividades</td>
              <td bgcolor='#ffffff' class="text">
                <?= gera_select_g($sql, "tsa_id", "tsa_nome", "ts_subatividade", $dados["ts_subatividades"], array("name" => "ts_subatividades[]", "multiple" => "", "size" => 10, "disabled" => ""),1) ?></td>
              </td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
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
