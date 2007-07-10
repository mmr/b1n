<?
// $Id: view.inc.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $
?>
<form method='post' action='<?= b1n_URL ?>'>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
            <input type='hidden' name='page'    value='<?= $page ?>' />
            <input type='hidden' name='action0' value='list' />
            <input type='hidden' name='action1' value='list' />
            &nbsp;&nbsp;<?= $page_title ?>Visualizar
          </td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>'>
            <i>Itens com <b>*</b>' s&atilde;o obrigat&oacute;rios</i>
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
  if(b1n_cmp($reg['type'], 'none') || !$reg['load'])
  {
    continue;
  }
?>
        <tr>
          <td class='thin'><?= $reg['mand'] ? '*' : '&nbsp;' ?></td>
          <td class='formitem'><?= $title ?></td>
          <td class='forminput'>
<?
  switch($reg['type'])
  {
  case 'text':
  case 'textarea':
    if(b1n_cmp($reg['check'], 'email') && !empty($reg_data[$reg['reg_data']]))
    {
      echo '&nbsp;<a href=\'mailto:' . $reg_data[$reg['reg_data']] . '\'>' . $reg_data[$reg['reg_data']] . '</a>';
    }
    else
    {
      echo '&nbsp;' . b1n_inHtml($reg_data[$reg['reg_data']]);
    }
    break;
  case 'select':
    switch($reg['extra']['seltype'])
    {
    case 'date':
      echo b1n_formatDateShow($reg_data[$reg['reg_data']]);
      break;
    case 'date_hour':
      echo b1n_formatDateHourShow(b1n_formatDateHourToDb($reg_data[$reg['reg_data']]));
      break;
    case 'defined':
      foreach($reg['extra']['options'] as $opt_title => $opt_value)
      {
        if(b1n_cmp($reg_data[$reg['reg_data']], $opt_value))
        {
          echo $opt_title;
          break;
        }
      }
      break;
    case 'fk':
      if(!isset($reg['extra']['params']))
      {
        echo '&nbsp;'.b1n_viewSelected($sql, $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']]);
      }
      else
      {
        if(!isset($reg['extra']['where']))
        {
          $reg['extra']['where'] = '';
        }
        echo '&nbsp;'.b1n_buildSelectCommon($sql, $reg['extra']['name'], $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['where']);
      }
      break;
    }
    break;
  case 'radio':
    foreach($reg['extra']['options'] as $opt_title => $opt_value)
    {
      if(b1n_cmp($opt_value, $reg_data[$reg['reg_data']]))
      {
        echo '&nbsp;'.$opt_title;
        break;
      }
    }
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
            <input type='submit' value=' Voltar ' />
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
