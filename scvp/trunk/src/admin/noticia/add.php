<?
// $Id: add.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$colspan = 2;
$year_end = date("Y");
$year_ini = $year_end - 10;
$back = false;
?>
<script type="text/javascript"><!-- // load htmlarea
_editor_url = "htmlarea/"; // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 
<form method='post' action='<?= b1n_URL ?>'>
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
<?
if(is_array($idiomas) && sizeof($idiomas))
{
  $aux = $idiomas;
  $aux2 = array_shift($aux);
  if($aux2['idi_padrao_site'])
  {
?>
        <tr>
          <td colspan='<?= $colspan ?>'>
            <i>Itens com o '<b>*</b>' s&atilde;o obrigat&oacute;rios</i>
          </td>
        </tr>
        <tr>
          <td class='formitem'>*Data</td>
          <td><?= b1n_buildSelectFromDate('not_dt', $reg_data['not_dt'], $year_end, $year_ini); ?></td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
        <tr>
          <td class='formitem' colspan='<?= $colspan ?>'><?= b1n_inHtml($aux2['idi_nome']) ?></td>
        </tr>
        <tr>
          <td class='formitem'>*Manchete</td>
          <td><input type='text' name='not_nome_<?= $aux2['idi_id'] ?>' value='<?= b1n_inHtml($reg_data['not_nome_' . $aux2['idi_id']]) ?>' size='70' maxlength='128'></td>
        </tr>
        <tr>
          <td class='formitem'>Resumo</td>
          <td>
            <textarea name='not_desc_<?= $aux2['idi_id'] ?>'><?= b1n_inHtml($reg_data['not_desc_' . $aux2['idi_id']]) ?></textarea>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Conte&uacute;do</td>
          <td>
            <textarea name='not_cont_<?= $aux2['idi_id'] ?>' rows='10' cols='80'><?= $reg_data['not_cont_' . $aux2['idi_id']] ?></textarea>
            <script>editor_generate('not_cont_<?= $aux2['idi_id'] ?>')</script>
          </td>
        </tr>
<?
    foreach($aux as $idi)
    {
?>
        <tr>
          <td colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>' class='formitem'><?= $idi['idi_nome'] ?></td>
        </tr>
        <tr>
          <td class='formitem'>Manchete</td>
          <td><input type='text' name='not_nome_<?= $idi['idi_id'] ?>' value='<?= b1n_inHtml($reg_data['not_nome_' . $idi['idi_id']]) ?>' size='70' maxlength='128'></td>
        </tr>
        <tr>
          <td class='formitem'>Resumo</td>
          <td>
            <textarea name='not_desc_<?= $idi['idi_id'] ?>'><?= b1n_inHtml($reg_data['not_desc_' . $idi['idi_id']]) ?></textarea>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Conte&uacute;do</td>
          <td>
            <textarea name='not_cont_<?= $idi['idi_id'] ?>' rows='10' cols='80'><?= b1n_inHtml($reg_data['not_cont_' . $idi['idi_id']]) ?></textarea>
            <script>editor_generate('not_cont_<?= $idi['idi_id'] ?>')</script>
          </td>
        </tr>
<?
    }
?>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='submit' value=' Adicionar ' />
            <input type='button' value=' Cancelar ' onclick="location='<?= b1n_URL . '?page=' . $page ?>'" />
          </td>
        </tr>
<?
  }
  else // Padrao
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o h&aacute; idioma padr&atilde;o configurado.');
    $back = true;
  }
}
else // Idiomas
{
  b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o h&aacute; idiomas cadastrados.<br />Por favor, cadastre alguem idioma.');
  $back = true;
}

if($back)
{
?>
        <tr>
          <td>
            <? require(b1n_PATH_INC . '/ret.inc.php'); ?>
          </td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='button' value=' Voltar ' onclick="location='<?= b1n_URL . '?page=' . $page ?>'" />
          </td>
        </tr>
<?
}
?>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
