<?
// $Id: area.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Check
function b1n_regCheckArea($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $tem_padrao = false;

  if(!b1n_checkNumeric($reg_data['are_ordem'], true))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Apenas n&uacute;meros s&atilde;o aceitos no campo <b>Ordem</b>');
  }

  if(!b1n_checkBoolean($reg_data['are_separador']))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Valor inv&aacute;lido em <b>Separador</b>');
  }

  if(!$ret)
  {
    return false;
  }

  if(is_array($idiomas) && sizeof($idiomas))
  {
    $aux = $idiomas;
    $aux = array_shift($aux);
    if($aux['idi_padrao_site'])
    {
      $tem_padrao = true;
      if(empty($reg_data['are_nome_' . $aux['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Nome</b> do idioma padr&atilde;o.');
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
    $aux = array();
    foreach($idiomas as $idi)
    {
      $idi['idi_id'] = b1n_inBd($idi['idi_id']);
      $aux[] = $idi['idi_id'];
      $query = "
        SELECT
          COUNT(are_id) AS count
        FROM
          area
        WHERE
          idi_id = '".$idi['idi_id']."' AND
          are_nome = '".b1n_inBd($reg_data['are_nome_'.$idi['idi_id']])."'";

      $rs = $sql->sqlSingleQuery($query);
      if(is_array($rs) && $rs['count'] > 0)
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe uma area com esse nome para esse idioma ('.$idi['idi_nome'].')');
      }
    }

    // Verificando Ordem
    $query = "
      SELECT
        COUNT(are_id) AS count
      FROM
        area
      WHERE
        are_ordem = '" . b1n_inBd($reg_data['are_ordem']) . "' AND " .
        "(idi_id = " .  implode(" OR idi_id = ", $aux) . ")";

    $rs = $sql->sqlSingleQuery($query);
    if(is_array($rs) && $rs['count'] > 0)
    {
      $query = "
        SELECT
          are_ordem
        FROm
          area
        ORDER BY
          are_ordem DESC";

      $rs = $sql->sqlSingleQuery($query);

      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe uma &Aacute;rea com esse valor de <b>Ordem</b>.<br />O n&uacute;mero de ordem mais alta existente &eacute; <b>'.$rs['are_ordem'].'</b>, tente usar o <b>'.($rs['are_ordem']+1).'</b>');

      return false;
    }
  }

  return $ret;
}

function b1n_regCheckChangeArea($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;

  if(!b1n_checkNumeric($reg_data['are_ordem'], true))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Apenas n&uacute;meros s&atilde;o aceitos no campo <b>Ordem</b>');
  }

  if(!b1n_checkBoolean($reg_data['are_separador']))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Valor inv&aacute;lido em <b>Separador</b>');
  }

  if(!$ret)
  {
    return false;
  }

  if(is_array($idiomas) && sizeof($idiomas))
  {
    $aux = $idiomas;
    $f = array_shift($aux);
    if($f['idi_padrao_site'])
    {
      if(empty($reg_data['are_nome_' . $f['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Nome</b> do idioma padr&atilde;o.');
      }
      if(empty($reg_data['are_cont_' . $f['idi_id']]))
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Conte&uacute;do</b> do idioma padr&atilde;o.');
      }
    }
  }

  // Unicidade da combinacao
  if($ret)
  {
    $aux = array();
    foreach($idiomas as $idi)
    {
      $idi['idi_id'] = b1n_inBd($idi['idi_id']);
      $aux[] = $idi['idi_id'];
      $query = "
        SELECT
          COUNT(are_id) AS count
        FROM
          area
        WHERE
          are_id != '".$reg_data['id']."' AND
          are_real_id != '".$reg_data['id']."' AND
          idi_id = '".$idi['idi_id']."' AND
          are_nome = '".b1n_inBd($reg_data['are_nome_'.$idi['idi_id']])."'";

      $rs = $sql->sqlSingleQuery($query);
      if(is_array($rs) && $rs['count'] > 0)
      {
        $ret = false;
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe uma area com esse nome para esse idioma ('.$idi['idi_nome'].')');
      }
    }

    // Verificando Ordem
    $query = "
      SELECT
        COUNT(are_id) AS count
      FROM
        area
      WHERE
        are_id != '".$reg_data['id']."' AND 
        are_real_id != '".$reg_data['id']."' AND
        are_ordem = '" . b1n_inBd($reg_data['are_ordem']) . "' AND " .
        "(idi_id = " .  implode(" OR idi_id = ", $aux) . ")";

    $rs = $sql->sqlSingleQuery($query);
    if(is_array($rs) && $rs['count'] > 0)
    {
      $query = "
        SELECT
          are_ordem
        FROm
          area
        ORDER BY
          are_ordem DESC";

      $rs = $sql->sqlSingleQuery($query);

      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe uma &Aacute;rea com esse valor de <b>Ordem</b>.<br />O n&uacute;mero de ordem mais alta existente &eacute; <b>'.$rs['are_ordem'].'</b>, tente usar o <b>'.($rs['are_ordem']+1).'</b>');

      return false;
    }
  }
  return $ret;
}

function b1n_regCheckDeleteArea($sql, &$ret_msgs, $reg_data)
{
  if(is_array($reg_data['ids']) && sizeof($reg_data['ids']))
  {
    $query = "SELECT are_nome FROM area WHERE are_codigo IS NOT NULL AND (are_id = " . implode('OR are_id = ', $reg_data['ids']) . ")";
    $rs = $sql->sqlQuery($query);
    if(is_array($rs))
    {
      $aux = array();
      foreach($rs as $a)
      {
        $aux[] = $a['are_nome'];
      }

      if(sizeof($aux) > 1)
      {
        $msg = 'As &aacute;reas ' . implode(', ',$aux) . ' n&atilde;o podem ser apagadas';
      }
      else
      {
        $msg = 'A &aacute;rea ' . $aux[0] . ' n&atilde;o pode ser apagada';
      }
      b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
    }
    else
    {
      return true;
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Voc&ecirc; precisa selecionar algo para ser exclu&iacute;do.');
  }

  return false;
}

// Add
function b1n_regAddArea($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  $reg_data['are_ordem'] = b1n_inBd($reg_data['are_ordem']);
  $reg_data['are_separador'] = b1n_inBd($reg_data['are_separador']);

  if($rs)
  {
    $aux = $idiomas;
    $first = array_shift($aux);

    $query = "
      INSERT INTO area
      (idi_id, are_ordem, are_separador, are_nome, are_cont)
      VALUES
      (
        '" . b1n_inBd($first['idi_id']) . "',
        '" . $reg_data['are_ordem'] . "',
        '" . $reg_data['are_separador'] . "',
        '" . b1n_inBd($reg_data['are_nome_' . $first['idi_id']]) . "',
        '" . b1n_inBd($reg_data['are_cont_' . $first['idi_id']]) . "'
      )";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $query = 'SELECT @@IDENTITY AS id';
      $rs = $sql->sqlSingleQuery($query);

      if(is_array($rs))
      {
        $are_real_id = b1n_inBd($rs['id']);
        foreach($aux as $idi)
        {
          if(!empty($reg_data['are_nome_' . $idi['idi_id']]))
          {
            $query = "
              INSERT INTO area
              (
                are_real_id, idi_id, are_nome, are_cont, are_ordem, are_separador
              )
              VALUES
              (
                '" . $are_real_id . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                '" . b1n_inBd($reg_data['are_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['are_cont_' . $idi['idi_id']]) . "',
                '" . $reg_data['are_ordem'] . "',
                '" . $reg_data['are_separador'] . "'
              )";
            $ret = $ret && $sql->sqlQuery($query);
          }
        }

        if($ret)
        {
          if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
          {
            b1n_retMsg($ret_msgs, b1n_SUCCESS, '&Aacute;rea adicionada com sucesso!');
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
function b1n_regChangeArea($sql, &$ret_msgs, $reg_data)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $aux = $idiomas;
    $first = array_shift($aux);

    $query = "
      UPDATE area SET
        are_nome = '" . b1n_inBd($reg_data['are_nome_' . $first['idi_id']]) . "',
        are_cont = '" . b1n_inBd($reg_data['are_cont_' . $first['idi_id']]) . "',
        are_ordem = '" . $reg_data['are_ordem'] . "',
        are_separador = '" . $reg_data['are_separador'] . "'
      WHERE
        are_id = '".$reg_data['id']."'";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $are_real_id = b1n_inBd($rs['id']);
      foreach($aux as $idi)
      {
        if(!empty($reg_data['are_nome_' . $idi['idi_id']]))
        {
          $query = "SELECT are_id FROM area WHERE are_real_id = '".$reg_data['id']."' AND idi_id = '".$idi['idi_id']."'";
          $rs = $sql->sqlSingleQuery($query);
          if($rs['are_id'])
          {
            $query = "
              UPDATE area SET
                are_nome = '" . b1n_inBd($reg_data['are_nome_'.$idi['idi_id']]) . "',
                are_cont = '" . b1n_inBd($reg_data['are_cont_'.$idi['idi_id']]) . "',
                are_ordem = '" . $reg_data['are_ordem'] . "',
                are_separador = '" . $reg_data['are_separador'] . "'
              WHERE
                are_id = '" . $rs['are_id'] . "'";
          }
          else
          {
            $query = "
              INSERT INTO area
              (
                are_real_id,
                idi_id,
                are_nome,
                are_cont,
                are_ordem
              )
              VALUES
              (
                '" . $reg_data['id'] . "',
                '" . b1n_inBd($idi['idi_id']) . "',
                '" . b1n_inBd($reg_data['are_nome_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['are_cont_' . $idi['idi_id']]) . "',
                '" . b1n_inBd($reg_data['are_ordem']) . "'
              )";
          }

          $ret = $ret && $sql->sqlQuery($query);
        }
      }

      if($ret)
      {
        if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, '&Aacute;rea alterada com sucesso!');
          return true;
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Unexpected error.');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not update.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false;
}

// Delete
function b1n_regDeleteArea($sql, &$ret_msgs, $reg_data)
{
  // Plural
  if(sizeof($reg_data['ids']) > 1 && !empty($msg_plural))
  {
    $msg = '&Aacute;reas exclu&iacute;das';
  }
  else
  {
    $msg = '&Aacute;rea exclu&iacute;da';
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    $query = 'DELETE FROM area WHERE are_id IS NULL';

    foreach($reg_data['ids'] as $id)
    {
      $query .= " OR are_id = '" . b1n_inBd($id) . "'";
    }

    $rs = $sql->sqlQuery($query, 'del');

    if($rs)
    {
      b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' com sucesso!');
      return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Delete.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Begin Transaction.');
    return false;
  }
  
  $sql->sqlQuery('ROLLBACK TRANSACTION');
  return false; 
}

// Activate
function b1n_regToggleActivationArea($sql, &$ret_msgs, $id)
{
  $ret = b1n_regToggleActivation($sql, $ret_msgs, 'are_id', $id, 'are_ativo', 'area');

  // Atualizando filhos
  if($ret)
  {
    $id = b1n_inBd($id);
    $ret = $sql->sqlQuery("UPDATE area SET are_ativo = (SELECT are_ativo FROM area WHERE are_id = '".$id."') WHERE are_real_id = '".$id."'");
  }

  return $ret;
}

// Load
function b1n_regLoadArea($sql, &$ret_msgs, &$reg_data)
{
  global $idiomas;
  $id = b1n_inBd($reg_data['id']);

  $query = "
    SELECT
      are_real_id, are_id,
      are_nome, are_ordem,
      are_separador,
      are_cont, idi_id
    FROM
      area
    WHERE
      are_id = '" . $id . "'";

  $rs = $sql->sqlSingleQuery($query);

  if(is_array($rs))
  {
    // Registrando valores para a area 
    $reg_data['are_nome_' . $rs['idi_id']] = $rs['are_nome'];
    $reg_data['are_cont_' . $rs['idi_id']] = $rs['are_cont'];

    $reg_data['are_ordem'] = $rs['are_ordem'];
    $reg_data['are_separador'] = $rs['are_separador'];

    // Verificando se eh um idioma real
    if(empty($rs['are_real_id']))
    {
      // Sim, eh um idioma real
      // Pegando dados e possiveis filhos
      $query = "
        SELECT
          are_id, are_nome, are_cont, idi_id
        FROM
          area
        WHERE
          are_real_id = '" . $id . "'";

      $filhos = $sql->sqlQuery($query);

      if(is_array($filhos))
      {
        foreach($filhos as $f)
        {
          $reg_data['are_nome_' . $f['idi_id']] = $f['are_nome'];
          $reg_data['are_cont_' . $f['idi_id']] = $f['are_cont'];
        }
      }
    }
    return true;
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'ID not Registered.');
  }

  return false;
}

// Search
function b1n_regSearchArea($sql, $search)
{
  $config['possible_fields'] = array(
    'Idioma'  => 'idi_nome',
    'Nome'    => 'are_nome',
    'Ordem'   => 'are_ordem',
    'Separador'  => 'are_separador',
    'Conte&uacute;do'  => 'are_cont');

  $config['select_fields'] = array(
    'Real'    => 'are_real_id',
    'Idioma'  => 'idi_nome',
    'Nome'    => 'are_nome',
    'Ordem'   => 'are_ordem',
    'Codigo'  => 'are_codigo',
    'Separador' => 'are_separador',
    'Ativo' => 'are_ativo');
  
  $config['possible_quantities'] = array(
    10=>10, 15=>15, 20=>20, 25=>25, 30=>30);

  $config['session_hash_name']  = 'area';
  $config['id_field'] = 'are_id';
  $config['table']    = 'view_area';

  #$search['order_type'] = 'ASC';
  #$search['quantity'] = '10';
  #$search['pg'] = '1';
  #$search['field'] = 'are_ordem';
  #echo "<pre>";
  #print_r($config);
  #print_r($search);
  #echo "</pre>";

  return b1n_searchG($sql, $config, $search);
}
?>
