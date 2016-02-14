<?
/*
$Id: reg.lib.php,v 1.70 2004/09/28 22:35:22 mmr Exp $

Reg_Config Structure

Items within '[]' are optional and depends on the 'type'.

$reg_config = 
        array("Item Title" =>
              array("reg_data"  => string,
                    "db"        => string,

                    "check"     => string,
                    "type"      => string,
                    "extra"     => "[seltype]" => string,
                                   "[value]"   => string
                                   "[view]"    => string,
                                   "[size]"    => numeric,
                                   "[maxlen]"  => numeric,
                                   "[rows]"    => numeric,
                                   "[cols]"    => numeric,
                                   "[wrap]"    => string,
                                   "[options]" => array,

                    "search"    => boolean,
                    "orderby"   => boolean,
                    "select"    => boolean,
                    "load"      => boolean,
                    "mand"      => boolean));

reg_data=> name of the key on the $reg_data hash (name of the <input>).
db      => name of the column on the database.
    none:       no db column, probably a control variable (e.g. usr_passwd2)
check   => validation (content checking).
    none:       no check
    numeric:    b1n_checkNumeric
    date:       b1n_checkDate
    date_hour:  b1n_checkDateHour
    email:      b1n_checkEmail
    boolean:    b1n_checkBoolean
type    => HTML <input> Type
    none: No input at all (probably passed through hidden input or same-name-array-checkbox, hehe)
    text, password, select, radio, textarea and checkbox
extra   => Extra Args depending on type or check.
    seltype => <select> type
        Only applicable if type is 'select' and you want to use the pre-existing b1n_buildSelect functions
        date: Need the "year_start" and "year_end" in the array. 
        hour: ...
        date_hour: Need the "year_start" and "year_end" in the array. 
    view    => value of the selected index.
        Only applicable if the type is select.
    size    => <input> size
        Only applicable if type is text, password or textarea.
    maxlen  => <input> MAX Length (also used when check = length)
        Only applicable if type is text or password.
    rows    => Rows number
        Only applicable if type is textarea.
    cols    => Cols number
        Only applicable if type is textarea.
    wrap    => Type of Wrap (virtual or hard).
        Only applicable if type is textarea.
    options => radio options
        Only applicable to type radio
search  => true if wanted as search/order field.
orderby => true if can be used in "ORDER BY" part of query (ATENTION: imply in select = true) 
select  => true if wanted listed after search is performed.
load    => true if wanted on $reg_data (got on SELECT query of load function).
mand    => true if item is mandatory.
*/

define("b1n_LIST_MAX_CHARS", 35);
define("b1n_DEFAULT_SIZE",   35);
define("b1n_DEFAULT_MAXLEN", 200);
define("b1n_DEFAULT_ROWS",   5);
define("b1n_DEFAULT_COLS",   35);
define("b1n_PHONE_DEFAULT_SIZE",   13);
define("b1n_PHONE_DEFAULT_MAXLEN", 13);
define("b1n_DEFAULT_WRAP",   "virtual");

define("b1n_DEFAULT_SELECT_SIZE",   5);
define("b1n_DEFAULT_SELECT_RATIO",  0.2);

define("b1n_DEFAULT_DATE_START_YEAR", 1900);
define("b1n_DEFAULT_DATE_INC", 7);
define("b1n_DEFAULT_DATE_DEC", 7);

define("b1n_MSG_ACCESS_DENIED", 
       "You do not have permission to perform this operation." . 
       "<br />For more information in this issue, contact the System Administrator" .
       "<br /><a href='mailto:" . b1n_SYSTEMADMIN_EMAIL . "'>" . b1n_SYSTEMADMIN_NAME . "</a>");

function b1n_regExtract($reg_config)
{
    $reg_data = array();

    foreach($reg_config as $r)
    {
        b1n_getVar($r["reg_data"], $reg_data[$r["reg_data"]]);
    }

    return $reg_data;
}

