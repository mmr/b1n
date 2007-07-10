<?
// $Id: select.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

function b1n_buildSelect($hash, $selected, $params, $first_selected_if_none = false)
{
  $ret = '';
  $multiple = false;

  // Parameters
  if(!is_array($selected))
  {
    if(!empty($selected))
    {
      $selected = array($selected);
    }
    else
    {
      $selected = array();
    }
  }

  // Open <select> with the given parameters
  $ret = '<select';
  if(is_array($params))
  {
    foreach($params as $param => $value)
    {
      $ret .= ' ' . $param;

      if(b1n_cmp($param, 'multiple'))
      {
        $multiple = true;

        $size = sizeof($hash);

        #if($size > b1n_DEFAULT_SELECT_SIZE)
        #{
        #  $size = round($size * b1n_DEFAULT_SELECT_RATIO) + b1n_DEFAULT_SELECT_SIZE;
        #  $size = round($size * b1n_DEFAULT_SELECT_RATIO) + b1n_DEFAULT_SELECT_SIZE;
        #  $ret .= " size = '" . $size . "'";
        #}
      }

      if(!empty($value)) 
      {
        $ret .= "='" . $value ."'";
      }
    }
  }
  $ret .= ">\n";

  // Options
  if(!$multiple)
  {
    $ret .= "<option value=''>---</option>";
  }

  if(is_array($hash))
  {
    if($first_selected_if_none && !sizeof($selected))
    {
      $first = array_shift($hash);
      $ret  .= "<option value='" . $first . "' selected='selected'>" . key($first) . "</option>";
    }

    foreach ($hash as $text => $value)
    {
      $ret .= "  <option value='" . $value . "'";
      if(in_array($value, $selected))
      {
        $ret .= " selected='selected'";
      }
      $ret .= ">" . $text . "</option>\n";
    }
  }

  /* Close Select */
  $ret .= '</select>';

  return $ret;
}

function b1n_buildSelectFromResult($result, $selected, $params)
{
  $hash = array();

  if(is_array($result))
  {
    foreach($result as $item)
    {
      $hash += array($item['text'] => $item['value']);
    }
  }
      
  return b1n_buildSelect($hash, $selected, $params);
}

function b1n_buildSelectCommon($sql, $name, $value, $text, $table, $selected='', $params=array(), $where = '')
{
  if(empty($params))
  {
    $params = array();
  }

  if(!empty($where))
  {
    $where = ' WHERE ' . $where;
  }

  $query = "
    SELECT
      " . $value . " AS value,
      " . $text  . " AS text
    FROM
      " . $table . " " .
    $where . "
    ORDER BY
      " . $text;

  return b1n_buildSelectFromResult($sql->sqlQuery($query), $selected, array('name' => $name) + $params);
}

function b1n_viewSelected($sql, $value, $text, $table, $selected)
{
  $query = "
    SELECT
      " . $text  . " AS text
    FROM
      " . $table . "
    WHERE 
      " . $value . " = '" . $selected . "'";

  $res = $sql->sqlSingleQuery($query);

  return $res['text'];
}

function b1n_buildSelectFromYear($name, $selected, $year_start = '', $year_end = '', $extra_params = array())
{
  $current_year = date('Y', time());

  if(empty($year_start))
  {
    $year_start = $current_year - 2;
  }

  if(empty($year_end))
  {
    $year_end = $current_year + 6;
  }

  if(empty($selected))
  {
    $selected = $current_year;
  }

  // Year
  $hash = array();
  $params = array('name' => $name);
  for($i=$year_start; $i<=$year_end; $i++)
  {
    $hash[$i] = $i;
  }
  return b1n_buildSelect($hash, $selected, $params + $extra_params);
}

function b1n_buildSelectFromDate($name, $array_date = array(), $year_start = '', $year_end = '', $extra_params = array())
{
  $current_year = date('Y', time());

  if(empty($year_start))
  {
    $year_start = $current_year - 2;
  }

  if(empty($year_end))
  {
    $year_end = $current_year + 6;
  }

  if(sizeof($array_date) < 2)
  {
    $array_date['month'] = 0;
    $array_date['day']   = 0;
    $array_date['year']  = 0;
  }
  // Day
  $hash = array();
  $params = array('name' => $name . '[day]');
  for($i=1; $i<=31; $i++)
  {
    $i = sprintf('%02d', $i);
    $hash[$i] = $i;
  }
  $ret = b1n_buildSelect($hash, $array_date['day'], $params + $extra_params);

  // Month
  $months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
  $hash = array();
  $params = array('name' => $name . '[month]');
  for($i=0; $i<=11; $i++)
  {
    $hash[$months[$i]] = $i+1;
  }
  $ret .= '/' . b1n_buildSelect($hash, $array_date['month'], $params + $extra_params);


  // Year
  $hash = array();
  $params = array('name' => $name . '[year]');
  if($year_end > $year_start)
  {
    for($i=$year_start; $i<=$year_end; $i++)
    {
      $hash[$i] = $i;
    }
  }
  else
  {
    for($i=$year_start; $i>=$year_end; $i--)
    {
      $hash[$i] = $i;
    }
  }
  $ret .= '/' . b1n_buildSelect($hash, $array_date['year'], $params + $extra_params);

  return $ret;
}

function b1n_buildSelectFromHour($name, $array_hour = array(), $extra_params = array(), $max_hour = 24)
{
  if(sizeof($array_hour) < 2 )
  {
    $array_hour['hour'] = 0;
    $array_hour['min']  = 0;
  }

  // Hour
  $params = array('name' => $name . '[hour]');
  $hash = array();
  for($i=0; $i < $max_hour; $i++) 
  {
    $i = sprintf('%02d', $i);
    $hash[$i] = $i;
  }
  $ret = b1n_buildSelect($hash, $array_hour['hour'], $params + $extra_params);

  // Minute
  $params = array('name' => $name . '[min]');
  $hash = array();
  for($i=0; $i<=59; $i++) 
  {
    $i = sprintf('%02d', $i);
    $hash[$i] = $i;
  }
  $ret .= ':' . b1n_buildSelect($hash, $array_hour['min'], $params + $extra_params);

  return $ret;
}

function b1n_buildSelectFromDateHour($name, $array_date_hour = array(), $year_start = '', $year_end = '', $extra_params = array())
{
  $ret  = b1n_buildSelectFromDate($name, $array_date_hour, $year_start, $year_end, $extra_params);
  $ret .= ' - ';
  $ret .= b1n_buildSelectFromHour($name, $array_date_hour, $extra_params);

  return $ret;
}


?>
