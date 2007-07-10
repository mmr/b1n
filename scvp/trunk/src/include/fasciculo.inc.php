<?
//$Id: fasciculo.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Pegando Dados do Fasciculo
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
    fas_ativo = 1 AND
    fas_id = '" . b1n_inBd($d['id']) . "'";

$rs = $sql->sqlSingleQuery($query);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><img src="img/shim.gif" width="15" height="1"></td>
    <td width="100%">
<?
if(is_array($rs))
{
  // Montando nome do Fasciculo
  $fasciculo = 'vol. ' . $rs['fas_vol_num'] . '(' . $rs['fas_num'] . ') ' . $rs['fas_ano'];

  // Vendo se tem capa
  if($rs['fas_capa'])
  {
    $capa = $rs['fas_id'] . '.' . $rs['fas_capa_tipo'];
  }
  else
  {
    $capa = 'sem_capa.bmp';
  }

?>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td class="tabverdeclaro"><img src="img/shim.gif" width="1" height="1"></td>
          <td class="tabverdeclaro" width="100%"><span class="secao"><?= $are_nome . ' - ' . $fasciculo ?></span></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0">
        <tr>
          <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="img/shim.gif" width="1" height="5"></td>
              </tr>
              <tr>
                <td><a href="<?= b1n_URL . "?p=browse&id=".$rs['fas_id'] ?>"><img src="<?= b1n_UPLOAD_DIR_CAPA . '/' . $capa ?>" width="91" height="117" border="1"></a></td>
              </tr>
            </table>
          </td>
          <td width="100%" valign="top">
<?
  // Pegando Artigos
  $query = "
    SELECT
    -- Idioma
      CASE WHEN (LEN(i.idi_nome_no_idioma) <= 4) THEN
        i.idi_nome_no_idioma
      ELSE
        SUBSTRING(i.idi_nome_no_idioma, 1, 4) + '.'
      END AS idi_nome,

    -- Secao
      CASE WHEN s.sec_real_id IS NULL THEN
        s.sec_id
      ELSE
        s.sec_real_id
      END AS sec_id,

    -- Dados do Artigo
      a.art_id,
      a.art_ordem,
      a.art_pag_ini,
      a.art_pag_fin,
      a.art_pdf,
      a.art_html
    FROM
      artigo  a JOIN
      idioma  i ON (a.idi_id = i.idi_id) LEFT JOIN
      secao   s ON (a.sec_id = s.sec_id)
    WHERE
      a.fas_id = " . $d['id'] . " AND
      a.art_ativo = 1 AND
      s.sec_ativo = 1 AND
      i.idi_ativo = 1
    ORDER BY
      a.art_ordem,
      a.sec_id";

  $rs = $sql->sqlQuery($query);

  if(is_array($rs))
  {
    // Idioma Padrao
    $query = "
      SELECT
        idi_id
      FROM
        idioma 
      WHERE
        idi_padrao_pub = 1";
    $idi_padrao = $sql->sqlSingleQuery($query);
    if(is_array($idi_padrao))
    {
      $idi_padrao = $idi_padrao['idi_id']
    }
    else
    {
      die('Nao ha idioma padrao para a Publicacao, abortando.');
    }


    // Pegando Secoes
    $query = "
      SELECT
        sec_id,
        sec_nome
      FROM
        secao
      WHERE
        sec_ativo = 1 AND
        (
          idi_id = " . $idi_padrao . " OR
          idi_id = " . $_SESSION['idi_id'] . "
        )"; 
    $rs_secao = $sql->sqlQuery($query);

    // Pegando idiomas
    $query = "
      SELECT
        idi_id,
        idi_nome_no_idioma
      FROM
        idioma
      WHERE
        idi_ativo = 1";
    $rs_idioma = $sql->sqlQuery($query);

    // Pegando dados dos Artigos
    $sec_velho = '';
    foreach($rs as $art)
    {
      // Titulo
      $query = "
        SELECT
          aid_titulo,
          idi_id,
          CASE WHEN (idi_id = " . $idi_padrao . ") THEN
            'padrao'
          WHEN (idi_id = " . $_SESSION['idi_id'] . ") THEN
            'sessao'
          ELSE
            'outro'
          END AS idioma
        FROM
          artigo_idioma
        WHERE
          art_id = " . $art['art_id'];

      $aid = $sql->sqlQuery($query);
      if(is_array($aid))
      {
        if($sec_velho != $art['sec_id'])
        {
          echo "Secao Nova: " . $art['sec_id'] . "<br>";
          $sec_velho = $art['sec_id'];
        }
      }
    }
  }
}
?>
          <td width="100%" valign="top">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
