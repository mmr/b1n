<?
// $Id: view.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$colspan = 4;
$back = false;
?>
<form method='post' action='<?= b1n_URL ?>'>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
            <input type='hidden' name='page'    value='<?= $page ?>' />
            &nbsp;&nbsp;<?= $page_title ?> - Visualizar
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
          <td>&nbsp;</td>
          <td class='formitem'>Nome</td>
          <td class='formitem'>URL</td>
          <td class='formitem'>Desc</td>
        </tr>
        <tr>
          <td class='formitem'><?= $aux2['idi_nome'] ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_nome_'.$aux2['idi_id']]) ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_url_'.$aux2['idi_id']]) ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_desc_' . $aux2['idi_id']]) ?></td>
        </tr>
<?
    foreach($aux as $idi)
    {
?>
        <tr>
          <td class='formitem'><?= $idi['idi_nome'] ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_nome_'.$idi['idi_id']]) ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_url_'.$idi['idi_id']]) ?></td>
          <td><?= b1n_inHtml($reg_data['lnk_desc_' . $idi['idi_id']]) ?></td>
        </tr>
<?
    }
?>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='button' value=' Voltar ' onclick="location='<?= b1n_URL . '?page=' . $page ?>'" />
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
