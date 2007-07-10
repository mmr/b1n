<?
/* $Id: select.lib.php,v 1.19 2004/09/28 22:35:22 mmr Exp $ */

function b1n_buildSelect($hash, $selected, $params, $first_selected_if_none = false)
{
    $ret = '';
    $multiple = false;

    /* Parameters */
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

    /* Open <select> with the given parameters */
    $ret = "<select";
    if(is_array($params))
    {
        foreach($params as $param => $value)
        {
            $ret .= " " . $param;

            if($param == "multiple")
            {
                $multiple = true;

                $size = sizeof($hash);

                if($size > b1n_DEFAULT_SELECT_SIZE)
                {
                    $size = round($size * b1n_DEFAULT_SELECT_RATIO) + b1n_DEFAULT_SELECT_SIZE;
                    $ret .= " size = '" . $size . "'";
                }

                continue;
            }

            if($value != "") 
            {
                $ret .= "='" . $value ."'";
            }
        }
    }
    $ret .= ">\n";

    /* Options */
    if(!$multiple)
    {
        $ret .= "<option value=''>---</option>";
    }

    if(is_array($hash))
    {
        if($first_selected_if_none && !sizeof($selected))
        {
            $first = array_shift($hash);
            $ret  .= "<option value='" . $first . "' selected>" . key($first) . "</option>";
        }

        foreach ($hash as $text => $value)
        {
            $ret .= "  <option value='" . $value . "'";
            if(in_array($value, $selected))
            {
                $ret .= " selected";
            }
            $ret .= ">" . $text . "</option>\n";
        }
    }

    /* Close Select */
    $ret .= "</select>";

    return $ret;
}

function b1n_buildSelectFromResult($result, $selected, $params)
{
    $hash = array();

    if(is_array($result))
    {
        foreach($result as $item)
        {
            $hash +=  array($item['text'] => $item['value']);
        }
    }
            
    return b1n_buildSelect($hash, $selected, $params);
}

function b1n_buildSelectCommon($sql, $name, $value, $text, $table, $selected="", $params=array(), $where = '')
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
        SELECT DISTINCT
            " . $value . " AS value,
            " . $text  . " AS text
        FROM
          \"" . $table . "\" " .
        $where . "
        ORDER BY
            " . $text;

    return b1n_buildSelectFromResult($sql->query($query), $selected, array("name" => $name) + $params);
}

function b1n_viewSelected($sql, $value, $text, $table, $selected)
{
    $query = "
        SELECT DISTINCT
            " . $text  . " AS text
        FROM
          \"" . $table . "\"
        WHERE 
            " . $value . " = '" . $selected . "'";
    
    $res = $sql->singleQuery($query);

    return $res["text"];
}

function b1n_buildSelectFromDate($name, $array_date = array(), $year_start = "", $year_end = "", $extra_params = array())
{
    $current_year = date("Y", time());

    if($year_start == "")
    {
        $year_start = $current_year - 2;
    }

    if($year_end == "")
    {
        $year_end = $current_year + 6;
    }

    if(sizeof($array_date) < 2)
    {
        $array_date['month'] = 0;
        $array_date['day']   = 0;
        $array_date['year']  = 0;
    }

    // Month
    $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    $hash = array();
    $params = array("name" => $name . "[month]");
    for($i=0; $i<=11; $i++)
    {
        $hash[$months[$i]] = $i+1;
    }
    $ret = b1n_buildSelect($hash, $array_date['month'], $params + $extra_params);

    // Day
    $hash = array();
    $params = array("name" => $name . "[day]");
    for($i=1; $i<=31; $i++)
    {
        $i = sprintf("%02d", $i);
        $hash[$i] = $i;
    }
    $ret .= "/" . b1n_buildSelect($hash, $array_date['day'], $params + $extra_params);

    // Year
    $hash = array();
    $params = array("name" => $name . "[year]");
    for($i=$year_start; $i<=$year_end; $i++)
    {
        $hash[$i] = $i;
    }
    $ret .= "/" . b1n_buildSelect($hash, $array_date['year'], $params + $extra_params);

    return $ret;
}

function b1n_buildSelectFromHour($name, $array_hour = array(), $extra_params = array(), $max_hour = 24, $min_inc = 1, $hour_inc = 1)
{
    if(sizeof($array_hour) < 2 )
    {
        $array_hour['hour'] = 0;
        $array_hour['min']  = 0;
    }

    // Hour
    $params = array("name" => $name . "[hour]");
    $hash = array();
    for($i=0; $i < $max_hour; $i+=$hour_inc) 
    {
        $i = sprintf("%02d", $i);
        $hash[$i] = $i;
    }
    $ret = b1n_buildSelect($hash, $array_hour['hour'], $params + $extra_params);

    // Minute
    $params = array("name" => $name . "[min]");
    $hash = array();
    for($i=0; $i<=59; $i+=$min_inc) 
    {
        $i = sprintf("%02d", $i);
        $hash[$i] = $i;
    }
    $ret .= ":" . b1n_buildSelect($hash, $array_hour['min'], $params + $extra_params);

    return $ret;
}

function b1n_buildSelectFromDateHour($name, $array_date_hour = array(), $year_start = "", $year_end = "", $extra_params = array())
{
    $ret  = b1n_buildSelectFromDate($name, $array_date_hour, $year_start, $year_end, $extra_params);
    $ret .= " - ";
    $ret .= b1n_buildSelectFromHour($name, $array_date_hour, $extra_params);

    return $ret;
}


?>
