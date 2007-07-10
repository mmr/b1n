<?
/* $Id: formatdata.lib.php,v 1.27 2003/03/15 17:21:33 binary Exp $ */

// EXP_CHECK_DATE (in Seconds)
    // 60*60*24*30*3 = 129600 = 3 months
define("b1n_DATECHECK_EXP_GRACE1", 7776000);

    // 60*60*24*30 = 43200s = 1 month
define("b1n_DATECHECK_EXP_GRACE2", 2592000);

    // 60*60*24*15 = 21600 = 15 days
define("b1n_DATECHECK_EXP_GRACE3", 1296000);

// DATECHECK_DOB (in Seconds)
    // 60*60*24*10 = 864000 = 10 days
define("b1n_DATECHECK_DOB_GRACE1", 864000);

    // 60*60*24*5 = 432000 = 5 days
define("b1n_DATECHECK_DOB_GRACE2", 432000);

    // 0 = BirthDay!!
define("b1n_DATECHECK_DOB_GRACE3", 0);

    // Date Format: dMY = ddMonYYYY. ie: 03Mar1983
define("b1n_DATE_FORMAT", "dMY"); 

function b1n_inBd($var)
{
    if(is_null($var))
    {
        return '';
    }

    return addslashes(trim($var));
}

function b1n_inHtml($var)
{
    return nl2br(htmlspecialchars($var, ENT_QUOTES));
}

function b1n_inHtmlNoBr($var)
{
    return "<nobr>" . htmlspecialchars($var, ENT_QUOTES) . "</nobr>";
}

function b1n_inHtmlLimit($var)
{
    return b1n_inHtml((strlen($var) <= b1n_LIST_MAX_CHARS)? $var : substr($var, 0, b1n_LIST_MAX_CHARS) . "...");
}

function b1n_formatCurrency($x)
{
    $decc = $decp = 0;
    $c_count = substr_count($x,',');
    $p_count = substr_count($x,'.');
    $c_pos   = strlen(strrchr($x,','));
    $p_pos   = strlen(strrchr($x,'.'));

    if(!ereg("^[ 0-9\.\, ]*$",$x))
    {
        return null;
    }

    if(($c_count>1)&& ($p_count>1))
    {
        return null;
    }

    if(($c_count==1)&& ($p_count==1))
    {
        $c_pos < $p_pos ?  $decc = 1 : $decp = 1; 
    }
    elseif($c_count==1)
    {
        $decc = 1;
    }
    elseif($p_count==1)
    {
        $decp = 1;
    }

    if($decp)
    {
        $x = str_replace(',','',$x);
    }
    elseif($decc)
    {
        $x = str_replace('.','',$x);
        $x = str_replace(',','.',$x);
    }
    else
    {
        $x = str_replace('.','',$x);
        $x = str_replace(',','',$x);
    }

    return (float)$x;
}

function b1n_crypt($str)
{
    require(b1n_LIBPATH . "/sec/.skey.lib.php");

    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv));
    return $str;
}

function b1n_decrypt($str)
{
    require(b1n_LIBPATH . "/sec/.skey.lib.php");

    $str = base64_decode($str);
    $iv  = mcrypt_create_iv(mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv);
    return $str;
}

function b1n_formatDate($a = array())
{
    if(is_array($a))
    {
        if(!empty($a['year'])  &&
           !empty($a['month']) &&
           !empty($a['day']))
        {
            return sprintf("%04d-%02d-%02d", $a['year'], $a['month'], $a['day']);
        }
    }
}

function b1n_formatHour($a = array())
{
    if(is_array($a))
    {
        if(!empty($a['hour']) && !empty($a['min']))
        {
            return sprintf("%02d:%02d", $a['hour'], $a['min']);
        }
    }
}

function b1n_formatDateHour($a = array())
{
    if(is_array($a))
    {
        if(!empty($a['year'])  &&
           !empty($a['month']) &&
           !empty($a['day']))
        {
            if(empty($a['hour']))
            {
                $a['hour'] = '0';
            }

            if(empty($a['min']))
            {
                $a['min'] = '0';
            }

            return sprintf("%04d-%02d-%02d %02d:%02d", $a['year'], $a['month'], $a['day'], $a['hour'], $a['min']);
        }
    }
}

function b1n_formatDateFromDb($a)
{
    if($a)
    {
        list($ret['year'], $ret['month'], $ret['day']) = explode('-', strtok($a, ' '));
        return $ret;
    }
}

function b1n_formatHourFromDb($a)
{
    if($a)
    {
        list($ret['hour'],  $ret['min']) = explode(':', strtok($a, ' '));
        return $ret;
    }
}

