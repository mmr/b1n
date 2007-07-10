<?
// $Id: fasciculo.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Check
function b1n_regCheckFasciculo($sql, &$ret_msgs, $reg_data)
{
  $ret = true;

  if(empty($reg_data['fas_seq_num']))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Num Seq</b>.');
  }

  if(!b1n_checkNumeric($reg_data['fas_seq_num'], true))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Num Seq deve ser um campo num&eacute;rico.');
  }

  if($ret)
  {
    $query = "
      SELECT fas_id
      FROM fasciculo WHERE
        fas_seq_num = '" . b1n_inBd($reg_data['fas_seq_num']) . "'";

    $rs = $sql->sqlSingleQuery($query);
    if(is_array($rs))
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe um fasc&iacute;culo com esse <b>N&uacute;mero Sequencial</b>.');
      $ret = false;
    }
  }

  return $ret;
}

function b1n_regCheckChangeFasciculo($sql, &$ret_msgs, $reg_data)
{
  $ret = true;

  if(empty($reg_data['fas_seq_num']))
  {
    $ret = false;
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>Num Seq</b>.');
  }

  if($ret)
  {
    $query = "
      SELECT fas_id
      FROM fasciculo WHERE
        fas_seq_num = '" . b1n_inBd($reg_data['fas_seq_num']) . "' AND
        fas_id != '" . b1n_inBd($reg_data['id']) . "'";

    $rs = $sql->sqlSingleQuery($query);
    if(is_array($rs))
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe um fasc&iacute;culo com esse <b>N&uacute;mero Sequencial</b>.');
      $ret = false;
    }
  }

  return $ret;
}

