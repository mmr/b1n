<?
/*
$Id: list.inc.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $ 

Search Config Structure

Structure used for searching, based on $reg_config.
So, if $reg_config is misconfigured, it will be too :P

$search_config = array(
  'possible_fields'     => array,
  'possible_quantites'  => array,
  'select_fields'     => array,
  'session_hash_name' => string,
  'table'     => string,
  'id_field'  => string
  ['mine'     => boolean]);

The array format is: 'title' => 'name_of_the_column_on_the_database'    

possible_fields => 
  Field used for filtering the search (WHERE field ILIKE '%...%').

possible_quantities =>
  Act with the pagination system, the default is defined on b1n_LIBPATH/search.lib.php:b1n_DEFAULT_QUANTITY

select_fields =>
  Fields to show after the search.

session_hash_name =>
  Name of the key in the $_SESSION['search'] hash to store the last search made.(The default value is $page)

table =>
  Table to search

id_field  =>
  ID Field from the table, got from $reg_config['ID']['db'] (The default value is $page)

Well... you can override the default values in the specific list.php file in the module directory.

To override EVERYTHING, use the 'mine' option
*/


// Activate
if(isset($activate_field) && !isset($reg_config['Ativo']))
{
  $reg_config += array(
    'Ativo'  => array(
      'reg_data'  => $activate_field,
      'db'    => $activate_field,
      'check' => 'boolean',
      'type'  => 'radio',
      'extra' => array(
        'options' => array(
          'Sim' => '1',
          'Não' => '0')),
      'search'  => false,
      'select'  => true,
      'load'    => true,
      'mand'    => true));
}

