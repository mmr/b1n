<? /* $Id: inserir.php,v 1.5 2002/07/16 17:47:42 binary Exp $ */ ?>

<? $colspan = 4; ?>

<br>
<br>
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"    value="<?= $suppagina ?>">
            <input type="hidden" name="pagina"    value="<?= $pagina ?>">
            <input type="hidden" name="subpagina" value="inserir">

            <input type="hidden" name="acao"      value="go">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom">&nbsp;&nbsp;Status de task - Inserir
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'><input type="text" name="stt_nome" value="<?= in_html($dados["stt_nome"]) ?>" size="30" ></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Descri��o</td>
              <td bgcolor='#ffffff'><input type="text" name="stt_desc" value="<?= in_html($dados["stt_desc"]) ?>" size="30" ></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;Inserir&nbsp;">
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'">
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
</center>
