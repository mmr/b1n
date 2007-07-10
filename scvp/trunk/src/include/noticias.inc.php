<?
// $Id: noticias.inc.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><img src="img/shim.gif" width="15" height="1"></td>
    <td width="100%">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td class="tabverdeclaro"><img src="img/shim.gif" width="1" height="1"></td>
          <td class="tabverdeclaro" width="100%"><span class="secao"><?= $are_nome ?></span></td>
        </tr>
      </table>
<?
// Listar noticias
if(empty($d['id']))
{
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="7" class="texto">
<?
  // Paginacao
  b1n_getVar('pg', $d['pg']);

  if(empty($d['pg']))
  {
    $d['pg'] = 1;
  }

  $base = b1n_URL . '?p=' . $d['p'];
  $qtd  = 9;
  $offset = $d['pg'] * $qtd;

  $query = "
    SELECT
      COUNT(not_id) AS count
    FROM
      noticia
    WHERE
      not_ativo = '1' AND
      idi_id = '" . $_SESSION['idi_id'] . "'";

  $rs   = $sql->sqlSingleQuery($query);
  $pgs  = max(1, ceil($rs['count'] / $qtd));

  if($d['pg'] < 1)
  {
    $d['pg'] = 1;
  }

  if($d['pg'] > $pgs)
  {
    $d['pg'] = $pgs;
  }

  // Ultima pagina
  if($d['pg'] == $pgs)
  {
    $qtd -= abs($offset - $rs['count']);
  }

  if($pgs > 1)
  {
    $paginacao = '<tr><td class="datasbold">';
    if($d['pg'] > 1)
    {
      $paginacao .=  '<a href="' . $base . '&amp;pg=' . ($d['pg'] - 1) . '"><img src="img/seta_esq.gif" border="0"></a>&nbsp;';
    }

    for($i=1; $i<=$pgs; $i++)
    {
      if($i == $d['pg']) 
      {
        $paginacao .= '&nbsp;' . $i;
      }
      else
      {
        $paginacao .= '&nbsp;<a href="' . $base . '&amp;pg=' . $i . '">' . $i . '</a>';
      }
    }

    if($pgs > $d['pg'])
    {
      $paginacao .=  '&nbsp;<a href="' . $base . '&amp;pg=' . ($d['pg'] + 1) . '"><img src="img/seta_dir.gif" border="0"></a>';
    }
    $paginacao .= '</td></tr>';
  }
  else
  {
    $paginacao = '';
  }

  // Pegando noticias
  $query = "
    SELECT
      not_id, not_nome, not_desc, not_dt
    FROM
    (
      SELECT TOP " . $qtd . "
        not_id, not_nome, not_desc, not_dt
      FROM
      (
        SELECT TOP " . $offset . "
          not_id, not_nome, not_desc, not_dt
        FROM
          noticia
        WHERE
          not_ativo = '1' AND
          idi_id = '" . $_SESSION['idi_id'] . "'
        ORDER BY not_dt DESC
      ) x
      ORDER BY not_dt ASC
    ) y
    ORDER BY not_dt DESC, not_nome";

  $rs = $sql->sqlQuery($query);

  if(is_array($rs))
  {
    $i=0;
    foreach($rs as $k => $v)
    {
      if($i>=3)
      {
        break;
      }
?>
        <tr> 
          <td class='tabcinza'><span class="datasbold"><?= b1n_formatDateShow($v['not_dt']) ?> - </span><span class="titulosbold"><a href='<?= b1n_URL . '?p=noticias&id=' . $v['not_id'] ?>' target='_self'><?= $v['not_nome'] ?></a></span><br>
            <span class="texto"><?= $v['not_desc'] ?></span>
            <span class="textoverde"><a href='<?= b1n_URL . '?p=noticias&id=' . $v['not_id'] ?>' target='_self'>[+]</a></span></td>
        </tr>
<?
      unset($rs[$k]);
      $i++;
    }

    if(sizeof($rs))
    {
?>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="100%">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="img/shim.gif" width="1" height="8"></td>
              </tr>
<?
      foreach($rs as $v)
      {
?>
              <tr> 
                <td height="20"> 
                  <p><span class="datas"><?= b1n_formatDateShow($v['not_dt']) ?> -</span>
                  <span class="textoverde"><a href='<?= b1n_URL . '?p=noticias&id=' . $v['not_id'] ?>' target='_self'><?= $v['not_nome'] ?></a></span></p>
                </td>
              </tr>
<?
      }
?>
            </table>
          </td>
          <td><img src="img/shim.gif" width="15" height="1"></td>
        </tr>
<?
    }
  }
?>  
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="img/shim.gif" width="1" height="5"></td>
        </tr>
        <tr> 
          <td background="img/back_verdeb.gif"><img src="img/shim.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td><img src="img/shim.gif" width="1" height="5"></td>
        </tr>
<?
  echo $paginacao;
}
else
{
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
        <tr>
          <td><img src="img/shim.gif" width="1" height="8"></td>
        </tr>
<?
  $query = "
    SELECT
      not_id, not_nome, not_dt, not_cont
    FROM
      noticia
    WHERE
      not_ativo = '1' AND
      not_id = '" . $d['id'] . "'";

  $rs = $sql->sqlSingleQuery($query);
  if(is_array($rs))
  {
?>
        <tr> 
          <td> 
            <p><span class="not_data"><?= b1n_formatDateShow($rs['not_dt']) ?></span></p>
            <p><span class="not_tit"><?= $rs['not_nome'] ?></span></p>
            <p class="texto"><?= $rs['not_cont'] ?></p>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="img/shim.gif" width="1" height="5"></td>
        </tr>
        <tr> 
          <td background="img/back_verdeb.gif"><img src="img/shim.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td><img src="img/shim.gif" width="1" height="5"></td>
        </tr>
<?
    $query = "
      SELECT
        not_id
      FROM
        noticia
      WHERE
        not_ativo = '1' AND
        idi_id = '".$_SESSION['idi_id']."' AND
        not_dt < '".$rs['not_dt']."' AND
        not_id != '".$d['id']."'
      ORDER BY
        not_dt DESC";

    $anterior = $sql->sqlSingleQuery($query);

    $query = "
      SELECT
        not_id
      FROM
        noticia
      WHERE
        not_ativo = '1' AND
        idi_id = '".$_SESSION['idi_id']."' AND
        not_dt > '".$rs['not_dt']."' AND
        not_id != '".$d['id']."'
      ORDER BY
        not_dt";

    $proxima = $sql->sqlSingleQuery($query);

    if(is_array($anterior) || is_array($proxima))
    {
?>
        <tr> 
          <td>
<?
      if(is_array($anterior))
      {
?>
            <a href='<?= b1n_URL . '?p=noticias&id=' . $anterior['not_id'] ?>'><img src="img/seta_esq.gif" width="15" height="15" border='0'></a>
<?
      }

      if(is_array($proxima))
      {
?>
            <a href='<?= b1n_URL . '?p=noticias&id=' . $proxima['not_id'] ?>'><img src="img/seta_dir.gif" width="15" height="15" border='0'></a>
<?
      }
?>
          </td>
        </tr>
<?
    }
  }
}
?>
      </table>
    </td>
  </tr>
</table>