function b1n_regAdd($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $module_function = "", $sequence = "")
{
    if(empty($sequence))
    {
        $sequence = $table . "_" . $reg_config["ID"]["db"] . "_seq";
    }

    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $rs = $sql->singleQuery("SELECT NEXTVAL('" . $sequence . "') AS next");
        if($rs)
        {
            $reg_data['id'] = $rs['next'];

            foreach($reg_config as $r)
            {
                $value = $reg_data[$r['reg_data']];
                $aux = "";

                // Fields
                if($r['db'] == 'none')
                {
                    continue;
                }

                // Values
                switch($r['type'])
                {
                case "select":
                    switch($r['extra']['seltype'])
                    {
                    case "date":
                    case "date_check_exp":
                    case "date_check_dob":
                        $aux = b1n_formatDate($value);
                        break;
                    case "date_hour":
                        $aux = b1n_formatDateHour($value);
                        break;
                    case "hour":
                        $aux = b1n_formatHour($value); 
                        break;
                    default:
                        $aux = $value;
                        break;
                    }
                    break;
                case "password":
                    $aux = b1n_crypt($value);
                    break;
                default:
                    $aux = $value;
                    break;
                }

                if(b1n_checkFilled($aux))
                {
                    $aux = "'" . b1n_inBd($aux) . "'";

                    // Setting values
                    $fields[] = $r['db'];
                    $values[] = $aux;
                }
            }

            $fields = implode(", ", $fields);
            $values = implode(", ", $values);

            $query = "INSERT INTO \"" . $table . "\" (" . $fields . ") VALUES (" . $values . ")";

            $rs = $sql->query($query);

            if ($rs)
            {
                $aux = false;

                if(empty($module_function))
                {
                    $aux = true;
                }
                else
                {
                    $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
                }

                if($aux)
                {
                    b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . " added successfully!");
                    return $sql->query("COMMIT TRANSACTION");
                }
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not get the nextval in sequence.");
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not begin transaction.");
    }

    $sql->query("ROLLBACK TRANSACTION");
    return false; 
}

function b1n_regChange($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $module_function = "")
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $query = "SELECT * FROM \"" . $table . "\" WHERE " . $reg_config["ID"]["db"] . " = '" . b1n_inBd($reg_data['id']) . "'";

        $update = "";
        $old_values = $sql->singleQuery($query);

        foreach($reg_config as $t => $r)
        {
            $value = $reg_data[$r['reg_data']];
            $aux = "";

            // Fields
            if($r['db'] == 'none')
            {
                continue;
            }

            // Values
            switch($r['type'])
            {
            case "select":
                switch($r['extra']['seltype'])
                {
                case "date":
                case "date_check_exp":
                case "date_check_dob":
                    $aux = b1n_formatDate($value);
                    $old_values[$r['db']] = b1n_formatDate(b1n_formatDateFromDb($old_values[$r['db']]));
                    break;
                case "date_hour":
                    $aux = b1n_formatDateHour($value);
                    $old_values[$r['db']] = b1n_formatDateHour(b1n_formatDateHourFromDb($old_values[$r['db']]));
                    break;
                case "hour":
                    $aux = b1n_formatHour($value);
                    $old_values[$r['db']] = b1n_formatHour(b1n_formatHourFromDb($old_values[$r['db']]));
                    break;
                default:
                    $aux = $value;
                    break;
                }
                break;
            case "password":
                if(empty($value))
                {
                    // For some reason, switch counts as a loop, so we need to use 'continue(2)' instead of just 'continue'
                    continue(2);
                }
                $aux = b1n_crypt($value);
                break;
            default:
                $aux = $value;
                break;
            }


            // Only update if the values changed ($aux != $old...)
            if($aux != $old_values[$r['db']])
            {
                if(b1n_checkFilled($aux))
                {
                    $aux = $r['db'] . " = '" . b1n_inBd($aux) . "'";
                }
                else
                {
                    $aux = $r['db'] . " = NULL";
                }

                // Setting update array
                $update[] = $aux;
            }
        }

        if(is_array($update))
        {
            $update = implode(", ", $update);
            $query = "UPDATE \"" . $table . "\" SET " . $update . " WHERE " . $reg_config['ID']['db'] . " = '" . b1n_inBd($reg_data['id']) . "'";

            $rs = $sql->query($query);
        }
        else
        {
            $rs = true;
        }

        if($rs)
        {
            $aux = true;

            if(!empty($module_function))
            {
                $reg_data['old_values'] = $old_values;
                $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
            }

            if($aux)
            {
                b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' changed successfully!');
                return $sql->query('COMMIT TRANSACTION');
            }
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
    }

    $sql->query('ROLLBACK TRANSACTION');
    return false;
}

