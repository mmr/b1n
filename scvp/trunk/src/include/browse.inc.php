<?
// $Id: browse.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$query = "
  SELECT
    fas_id,
    SUBSTRING(CAST(fas_seq_num AS varchar(6)), 1, 4) AS fas_ano,
    fas_vol_num,
    fas_capa,
    fas_capa_tipo,
    fas_num
  FROM
    fasciculo
  WHERE
    fas_ativo = 1
  ORDER BY
    SUBSTRING(CAST(fas_seq_num AS varchar(6)), 1, 4) DESC,
    fas_num ASC";

$rs = $sql->sqlQuery($query);

if($f['fas_capa'])
{
  $capa = $f['fas_id'] . '.' . $f['fas_capa_tipo'];
}
else
{
  $capa = 'sem_capa.bmp';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100%" valign="top"> 
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
<?
if(is_array($rs))
{
?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellpadding="0" cellspacing="4">
                          <tr>
<?
  $ano_velho = 0;
  $vol_velho = 0;
  foreach($rs as $f)
  {
    if($f['fas_capa'])
    {
      $capa = $f['fas_id'] . '.' . $f['fas_capa_tipo'];
    }
    else
    {
      $capa = 'sem_capa.bmp';
    }

    if($f['fas_ano'] != $ano_velho)
    {
?>
                          <tr>
                            <td align="left" valign="top" class="tabcinza" colspan='24'>
                              <span class="not_tit">&nbsp;<?= $f['fas_ano'] ?></span>
                            </td>
                          </tr>
                          <tr>
<?
      $ano_velho = $f['fas_ano'];
    }
?>
                            <td align="center">
                              <a href="<?= b1n_URL . "?p=browse&id=".$f['fas_id'] ?>"><img src="<?= b1n_UPLOAD_DIR_CAPA . '/' . $capa ?>" width="91" height="117" border="1"></a><br>
                              <span class="textoverde">vol. <?= $f['fas_vol_num'] . '(' . $f['fas_num'] . ')' ?></span>
                            </td>
                            <td><img src='img/shim.gif' width='5' height='1' /></td>
<?
  }
?>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
<?
}
?>
          </td>
        </tr> 
      </table>
    </td>
  </tr> 
</table>
