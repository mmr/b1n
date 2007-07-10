<?
// $Id: artigo.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
// Check
function b1n_regCheckArtigo($sql, &$ret_msgs, $reg_data)
{
  $ret_msgs = array();

  // Verifiando se preencheu todos idiomas do front
  if(empty($reg_data['qt_idioma']) ||
    !b1n_checkNumeric($reg_data['qt_idioma']) ||
    $reg_data['qt_idioma'] < 1 ||
    $reg_data['qt_idioma'] > 5)
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Escolha a quantidade de Idiomas no Front');
    return false;
  }

  for($i=1; $i<=$reg_data['qt_idioma']; $i++)
  {
    if(empty($reg_data['front_idi_id'][$i]))
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, selecione o idioma no Front '.$i);
    }

    #if(empty($reg_data['titulo'][$i]))
    #{
    #  b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o t&iacute;tulo no Front '.$i);
    #}
  }

  if(empty($reg_data['body_idi_id']))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, selecione o idioma do Body');
  }

  if(empty($reg_data['ordem']) || !b1n_checkNumeric($reg_data['ordem']))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Digite o n&uacute;mero de ordem do Artigo');
  }

  if(sizeof($ret_msgs) == 0)
  {
    $query = "
      SELECT
        COUNT(art_id) AS count
      FROM
        artigo
      WHERE
        fas_id = '" . b1n_inBd($reg_data['fas_id']) . "' AND
        art_ordem = '".b1n_inBd($reg_data['ordem']) . "'";

    $rs = $sql->sqlSingleQuery($query);
    if($rs['count'] > 0)
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe um artigo para esse fasc&iacute;culo com n&uacute;mero de ordem ' . $reg_data['ordem']);
      $ret = false;
    }
  }

  return (sizeof($ret_msgs) == 0);
}

function b1n_regCheckChangeArtigo($sql, &$ret_msgs, $reg_data)
{
  $ret_msgs = array();

  // Verifiando se preencheu todos idiomas do front
  if(empty($reg_data['qt_idioma']) ||
    !b1n_checkNumeric($reg_data['qt_idioma']) ||
    $reg_data['qt_idioma'] < 1 ||
    $reg_data['qt_idioma'] > 5)
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Escolha a quantidade de Idiomas no Front');
    return false;
  }

  for($i=1; $i<=$reg_data['qt_idioma']; $i++)
  {
    if(empty($reg_data['front_idi_id'][$i]))
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, selecione o idioma no Front '.$i);
    }

    #if(empty($reg_data['titulo'][$i]))
    #{
    #  b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, preencha o t&iacute;tulo no Front '.$i);
    #}
  }

  if(empty($reg_data['body_idi_id']))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Por favor, selecione o idioma do Body');
  }

  if(empty($reg_data['ordem']) || !b1n_checkNumeric($reg_data['ordem']))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Digite o n&uacute;mero de ordem do Artigo');
  }

  if(sizeof($ret_msgs) == 0)
  {
    $query = "
      SELECT
        COUNT(art_id) AS count
      FROM
        artigo
      WHERE
        art_id != '" . b1n_inBd($reg_data['id']) . "' AND
        fas_id = '" . b1n_inBd($reg_data['fas_id']) . "' AND
        art_ordem = '".b1n_inBd($reg_data['ordem']) . "'";

    $rs = $sql->sqlSingleQuery($query);
    if($rs['count'] > 0)
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'J&aacute; existe um artigo para esse fasc&iacute;culo com n&uacute;mero de ordem ' . $reg_data['ordem']);
      $ret = false;
    }
  }

  return (sizeof($ret_msgs) == 0);
}

