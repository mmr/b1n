<?
// $Id: change.inc.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $
?>
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
            <input type='hidden' name='id'      value='<?= $reg_data['id'] ?>' />
            &nbsp;&nbsp;<?= $page_title ?> - Alterar
          </td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>'>
            <i>Itens com o '<b>*</b>' s&atilde;o obrigat&oacute;rios</i>
<?
if(isset($title_msg) && !empty($title_msg))
{
  echo '<br /><i>' . $title_msg . '</i>';
}
?>
          </td>
        </tr>
<?
foreach($reg_config as $title => $reg)
{
  if(b1n_cmp($reg['type'], 'none'))
  {
    continue;
  }
?>
        <tr>
          <td class='thin'><?= $reg['mand'] && $reg['type'] != 'password' ? '*' : '&nbsp;' ?></td>
          <td class='formitem'><?= $title ?></td>
          <td class='forminput'>
<?
    switch($reg['type'])
    {
    case 'text':
    case 'password':
?>
            <input type='<?= $reg['type'] ?>' name='<?= $reg['reg_data'] ?>' value='<?= $reg_data[$reg['reg_data']] ?>' size='<?= $reg['extra']['size'] ?>' maxlength='<?= $reg['extra']['maxlen'] ?>' />
<?
      break;
  case 'select':
    if(!isset($reg['extra']['params']))
    {
      $reg['extra']['params'] = array();
    }

    switch($reg['extra']['seltype'])
    {
    case 'date':
      echo b1n_buildSelectFromDate($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
      break;
    case 'date_hour':
      echo b1n_buildSelectFromDateHour($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
      break;
    case 'fk':
      if(!isset($reg['extra']['where']))
      {
        $reg['extra']['where'] = '';
      }

      echo b1n_buildSelectCommon($sql, $reg['extra']['name'], $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['where']);
      break;
    case 'defined':
      echo b1n_buildSelect($reg['extra']['options'], $reg_data[$reg['reg_data']], $reg['extra']['params'] + array('name' => $reg['reg_data']));
      break;
    }
    break;
  case 'radio':
    foreach($reg['extra']['options'] as $opt_title => $opt_value)
    {
?>
            <input type='radio' name='<?= $reg['reg_data'] ?>' value='<?= $opt_value ?>' class='noborder'<? if(b1n_cmp($opt_value, $reg_data[$reg['reg_data']])){ echo " checked='checked'"; } ?> /> <?= $opt_title ?><br />
<?
    }
    break;
  case 'textarea':
?>
            <textarea name='<?= $reg['reg_data'] ?>' rows='<?= $reg['extra']['rows'] ?>' cols='<?= $reg['extra']['cols'] ?>'><?= b1n_inHtml($reg_data[$reg['reg_data']]) ?></textarea>
<?
    break;
  }
?>
          </td>
        </tr>
<?
}
?>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='submit' value=' Alterar ' />
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