// Functions
if(!isset($functions))
{
  $functions = array(
    'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;',
    'Altera'    => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;');
}

// Search_Config
if(!isset($search_config['mine']))
{
  // If $search_config is not set, do it!
  if(!isset($search_config))
  {
    $search_config = array();
  }

  if(!isset($search_config['id_field']))
  {
    $search_config['id_field'] = $reg_config['ID']['db'];
  }

  // Possible Quantities (10, 20, 30, 50, 100)
  if(!isset($search_config['possible_quantities']))
  {
    $search_config['possible_quantities'] = array(
      '5'   => '5',
      '10'  => '10',
      '20'  => '20',
      '30'  => '30',
      '50'  => '50',
      '100' => '100');
  }

  if(!isset($search_config['session_hash_name']))
  {
    $search_config['session_hash_name'] = $page;
  }

  if(!isset($search_config['table']))
  {
    $search_config['table'] = $page;
  }

  if(!isset($search_config['possible_fields']) ||
     !isset($search_config['select_fields']))
  {
    $search_config['possible_fields'] = array();
    $search_config['select_fields']   = array();

    // Getting Values from reg_config and putting them in $search_config.
    foreach($reg_config as $t => $r)
    {
      if(b1n_cmp($r['type'], 'select') && b1n_cmp($r['extra']['seltype'], 'fk'))
      {
        $v = array($t => $r['extra']['text']);
      }
      else
      {
        $v = array($t => $r['db']);
      }

      if($r['search'])
      {
        $search_config['possible_fields'] += $v;
      }

      if($r['select'])
      {
        $search_config['select_fields'] += $v;
      }
    }
  }
}

b1n_getVar('search_text',   $search['search_text']);
b1n_getVar('search_field',  $search['search_field']);
b1n_getVar('search_order',  $search['search_order']);
b1n_getVar('search_order_type', $search['search_order_type']);
b1n_getVar('search_quantity',   $search['search_quantity']);
b1n_getVar('pg',  $search['pg']);

if(!$filtro)
{
  unset($_SESSION['search'][$page]);
}

$search = b1n_search($sql, $search_config, $search);
// Colspan = select_fields(?) + functions(?) + checkbox(1) + number(1)
#$colspan = sizeof($search_config['select_fields']) + sizeof($functions) + 2;
$colspan = sizeof($search_config['select_fields']) + sizeof($functions) + 1;

// Navigation System
$pagging = '';
if($search['pg_pages']>1)
{
  $base = b1n_URL . '?page=' . $page;
  $pagging = '<tr><td colspan="' . $colspan . '" class="searchitem c">';
  if($search['pg'] > 1)
  {
    $pagging .=  '<a href="' . $base . '&amp;pg=' . ($search['pg'] - 1) . '">&nbsp;&lt;&lt;&nbsp;</a>';
  }

  for($i=1; $i<=$search['pg_pages']; $i++)
  {
    if($i == $search['pg']) 
    {
      $pagging .= '&nbsp;' . $i;
    }
    else
    {
      $pagging .= '&nbsp;<a href="' . $base . '&amp;pg=' . $i . '">' . $i . '</a>';
    }
  }

  if($search['pg_pages']> $search['pg'])
  {
    $pagging .= '<a href="' . $base . '&amp;pg=' . ($search['pg'] + 1) . '">&nbsp;&gt;&gt;&nbsp;</a>';
  }
  $pagging .= '</td></tr>';
}

if(isset($search['result']))
{
?>
<script type='text/javascript' src='<?= b1n_PATH_JS ?>/form.js'></script>
<form name='form_data' method='post' action='<?= b1n_URL ?>'>
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
          <td class='thin'>
            <script type='text/javascript'>
            // <!--
            function b1n_orderBy(col)
            {
              var f=document.form_search;
              f.search_order.options[f.search_order.selectedIndex].value = col;

              if(col == '<?= $search['search']['search_order'] ?>')
              {
                for(i=0; i<f.search_order_type.length; i++)
                {
                  if(f.search_order_type[i].checked)
                  {
                    break;
                  }
                }

                if(f.search_order_type[i].value == 'ASC')
                {
                  f.search_order_type[i].checked = false; 
                  f.search_order_type[i+1].checked = true;
                }
                else
                {
                  f.search_order_type[i].checked = false; 
                  f.search_order_type[i-1].checked = true;
                }
              }

              f.submit();
            }
            // -->
            </script>
          </td>
<?
    foreach($search_config['select_fields'] as $field_name => $field_column)
    {
?>
          <td class='c'><a href="javascript:b1n_orderBy('<?= $field_column ?>');"><?= $field_name ?></a></td>
<?
    }

    $s = sizeof($functions);
    if($s > 0)
    {
?>
          <td class='c' colspan='<?= $s ?>'><b>Fun&ccedil;&otilde;es</b></td>
<?
    }
?>
        </tr>
<?
    $i = ($search['pg'] * $search['search']['search_quantity']) - $search['search']['search_quantity'] + 1;

    foreach($search['result'] as $item)
    {
?>
        <tr>
          <td class='thin'>
            <input type='checkbox' name='ids[]' value='<?= $item['id'] ?>' class='noborder' <?= $b1n_check ?> />
          </td>
<?
      foreach($search_config['select_fields'] as $t => $f)
      {
      ?>
        <td class='searchitem'>&nbsp;
      <?
        $r = $reg_config[$t];
        $v = $item[$f];
        if(!empty($v) || b1n_cmp($v, '0'))
        {
          switch($r['type'])
          {
          case 'text':
          case 'textarea':
            if(b1n_cmp($r['check'], 'email'))
            {
              echo '&nbsp;<a href="mailto:' . $v . '">' . b1n_inHtmlLimit($v) . '</a>';
            }
            else
            {
              echo b1n_inHtmlLimit($v);
            }
            break;
          case 'select':
            switch($r['extra']['seltype'])
            {
            case 'date':
              echo b1n_formatDateShow(b1n_formatDateFromDb($v));
              break;
            case 'date_hour':
              echo b1n_formatDateHourShow(b1n_formatDateHourFromDb($v));
              break;
            case 'defined':
              foreach($r['extra']['options'] as $opt_title => $opt_value)
              {
                if(b1n_cmp($v, $opt_value))
                {
                  $v = $opt_title;
                  break;
                }
              }
              echo b1n_inHtmlLimit($v);
              break;
            default:
              echo b1n_inHtmlLimit($v);
              break;
            }
            break;
          case 'radio':
            foreach($r['extra']['options'] as $opt_title => $opt_value)
            {
              if(b1n_cmp($v, $opt_value))
              {
                $v = $opt_title;
                break;
              }
            }
            echo b1n_inHtmlLimit($v);
            break;
          default:
            echo b1n_inHtmlLimit($v);
          }
        }
      ?>
          </td>
<?
      }

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
            <a href='javascript:b1n_checkAllLink(document.form_data)' onmouseover='window.status="Marcar Todos";return true;' onmouseout='window.status="";return true;'>Marcar Todos</a>
          </td>
        </tr>
<?
    }

    echo $pagging;
?>
        <tr>
          <td class='c' colspan='<?= $colspan ?>'>
            <input type='button' value=' Adicionar ' onclick='this.form.action1.value="add";this.form.submit()' />
            <input type='button' name='delete' value=' Excluir ' onclick='this.form.action0.value="delete";this.form.submit()' disabled='disabled' />
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
    if(isset($_SESSION['search'][$search_config['session_hash_name']]))
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
<form name='form_search' method='post' action='<?= b1n_URL ?>'>
<table class='extbox'>
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
            <?= b1n_buildSelect($search['possible_fields'], array($search['search']['search_field']), array('name' => 'search_field')); ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Ordenar por</td>
          <td class='forminput'>
            <?= b1n_buildSelect($search['select_fields'], array($search['search']['search_order']), array('name' => 'search_order')); ?>
            <input type='radio' name='search_order_type' value='ASC' class='noborder' <?= (($search['search']['search_order_type'] != 'DESC')?'checked="checked"':'') ?> /> Asc
            <input type='radio' name='search_order_type' value='DESC' class='noborder' <?= (b1n_cmp($search['search']['search_order_type'], 'DESC')?'checked="checked"':'') ?> /> Desc
          </td>
        </tr>
        <tr>
          <td class='formitem'>Quantidade</td>
          <td class='forminput'>
            <?= b1n_buildSelect($search['possible_quantities'], array($search['search']['search_quantity']), array('name' => 'search_quantity')); ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Filtro</td>
          <td class='forminput'>
            <input type='text' name='search_text' value='<?= b1n_inHtml($search['search']['search_text'])?>' size='<?= b1n_DEFAULT_SIZE ?>' maxlength='<?= b1n_DEFAULT_MAXLEN ?>' />
          </td>
        </tr>
        <tr>
          <td class='c' colspan='2'>
            <input type='submit' value=' Filtrar ' />
            <input type='button' value=' Todos ' onclick="this.form.search_text.value = ''; this.form.submit();" />
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
  f.search_order.value = col;
  //f.field.value = col;

  if(col == '<?= $search['search']['search_order'] ?>')
  {
    if('<?= $search['search']['search_order_type'] ?>' == 'ASC')
    {
      f.search_order_type.value = 'DESC'; 
    }
    else
    {
      f.search_order_type.value = 'ASC';
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
<input type='hidden' name='search_field'   value='<?= $search['search']['search_field'] ?>' />
<input type='hidden' name='search_order'   value='<?= $search['search']['search_order'] ?>' />
<input type='hidden' name='search_quantity'    value='<?= $search['search']['search_quantity'] ?>' />
<input type='hidden' name='search_order_type'  value='<?= $search['search']['search_order_type'] ?>' />
<input type='hidden' name='pg'  value='1' />
</form>
<?
}
?>
