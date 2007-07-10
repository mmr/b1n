<br />
<?
b1n_getVar('id', $d['id']);

$query = "
  SELECT
    a.are_nome
  FROM
    area a LEFT JOIN
    area pai ON (a.are_real_id = pai.are_id)
  WHERE
    a.are_ativo = '1' AND
    a.idi_id = '".$_SESSION['idi_id']."' AND
    (
      CASE WHEN (a.are_codigo IS NULL) THEN
        pai.are_codigo
      ELSE
        a.are_codigo
      END = '" . $d['p'] . "'
    )";

$rs = $sql->sqlSingleQuery($query);

if(is_array($rs))
{
  $are_nome = $rs['are_nome'];
}
else
{
#  exit('Erro: Nao conseguiu pegar nome da area');
}

switch($d['p'])
{
case 'links':
  $inc = 'links.inc.php';
  break;
case 'noticia':
  $inc = 'noticia.inc.php';
  break;
case 'noticias':
  $inc = 'noticias.inc.php';
  break;
case 'busca_simples':
  $inc = 'busca_simples.inc.php';
  break;
case 'busca_avancada':
  $inc = 'busca_avancada.inc.php';
  break;
case 'browse':
  if(empty($d['id']))
  {
    $inc = 'browse.inc.php';
  }
  else
  {
    $inc = 'fasciculo.inc.php';
  }
  break;
default:
  $inc = 'area.inc.php';
  break;
}

$inc = b1n_PATH_INC . '/' . $inc;
if(file_exists($inc))
{
  require($inc);
}
?>
<br />
