<?
/* $Id: checkdata.lib.php,v 1.9 2003/04/13 22:15:36 binary Exp $ */

function b1n_checkDate($month, $day, $year, $mandatory = false)
{
    if(!$mandatory && (empty($month) && empty($day) && empty($year)))
    {
        return true;
    }

    return checkdate($month, $day, $year);
}

function b1n_checkHour($hour, $min, $mandatory = false)
{
    if(!$mandatory && (empty($hour) && empty($min)))
    {
        return true;
    }

    $ret = b1n_checkNumeric($hour, $mandatory) && b1n_checkNumeric($min, $mandatory);
    $ret = $ret && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59);

    return $ret;
}

function b1n_checkDateHour($month, $day, $year, $hour, $min, $mandatory = false)
{
    return b1n_checkDate($month, $day, $year, $mandatory) && b1n_checkHour($hour, $min, $mandatory);
}

function b1n_checkNumeric($str, $mandatory = false)
{
    if(!$mandatory && empty($str))
    {
        return true;
    }

    return is_numeric($str);
}

function b1n_checkFilled($str)
{
    return is_numeric($str) || !empty($str);
}

function b1n_checkBoolean($str, $mandatory = false)
{
    if(!$mandatory && empty($str))
    {
        return true;
    }

    return is_numeric($str) && is_int($str+0);
}

function b1n_checkPhone($str, $mandatory = false)
{
    if(!$mandatory && empty($str))
    {
        return true;
    }

    return ereg("^([[:digit:]]|-)$", $str);
}

function b1n_checkEmail($str, $mandatory = false)
{
    if(!$mandatory && empty($str))
    {
        return true;
    }

    return ereg("^[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*\@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_-]+)+$", $str); 
}
?>
