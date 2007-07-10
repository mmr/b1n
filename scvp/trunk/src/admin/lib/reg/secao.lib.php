<?
// $Id: secao.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

function b1n_regAddSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  global $idiomas;

  $ret = true;

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $rs = $sql->sqlSingleQuery("SELECT idi_id FROM idioma WHERE idi_padrao_pub = 1");

    if($rs)
    {
      $idi_padrao_nome = b1n_inBd($reg_data['sec_nome_' . $rs['idi_id']]);
      $idi_padrao_desc = b1n_inBd($reg_data['sec_desc_' . $rs['idi_id']]);

      $query = "
        INSERT INTO secao
          (idi_id, sec_nome, sec_desc)
        VALUES
        (
          '" . b1n_inBd($rs['idi_id']) . "',
          '" . $idi_padrao_nome . "',
          '" . $idi_padrao_desc . "'
        )";

      $rs = $sql->sqlQuery($query);

      if($rs)
      {
        $query = 'SELECT @@IDENTITY AS id'; 
        $rs = $sql->sqlSingleQuery($query);
        
        if($rs)
        {
          $sec_real_id = b1n_inBd($rs['id']);

          // Tirando o padrao
          array_shift($idiomas);

          foreach($idiomas as $idi)
          {
            if(!empty($reg_data['sec_nome_' . $idi['idi_id']]))
            {
              $query = "
                INSERT INTO secao
                  (idi_id, sec_real_id, sec_nome, sec_desc)
                VALUES
                (
                  '" . b1n_inBd($idi['idi_id']) . "',
                  '" . $sec_real_id . "',
                  '" . b1n_inBd($reg_data['sec_nome_' . $idi['idi_id']]) . "',
                  '" . b1n_inBd($reg_data['sec_desc_' . $idi['idi_id']]) . "'
                )";

              $ret = $ret && $sql->sqlQuery($query);
            }
          }

          if($ret)
          {
            b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Se&ccedil;&atilde;o adicionada com sucesso!');
            return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
          }
          else
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro inesperado ao tentar incluir secoes em idiomas diferentes do padrao.');
          }
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar @@identity.');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir secao usando idioma padrao.');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar idioma padrao.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu iniciar transaction.');
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false;
}

function b1n_regCheckSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  $ret = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);

  if($ret)
  {
    $rs = $sql->sqlSingleQuery("SELECT idi_id FROM idioma WHERE idi_padrao_pub = 1");
    if($rs)
    {
      if(empty($reg_data['sec_nome_' . $rs['idi_id']]))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o campo <b>nome</b> da se&ccedil;&atilde;o no idioma padr&atilde;o.');
        $ret = false;
      }
      else
      {
        $ret = true;
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar idioma padrao.');
      $ret = false;
    }
  }

  return $ret;
}

function b1n_regCheckChangeSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  global $idiomas;
  $ret = true;
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  $id = b1n_inBd($reg_data['id']);

  if($rs)
  {
    $rs = $sql->sqlSingleQuery("SELECT idi_id FROM idioma WHERE idi_padrao_pub = 1");

    if($rs)
    {
      if(!isset($reg_data['sec_nome_' . $rs['idi_id']]))
      {
        // Idioma padrao nao enviado, eh um idioma filho
        $ret = $sql->sqlQuery("UPDATE secao SET sec_nome = '".b1n_inBd($reg_data['sec_nome'])."', sec_desc = '".b1n_inBd($reg_data['sec_desc'])."' WHERE sec_id = '".$id."'");
        if($ret)
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Se&ccedil;&atilde;o alterada com sucesso!');
          return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Erro inesperado ao tentar atualizar secao.');
          $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
          return false;
        }
      }

      $idi_padrao_nome = b1n_inBd($reg_data['sec_nome_' . $rs['idi_id']]);
      $idi_padrao_desc = b1n_inBd($reg_data['sec_desc_' . $rs['idi_id']]);

      $query = "
        UPDATE secao SET
          idi_id = '" . b1n_inBd($rs['idi_id']) . "',
          sec_nome = '" . b1n_inBd($idi_padrao_nome) . "',
          sec_desc = '" . b1n_inBd($idi_padrao_desc) . "'
        WHERE
          sec_id = '" . $id . "'";

      $rs = $sql->sqlQuery($query);

      if($rs)
      {
        // Tirando o padrao
        array_shift($idiomas);

        foreach($idiomas as $idi)
        {
          if(!empty($reg_data['sec_nome_' . $idi['idi_id']]))
          {
            $query = "
              UPDATE secao SET
                sec_nome = '" . b1n_inBd($reg_data['sec_nome_' . $idi['idi_id']]) . "',
                sec_desc = '" . b1n_inBd($reg_data['sec_desc_' . $idi['idi_id']]) . "'
              WHERE
                sec_real_id = '" . $id . "' AND
                idi_id = '" . $idi['idi_id'] . "'";

            // Vendo se consegue atualizar
            $ret = $ret && $sql->sqlQuery($query);
            if($ret)
            {
              // Conseguiu, ir para o proximo
              continue;
            }
            else
            {
              // Nao conseguiu atualizar, idioma novo, precisamos inserir
              $query = "
                INSERT INTO secao
                  (idi_id, sec_real_id, sec_nome, sec_desc)
                VALUES
                (
                  '" . b1n_inBd($idi['idi_id']) . "',
                  '" . $id . "',
                  '" . b1n_inBd($reg_data['sec_nome_' . $idi['idi_id']]) . "',
                  '" . b1n_inBd($reg_data['sec_desc_' . $idi['idi_id']]) . "'
                )";

              $ret = $sql->sqlQuery($query);
            }
          }
        }

        if($ret)
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Se&ccedil;&atilde;o alterada com sucesso!');
          return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro inesperado ao tentar incluir secoes em idiomas diferentes do padrao.');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu aletarar secao usando idioma padrao.');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar idioma padrao.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu iniciar transaction.');
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false;
}