function b1n_regLoad($sql, &$ret_msgs, &$reg_data, $reg_config, $table)
{
    foreach($reg_config as $r)
    {
        if($r['db'] == 'none' || !$r['load'])
        {
            continue;
        }
        $fields[] = $r['db'];
    }

    $fields = implode(", ", $fields);

    $query = "SELECT " . $fields . " FROM \"" . $table . "\" WHERE " . $reg_config['ID']['db'] . " = '" . b1n_inBd($reg_data["id"]) . "'";

    $rs = $sql->singleQuery($query);

    if(is_array($rs))
    {
        foreach($reg_config as $r)
        {
            if($r['db'] == 'none' || !$r['load'])
            {
                continue;
            }

            if($r['type'] == 'select')
            {
                switch($r['extra']['seltype'])
                {
                case 'date':
                case 'date_check_exp':
                case 'date_check_dob':
                    $rs[$r['db']] = b1n_formatDateFromDb($rs[$r['db']]);
                    break;
                case 'date_hour':
                    $rs[$r['db']] = b1n_formatDateHourFromDb($rs[$r['db']]);
                    break;
                }
            }
                
            $reg_data[$r['reg_data']] = $rs[$r['db']];
        }

        $ret = true;
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'ID not Registered.');
        $ret = false;
    }

    return $ret; 
}

function b1n_regDelete($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $msg_plural = "", $module_function = "")
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $query = "DELETE FROM \"" . $table . "\" WHERE " . $reg_config['ID']['db'] . " IS NULL";

        foreach($reg_data["ids"] as $id)
        {
            $query .= " OR " . $reg_config['ID']['db'] . " = '" . b1n_inBd($id) . "'";
        }

        $rs = $sql->query($query);

        if(sizeof($reg_data["ids"]) > 1 && !empty($msg_plural))
        {
            $msg = $msg_plural;
        }

        if($rs)
        {
            $aux = true;

            if(!empty($module_function))
            {
                $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
            }

            if($aux)
            {
                b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . " deleted successfully!");
                return $sql->query("COMMIT TRANSACTION");
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Delete ' . $msg . '.');
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Begin Transaction.');
        return false;
    }
    
    $sql->query("ROLLBACK TRANSACTION");
    return false; 
}

