<?
// $Id: links.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$query = "
  SELECT
    lnk_nome, lnk_desc, lnk_url
  FROM
    link
  WHERE
    lnk_ativo = '1' AND
    idi_id = '" . $_SESSION['idi_id'] . "'
  ORDER BY
    lnk_nome";

$rs = $sql->sqlQuery($query);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><img src="img/shim.gif" width="15" height="1"></td>
    <td width="100%">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td class="tabverdeclaro"><img src="img/shim.gif" width="1" height="1"></td>
          <td class="tabverdeclaro" width="100%"><span class="secao"><?= $are_nome ?></span></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="100%"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="7">
<?
if(is_array($rs))
{
  foreach($rs as $i)
  {
?>
              <tr> 
                <td valign="top" class="tabcinza"> 
                  <div align="right" class="titulosbold"><a href='<?= $i['lnk_url'] ?>' target='_blank'><?= $i['lnk_nome'] ?></a></div>
                </td>
                <td width="100%" class="texto"><?= $i['lnk_desc'] ?></td>
              </tr>
<?
  }
}
?>
            </table>
          </td>
          <td><img src="img/shim.gif" width="15" height="1"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><img src="img/shim.gif" width="1" height="15"></td>
  </tr>
</table>
