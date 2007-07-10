<?
// $Id: area.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$query = "
  SELECT
    are_nome, are_cont
  FROM
    area
  WHERE
    are_ativo = '1' AND
    are_id = '" . $d['are_id'] . "'";

$rs = $sql->sqlSingleQuery($query);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><img src="img/shim.gif" width="15" height="1"></td>
    <td width="100%">
<?
if(is_array($rs))
{
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td class="tabverdeclaro"><img src="img/shim.gif" width="1" height="1"></td>
          <td class="tabverdeclaro" width="100%"><span class="secao"><?= $rs['are_nome'] ?></span></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
        <tr>
          <td><img src="img/shim.gif" width="1" height="8"></td>
        </tr>

        <tr> 
          <td class=`texto`><?= $rs['are_cont'] ?></td>
        </tr>
      </table>
<?
}
?>
    </td>
  </tr>
</table>
