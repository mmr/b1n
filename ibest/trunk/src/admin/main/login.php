<? /* $Id: login.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */ ?>
  <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
    <input type="hidden" name="pagina" value="login"/>
    <input type="hidden" name="acao" value="login"/>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="350">
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom"/>&nbsp;&nbsp;Login
              </td>
            </tr>
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="3"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print $msg."<br/>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff'>Login:</td>
              <td bgcolor='#ffffff'><input name="login" type="text" value="binary" size="30" maxlength="255"/></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff'>Senha:</td>
              <td bgcolor='#ffffff'><input name="senha" type="password" value="123123" size="30" maxlength="255"></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor='#ffffff'>
                <input type="submit" value="&nbsp;OK&nbsp;"/>
                <input type="reset"  value="Cancela"/>
              </td>
            </tr>
            <tr>
              <td class="text" colspan="3" bgcolor="#336699">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>
<!--fim conteudo -->