function b1n_regCheckDeleteSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteSecao($sql, &$ret_msgs, $reg_data, $reg_config)
{
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    $q1 = 'DELETE FROM secao WHERE sec_id IS NULL';
    $q2 = '';

    foreach($reg_data['ids'] as $id)
    {
      $id = b1n_inBd($id);
      $q1 .= " OR sec_real_id = '" . $id . "'";
      $q2 .= " OR sec_id = '" . $id . "'";
    }

    $query = $q1 . $q2;

    $rs = $sql->sqlQuery($query, 'del');

    if($rs)
    {
      b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Se&ccedil;&otilde;es exclu&iacute;do(a) com sucesso!');
      return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Delete .');
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

function b1n_regLoadSecao($sql, &$ret_msgs, &$reg_data, &$reg_config)
{
  global $idiomas;
  $id = b1n_inBd($reg_data['id']);

  $query = "SELECT sec_real_id FROM secao WHERE sec_id = '" . $id . "'";
  $rs = $sql->sqlSingleQuery($query);
  $ret = false;

  if(is_array($rs))
  {
    if(empty($rs['sec_real_id']))
    {
      $query = "
        SELECT
          sec_id, sec_nome, sec_desc, idi_id
        FROM
          secao
        WHERE
          sec_real_id = '" . $id . "' OR sec_id = '" . $id . "'";

      $filhos = $sql->sqlQuery($query);

      if(is_array($filhos))
      {
        foreach($filhos as $f)
        {
          $reg_data['sec_nome_' . $f['idi_id']] = $f['sec_nome'];
          $reg_data['sec_desc_' . $f['idi_id']] = $f['sec_desc'];
        }
      }

      if(is_array($idiomas) && sizeof($idiomas))
      {
        foreach($idiomas as $idi)
        {
          $reg_config[$idi['idi_nome'] . ' - Nome'] = array(
            'reg_data'  => 'sec_nome_' . $idi['idi_id'],
            'db'    => 'sec_nome',
            'check' => 'none',
            'type'  => 'text',
            'extra' => array(
              'size'    => b1n_DEFAULT_SIZE,
              'maxlen'  => b1n_DEFAULT_MAXLEN),
            'load'    => true,
            'mand'    => false);

          $reg_config[$idi['idi_nome'] . ' - Desc'] = array(
            'reg_data'  => 'sec_desc_' . $idi['idi_id'],
            'db'    => 'sec_desc',
            'check' => 'none',
            'type'  => 'text',
            'extra' => array(
              'size'    => b1n_DEFAULT_SIZE,
              'maxlen'  => b1n_DEFAULT_MAXLEN),
            'load'    => true,
            'mand'    => false);

          if(!isset($reg_data['sec_nome_' . $idi['idi_id']]))
          {
            $reg_data['sec_nome_' . $idi['idi_id']] = '';
            $reg_data['sec_desc_' . $idi['idi_id']] = '';
          }
        }
      }
      $ret = true;
    }
    else
    {
      $ret = true;
      $reg_config = array(
        'ID' => array(
          'reg_data'  => 'id',
          'db'      => 'sec_id',
          'check'   => 'none',
          'type'    => 'none',
          'load'    => false,
          'mand'    => false),
        'Nome' => array(
          'reg_data'  => 'sec_nome',
          'db'    => 'sec_nome',
          'check' => 'none',
          'type'  => 'text',
          'extra' => array(
            'size'    => b1n_DEFAULT_SIZE,
            'maxlen'  => b1n_DEFAULT_MAXLEN),
          'load'    => true,
          'mand'    => false),
        'Desc' => array(
          'reg_data'  => 'sec_desc',
          'db'    => 'sec_desc',
          'check' => 'none',
          'type'  => 'text',
          'extra' => array(
            'size'    => b1n_DEFAULT_SIZE,
            'maxlen'  => b1n_DEFAULT_MAXLEN),
          'load'    => true,
          'mand'    => false));

      //$reg_data = b1n_regExtract($reg_config);
      $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, 'secao');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'ID not Registered.');
  }

  return $ret;
}

function b1n_regToggleActivationSecao($sql, &$ret_msgs, $col_id, $id, $activate_field, $table = '') 
{
  $ret = b1n_regToggleActivation($sql, $ret_msgs, $col_id, $id, $activate_field, $table);

  // Atualizando filhos
  if($ret)
  {
    $id = b1n_inBd($id);
    $ret = $sql->sqlQuery("UPDATE secao SET sec_ativo = (SELECT sec_ativo FROM secao WHERE sec_id = '".$id."') WHERE sec_real_id = '".$id."'");
  }

  return $ret;
}
?>
