<?
// $Id: noticias_old.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$query = "
  SELECT TOP 5
    not_id, not_nome, not_desc, not_dt
  FROM
    noticia
  WHERE
    not_ativo = '1' AND
    idi_id = '" . $_SESSION['idi_id'] . "'
  ORDER BY
    not_dt DESC";

$rs = $sql->sqlQuery($query);

if(is_array($rs))
{
?>
<table>
  <tr>
    <td>
      <table>
<?
  foreach($rs as $not)
  {
?>
        <tr>
          <td><?= b1n_formatDateShow($not['not_dt']) ?> - <?= b1n_inHtml($not['not_nome']) ?>
            <blockquote>
              <?= b1n_inHtml($not['not_desc']) ?>
            </blockquote>
            <a href='<?= b1n_URL . '?p=noticia&id=' . $not['not_id'] ?>'>Ler mais</a>
          </td>
        </tr>
<?
  }
?>
      </table>
    </td>
  </tr>
</table>
<?
}
?>
