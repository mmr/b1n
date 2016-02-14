<?
/* $Id: permission.lib.php,v 1.18 2003/03/15 17:21:33 binary Exp $ */

function b1n_getPermissions($sql, $usr_id)
{
    $perm = array();

    $query = "
        SELECT
            fnc_name
        FROM
            view_usr_fnc
        WHERE
            usr_id = '" . b1n_inBd($usr_id) . "'";

    $rs = $sql->query($query);

    if (is_array($rs))
    {
        foreach ($rs as $row)
        {
	    array_push($perm, $row["fnc_name"]);
        }
    }

    return $perm;
}

function b1n_getBookmarks($sql, $usr_id)
{
    $syslink = array();

    $query = "
        SELECT
            slk_name,
            slk_url
        FROM
            syslink
        WHERE
            usr_id = '" . b1n_inBd($usr_id) . "' AND
            slk_name IS NOT NULL AND
            slk_url  IS NOT NULL";

    $rs = $sql->query($query);

    if (is_array($rs))
    {
        foreach ($rs as $row)
        {
	    $syslink[$row["slk_name"]] = $row["slk_url"];
        }
    }

    return $syslink;
}


function b1n_logOut($sql)
{
    session_destroy();
}

function b1n_doLogin($sql, &$ret_msgs, &$logging)
{
    $ret_msgs = array();

    session_unset();

    if((!b1n_getVar("page0",    $page0)) ||
       (!b1n_getVar("action0",  $action0)) ||
       (!b1n_getVar("login",    $login))  ||
       (!b1n_getVar("passwd",   $passwd)) ||
       ($page0   != "login") ||
       ($action0 != "login"))
    {
        return false;
    }

    $query = "
        SELECT
            usr_id,
            usr_name,
            usr_start_page,
            usr_email
        FROM
            view_active_usr
        WHERE
            usr_login = '" . b1n_inBd($login) . "'
            AND usr_passwd = '" . b1n_inBd(b1n_crypt($passwd)) . "'";

    $rs = $sql->singleQuery($query);

    if(!is_array($rs))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Login incorrect");
        return false;
    }

    $user = array("usr_id"    => $rs["usr_id"],
                  "usr_name"  => ucfirst(strtok($rs["usr_name"], " ")),
                  "usr_email" => $rs["usr_email"]);

    if(!empty($rs['usr_start_page']))
    {
        $aux = explode(': ', $rs['usr_start_page']);
        if(sizeof($aux))
        {
            $user += array("usr_toc" => $aux[0]);

            if(isset($aux[1]));
            {
                $user += array("usr_content" => $aux[1]);
            }
        }
    }

    $user["permission"] = b1n_getPermissions($sql, $user["usr_id"]);
    $user["bookmark"]   = b1n_getBookmarks($sql, $user["usr_id"]);

    $_SESSION["user"] = $user;
    $logging = 1;

    return true;
}

function b1n_isLogged ()
{
    return (session_is_registered("user"));
}

function b1n_havePermission($required)
{
    if(!session_is_registered("user"))
    {
        return false;
    }

    return in_array($required, $_SESSION["user"]["permission"]);
}

/* Function List */
require(b1n_LIBPATH . "/functionlist.lib.php");
?>
