<?
// $Id: list.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
b1n_getVar('text',        $search['text']);
b1n_getVar('field',       $search['field']);
b1n_getVar('order',       $search['order']);
b1n_getVar('order_type',  $search['order_type']);
b1n_getVar('quantity',    $search['quantity']);
b1n_getVar('pg',          $search['pg']);

if(!$filtro)
{
  unset($_SESSION['search'][$page]);
}

$search = b1n_regSearchFasciculo($sql, $search);
// Colspan = checkbox(1) + number(1) + select_fields(?) + functions(?)
$colspan = 1 + sizeof($search['select_fields']) + sizeof($functions);

// Navigation System
$pagging = '';
if($search['pg_pages']>1)
{
  $base = b1n_URL . '?page=' . $page;
  $pagging = '<tr><td colspan="' . $colspan . '" class="searchitem c">';
  if($search['search']['pg'] > 1)
  {
    $pagging .=  '<a href="' . $base . '&amp;pg=' . ($search['search']['pg'] - 1) . '">&nbsp;&lt;&lt;&nbsp;</a>';
  }

  for($i=1; $i<=$search['pg_pages']; $i++)
  {
    if($i == $search['search']['pg']) 
    {
      $pagging .= '&nbsp;' . $i;
    }
    else
    {
      $pagging .= '&nbsp;<a href="' . $base . '&amp;pg=' . $i . '">' . $i . '</a>';
    }
  }

  if($search['pg_pages']> $search['search']['pg'])
  {
    $pagging .= '<a href="' . $base . '&amp;pg=' . ($search['search']['pg'] + 1) . '">&nbsp;&gt;&gt;&nbsp;</a>';
  }
  $pagging .= '</td></tr>';
}

if(isset($search['result']))
{
?>
<script type='text/javascript' src='<?= b1n_PATH_JS ?>/form.js'></script>
<form id='form_data' method='post' action='<?= b1n_URL ?>'>
<table class='extbox'>
  <tr>
    <td>
      <input type='hidden' name='page'    value='<?= $page ?>' />
      <input type='hidden' name='action0' value='' />
      <input type='hidden' name='action1' value='' />
      <table class='intbox'>
<?
  if(is_array($search['result']))
  {
    if(sizeof($search['result']) > 1)
    {
      $b1n_check = "onclick='b1n_check(this)'";
    }
    else
    {
      $b1n_check = " onclick='this.form.elements[\"delete\"].disabled = !this.checked'";
    }
?>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'><?= $page_title ?></td>
        </tr>
        <tr>
          <td class='thin'>&nbsp;</td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_seq_num');">Num Seq</a></td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_vol_num');">Volume</a></td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_num');">N&uacute;mero</a></td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_artigos');">N&ordm; Artigos</a></td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_capa');">Tem Capa</a></td>
          <td class='c'><a href="javascript:b1n_orderBy('fas_ativo');">Ativo</a></td>
<?
    if($s = sizeof($functions))
    {
?>
          <td class='c' colspan='<?= $s ?>'><b>Fun&ccedil;&otilde;es</b></td>
<?
      unset($s);
    }
?>
        </tr>
<?
    $i = ($search['pg'] * $search['search']['quantity']) - $search['search']['quantity'] + 1;
    foreach($search['result'] as $item)
    {
?>
        <tr>
          <td class='thin'><input type='checkbox' name='ids[]' value='<?= $item['id'] ?>' class='noborder' <?= $b1n_check ?> /></td>
          <td><?= $item['fas_seq_num'] ?></td>
          <td><?= $item['fas_vol_num'] ?></td>
          <td><?= $item['fas_num'] ?></td>
          <td><?= $item['fas_artigos'] ?></td>
          <td><?= $item['fas_capa'] == 1?'Sim':'N�o' ?></td>
          <td><?= $item['fas_ativo'] == 1?'Sim':'N�o' ?></td>
<?
      foreach($functions as $func => $url)
      {
?>
          <td class='c'>
            <a href='<?= $url . 'id=' . $item['id'] ?>'><?= $func ?></a>
          </td>
<?
      }
?>
        </tr>
<?
      $i++;
    }

    if(sizeof($search['result']) > 1)
    {
?>
        <tr>
          <td colspan='<?= $colspan ?>' class='searchitem'>
            <input type='checkbox' name='checkall' class='noborder' onclick='b1n_checkAll(this.form)' />
            <a href='javascript:b1n_checkAllLink(document.forms["form_data"])' onmouseover='window.status="Marcar Todos";return true;' onmouseout='window.status="";return true;'>Marcar Todos</a>
          </td>
        </tr>
<?
    }

    echo $pagging;
?>
        <tr>
          <td class='c' colspan='<?= $colspan ?>'>
            <script type='text/javascript'>
            function b1n_apagarFasciculo(f)
            {
              var msg = "Tem certeza que deseja apagar esse(s) Fasc�culo(s) (e todos seus Artigos)?";

              if(confirm(msg))
              {
                f.action0.value="delete";
                f.submit();
              }
            }
            </script>
            <input type='button' value=' Adicionar ' onclick='this.form.action1.value="add";this.form.submit()' />
            <input type='button' name='delete' value=' Excluir ' onclick='b1n_apagarFasciculo(this.form)' disabled='disabled' />
          </td>
        </tr>
