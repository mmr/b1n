<?
// $Id: link.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Check
function b1n_regCheckLink($sql, &$ret_msgs, &$reg_data)
{
  global $idiomas;
  $ret = true;
  $tem_padrao = false;

  if(is_array($idiomas) && sizeof($idiomas))
  {
    $aux = $idiomas;
    $aux = array_shift($aux);
    if($aux['idi_padrao_site'])
    {
      $tem_padrao = true;
      if(empty($reg_data['lnk_nome_'.$aux['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Nome</b> do idioma padr&atilde;o.');
      }
      if(empty($reg_data['lnk_url_'.$aux['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>URL</b> do idioma padr&atilde;o.');
      }

      if(!b1n_checkUrl($reg_data['lnk_url_'.$aux['idi_id']], true))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'URL Inv&aacute;lida para Idioma padr&atilde;o');
      }
    }
  }

  if($ret && !$tem_padrao)
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o h&aacute; idioma padr&atilde;o configurado.');
  }

  // Unicidade da combinacao
  if($ret)
  {
    foreach($idiomas as $idi)
    {
      if(!b1n_checkUrl($reg_data['lnk_url_'.$idi['idi_id']], false))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'URL Inv&aacute;lida para idioma ' . $idi['idi_nome']);
        return false;
      }

      $query = "
        SELECT
          COUNT(lnk_id) AS count
        FROM
          link
        WHERE
          idi_id = '".$idi['idi_id']."' AND
          lnk_nome = '".$reg_data['lnk_nome_'.$idi['idi_id']]."'";

      $rs = $sql->sqlSingleQuery($query);
      if(is_array($rs) && $rs['count'] > 0)
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe uma not&iacute;cia com esse nome para esse idioma ('.$idi['idi_nome'].')');
      }
    }
  }

  return $ret;
}