function b1n_regCheck($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = true;

    foreach($reg_config as $t => $r)
    {
        $aux = split("[[:space:]]?&&[[:space:]]?", $r['check']);

        foreach($aux as $check)
        {
            $msg = "";
            switch($check)
            {
            case "none":
                if($r['mand'] && empty($reg_data[$r['reg_data']]))
                {
                    $msg = "Please, fill the <b>" . $t . "</b> field.";
                }
                break;
            case "numeric":
                if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
                {
                    $msg = "Invalid <b>" . $t . "</b> (Only numbers are allowed).";
                }
                break;
            case "date":
                if(isset($reg_data[$r["reg_data"]]["month"]))
                {
                    if(!b1n_checkDate($reg_data[$r["reg_data"]]["month"],
                                      $reg_data[$r["reg_data"]]["day"],
                                      $reg_data[$r["reg_data"]]["year"],
                                      $r['mand']))
                    {
                        $msg = "Invalid date in <b>" . $t . "</b>.";
                    }
                }
                elseif($r['mand'])
                {
                    $msg = "Invalid date in <b>" . $t . "</b>.";
                }
                break;
            case "date_hour":
                if(isset($reg_data[$r["reg_data"]]["month"]))
                {
                    if(!b1n_checkDateHour($reg_data[$r["reg_data"]]["month"],
                                          $reg_data[$r["reg_data"]]["day"],
                                          $reg_data[$r["reg_data"]]["year"],
                                          $reg_data[$r["reg_data"]]["hour"],
                                          $reg_data[$r["reg_data"]]["min"],
                                          $r['mand']))
                    {
                        $msg = "Invalid Date/Hour in <b>" . $t . "</b>.";
                    }
                }
                elseif($r['mand'])
                {
                    $msg = "Invalid date in <b>" . $t . "</b>.";
                }
                break;
            case "email":
                if(!b1n_checkEmail($reg_data[$r["reg_data"]]))
                {
                    $msg = "Invalid <b>" . $t . "</b> (Example: user@domain.org).";
                }
                break;
            case "length":
                if(strlen(trim($reg_data[$r["reg_data"]])) > $r["extra"]["maxlen"])
                {
                    $msg = "No more than '" . $r["extra"]["maxlen"] . "' characters (no leading blank spaces) are allowed in <b>" . $t . "</b>";
                }
                break;
            case "exactlength":
                if(strlen(trim($reg_data[$r["reg_data"]])) != $r["extra"]["maxlen"])
                {
                    $msg = "Exactly '" . $r["extra"]["maxlen"] . "' characters (no leading blank spaces) are allowed in <b>" . $t . "</b>";
                }
                break;
            case "radio":
                if(!b1n_checkFilled($reg_data[$r["reg_data"]]))
                {
                    $msg = "Please, choose something on <b>" . $t . "</b>.";
                }
                break;
            case "boolean":
                if(!b1n_checkBoolean($reg_data[$r["reg_data"]], $r['mand']))
                {
                    $msg = "Please, choose something on <b>" . $t . "</b>.";
                }
                break;
            case "unique":
                if($r['mand'] && empty($reg_data[$r['reg_data']]))
                {
                    $msg = "Please, fill the <b>" . $t . "</b> field.";
                    break;
                }

                if(b1n_checkFilled($reg_data[$r['reg_data']]))
                {
                    $query = "SELECT " . $reg_config['ID']['db'] . " FROM \"" . $r['extra']['table'] . "\" WHERE " . $r['db'] . " = '" . b1n_inBd($reg_data[$r["reg_data"]]) . "'";

                    $rs = $sql->singleQuery($query);

                    if($rs && is_array($rs))
                    {
                        global $page1_title;
                        $msg = "There is already one " . $page1_title . " with this <b>" . $t . "</b>.";
                        unset($page1_title);
                    }
                }
                break;
            case "fk":
                if(is_array($reg_data[$r['reg_data']]))
                {
                    if($r['mand'] && !sizeof($reg_data[$r['reg_data']]))
                    {
                        $msg = "Please, select something in <b>" . $t . "</b>.";
                    }
                }
                else
                {
                    if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
                    {
                        $msg = "Please, select something in <b>" . $t . "</b>.";
                    }
                }
                break;
            case "hour":
                if(!b1n_checkHour($reg_data[$r["reg_data"]]["hour"], $reg_data[$r["reg_data"]]["min"], $r["mand"]))
                {
                    $msg = "Invalid Hour/Minute in <b>" . $t . "</b>.";
                }
                break;
            }

            if(!empty($msg))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
                $ret = false;
            }
        }
    }

    return $ret;
}

