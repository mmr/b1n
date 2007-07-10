<?
// $Id: permission.lib.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $

function b1n_doLogin($sql, &$ret_msgs, &$logging)
{
  $ret_msgs = array();

  session_unset();

  if((!b1n_getVar('page',     $page))   ||
     (!b1n_getVar('action0',  $action0))||
     (!b1n_getVar('login',    $login))  ||
     (!b1n_getVar('passwd',   $passwd)) ||
     ($page     != 'login') ||
     ($action0  != 'login'))
  {
    return false;
  }

  $query = "
    SELECT
      usr_id, usr_nome
    FROM
      view_usr_ativo
    WHERE
      usr_login = '" . b1n_inBd($login) . "' AND
      usr_senha = '" . b1n_inBd(b1n_crypt($passwd)) . "'";

  $ret = $sql->sqlSingleQuery($query);

  if(!is_array($ret))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Login incorreto');
    return false;
  }

  $user = array('usr_id'    => $ret['usr_id'],
                'usr_nome'  => $ret['usr_nome']);
#                'usr_nome'  => ucfirst(strtok($ret['usr_nome'], ' ')));

  $user['permission'] = b1n_getPermissions($sql, $user['usr_id']);

  $_SESSION['user'] = $user;
  $logging = 1;

  return true;
}

function b1n_isLogged ()
{
  return isset($_SESSION['user']);
}

function b1n_getPermissions($sql, $usr_id)
{
  $perm = array();

  $query = "
    SELECT
      fnc_nome
    FROM
      view_usr_fnc
    WHERE
      usr_id = '" . b1n_inBd($usr_id) . "'";

  $ret = $sql->sqlQuery($query);

  if(is_array($ret))
  {
    foreach ($ret as $fnc)
    {
      array_push($perm, $fnc['fnc_nome']);
    }
  }

  return $perm;
}

function b1n_havePermission($required)
{
  if(!isset($_SESSION['user']))
  {
    return false;
  }

  return in_array($required, $_SESSION['user']['permission']);
}

function b1n_logOut()
{
  unset($_SESSION['user']);
  return session_destroy();
}


function b1n_crypt($str)
{
  require(b1n_SECRETKEY_FILE);
  $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
  $str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv));
  return $str;
}

function b1n_decrypt($str)
{
  require(b1n_SECRETKEY_FILE);
  $str = base64_decode($str);
  $iv  = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
  $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv);
  return $str;
}

// Function List
require(b1n_PATH_LIB . '/functionlist.lib.php');
?>