function b1n_regCheckDeleteArtigo($sql, &$ret_msgs, $reg_data)
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
function b1n_regAddArtigo($sql, &$ret_msgs, $reg_data)
{
  $mandou_pdf  = 0;
  $mandou_html = 0;
  $arquivo_html = '';

  // PDF
  if(is_writable(b1n_UPLOAD_DIR_ARTIGO_PDF))
  {
    if(!empty($_FILES['arquivo_pdf']['name']))
    {
      if(is_uploaded_file($_FILES['arquivo_pdf']['tmp_name']) &&
        $_FILES['arquivo_pdf']['error'] == 0 &&
        $_FILES['arquivo_pdf']['size']  != 0)
      {
        if(b1n_cmp($_FILES['arquivo_pdf']['type'], 'application/pdf'))
        {
          $mandou_pdf = 1;
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos PDF s&atilde;o suportados em PDF');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido em PDF.');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_ARTIGO_PDF . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  // HTML
  if(is_writable(b1n_UPLOAD_DIR_ARTIGO_HTML))
  {
    if(!empty($_FILES['arquivo_html']['name']))
    {
      if(is_uploaded_file($_FILES['arquivo_html']['tmp_name']) &&
        $_FILES['arquivo_html']['error'] == 0 &&
        $_FILES['arquivo_html']['size']  != 0)
      {
        if(b1n_cmp($_FILES['arquivo_html']['type'], 'application/x-zip-compressed'))
        {
          if(!empty($_FILES['arquivo_html']['tmp_name']))
          {
            $ret = b1n_unzipCheckHtml($_FILES['arquivo_html']['tmp_name']);
            if($ret[0])
            {
              $mandou_html  = 1;
              $arquivo_html = $ret[1];
            }
            else
            {
              b1n_retMsg($ret_msgs, b1n_FIZZLES, $ret[1]);
            }
          }
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos ZIP s&atilde;o suportados em ZIP');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido em ZIP.');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_ARTIGO_HTML . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $query = "
      INSERT INTO artigo
      (
        idi_id,
        fas_id,
        sec_id,

        art_ordem,
        art_pag_ini,
        art_pag_fin,
        art_pdf,
        art_html,
        art_html_pag
      )
      VALUES
      (
        '" . b1n_inBd($reg_data['body_idi_id']) . "',
        '" . b1n_inBd($reg_data['fas_id']) . "',
        " . (empty($reg_data['sec_id'])?'NULL':"'".b1n_inBd($reg_data['sec_id'])."'").",
        '" . b1n_inBd($reg_data['ordem']) . "',
        '" . b1n_inBd($reg_data['pag_ini']) . "',
        '" . b1n_inBd($reg_data['pag_fin']) . "',
        '" . b1n_inBd($mandou_pdf)  . "',
        '" . b1n_inBd($mandou_html) . "',
        '" . b1n_inBd($arquivo_html). "'
      )";

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      // Pegando ID do artigo
      $rs = $sql->sqlSingleQuery("SELECT @@IDENTITY AS art_id");

      if(is_array($rs) && !empty($rs['art_id']))
      {
        $art_id = b1n_inBd($rs['art_id']);

        // ----------- (titulos, resumos, palavras-chave)
        for($i=1; $i<=$reg_data['qt_idioma']; $i++)
        {
          // Inserindo Titulo e Resumo para esse Idioma
          $query = "
            INSERT INTO artigo_idioma
            (
              idi_id,
              art_id,
              aid_titulo,
              aid_resumo
            )
            VALUES
            (
              '" . b1n_inBd($reg_data['front_idi_id'][$i]) . "',
              '" . $art_id . "',
              '" . b1n_inBd($reg_data['titulo'][$i]) . "',
              '" . b1n_inBd($reg_data['resumo'][$i]) . "'
            )";

          $rs = $sql->sqlQuery($query);

          if($rs)
          {
            // Pegando ID do artigo_idioma
            $rs = $sql->sqlSingleQuery("SELECT @@IDENTITY AS aid_id");

            if(is_array($rs) && !empty($rs['aid_id']))
            {
              $aid_id = b1n_inBd($rs['aid_id']);
              $rs = true;

              // Inserindo palavras-chave
              for($j=1; $j<=$reg_data['qt_palchave'][$i]; $j++)
              {
                if(empty($reg_data['palchave'][$i][$j]))
                {
                  continue;
                }

                $query = "
                  INSERT INTO palchave
                  (
                    aid_id,
                    pch_cont
                  )
                  VALUES
                  (
                    '" . $aid_id . "',
                    '" . b1n_inBd($reg_data['palchave'][$i][$j]) . "'
                  )";

                if(!$sql->sqlQuery($query))
                {
                  b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir palavra chave, abortando');
                  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
                  return false;
                }
              } // PalChave
            }   // Pegou aid_id
            else
            {
              b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar aid_id, abortando');
              $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
              return false;
            }
          } // Conseguiu inserir artigo_idioma
          else
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir artigo_idioma');
            $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
            return false;
          }
        }

        // ----------- (autores)
        for($i=1; $i<=$reg_data['qt_autor']; $i++)
        {
          if(empty($reg_data['aut_prinome'][$i]) || empty($reg_data['aut_sobnome'][$i]))
          {
            continue;
          }

          $query = "
            INSERT INTO autor
            (art_id, aut_prinome, aut_sobnome)
            VALUES
            (
              '".$art_id."',
              '".b1n_inBd($reg_data['aut_prinome'][$i])."',
              '".b1n_inBd($reg_data['aut_sobnome'][$i])."'
            )";

          if(!$sql->sqlQuery($query))
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir autor, abortando');
            $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
            return false;
          }
        }
      } // Pegou art_id
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar art_id, abortando');
        $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
        return false;
      }
    } // Conseguiu inserir artigo
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir artigo, abortando');
      $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
      return false;
    }
  } // conseguiu iniciar Transaction
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu iniciar transaction');
    return false;
  }

  $commit = true;

  // Upload PDF
  if($mandou_pdf)
  {
    $commit = false;

    $arquivo = b1n_UPLOAD_DIR_ARTIGO_PDF . '/' . $art_id . '.pdf';

    // Gravando arquivo
    if(move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $arquivo))
    {
      $commit = true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao gravar arquivo de upload (PDF)');
    }
  }

  // Upload HTML
  if($mandou_html)
  {
    $commit = false;

    // Deszipando arquivo
    $arq = $_FILES['arquivo_html']['tmp_name'];
    $dir = '../upload/html/' . $art_id . '/';
    if(b1n_unzip($arq, $dir))
    {
      $commit = true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao descomprimir arquivo ZIP');
    }
  }

  if($commit)
  {
    if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
    {
      b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Artigo adicionado com sucesso!');
      return true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu completar transacao');
    }
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

// Change
function b1n_regChangeArtigo($sql, &$ret_msgs, $reg_data)
{
  $mandou_pdf  = 0;
  $mandou_html = 0;
  $arquivo_html = '';
  $art_id = b1n_inBd($reg_data['id']);

  // PDF
  if(is_writable(b1n_UPLOAD_DIR_ARTIGO_PDF))
  {
    if(!empty($_FILES['arquivo_pdf']['name']))
    {
      if(is_uploaded_file($_FILES['arquivo_pdf']['tmp_name']) &&
        $_FILES['arquivo_pdf']['error'] == 0 &&
        $_FILES['arquivo_pdf']['size']  != 0)
      {
        if(b1n_cmp($_FILES['arquivo_pdf']['type'], 'application/pdf'))
        {
          $mandou_pdf = 1;
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos PDF s&atilde;o suportados em PDF');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido em PDF.');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_ARTIGO_PDF . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  // HTML
  if(is_writable(b1n_UPLOAD_DIR_ARTIGO_HTML))
  {
    if(!empty($_FILES['arquivo_html']['name']))
    {
      if(is_uploaded_file($_FILES['arquivo_html']['tmp_name']) &&
        $_FILES['arquivo_html']['error'] == 0 &&
        $_FILES['arquivo_html']['size']  != 0)
      {
        if(b1n_cmp($_FILES['arquivo_html']['type'], 'application/x-zip-compressed'))
        {
          if(!empty($_FILES['arquivo_html']['tmp_name']))
          {
            $ret = b1n_unzipCheckHtml($_FILES['arquivo_html']['tmp_name']);
            if($ret[0])
            {
              $mandou_html  = 1;
              $arquivo_html = $ret[1];
            }
            else
            {
              b1n_retMsg($ret_msgs, b1n_FIZZLES, $ret[1]);
            }
          }
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Tipo de arquivo inv&aacute;lido, apenas arquivos ZIP s&atilde;o suportados em ZIP');
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Arquivo de upload inv&aacute;lido em ZIP.');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Diret&oacute;rio ' . b1n_UPLOAD_DIR_ARTIGO_HTML . ' n&atilde;o existe ou n&atilde;o pode ser escrito.');
  }

  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $query = "
      UPDATE artigo SET
        idi_id = '" . b1n_inBd($reg_data['body_idi_id']) . "',
        sec_id = " . (empty($reg_data['sec_id'])?'NULL':"'".b1n_inBd($reg_data['sec_id'])."'").",
        art_ordem = '"    . b1n_inBd($reg_data['ordem']) . "',
        art_pag_ini = '"  . b1n_inBd($reg_data['pag_ini']) . "',
        art_pag_fin = '"  . b1n_inBd($reg_data['pag_fin']) . "'";

    if($mandou_pdf)
    {
      $query .= ",
        art_pdf = 1";
    }

    if($mandou_html)
    {
      $query .= ",
        art_html = 1,
        art_html_pag = '" . b1n_inBd($arquivo_html) . "'";
    }

    $query .= "
      WHERE
        art_id = '" . $art_id . "'";

    $rs = $sql->sqlQuery($query, 'update');

    if($rs)
    {
      // Deletando itens
      // artigo_idioma e autores
      // (as palavras-chave serao excluidas pela constraint -CASCADE)
      $query = "
        DELETE FROM artigo_idioma
        WHERE
          art_id = '" . $art_id . "'";
      $rs1 = $sql->sqlQuery($query, 'del');

      $query = "
        DELETE
          FROM autor
        WHERE
          art_id = '" . $art_id . "'";
      $rs2 = $sql->sqlQuery($query, 'del');

      if(!$rs1)
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu desvincular itens velhos, abortando');
        $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
        return false;
      }

      if(!$rs2)
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu desvincular autores velhos, abortando');
        $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
        return false;
      }

      // Recadastrando itens
      // ARTIGO_IDIOMA (titulos, resumos, palavras-chave)
      for($i=1; $i<=$reg_data['qt_idioma']; $i++)
      {
        // Inserindo Titulo e Resumo para esse Idioma
        $query = "
          INSERT INTO artigo_idioma
          (
            idi_id,
            art_id,
            aid_titulo,
            aid_resumo
          )
          VALUES
          (
            '" . b1n_inBd($reg_data['front_idi_id'][$i]) . "',
            '" . $art_id . "',
            '" . b1n_inBd($reg_data['titulo'][$i]) . "',
            '" . b1n_inBd($reg_data['resumo'][$i]) . "'
          )";

        $rs = $sql->sqlQuery($query);

        if($rs)
        {
          // Pegando ID do artigo_idioma
          $rs = $sql->sqlSingleQuery("SELECT @@IDENTITY AS aid_id");

          if(is_array($rs) && !empty($rs['aid_id']))
          {
            $aid_id = b1n_inBd($rs['aid_id']);
            $rs = true;

            // Inserindo palavras-chave
            for($j=1; $j<=$reg_data['qt_palchave'][$i]; $j++)
            {
              if(empty($reg_data['palchave'][$i][$j]))
              {
                continue;
              }

              $query = "
                INSERT INTO palchave
                (
                  aid_id,
                  pch_cont
                )
                VALUES
                (
                  '" . $aid_id . "',
                  '" . b1n_inBd($reg_data['palchave'][$i][$j]) . "'
                )";

              if(!$sql->sqlQuery($query))
              {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir palavra-chave, abortando');
                $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
                return false;
              }
            } // PalChave
          }   // Pegou aid_id
          else
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu pegar aid_id, abortando');
            $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
            return false;
          }
        } // Conseguiu inserir artigo_idioma
        else
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir artigo_idioma');
          $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
          return false;
        }
      }

      // AUTORES
      for($i=1; $i<=$reg_data['qt_autor']; $i++)
      {
        if(empty($reg_data['aut_prinome'][$i]) || empty($reg_data['aut_sobnome'][$i]))
        {
          continue;
        }

        $query = "
          INSERT INTO autor
            (art_id, aut_prinome, aut_sobnome)
          VALUES
            (
              '".$art_id."',
              '".b1n_inBd($reg_data['aut_prinome'][$i])."',
              '".b1n_inBd($reg_data['aut_sobnome'][$i])."'
            )";

        if(!$sql->sqlQuery($query))
        {
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu inserir autor, abortando');
          $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
          return false;
        }
      }
    } // Conseguiu atualizar artigo
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu atualizar artigo, abortando');
      $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
      return false;
    }
  } // conseguiu iniciar Transaction
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu iniciar transaction');
    return false;
  }

  $commit = true;

  // Upload PDF
  if($mandou_pdf)
  {
    $commit = false;
    $arquivo = b1n_UPLOAD_DIR_ARTIGO_PDF . '/' . $art_id . '.pdf';

    // Gravando arquivo
    if(move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $arquivo))
    {
      $commit = true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao gravar arquivo de upload (PDF)');
    }
  }

  // Upload HTML
  if($mandou_html)
  {
    $commit = false;

    // Deszipando arquivo
    $arq = $_FILES['arquivo_html']['tmp_name'];
    $dir = '../upload/html/' . $art_id . '/';
  
    // Se o diretorio existir, apaga-lo
    if(file_exists($dir))
    {
      b1n_deltree($dir, $ret_msgs);
    }

    // Descomprimindo arquivo zip
    if(b1n_unzip($arq, $dir))
    {
      $commit = true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Erro ao descomprimir arquivo ZIP');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'nao mandou zip');
  }

  if($commit)
  {
    if($sql->sqlQuery('COMMIT TRANSACTION', 'trans'))
    {
      b1n_retMsg($ret_msgs, b1n_SUCCESS, 'Artigo alterado com sucesso!');
      return true;
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Nao conseguiu completar transacao');
    }
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

// Delete
function b1n_regDeleteArtigo($sql, &$ret_msgs, $reg_data, $sendmsg = true)
{
  $ret = false;
  $ids = "art_id = " . implode(' OR art_id = ', $reg_data['ids']);
  $query = "
    SELECT
      art_id,
      art_pdf,
      art_html
    FROM
      artigo
    WHERE
      " . $ids;

  $rs = $sql->sqlQuery($query);

  if(is_array($rs))
  {
    $ret = $sql->sqlQuery("DELETE FROM artigo WHERE " . $ids, 'del');

    if($ret)
    {
      foreach($rs as $art)
      {
        // Apagando PDF
        if($art['art_pdf'])
        {
          $arquivo = b1n_UPLOAD_DIR_ARTIGO_PDF . '/' . $art['art_id'] . '.pdf';
          if(! @unlink($arquivo))
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "N&atilde;o conseguiu apagar " . $arquivo);
          }
        }

        // Apagando HTML
        if($art['art_html'])
        {
          $dir = b1n_UPLOAD_DIR_ARTIGO_HTML . '/' . $art['art_id'];
          if(!b1n_deltree($dir, $ret_msgs))
          {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "N&atilde;o conseguiu apagar " . $dir);
          }
        }
      }

      if($sendmsg)
      {
        if(sizeof($rs) == 1)
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, "Artigo exclu&iacute;do com sucesso");
        }
        else
        {
          b1n_retMsg($ret_msgs, b1n_SUCCESS, "Artigos exclu&iacute;dos com sucesso");
        }
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, "N&atilde;o conseguiu apagar artigos");
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, "N&atilde;o conseguiu pegar dados de artigos");
  }
  return $ret;
}

