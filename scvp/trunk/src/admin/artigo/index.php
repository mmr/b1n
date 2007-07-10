<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
require(b1n_PATH_REGLIB . '/' . $page . '.lib.php');

// monta uma estrutura com os dados da busca.
b1n_getVar('id',  $reg_data['id']);
b1n_getVar('ids', $reg_data['ids']);

// --/--/----------------
// Pegando as coisas do $_REQUEST

// Fasciculo
b1n_getVar('fas_id',      $reg_data['fas_id']);

// Dados de cadastro
b1n_getVar('qt_idioma', $reg_data['qt_idioma']);
b1n_getVar('front_idi_id', $reg_data['front_idi_id']);
b1n_getVar('titulo',  $reg_data['titulo']);
b1n_getVar('resumo',   $reg_data['resumo']);
b1n_getVar('qt_palchave', $reg_data['qt_palchave']);
b1n_getVar('palchave', $reg_data['palchave']);

b1n_getVar('body_idi_id', $reg_data['body_idi_id']);
b1n_getVar('sec_id',      $reg_data['sec_id']);
b1n_getVar('ordem',       $reg_data['ordem']);
b1n_getVar('pag_ini',     $reg_data['pag_ini']);
b1n_getVar('pag_fin',     $reg_data['pag_fin']);
b1n_getVar('qt_autor',    $reg_data['qt_autor']);
b1n_getVar('aut_prinome', $reg_data['aut_prinome']);
b1n_getVar('aut_sobnome', $reg_data['aut_sobnome']);
b1n_getVar('art_pdf',     $reg_data['art_pdf']);
b1n_getVar('art_html',    $reg_data['art_html']);

// Pegando filhos (indices dos arrays)
  // Idiomas
if(!b1n_checkNumeric($reg_data['qt_idioma']) || $reg_data['qt_idioma'] < 1 && $reg_data['qt_idioma'] > 5)
{
  $reg_data['qt_idioma'] = 0;
}

for($i=1; $i <= $reg_data['qt_idioma']; $i++)
{
  b1n_getVar('front_idi_id['.$i.']', $reg_data['front_idi_id'][$i], $reg_data['front_idi_id'][$i]);

  // Titulo
  b1n_getVar('titulo['.$i.']', $reg_data['titulo'][$i], $reg_data['titulo'][$i]);

  // Resumo
  b1n_getVar('resumo['.$i.']', $reg_data['resumo'][$i], $reg_data['resumo'][$i]);

  // Palavra Chave
  b1n_getVar('qt_palchave['.$i.']', $reg_data['qt_palchave'][$i], $reg_data['qt_palchave'][$i]);

  if(!b1n_checkNumeric($reg_data['qt_palchave'][$i]) || $reg_data['qt_palchave'][$i] < 1 || $reg_data['qt_palchave'][$i] > 10)
  {
    $reg_data['qt_palchave'][$i] = 4;
  }

  for($j=1; $j <= $reg_data['qt_palchave'][$i]; $j++)
  {
    b1n_getVar('palchave['.$i.']['.$j.']', $reg_data['palchave'][$i][$j], $reg_data['palchave'][$i][$j]);
  }
}

// Autores
if(!b1n_checkNumeric($reg_data['qt_autor']) || $reg_data['qt_autor'] < 1 || $reg_data['qt_autor'] > 25)
{
  $reg_data['qt_autor'] = 4;
}

for($i=1; $i<= $reg_data['qt_autor']; $i++)
{
  b1n_getVar('aut_prinome['.$i.']', $reg_data['aut_prinome'][$i], $reg_data['aut_prinome'][$i]);
  b1n_getVar('aut_sobnome['.$i.']', $reg_data['aut_sobnome'][$i], $reg_data['aut_sobnome'][$i]);
}

// --/--/----------------

// Verificando se sabe em qual Fasciculo esta
if(empty($reg_data['fas_id']))
{
  if(empty($reg_data['id']))
  {
    die('Erro ao tentar descobrir o Fasc&iacute;culo, processo abortado. (1)');
  }
  else
  {
    if(empty($action0) && empty($action1))
    {
      $reg_data['fas_id'] = $reg_data['id'];
    }
    else
    {
      die('Erro ao tentar descobrir o Fasc&iacute;culo, processo abortado. (2)');
    }
  }
}

$query = "SELECT fas_vol_num + '(' + fas_num + ')' AS fas_codigo FROM fasciculo WHERE fas_id = '" . $reg_data['fas_id'] . "'";
$rs = $sql->sqlSingleQuery($query);
if(is_array($rs))
{
  $reg_data['fas_codigo'] = $rs['fas_codigo'];
}
else
{
  die('Erro ao tentar descobrir o Fasc&iacute;culo, processo abortado. (3)');
}

$page_title = 'Artigos do Fasc&iacute;culo ' . $reg_data['fas_codigo'];
$functions = array();

// Mexendo no BD
switch($action0)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_ARTIGO))
  {
    if(b1n_regCheckArtigo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regAddArtigo($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'change':
  if(b1n_havePermission(b1n_FUNC_CHANGE_ARTIGO))
  {
    if(b1n_regCheckChangeArtigo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regChangeArtigo($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'activate':
  if(b1n_havePermission(b1n_FUNC_ACTIVATE_ARTIGO))
  {
    if(b1n_regToggleActivationArtigo($sql, $ret_msgs, $reg_data['id']))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'delete':
  if(b1n_havePermission(b1n_FUNC_DELETE_ARTIGO))
  {
    if(b1n_regCheckDeleteArtigo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regDeleteArtigo($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'load':
  if(b1n_havePermission(b1n_FUNC_VIEW_ARTIGO) || 
     b1n_havePermission(b1n_FUNC_CHANGE_ARTIGO))
  {
    if(!b1n_regLoadArtigo($sql, $ret_msgs, $reg_data))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
}

// Mensagens (sucesso/falha)
if(sizeof($ret_msgs))
{
?>
<div><br /><br /></div>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box'>Mensagens do Sistema</td>
        </tr>
        <tr>
          <td>
            <? require(b1n_PATH_INC . '/ret.inc.php'); ?>
          </td>
        </tr>
        <tr>
          <td class='box'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?   
}

$already_denied = false;
$functions = array();

// Mostrando Form na Tela
switch($action1)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_ARTIGO))
  {
    require_once($page . '/add.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
case 'change':
  if(b1n_havePermission(b1n_FUNC_CHANGE_ARTIGO))
  {
    require_once($page . '/change.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
case 'view':
  if(b1n_havePermission(b1n_FUNC_CHANGE_ARTIGO))
  {
    require_once($page . '/view.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
default:
  if(b1n_havePermission(b1n_FUNC_LIST_ARTIGO))
  {
    if(b1n_havePermission(b1n_FUNC_VIEW_ARTIGO))
    {
      $functions += array(
        'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;fas_id='.$reg_data['fas_id'].'&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_CHANGE_ARTIGO))
    {
      $functions += array(
        'Altera' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;fas_id='.$reg_data['fas_id'].'&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_ACTIVATE_ARTIGO))
    {
      $functions += array(
        'Ativa/Desativa' => b1n_URL . '?page='.$page.'&amp;action0=activate&amp;fas_id='.$reg_data['fas_id'].'&amp;');
    }
    require_once($page . '/list.php');
  }
  else
  {
    if(!$already_denied)
    {
      require_once(b1n_PATH_INC . '/denied.inc.php');
    }
  }
  break;
}
?>