function b1n_regCheckDeleteFasciculo($sql, &$ret_msgs, $reg_data)
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
function b1n_regAddFasciculo($sql, &$ret_msgs, $reg_data)
{
  $mandou_capa = 0;
  $capa_tipo = '';

  // Verificando Upload de arquivo
  if(is_writable(b1n_UPLOAD_DIR_CAPA))
  {
    if(!empty($_FILES['fas_capa']['name']))
    {
      if(is_uploaded_file($_FILES['fas_capa']['tmp_name']) &&
        $_FILES['fas_capa']['error'] == 0 &&
        $_FILES['fas_capa']['size']  != 0)
      {
        $mandou_capa = 1;

        // Pegando tipo do arquivo
        switch($_FILES['fas_capa']['type'])
        {
        case 'image/jpeg':
        case 'image/pjpeg':
          $capa_tipo = 'jpg';
          break;
        case 'image/png':
          $capa_tipo = 'png';
          break;
        case 'image/gif':
          $capa_tipo = 'gif';
          break;
        default:
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos JPG, GIF e PNG s&atilde;o permitidos.');
          $mandou_capa = 0;
          break;
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_CAPA . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $query = "
      INSERT INTO fasciculo
      (
        fas_vol_num, fas_num,
        fas_seq_num, fas_capa, fas_capa_tipo
      )
      VALUES
      (
        '" . b1n_inBd($reg_data['fas_vol_num']) . "',
        '" . b1n_inBd($reg_data['fas_num']) . "',
        '" . b1n_inBd($reg_data['fas_seq_num']) . "',
        '" . b1n_inBd($mandou_capa) . "',
        '" . b1n_inBd($capa_tipo) . "'
      )";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $commit = true;

      if($mandou_capa)
      {
        $commit = false;

        // pegando id do fasciculo para gravar na capa
        $rs = $sql->sqlSingleQuery('SELECT @@IDENTITY AS id');
        if(is_array($rs) && !empty($rs['id']))
        {
          $arquivo = b1n_UPLOAD_DIR_CAPA . '/' . $rs['id'] . '.' . $capa_tipo;

          // Gravando arquivo
          if(move_uploaded_file($_FILES['fas_capa']['tmp_name'], $arquivo))
          {
            $commit = true;
          }
          else
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao gravar arquivo de upload');
          }
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao pegar ID da insercao');
        }
      }

      if($commit)
      {
        if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Fasc&iacute;culo adicionado com sucesso!');
          return true;
        }
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

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

// Change
function b1n_regChangeFasciculo($sql, &$ret_msgs, $reg_data)
{
  $mandou_capa = 0;
  $capa_tipo = '';

  // Verificando Upload de arquivo
  if(is_writable(b1n_UPLOAD_DIR_CAPA))
  {
    if(!empty($_FILES['fas_capa']['name']))
    {
      if(is_uploaded_file($_FILES['fas_capa']['tmp_name']) &&
        $_FILES['fas_capa']['error'] == 0 &&
        $_FILES['fas_capa']['size']  != 0)
      {
        $mandou_capa = 1;

        // Pegando tipo do arquivo
        switch($_FILES['fas_capa']['type'])
        {
        case 'image/jpeg':
        case 'image/pjpeg':
          $capa_tipo = 'jpg';
          break;
        case 'image/png':
          $capa_tipo = 'png';
          break;
        case 'image/gif':
          $capa_tipo = 'gif';
          break;
        default:
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos JPG, GIF e PNG s&atilde;o permitidos.');
          $mandou_capa = 0;
          break;
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_CAPA . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $query = "
      UPDATE fasciculo
      SET
        fas_vol_num = '" . b1n_inBd($reg_data['fas_vol_num']) . "',
        fas_num = '" . b1n_inBd($reg_data['fas_num']) . "',
        fas_seq_num = '" . b1n_inBd($reg_data['fas_seq_num']) . "'";

    if($mandou_capa)
    {
      $query .= ",
        fas_capa = '1',
        fas_capa_tipo = '" . b1n_inBd($capa_tipo) . "'";
    }

    $query .= "
      WHERE
        fas_id = '" . b1n_inBd($reg_data['id']) . "'";

    $ret = $sql->sqlQuery($query);

    if($ret)
    {
      $commit = true;

      if($mandou_capa)
      {
        $commit = false;

        $arquivo = b1n_UPLOAD_DIR_CAPA . '/' . $reg_data['id'] . '.' . $capa_tipo;

        // Gravando arquivo
        if(move_uploaded_file($_FILES['fas_capa']['tmp_name'], $arquivo))
        {
          $commit = true;
        }
        else
        {
           b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao gravar arquivo de upload');
        }
      }

      if($commit)
      {
        $ret = $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
        if($ret)
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Fasc&iacute;culo alterado com sucesso!');
          return true;
        }
      }
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro inesperado ao tentar atualizar fasciulo');
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
function b1n_regDeleteFasciculo($sql, &$ret_msgs, $reg_data)
{
  // Plural
  if(sizeof($reg_data['ids']) > 1 && !empty($msg_plural))
  {
    $msg = 'Fasc&iacute;culos exclu&iacute;dos';
  }
  else
  {
    $msg = 'Fasc&iacute;culo exclu&iacute;do';
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    // Pegando artigos
    $query = '
      SELECT art_id FROM artigo
      WHERE
        fas_id = ' . implode(' OR fas_id = ', $reg_data['ids']);

    $rs = $sql->sqlQuery($query);

    if(is_array($rs))
    {
      // Pegando biblioteca de artigos
      require(b1n_PATH_REGLIB . '/artigo.lib.php');

      // Apagando artigos
      $ids = array();
      foreach($rs as $art)
      {
        $ids[] = $art['art_id'];
      }
      $aux['ids'] = $ids;

      if(!b1n_regDeleteArtigo($sql, $ret_msgs, $aux, false))
      {
        $sql->sqlQuery('ROLLBACK TRANSACTION');
        return false;
      }
    }

    // Apagando Fasciculos
    // Montando query
    $query = 'DELETE FROM fasciculo WHERE fas_id IS NULL';
    foreach($reg_data['ids'] as $id)
    {
      $query .= " OR fas_id = '" . b1n_inBd($id) . "'";
    }
    $rs = $sql->sqlQuery($query, 'del');

    if($rs)
    {
      // Apagando arquivos de upload
      foreach($reg_data['ids'] as $id)
      {
        // Nao vale a pena fazer error reporting
        @unlink(b1n_UPLOAD_DIR_CAPA . '/' . $id . '.gif');
        @unlink(b1n_UPLOAD_DIR_CAPA . '/' . $id . '.jpg');
        @unlink(b1n_UPLOAD_DIR_CAPA . '/' . $id . '.png');
      }

      $rs = $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
      if($rs)
      {
        b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' com sucesso!');
        return true;
      }
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
function b1n_regToggleActivationFasciculo($sql, &$ret_msgs, $id)
{
  return b1n_regToggleActivation($sql, $ret_msgs, 'fas_id', $id, 'fas_ativo', 'fasciculo');
}

// Load
function b1n_regLoadFasciculo($sql, &$ret_msgs, &$reg_data)
{
  $query = "
    SELECT
      fas_vol_num, fas_num, fas_seq_num,
      fas_capa, fas_capa_tipo
    FROM
      fasciculo
    WHERE
      fas_id = '" . b1n_inBd($reg_data['id']) . "'";

  $rs = $sql->sqlSingleQuery($query);

  if(is_array($rs))
  {
    $reg_data['fas_vol_num']  = $rs['fas_vol_num'];
    $reg_data['fas_num']      = $rs['fas_num'];
    $reg_data['fas_seq_num']  = $rs['fas_seq_num'];
    $reg_data['fas_capa']     = $rs['fas_capa'];
    $reg_data['fas_capa_tipo']= $rs['fas_capa_tipo'];
    $ret = true;
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'ID not Registered.');
    $ret = false;
  }

  return $ret; 
}

// Search
function b1n_regSearchFasciculo($sql, $search)
{
  $config['possible_fields'] = array(
    'Num Seq' => 'fas_seq_num',
    'Artigos' => 'fas_artigos',
    'Volume'  => 'fas_vol_num',
    'N&uacute;mero' => 'fas_num');

  $config['select_fields'] = array(
    'Num Seq' => 'fas_seq_num',
    'Volume'  => 'fas_vol_num',
    'N&uacute;mero' => 'fas_num',
    'Artigos'   => 'fas_artigos',
    'Tem Capa'  => 'fas_capa',
    'Ativo'     => 'fas_ativo');
  
  $config['possible_quantities'] = array(
    10=>10, 15=>15, 20=>20, 25=>25, 30=>30);

  $config['default_order_type'] = 'DESC';

  $config['session_hash_name']  = 'fasciculo';
  $config['id_field'] = 'fas_id';
  $config['table']    = 'view_fasciculo';

  return b1n_searchG($sql, $config, $search);
}

?>