// Activate
function b1n_regToggleActivationArtigo($sql, &$ret_msgs, $id)
{
  return b1n_regToggleActivation($sql, $ret_msgs, 'art_id', $id, 'art_ativo', 'artigo');
}

// Load
function b1n_regLoadArtigo($sql, &$ret_msgs, &$reg_data)
{
  // Pegando dados do artigo
  $query = "
    SELECT
      idi_id,
      sec_id,
      art_ordem,
      art_pag_ini,
      art_pag_fin,
      art_pdf,
      art_html,
      art_html_pag
    FROM
      artigo
    WHERE
      art_id = '" . $reg_data['id'] . "' AND
      fas_id = '" . $reg_data['fas_id'] . "'";

  $rs = $sql->sqlSingleQuery($query);

  if(is_array($rs))
  {
    // Secao, Ordem, Paginacao, PDF e HTML
    $reg_data['body_idi_id'] = $rs['idi_id'];
    $reg_data['sec_id']   = $rs['sec_id'];
    $reg_data['ordem']    = $rs['art_ordem'];
    $reg_data['pag_ini']  = $rs['art_pag_ini'];
    $reg_data['pag_fin']  = $rs['art_pag_fin'];
    $reg_data['art_pdf']  = $rs['art_pdf'];
    $reg_data['art_html'] = $rs['art_html'];
    $reg_data['art_html_pag'] = $rs['art_html_pag'];

    // Titulos, Resumos e Palavras-Chave
    $query = "
      SELECT
        idi_id,
        aid_id,
        aid_titulo,
        aid_resumo
      FROM
        artigo_idioma
      WHERE
        art_id = '" . $reg_data['id'] . "'";

    $i = 1;
    $rs = $sql->sqlQuery($query);
    if(is_array($rs))
    {
      foreach($rs as $aid)
      {
        // Titulo e Resumo
        $reg_data['front_idi_id'][$i] = $aid['idi_id'];
        $reg_data['titulo'][$i] = $aid['aid_titulo'];
        $reg_data['resumo'][$i] = $aid['aid_resumo'];
        
        // PalChave
        $query = "
          SELECT
            pch_cont
          FROM
            palchave
          WHERE
            aid_id = '" . $aid['aid_id'] . "'";

        $aux = $sql->sqlQuery($query);

        $j = 1;
        if(is_array($aux))
        {
          foreach($aux as $pch)
          {
            $reg_data['palchave'][$i][$j] = $pch['pch_cont'];
            $j++;
          }
        }
        $reg_data['qt_palchave'][$i] = $j-1;

        $i++;
      }
      $reg_data['qt_idioma'] = $i-1;

      // Autores
      $query = "
        SELECT
          aut_prinome,
          aut_sobnome
        FROM
          autor
        WHERE
          art_id = '" . $reg_data['id'] . "'";

      $i = 1;
      $rs = $sql->sqlQuery($query);
      if(is_array($rs))
      {
        foreach($rs as $aut)
        {
          $reg_data['aut_prinome'][$i] = $aut['aut_prinome'];
          $reg_data['aut_sobnome'][$i] = $aut['aut_sobnome'];
          $i++;
        }
      }
      $reg_data['qt_autor'] = $i-1;
    }
    return true;
  }

  return false;
}

