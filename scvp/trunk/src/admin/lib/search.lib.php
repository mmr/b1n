<?
// $Id: search.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
function b1n_checkSearch(&$search, $ret, $session_hash_name, $select_first_if_none = true)
{
  if(!isset($search['pg'])  || 
     !b1n_checkNumeric($search['pg']) || 
     $search['pg'] <= 0)
  {
    $pg = 1;
  }
  else
  {
    $pg = $search['pg'];
  }

  if(!isset($search['search_quantity'])  ||
     !in_array($search['search_quantity'], $ret['possible_quantities']))
  {
    if(isset($_SESSION['search'][$session_hash_name]['search_quantity']))
    {
      $search['search_quantity'] = $_SESSION['search'][$session_hash_name]['search_quantity'];
    }
    else
    {
      $search['search_quantity'] = b1n_DEFAULT_QUANTITY;
    }
  }

  if(!b1n_cmp($search['search_order_type'], 'ASC') && 
     !b1n_cmp($search['search_order_type'], 'DESC'))
  {
    if(isset($_SESSION['search'][$session_hash_name]['search_order_type']))
    {
      $search['search_order_type'] = $_SESSION['search'][$session_hash_name]['search_order_type'];
    }
    else
    {
      $search['search_order_type'] = 'ASC';
    }
  }

  if(in_array($search['search_field'], $ret['possible_fields']) &&
     in_array($search['search_order'], $ret['select_fields']))
  {
    return true;
  }
  else
  {
    if(isset($_SESSION['search'][$session_hash_name]['search_field']))
    {
      $search['search_field'] = $_SESSION['search'][$session_hash_name]['search_field'];
    }
    else
    {
      if($select_first_if_none)
      {
        $search['search_field'] = array_shift($ret['possible_fields']);
      }
      else
      {
        $search['search_field'] = '';
      }
    }

    if(isset($_SESSION['search'][$session_hash_name]['search_order']))
    {
      $search['search_order'] = $_SESSION['search'][$session_hash_name]['search_order'];
    }
    else
    {
      $search['search_order'] = $search['search_field'];
    }

    if(isset($_SESSION['search'][$session_hash_name]['search_text']))
    {
      $search['search_text'] = $_SESSION['search'][$session_hash_name]['search_text'];
    }

    $search['pg'] = $pg;
  }

  return true;
}