<?
  }
  else // no results
  {
?>
        <tr>
          <td class='box'><?= $page_title ?></td>
        </tr>
<?
    if(isset($_SESSION['search'][$search['session_hash_name']]))
    {
?>
        <tr>
          <td class='c'>N&atilde;o h&aacute; registros</td>
        </tr>
<?
    }
?>
        <tr>
          <td class='c' colspan='<?= $colspan ?>'>
            <input type='button' value=' Adicionar ' onclick='this.form.action1.value="add";this.form.submit()' />
          </td>
        </tr>
<?
  }
?>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
<?
if(empty($filtro))
{
  echo "<a href='".b1n_URL."?page=".$page."&filtro=1'>Filtrar</a>";
}
else
{
  echo "<a href='".b1n_URL."?page=".$page."&filtro=0'>Desligar filtro</a>";
}
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?
}

if($filtro)
{
?>
<script type='text/javascript'>
// <!--
function b1n_orderBy(col)
{
  var f=document.forms['form_search'];
  if(f.order.options)
  {
    f.order.options[f.order.selectedIndex].value = col;
  }
  else
  {
    f.order.value = col;
  }

  if(col == '<?= $search['search']['order'] ?>')
  {
    for(i=0; i<f.order_type.length; i++)
    {
      if(f.order_type[i].checked)
      {
        break;
      }
    }

    if(f.order_type[i].value == 'ASC')
    {
      f.order_type[i].checked = false; 
      f.order_type[i+1].checked = true;
    }
    else
    {
      f.order_type[i].checked = false; 
      f.order_type[i-1].checked = true;
    }
  }

  f.submit();
}
// -->
</script>
<form id='form_search' method='post' action='<?= b1n_URL ?>'>
<table class='extbox neon'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='2'><?= $page_title ?> - Filtro</td>
        </tr>
        <tr>
          <td class='formitem'>Campo
            <input type='hidden' name='page'    value='<?= $page ?>' />
            <input type='hidden' name='action0' value='' />
            <input type='hidden' name='action1' value='' />
            <input type='hidden' name='filtro'  value='<?= $filtro ?>' />
            <input type='hidden' name='pg' value='1' />
          </td>
          <td class='forminput'>
            <?= b1n_buildSelect($search['possible_fields'], array($search['search']['field']), array('name' => 'field')); ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Ordenar por</td>
          <td class='forminput'>
            <?= b1n_buildSelect($search['select_fields'], array($search['search']['order']), array('name' => 'order')); ?>
            <input type='radio' name='order_type' value='ASC' class='noborder' <?= (($search['search']['order_type'] != 'DESC')?'checked="checked"':'') ?> /> Asc
            <input type='radio' name='order_type' value='DESC' class='noborder' <?= (b1n_cmp($search['search']['order_type'], 'DESC')?'checked="checked"':'') ?> /> Desc
          </td>
        </tr>
        <tr>
          <td class='formitem'>Quantidade</td>
          <td class='forminput'>
            <?= b1n_buildSelect($search['possible_quantities'], array($search['search']['quantity']), array('name' => 'quantity')); ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Filtro</td>
          <td class='forminput'>
            <input type='text' name='text' value='<?= b1n_inHtml($search['search']['text'])?>' size='<?= b1n_DEFAULT_SIZE ?>' maxlength='<?= b1n_DEFAULT_MAXLEN ?>' />
          </td>
        </tr>
        <tr>
          <td class='c' colspan='2'>
            <input type='submit' value=' Filtrar ' />
            <input type='button' value=' Todos ' onclick="this.form.elements['text'].value = ''; this.form.submit();" />
          </td>
        </tr>
        <tr><td class='box' colspan='2'>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?
}
else
{
?>
<script type='text/javascript'>
// <!--
function b1n_orderBy(col)
{
  var f=document.forms['form_search'];
  f.order.value = col;
  //f.field.value = col;

  if(col == '<?= $search['search']['order'] ?>')
  {
    if('<?= $search['search']['order_type'] ?>' == 'ASC')
    {
      f.order_type.value = 'DESC'; 
    }
    else
    {
      f.order_type.value = 'ASC';
    }
  }

  f.submit();
}
// -->
</script>
<form id='form_search' method='post' action='<?= b1n_URL ?>'>
<input type='hidden' name='page'    value='<?= $page ?>' />
<input type='hidden' name='action0' value='' />
<input type='hidden' name='action1' value='' />
<input type='hidden' name='filtro'  value='<?= $filtro ?>' />
<input type='hidden' name='field'   value='<?= $search['search']['field'] ?>' />
<input type='hidden' name='order'   value='<?= $search['search']['order'] ?>' />
<input type='hidden' name='quantity'    value='20' />
<input type='hidden' name='pg'    value='1' />
<input type='hidden' name='order_type'  value='' />
</form>
<?
}
?>