// Search
function b1n_regSearchArtigo($sql, $search, $fas_id)
{
  $config['possible_fields'] = array(
    'Ordem' => 'art_ordem',
    'T&iacute;tulo' => 'aid_titulo',
    'Se&ccedil;&atilde;o' => 'sec_nome');

  $config['select_fields'] = array(
    'Ordem' => 'art_ordem',
    'Se&ccedil;&atilde;o' => 'sec_nome',
    'T&iacute;tulo' => 'aid_titulo',
    'Tem PDF' => 'art_pdf',
    'Tem HTML'  => 'art_html',
    'Ativo'   => 'art_ativo');
  
  $config['possible_quantities'] = array(
    10=>10, 15=>15, 20=>20, 25=>25, 30=>30);

  $config['session_hash_name']  = 'artigo';
  $config['id_field'] = 'art_id';
  $config['table']    = 'view_artigo';

  $where = "fas_id = '".$fas_id."'";

  return b1n_searchG($sql, $config, $search, true, $where);
}

function b1n_unzipCheckHtml($file)
{
  $i = 0;
  $zip = zip_open($file);
  if($zip)
  {
    while($zip_entry = zip_read($zip))
    {
      $nome = zip_entry_name($zip_entry);
      if(b1n_cmp(dirname($nome), 'body')) 
      {
        $ext = explode('.', $nome);
        $ext = $ext[sizeof($ext)-1];
        if(b1n_cmp($ext, 'htm') || b1n_cmp($ext, 'html'))
        {
          $arquivo = $nome;
          $i++;
        }
      }
    }
  }

  if($i == 1)
  {
    $ret = array(true, $arquivo);
  }
  elseif($i < 1)
  {
    $ret = array(false, 'N&atilde;o h&aacute; arquivos HTML no diret&oacute;rio body do arquivo ZIP, abortando upload');
  }
  elseif($i > 1)
  {
    $ret = array(false, 'H&aacute; mais de um arquivo HTML no diret&oacute;rio body do arquivo ZIP, abortando upload');
  }

  return $ret;
}