function b1n_regCheckChange($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = true;

    foreach($reg_config as $t => $r)
    {
        $msg = "";

        switch($r['check'])
        {
        case "none":
            if($r['mand'] && empty($reg_data[$r['reg_data']]) && $r['type'] != 'password')
            {
                $msg = "Please, fill the <b>" . $t . "</b> field.";
            }
            break;
        case "numeric":
            if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
            {
                $msg = "Invalid <b>" . $t . "</b> (Only numbers are allowed).";
            }
            break;
        case "date":
            if(!b1n_checkDate($reg_data[$r["reg_data"]]["month"],
                              $reg_data[$r["reg_data"]]["day"],
                              $reg_data[$r["reg_data"]]["year"],
                              $r['mand']))
            {
                $msg = "Invalid date in <b>" . $t . "</b>.";
            }
            break;
        case "date_hour":
            if(!b1n_checkDate($reg_data[$r["reg_data"]]["month"],
                              $reg_data[$r["reg_data"]]["day"],
                              $reg_data[$r["reg_data"]]["year"],
                              $reg_data[$r["reg_data"]]["hour"],
                              $reg_data[$r["reg_data"]]["min"],
                              $r['mand']))
            {
                $msg = "Invalid date/hour in <b>" . $t . "</b>.";
            }
            break;
        case "email":
            if(!b1n_checkEmail($reg_data[$r["reg_data"]]))
            {
                $msg = "Invalid <b>" . $t . "</b> (Example: user@domain.org).";
            }
            break;
        case "length":
            if(strlen($reg_data[$r["reg_data"]]) > $r["extra"]["maxlen"])
            {
                $msg = "No more than '" . $r["extra"]["maxlen"] . "' characters are allowed in <b>" . $t . "</b>";
            }
            break;
        case "radio":
            if(!b1n_checkFilled($reg_data[$r["reg_data"]]))
            {
                $msg = "Please, choose something on <b>" . $t . "</b>.";
            }
            break;
        case "boolean":
            if(!b1n_checkBoolean($reg_data[$r["reg_data"]], $r['mand']))
            {
                $msg = "Please, choose something on <b>" . $t . "</b>.";
            }
            break;
        case "unique":
            if($r['mand'] && empty($reg_data[$r['reg_data']]))
            {
                $msg = "Please, fill the <b>" . $t . "</b> field.";
                break;
            }

            $query = "SELECT " . $reg_config['ID']['db'] . " AS id FROM \"" . $r['extra']['table'] . "\" WHERE " . $r['db'] . " = '" . b1n_inBd($reg_data[$r["reg_data"]]) . "' AND " . $reg_config['ID']['db'] . " != '" . b1n_inBd($reg_data["id"]) . "'";
            $rs = $sql->singleQuery($query);

            if(is_array($rs))
            {
                global $page1_title;
                $msg = "There is already one " . $page1_title . " with this <b>" . $t . "</b>.";
                unset($page1_title);
            }
            break;
        case "fk":
            if(is_array($reg_data[$r['reg_data']]) && $r['mand'] && !sizeof($reg_data[$r['reg_data']]))
            {
                $msg = "Please, select something in <b>" . $t . "</b>.";
            }
            else
            {
                if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
                {
                    $msg = "Please, select something in <b>" . $t . "</b>.";
                }
            }
            break;
        case "hour":
            if(isset($reg_data[$r["reg_data"]]["hour"]))
            {
                if(!b1n_checkHour($reg_data[$r["reg_data"]]["hour"], $reg_data[$r["reg_data"]]["min"], $r["mand"]))
                {
                    $msg = "Invalid Hour/Minute in <b>" . $t . "</b>.";
                }
            }
            break;
        }

        if(!empty($msg))
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
            $ret = false;
        }
    }

    return $ret;
}

function b1n_regCheckDelete($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if(is_array($reg_data["ids"]))
    {
        return true;
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You need to check something to Delete.');
    }

    return false;
}

function b1n_regCheckRelationship($sql, &$ret_msgs, $ids, $rel, $table, $col_id, $col_name, $msg)
{
    if(!is_array($rel))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Rel is not an Array.');
        return false;
    }

    $ret = true;

    foreach($ids as $id)
    {
        foreach($rel as $d)
        {
            $query = '
                SELECT
                    ' . $d['col_name'] . '
                FROM
                    "' . $d['table'] . '"
                WHERE
                    ' . $d['col_ref_id'] . ' = \'' . b1n_inBd($id) . '\'';

            $rs = $sql->singleQuery($query);

            if(is_array($rs) && sizeof($rs))
            {
                $rs2 = $sql->singleQuery('
                    SELECT
                        ' . $col_name . '
                    FROM
                        "' . $table . '"
                    WHERE
                        ' . $col_id . ' = \'' . b1n_inBd($id) . '\'');

                $msg = 'You cannot delete the <b><i>' . $rs2[$col_name] . '</i> ' . $msg . '</b> because it is still refered by the <b><i>' . $rs[$d['col_name']] . '</i> ' . $d['title'] . '</b>';

                if(!empty($d['as']))
                {
                    $msg .= ' as <b>' . $d['as'] . '</b>';
                }

                $msg .= '.<br />Process Aborted';

                b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
                $ret = false;
                break(2);
            }
        }
    }

    return $ret;
}

function b1n_regGoBackExit($str = '', $back = '1')
{
    $msg = '<script>';
    if(!empty($str))
    {
        $msg .= 'alert("' . $str . '");';
    }
    $msg .= 'history.go(-' . $back . ')</script>';

    exit($msg);
}
?>
