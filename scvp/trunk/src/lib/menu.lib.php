<?
function b1n_buildMenu($sql)
{
  $usar_idioma_padrao = true;
  $usar_sessao = true;

  // Pegando idioma padrao
  // vendo se tem na sessao
  if(!isset($_SESSION['idi_padrao_site']))
  {
    // Nao tem, pegar do banco
    $rs = $sql->sqlSingleQuery("SELECT idi_id FROM idioma WHERE idi_padrao_site = 1 AND idi_site = 1");
    if($rs)
    {
      $_SESSION['idi_padrao_site'] = $rs['idi_id'];
    }
    else
    {
      // Nao ha idioma padrao, abortar
      die('N&atilde;o h&aacute; idioma padr&atilde;o configurado, por favor, contacte o administrador.');
      return false;
    }
  }

  // Pegando idioma
  // Vendo se o usuario mudou de idioma (clicando no link)
  b1n_getVar('idi_id', $idi_id);
  if(empty($idi_id))
  {
    // Nao, nao mudou, olhando na sessao
    if(isset($_SESSION['idi_id']))
    {
      // Usar idioma da sessao
      $usar_idioma_padrao = false;
    }
  }
  else
  {
    // Passou idioma pela URL, usa-lo
    // mas antes verificar se o idioma de fato existe
    $rs = $sql->sqlSingleQuery("SELECT idi_id FROM idioma WHERE idi_id = '".$idi_id."' AND idi_site = 1");
    if(is_array($rs) && !empty($rs['idi_id']))
    {
      // Sim, existe, usa-lo
      $_SESSION['idi_id'] = $idi_id;
      $usar_sessao = false;
      $usar_idioma_padrao = false;
    }
  }

  if($usar_idioma_padrao)
  {
    $_SESSION['idi_id'] = $_SESSION['idi_padrao_site'];
  }

  $menu['idiomas']  = array();
  $menu['areas']    = array();
  
  // Olhando se ha idiomas na sessao
  if($usar_sessao && isset($_SESSION['menu']['idiomas']))
  {
    // Sim, ha, usa-los
    $menu['idiomas'] = $_SESSION['menu']['idiomas'];
  }
  else
  {
      $query = "
        SELECT
          idi_id, idi_nome_no_idioma as idi_nome
        FROM
          idioma
        WHERE
          idi_ativo = 1 AND
          idi_site  = 1 AND
          idi_id != " . $_SESSION['idi_id'] . "
        ORDER BY
          idi_padrao_site DESC, idi_nome_no_idioma";

    $ret = $sql->sqlQuery($query);

    if($ret)
    {
      // Salvando na sessao
      $_SESSION['menu']['idiomas'] = $ret;

      // Salvando para retorno
      $menu['idiomas'] = $ret;
    }
  }

  // Olhando se ha areas na sessao
  if($usar_sessao && isset($_SESSION['menu']['areas']))
  {
    // Sim, ha, vamos usa-las
    $menu['areas'] = $_SESSION['menu']['areas'];
  }
  else
  {
    // Nao ha, pegar do banco
      // Pegando areas fixas
    $query = "
      SELECT
        a.are_id,
        CASE WHEN (a.are_codigo IS NULL) THEN
          pai.are_codigo
        ELSE
          a.are_codigo
        END AS are_codigo,
        a.are_separador,
        a.are_nome,
        a.are_ordem
      FROM
        area a    LEFT JOIN
        area pai  ON (a.are_real_id = pai.are_id)
      WHERE
        a.are_ativo = 1 AND
        a.idi_id = '" . $_SESSION['idi_id'] . "'
      ORDER BY
        a.are_ordem";

    $ret = $sql->sqlQuery($query);
    if($ret)
    {
      // Salvando na sessao
      $_SESSION['menu']['areas'] = $ret;
      $menu['areas'] = $ret;
    }
  }

  return $menu;
}
?>