function b1n_search($sql, $search_config, $search, $select_first_if_none = true, $where_plus = '')
{
  $ret['select_fields']     = $search_config['select_fields'];
  $ret['possible_fields']   = $search_config['possible_fields'];
  $ret['possible_quantities'] = $search_config['possible_quantities'];

// ---------------------- Checking and Session storing ----------------

  if(!b1n_checkSearch($search, $ret, $search_config['session_hash_name'], $select_first_if_none))
  {
    $ret['search'] = $search;
    return $ret;
  }

  $ret['search'] = $search;
  $_SESSION['search'][$search_config['session_hash_name']] = $search;

  if(empty($search['search_text']))
  {
    $isnull = ' OR '. $search['search_field'] . ' IS NULL';
  }
  else
  {
    $isnull = '';
  }

// ---------------------- WHERE ---------------------------
  if(!empty($search['search_field']))
  {
    $where = '
        WHERE
          LOWER(' . $search['search_field'] . ") LIKE '%" . b1n_inBd($search['search_text']) . "%'" . $where_plus . $isnull;
  }
  else
  {
    $where = $where_plus;
  }

// ---------------------- Paggination ----------------
  $query = '
      SELECT
        COUNT('   . $search_config['id_field'] . ') AS count
      FROM
        ' . $search_config['table'] . $where;

  $rs_count = $sql->sqlSingleQuery($query);
  $ret['pg_pages'] = max(1, ceil($rs_count['count'] / $search['search_quantity']));

  if($search['pg'] > $ret['pg_pages']) 
  {
    $search['pg'] = $ret['pg_pages'];
  }

  $ret['pg'] = $search['pg'];

// ---------------------- DB Search ---------------------------
  $select_fields1 = $search_config['id_field'] . ' AS id, ' . implode(', ', $search_config['select_fields']);
  $select_fields2 = $search_config['id_field'] . ', ' . implode(', ', $search_config['select_fields']);

  $offset = $search['pg'] * $search['search_quantity'];

  $x = $search['search_order_type'];
  $y = ($x=='ASC'?'DESC':'ASC');

  if($search['pg'] == $ret['pg_pages'])
  {
    // Last page
    $search['search_quantity'] -= abs($rs_count['count']-$offset);
  }

  $query = '
    SELECT
      ' . $select_fields1 . '
    FROM
    (
      SELECT TOP ' . $search['search_quantity'] . ' 
        ' . $select_fields2 . '
      FROM
      (
        SELECT TOP ' . $offset . '
          ' . $select_fields2 . '
        FROM
          ' . $search_config['table'] . $where . '
        ORDER BY ' . $search['search_order'] . ' ' . $x . '
      ) x
      ORDER BY ' . $search['search_order'] . ' ' . $y . '
    ) y
    ORDER BY ' . $search['search_order'] . ' ' . $search['search_order_type'];

  $ret['result'] = $sql->sqlQuery($query);

  return $ret;  
}




























function b1n_checkSearchG(&$search, $ret, $session_hash_name, $default_order_type, $select_first_if_none = true)
{
  if(empty($default_order_type))
  {
    $default_order_type = 'ASC';
  }

  if(!isset($search['pg'])  || 
     !b1n_checkNumeric($search['pg']) || 
     $search['pg'] <= 0)
  {
    $pg = 1;
  }
  else
  {
    $pg = $search['pg'];
  }

  if(!isset($search['quantity'])  ||
     !in_array($search['quantity'], $ret['possible_quantities']))
  {
    if(isset($_SESSION['search'][$session_hash_name]['quantity']))
    {
      $search['quantity'] = $_SESSION['search'][$session_hash_name]['quantity'];
    }
    else
    {
      $search['quantity'] = b1n_DEFAULT_QUANTITY;
    }
  }

  if(!b1n_cmp($search['order_type'], 'ASC') && 
     !b1n_cmp($search['order_type'], 'DESC'))
  {
    if(isset($_SESSION['search'][$session_hash_name]['order_type']))
    {
      $search['order_type'] = $_SESSION['search'][$session_hash_name]['order_type'];
    }
    else
    {
      if(isset($default_order_type))
      {
        $search['order_type'] = $default_order_type;
      }
    }
  }

  if(in_array($search['field'], $ret['possible_fields']) &&
     in_array($search['order'], $ret['select_fields']))
  {
    return true;
  }
  else
  {
    if(isset($_SESSION['search'][$session_hash_name]['field']))
    {
      $search['field'] = $_SESSION['search'][$session_hash_name]['field'];
    }
    else
    {
      if($select_first_if_none)
      {
        $search['field'] = array_shift($ret['possible_fields']);
      }
      else
      {
        $search['field'] = '';
      }
    }

    if(isset($_SESSION['search'][$session_hash_name]['order']))
    {
      $search['order'] = $_SESSION['search'][$session_hash_name]['order'];
    }
    else
    {
      $search['order'] = $search['field'];
    }

    if(isset($_SESSION['search'][$session_hash_name]['text']))
    {
      $search['text'] = $_SESSION['search'][$session_hash_name]['text'];
    }

    $search['pg'] = $pg;
  }

  return true;
}

function b1n_searchG($sql, $config, $search, $select_first_if_none = true, $where_plus = '')
{
  $ret['select_fields']     = $config['select_fields'];
  $ret['possible_fields']   = $config['possible_fields'];
  $ret['possible_quantities'] = $config['possible_quantities'];
  $ret['session_hash_name'] = $config['session_hash_name'];

// ---------------------- Checking and Session storing ----------------
  if(!b1n_checkSearchG($search, $ret, $config['session_hash_name'], $config['default_order_type'], $select_first_if_none))
  {
    $ret['search'] = $search;
    return $ret;
  }

  $ret['search'] = $search;
  $_SESSION['search'][$config['session_hash_name']] = $search;

  if(empty($search['text']))
  {
    $isnull = ' OR '. $search['field'] . ' IS NULL';
  }
  else
  {
    $isnull = '';
  }

// ---------------------- WHERE ---------------------------

  $where = '';
  if(empty($where_plus))
  {
    if(!empty($search['field']))
    {
      $where = ' WHERE ' . $search['field'] . " LIKE '%" . b1n_inBd($search['text']) . "%'" . $isnull;
    }
  }
  else
  {
    if(empty($search['field']))
    {
      $where = ' WHERE ' . $where_plus;
    }
    else
    {
      $where = ' WHERE (' . $where_plus . ') AND (' . $search['field'] . " LIKE '%" . b1n_inBd($search['text']) . "%'" . $isnull . ')';
    }
  }

// ---------------------- Paggination ----------------
  $query = '
      SELECT
        COUNT(' . $config['id_field'] . ') AS count
      FROM
        ' . $config['table'] . $where;

  $rs_count = $sql->sqlSingleQuery($query);
  $ret['pg_pages'] = max(1, ceil($rs_count['count'] / $search['quantity']));

  if($search['pg'] > $ret['pg_pages']) 
  {
    $search['pg'] = $ret['pg_pages'];
  }

  $ret['pg'] = $search['pg'];

// ---------------------- DB Search ---------------------------
  $fields = implode(', ', $config['select_fields']);
  $select_fields1 = $config['id_field'] . ' AS id, ' . $fields;
  $select_fields2 = $config['id_field'] . ', ' . $fields;
  unset($fields);

  $offset = $search['pg'] * $search['quantity'];

  $x = $search['order_type'];
  $y = ($x=='ASC'?'DESC':'ASC');

  if($search['pg'] == $ret['pg_pages'])
  {
    // Last page
    $search['quantity'] -= abs($rs_count['count']-$offset);
  }

  $query = '
    SELECT
      ' . $select_fields1 . '
    FROM
    (
      SELECT TOP ' . $search['quantity'] . ' 
        ' . $select_fields2 . '
      FROM
      (
        SELECT TOP ' . $offset . '
          ' . $select_fields2 . '
        FROM
          ' . $config['table'] . $where . '
        ORDER BY ' . $search['order'] . ' ' . $x . '
      ) x
      ORDER BY ' . $search['order'] . ' ' . $y . '
    ) y
    ORDER BY ' . $search['order'] . ' ' . $search['order_type'];

  $ret['result'] = $sql->sqlQuery($query);

  return $ret;  
}
?>