function b1n_unzip($file, $path)
{
  $ret = true;
  $zip = zip_open($file);
  if($zip)
  {
    while($zip_entry = zip_read($zip))
    {
      if(zip_entry_filesize($zip_entry) > 0)
      {
        // convertendo \ para // (windows)
        $complete_path = $path . dirname(zip_entry_name($zip_entry));
        $complete_path = str_replace('/', '\\', $complete_path);
        $complete_name = $path . zip_entry_name($zip_entry);
        $complete_name = str_replace ('/', '\\', $complete_name);

        // olhando se o diretorio existe
        if(!file_exists($complete_path))
        { 
          $tmp = '';
          foreach(explode('\\', $complete_path) AS $k)
          {
            $tmp .= $k.'\\';
            if(!file_exists($tmp))
            {
              mkdir($tmp, 0750);
            }
          }
        }

        if(zip_entry_open($zip, $zip_entry, 'rb'))
        {
          $fd = fopen($complete_name, 'w');
          $ret = fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)), zip_entry_filesize($zip_entry));
          fclose($fd);
          zip_entry_close($zip_entry);
          if($ret === false)
          {
            return false;
          }
        }
      }
    }
    zip_close($zip);
    return true;
  }
  return false;
}

function b1n_deltree($dir, &$ret_msgs)
{
  $ret = true;

  if(is_writable($dir))
  {
    if($handle = opendir($dir))
    {
      while(($file = readdir($handle)) !== false)
      {
        if($file != '.' && $file != '..')
        {
          $file = str_replace('/', '\\', $dir.'/'.$file);;
          if(is_dir($file))
          {
            $ret = b1n_deltree($file, $ret_msgs);
          }
          else
          {
            if(! @unlink($file))
            {
              $ret = false;
              b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o conseguiu apagar ' . $file);
            }
          }
        }
      }
      closedir($handle);

      if($ret)
      {
        if(!rmdir($dir))
        {
          $ret = false;
          b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o conseguiu apagar dir ' . $dir);
        }
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o consegue escrever em ' . $dir);
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o consegue escrever em ' . $dir);
  }

  return $ret;
}
?>