function b1n_regCheckChangeLink($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;

  if(is_array($idiomas) && sizeof($idiomas))
  {
    $aux = $idiomas;
    $f = array_shift($aux);
    if($f['idi_padrao_site'])
    {
      if(empty($reg_data['lnk_nome_'.$f['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Nome</b> do idioma padr&atilde;o.');
      }
      if(empty($reg_data['lnk_url_'.$f['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>URL</b> do idioma padr&atilde;o.');
      }

      if(!b1n_checkUrl($reg_data['lnk_url_'.$f['idi_id']], true))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'URL Inv&aacute;lida para Idioma padr&atilde;o');
      }
    }
  }
  return $ret;
}

function b1n_regCheckDeleteLink($sql, &$ret_msgs, $reg_data)
{
  if(is_array($reg_data['ids']) && sizeof($reg_data['ids']))
  {
    return true;
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Voc&ecirc; precisa selecionar algo para ser exclu&iacute;do.');
  }

  return false;
}

// Add
function b1n_regAddLink($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  
  if($rs)
  {
    $aux = $idiomas;
    $first = array_shift($aux);

    $query = "
      INSERT INTO link
      (
        idi_id,
        lnk_nome, lnk_desc, lnk_url
      )
      VALUES
      (
        '" . b1n_inBd($first['idi_id']) . "',
        '" . b1n_inBd($reg_data['lnk_nome_' . $first['idi_id']]) . "',
        '" . b1n_inBd($reg_data['lnk_desc_' . $first['idi_id']]) . "',
        '" . b1n_inBd($reg_data['lnk_url_'  . $first['idi_id']]) . "'
      )";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $query = 'SELECT @@IDENTITY AS id';
      $rs = $sql->sqlSingleQuery($query);

      if(is_array($rs))
      {
        $lnk_real_id = b1n_inBd($rs['id']);
        foreach($aux as $idi)
        {
          if(!empty($reg_data['lnk_nome_' . $idi['idi_id']]))
          {
            $query = "
              INSERT INTO link
              (
                lnk_real_id, idi_id,
                lnk_nome, lnk_desc, lnk_url
              )
              VALUES
              (
                '" . $lnk_real_id . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                '" . b1n_inBd($reg_data['lnk_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['lnk_desc_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['lnk_url_'  . $idi['idi_id']]) . "'
              )";
            $ret = $ret && $sql->sqlQuery($query);
          }
        }

        if($ret)
        {
          if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
          {
            b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Link adicionado com sucesso!');
            return true;
          }
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not insert registry.');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
      }
    }
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

// Change
function b1n_regChangeLink($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  
  if($rs)
  {
    $aux = $idiomas;
    $first = array_shift($aux);

    $id = b1n_inBd($reg_data['id']);

    $query = "
      UPDATE link SET
        lnk_nome = '" . b1n_inBd($reg_data['lnk_nome_' . $first['idi_id']]) . "',
        lnk_desc = '" . b1n_inBd($reg_data['lnk_desc_' . $first['idi_id']]) . "',
        lnk_url = '" . b1n_inBd($reg_data['lnk_url_'  . $first['idi_id']]) . "'
      WHERE
        lnk_id = '" . $id . "'";

    $rs = $sql->sqlQuery($query);

    // Atualizando filhos
    if($rs)
    {
      // Apagando Filhos
      $query = "DELETE FROM link WHERE lnk_real_id = '" . $id . "'";
      $rs = $sql->sqlQuery($query, 'del');

      // Incluindo novamente com os dados novos
      if($rs)
      {
        foreach($aux as $idi)
        {
          if(!empty($reg_data['lnk_nome_' . $idi['idi_id']]))
          {
            $query = "
              INSERT INTO link
              (
                lnk_real_id, idi_id,
                lnk_nome, lnk_desc, lnk_url
              )
              VALUES
              (
                '" . $id . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                '" . b1n_inBd($reg_data['lnk_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['lnk_desc_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['lnk_url_'  . $idi['idi_id']]) . "'
              )";
            $ret = $ret && $sql->sqlQuery($query);
          }
        }
      }

      if($ret)
      {
        if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Link alterado com sucesso!');
          return true;
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not insert registry.');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
    }
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

// Delete
function b1n_regDeleteLink($sql, &$ret_msgs, $reg_data)
{
  // Plural
  if(sizeof($reg_data['ids']) > 1 && !empty($msg_plural))
  {
    $msg = 'Links exclu&iacute;dos';
  }
  else
  {
    $msg = 'Link exclu&iacute;do';
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    // Apagando filhos
    $query = 'DELETE FROM link WHERE lnk_real_id = ' . implode(' OR lnk_real_id = ', $reg_data['ids']);
    $rs = $sql->sqlQuery($query, 'del');

    if($rs)
    {
      // Apagando pais
      $query = 'DELETE FROM link WHERE lnk_id = ' . implode(' OR lnk_id = ', $reg_data['ids']);
      $rs = $sql->sqlQuery($query, 'del');

      if($rs)
      {
        b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' com sucesso!');
        return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Begin Transaction.');
    return false;
  }
  
  b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro inesperado.');
  $sql->sqlQuery('ROLLBACK TRANSACTION');
  return false; 
}

// Activate
function b1n_regToggleActivationLink($sql, &$ret_msgs, $id)
{
  $ret = b1n_regToggleActivation($sql, $ret_msgs, 'lnk_id', $id, 'lnk_ativo', 'link');

  // Atualizando filhos
  if($ret)
  {
    $id = b1n_inBd($id);
    $ret = $sql->sqlQuery("UPDATE link SET lnk_ativo = (SELECT lnk_ativo FROM link WHERE lnk_id = '".$id."') WHERE lnk_real_id = '".$id."'");
  }

  return $ret;
}

// Load
function b1n_regLoadLink($sql, &$ret_msgs, &$reg_data)
{
  $query = "
    SELECT
      idi_id, lnk_nome, lnk_url, lnk_desc
    FROM
      link
    WHERE
      lnk_id = '".b1n_inBd($reg_data['id'])."' OR
      lnk_real_id = '".b1n_inBd($reg_data['id'])."'";

  $rs = $sql->sqlQuery($query);

  foreach($rs as $i)
  {
    $reg_data['lnk_nome_'.$i['idi_id']] = $i['lnk_nome'];
    $reg_data['lnk_desc_'.$i['idi_id']] = $i['lnk_desc'];
    $reg_data['lnk_url_'.$i['idi_id']]  = $i['lnk_url'];
  }
  return true;
}

// Search
function b1n_regSearchLink($sql, $search)
{
  $config['possible_fields'] = array(
    'Idioma'  => 'idi_nome',
    'Nome'  => 'lnk_nome',
    'URL'   => 'lnk_url',
    'Ativo'   => 'lnk_ativo');

  $config['select_fields'] = array(
    'Idioma'  => 'idi_nome',
    'Nome'  => 'lnk_nome',
    'URL'   => 'lnk_url',
    'Ativo' => 'lnk_ativo');
  
  $config['possible_quantities'] = array(
    10=>10, 15=>15, 20=>20, 25=>25, 30=>30);

  $config['session_hash_name']  = 'link';
  $config['id_field'] = 'lnk_id';
  $config['table']    = 'view_link';

  return b1n_searchG($sql, $config, $search);
}
?>
