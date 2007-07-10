<?
// $Id: add.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
$colspan = 4;
?>
<form method='post' action='<?= b1n_URL ?>' enctype='multipart/form-data'>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
            <input type='hidden' name='page'    value='<?= $page ?>' />
            <input type='hidden' name='action0' value='<?= $action1 ?>' />
            <input type='hidden' name='action1' value='<?= $action1 ?>' />
            &nbsp;&nbsp;<?= $page_title ?> - Adicionar
          </td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>'>
            <i>Itens com o '<b>*</b>' s&atilde;o obrigat&oacute;rios</i>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Volume</td>
          <td class='formitem'>N&uacute;mero</td>
          <td class='formitem'>*Num Seq</td>
          <td class='formitem'>Capa</td>
        </tr>
        <tr>
          <td>
            <input type='text' name='fas_vol_num' value='<?= $reg_data['fas_vol_num'] ?>' size='10' maxlength='10' />
          </td>
          <td>
            <input type='text' name='fas_num' value='<?= $reg_data['fas_num'] ?>' size='10' maxlength='10' />
          </td>
          <td>
            <input type='text' name='fas_seq_num' value='<?= $reg_data['fas_seq_num'] ?>' size='10' maxlength='10' />
          </td>
          <td><input type='file' name='fas_capa' /></td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='submit' value=' Adicionar ' />
            <input type='button' value=' Cancelar ' onclick="location='<?= b1n_URL . '?page=' . $page ?>'" />
          </td>
        </tr>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
