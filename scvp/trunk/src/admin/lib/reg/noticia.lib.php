<?
// $Id: noticia.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Check
function b1n_regCheckNoticia($sql, &$ret_msgs, $reg_data)
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
      if(!b1n_checkDate($reg_data['not_dt']['month'],
                        $reg_data['not_dt']['day'],
                        $reg_data['not_dt']['year'], true))
      {
        $ret = false;  
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Data inv&aacute;lida em <b>Data</b>');
      }

      if(empty($reg_data['not_nome_'.$aux['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Manchete</b> do idioma padr&atilde;o');
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
      $query = "
        SELECT
          COUNT(not_id) AS count
        FROM
          noticia
        WHERE
          idi_id = '".$idi['idi_id']."' AND
          not_nome = '".$reg_data['not_nome_'.$idi['idi_id']]."'";

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

function b1n_regCheckChangeNoticia($sql, &$ret_msgs, $reg_data)
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
      if(!b1n_checkDate($reg_data['not_dt']['month'],
                        $reg_data['not_dt']['day'],
                        $reg_data['not_dt']['year'], true))
      {
        $ret = false;  
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Data inv&aacute;lida em <b>Data</b>');
      }

      if(empty($reg_data['not_nome_'.$aux['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Manchete</b> do idioma padr&atilde;o');
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
      $query = "
        SELECT
          COUNT(not_id) AS count
        FROM
          noticia
        WHERE
          not_id != '".$reg_data['id']."' AND
          not_real_id != '".$reg_data['id']."' AND
          idi_id = '".$idi['idi_id']."' AND
          not_nome = '".$reg_data['not_nome_'.$idi['idi_id']]."'";

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

function b1n_regCheckDeleteNoticia($sql, &$ret_msgs, $reg_data)
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
function b1n_regAddNoticia($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  
  if($rs)
  {
    $data = b1n_formatDateToDb($reg_data['not_dt']);

    $aux = $idiomas;
    $first = array_shift($aux);

    $query = "
      INSERT INTO noticia
      (
        idi_id,
        not_dt,
        not_nome,
        not_desc,
        not_cont
      )
      VALUES
      (
        '" . b1n_inBd($first['idi_id']) . "',
        " . $data . ",
        '" . b1n_inBd($reg_data['not_nome_' . $first['idi_id']]) . "',
        '" . b1n_inBd($reg_data['not_desc_' . $first['idi_id']]) . "',
        '" . b1n_inBd($reg_data['not_cont_'  . $first['idi_id']]) . "'
      )";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $query = 'SELECT @@IDENTITY AS id';
      $rs = $sql->sqlSingleQuery($query);

      if(is_array($rs))
      {
        $not_real_id = b1n_inBd($rs['id']);
        foreach($aux as $idi)
        {
          if(!empty($reg_data['not_nome_' . $idi['idi_id']]))
          {
            $query = "
              INSERT INTO noticia
              (
                not_real_id,
                idi_id,
                not_dt,
                not_nome,
                not_desc,
                not_cont
              )
              VALUES
              (
                '" . $not_real_id . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                " . $data . ",
                '" . b1n_inBd($reg_data['not_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['not_desc_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['not_cont_'  . $idi['idi_id']]) . "'
              )";
            $ret = $ret && $sql->sqlQuery($query);
          }
        }

        if($ret)
        {
          if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
          {
            b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Not&iacute;cia adicionada com sucesso!');
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
function b1n_regChangeNoticia($sql, &$ret_msgs, $reg_data)
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
      UPDATE noticia SET
        not_dt = " . b1n_formatDateToDb($reg_data['not_dt']) . ",
        not_nome = '" . b1n_inBd($reg_data['not_nome_' . $first['idi_id']]) . "',
        not_desc = '" . b1n_inBd($reg_data['not_desc_' . $first['idi_id']]) . "',
        not_cont = '" . b1n_inBd($reg_data['not_cont_'  . $first['idi_id']]) . "'
      WHERE
        not_id = '" . $id . "'";

    $rs = $sql->sqlQuery($query);

    // Atualizando filhos
    if($rs)
    {
      // Apagando Filhos
      $query = "DELETE FROM noticia WHERE not_real_id = '" . $id . "'";
      $rs = $sql->sqlQuery($query, 'del');

      // Incluindo novamente com os dados novos
      if($rs)
      {
        foreach($aux as $idi)
        {
          if(!empty($reg_data['not_nome_' . $idi['idi_id']]))
          {
            $query = "
              INSERT INTO noticia
              (
                not_real_id,
                idi_id,
                not_dt,
                not_nome,
                not_desc,
                not_cont
              )
              VALUES
              (
                '" . $id . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                " . b1n_formatDateToDb($reg_data['not_dt']) . ",
                '" . b1n_inBd($reg_data['not_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['not_desc_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['not_cont_'  . $idi['idi_id']]) . "'
              )";
            $ret = $ret && $sql->sqlQuery($query);
          }
        }
      }

      if($ret)
      {
        if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Not&iacute;cia alterada com sucesso!');
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
function b1n_regDeleteNoticia($sql, &$ret_msgs, $reg_data)
{
  // Plural
  if(sizeof($reg_data['ids']) > 1 && !empty($msg_plural))
  {
    $msg = 'Not&iacute;cias exclu&iacute;das';
  }
  else
  {
    $msg = 'Not&iacute;cia exclu&iacute;da';
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    // Apagando filhos
    $query = 'DELETE FROM noticia WHERE not_real_id = ' . implode(' OR not_real_id = ', $reg_data['ids']);
    $rs = $sql->sqlQuery($query, 'del');

    if($rs)
    {
      // Apagando pais
      $query = 'DELETE FROM noticia WHERE not_id = ' . implode(' OR not_id = ', $reg_data['ids']);
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
function b1n_regToggleActivationNoticia($sql, &$ret_msgs, $id)
{
  $ret = b1n_regToggleActivation($sql, $ret_msgs, 'not_id', $id, 'not_ativo', 'noticia');

  // Atualizando filhos
  if($ret)
  {
    $id = b1n_inBd($id);
    $ret = $sql->sqlQuery("UPDATE noticia SET not_ativo = (SELECT not_ativo FROM noticia WHERE not_id = '".$id."') WHERE not_real_id = '".$id."'");
  }

  return $ret;
}

// Load
function b1n_regLoadNoticia($sql, &$ret_msgs, &$reg_data)
{
  $query = "
    SELECT
      idi_id, not_dt, not_nome, not_cont, not_desc
    FROM
      noticia
    WHERE
      not_id = '".b1n_inBd($reg_data['id'])."' OR
      not_real_id = '".b1n_inBd($reg_data['id'])."'";

  $rs = $sql->sqlQuery($query);

  foreach($rs as $i)
  {
    $reg_data['not_dt'] = $i['not_dt'];
    $reg_data['not_nome_'.$i['idi_id']] = $i['not_nome'];
    $reg_data['not_desc_'.$i['idi_id']] = $i['not_desc'];
    $reg_data['not_cont_'.$i['idi_id']] = $i['not_cont'];
  }
  return true;
}

// Search
function b1n_regSearchNoticia($sql, $search)
{
  $config['possible_fields'] = array(
    'Idioma'    => 'idi_nome',
    'Manchete'  => 'not_nome',
    'Resumo'    => 'not_desc',
    'Data'    => 'not_dt',
    'Ativo'     => 'not_ativo');

  $config['select_fields'] = array(
    'Idioma'  => 'idi_nome',
    'Data'    => 'not_dt',
    'Manchete'  => 'not_nome',
    'Ativo'     => 'not_ativo',
    'Resumo'    => 'not_desc');
  
  $config['possible_quantities'] = array(
    10=>10, 15=>15, 20=>20, 25=>25, 30=>30);

  $config['session_hash_name']  = 'noticia';
  $config['id_field'] = 'not_id';
  $config['table']    = 'view_noticia';

  return b1n_searchG($sql, $config, $search);
}
?>