function b1n_formatDateHourFromDb($a)
{
    if($a)
    {
        list($ret['year'], $ret['month'], $ret['day']) = explode('-', strtok($a, ' '));
        list($ret['hour'],  $ret['min']) = explode(':', strtok(' '));
        return $ret;
    }
}

function b1n_formatDateShow($a)
{
    if(is_array($a))
    {
        return strtoupper(date(b1n_DATE_FORMAT, strtotime($a['month'] . "/" . $a['day'] . "/" . $a['year'])));
    }
}

function b1n_formatHourShow($a)
{
    if(is_array($a))
    {
        return $a['hour'] . ":" . $a['min'];
    }
}

function b1n_formatDateHourShow($a)
{
    if(is_array($a))
    {
        return strtoupper(date(b1n_DATE_FORMAT, strtotime($a['month'] . "/" . $a['day'] . "/" . $a['year']))) . " " . $a['hour'] . ":" . $a['min'];
    }
}


function b1n_formatDateCheckExpShow($a)
{
    if(is_array($a))
    {
        $ts_now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $ts_exp_dt = mktime(0, 0, 0, $a['month'], $a['day'], $a['year']);
        $time_left = $ts_exp_dt - $ts_now;

        $ret = strtoupper(date(b1n_DATE_FORMAT, $ts_exp_dt));

        if($time_left >= 0)
        {
            if($time_left <= b1n_DATECHECK_EXP_GRACE3)
            {
                $ret = "<span class='exp_grace3'>" . $ret . "</span>";
            }
            elseif($time_left <= b1n_DATECHECK_EXP_GRACE2)
            {
                $ret = "<span class='exp_grace2'>" . $ret . "</span>";
            }
            elseif($time_left <= b1n_DATECHECK_EXP_GRACE1)
            {
                $ret = "<span class='exp_grace1'>" . $ret . "</span>";
            }
        }
        else
        {
            $ret = "<span class='exp_grace3'>" . $ret . "</span>";
        }

        return $ret;
    }
}

function b1n_formatDateCheckDobShow($a)
{
    if(is_array($a))
    {
        $ts_now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $ts_exp_dt = mktime(0, 0, 0, $a['month'], $a['day'], $a['year']);

        /* Well... *magic*, i use the actual year... */
        $ts_exp_dt_tricky = mktime(0, 0, 0, $a['month'], $a['day'], date("Y"));
        $time_left = $ts_exp_dt_tricky - $ts_now;

        $ret = strtoupper(date(b1n_DATE_FORMAT, $ts_exp_dt));

        if($time_left >= 0)
        {
            if($time_left <= b1n_DATECHECK_DOB_GRACE3)
            {
                $ret = "<span class='dob_grace3'>" . $ret . "</span>";
            }
            elseif($time_left <= b1n_DATECHECK_DOB_GRACE2)
            {
                $ret = "<span class='dob_grace2'>" . $ret . "</span>";
            }
            elseif($time_left <= b1n_DATECHECK_DOB_GRACE1)
            {
                $ret = "<span class='dob_grace1'>" . $ret . "</span>";
            }
        }

        return $ret;
    }
}

/* Convert seconds to months, weeks, days, hours and seconds */
function b1n_formatDateFromSeconds($secs)
{
    $ret = '';

    if(empty($secs) || $secs < 0)
    {
        return $ret;
    }

    // ------------- MONTHS
    // 1 month = 30 days * 24 hours * 60 minutes * 60 seconds = 2592000 seconds
    $months = intval($secs/2592000);
    $secs  -= $months * 2592000;
    if($months > 0)
    {
        $ret .= $months . "m&nbsp;";
    }

    // ------------- WEEKS
    // 1 week = 7 days * 24 hours * 60 minutes * 60 seconds = 604800
    $weeks = intval($secs/604800);
    $secs -= $weeks * 604800;
    if($weeks > 0)
    {
        $ret .= $weeks . "w&nbsp;";
    }
  
    // ------------- DAYS
    // 1 day = 24 hours * 60 minutes * 60 seconds = 86400
    $days  = intval($secs/86400);
    $secs -= $days * 86400;
    if($days > 0)
    {
        $ret .= $days . "d&nbsp;";
    }

    // ------------- HOURS:MINUTES
    // 1 hour = 60 minutes * 60 seconds = 3600
    $hours = intval($secs/3600);
    $secs -= $hours * 3600;

    // 1 minute = 60 seconds
    $mins  = intval($secs/60);
    $secs -= $mins * 60;
    $ret .= sprintf("%02d:%02d", $hours, $mins);

    return $ret;
}
?>
