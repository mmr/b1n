<?  
// $Id: menu.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
if(is_array($menu['areas']))
{
  foreach($menu['areas'] as $are)
  {
?>
        <tr>
          <td><a href='<?= b1n_URL . '?p=area&amp;id=' . $are['are_id'] ?>'><?= $are['are_nome'] ?></a></td>
        </tr>
<?
  }
}
?>
