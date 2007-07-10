<form method='post' action='<?= b1n_URL ?>'>
  <table class='extbox'>
    <tr>
      <td>
        <input type='hidden' name='page'    value='login' />
        <input type='hidden' name='action0' value='login' />
        <table class='intbox'>
          <tr>
            <td class='box' colspan='2'>Login</td>
          </tr>
          <tr>
            <td class='formitem'>Login</td>
            <td class='forminput'>
              <input name='login' type='text' size='30' maxlength='255' />
            </td>
          </tr>
          <tr>
            <td class='formitem'>Senha</td>
            <td class='forminput'>
              <input name='passwd' type='password' size='30' maxlength='64' />
            </td>
          </tr>
          <tr>
            <td colspan='2' style='text-align: center'>
              <input type='submit' value='&nbsp;OK&nbsp;' />
            </td>
          </tr>
          <tr>
            <td colspan='2' class='box'>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
