<? /* $Id: reg.inc.php,v 1.35 2002/12/21 18:16:32 binary Exp $ */ ?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?> - <?= $page1_title ?> Module</title>
<?
if(isset($_SESSION['user']['bookmark'][$page0 . ": " . $page1]))
{
    $page1_title = "<a href='" . $_SESSION['user']['bookmark'][$page0 . ": " . $page1] . "' target='_blank' class='menu' style='text-decoration: underline'>" . $page1_title . "</a>";
}
?>
    <link rel="stylesheet" href="<?= b1n_CSS ?>" />
    <script language="JavaScript">
    function changeTitles()
    {
        var p = parent.statusbar;

        if(document.getElementById)
        {
            o = p.document.getElementById('text');
        }
        <?
        $aux  = "<a href='" . b1n_URL . '?page0=' . $page0 . "' target='toc' class='menu'>" . $page0_title . "</a>";
        $aux .= " &gt;&gt; ";
        $aux .= "<a href='" . b1n_URL . '?page0=' . $page0 . "&page1=" . $page1 . "' target='content' class='menu'>" . $page1_title . "</a>";

        if(!empty($_SESSION['user']['usr_name']))
        {
            $aux = "<a href='" . b1n_URL . "' target='_top' class='menu'>" . $_SESSION['user']['usr_name'] . "</a> &gt;&gt; " . $aux;
        }
        echo 'o.innerHTML = "' . $aux . "\";\n";
        unset($aux);
        ?>
    }
    </script>
</head>
<body onLoad='changeTitles();'>
<?
$ret_msgs = array();

$module = $page1;
$Module = ucfirst($module);
$MODULE = strtoupper($module);

eval("
\$perm = array('add'    => b1n_FUNC_ADD_$MODULE,
               'list'   => b1n_FUNC_LIST_$MODULE,
               'change' => b1n_FUNC_CHANGE_$MODULE,
               'delete' => b1n_FUNC_DELETE_$MODULE,
               'view'   => b1n_FUNC_VIEW_$MODULE);");

$func = array('add'         => 'b1n_regAdd'         . $Module,
              'check'       => 'b1n_regCheck'       . $Module,
              'change'      => 'b1n_regChange'      . $Module,
              'delete'      => 'b1n_regDelete'      . $Module,
              'view'        => 'b1n_regView'        . $Module,
              'load'        => 'b1n_regLoad'        . $Module,
              'checkChange' => 'b1n_regCheckChange' . $Module,
              'checkDelete' => 'b1n_regCheckDelete' . $Module);

unset($module);
unset($Module);
unset($MODULE);

switch($action0)
{
case "add":
    if(b1n_havePermission($perm['add']))
    {
        if($func['check']($sql, $ret_msgs, $reg_data, $reg_config))
        {
            if($func['add']($sql, $ret_msgs, $reg_data, $reg_config))
            {
                $action1 = "list";
            }
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
    }
    break;
case "change":
    if(b1n_havePermission($perm['change']))
    {
        if($func['checkChange']($sql, $ret_msgs, $reg_data, $reg_config))
        {
            if($func['change']($sql, $ret_msgs, $reg_data, $reg_config))
            {
                $action1 = "list";
            }
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
    }
    break;
case "delete":
    if(b1n_havePermission($perm['delete']))
    {
        if($func['checkDelete']($sql, $ret_msgs, $reg_data, $reg_config))
        {
            if($func['delete']($sql, $ret_msgs, $reg_data, $reg_config))
            {
                $action1 = 'list';
            }
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
    }
    break;
case "load":
    if(b1n_havePermission($perm['view']) || 
       b1n_havePermission($perm['change']))
    {
        if(!$func['load']($sql, $ret_msgs, $reg_data, $reg_config))
        {
            $action1 = "list";
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
    }
}

unset($func);

if(sizeof($ret_msgs))
{
?>
<center>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <tr>
                        <td class="box">System Messages</td>
                    </tr>
                    <? require(b1n_INCPATH . "/ret.inc.php"); ?>
                    <tr>
                        <td class="box">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
<?   
}

$already_denied = false;

switch($action1)
{
case "add":
case "change":
case "view":
    if(b1n_havePermission($perm[$action1]))
    {
        require($page0 . "/" . $page1 . "/" . "$action1" . ".php");
        break;
    }
    else
    {
        $already_denied = true;
        require(b1n_INCPATH . "/denied.inc.php");
    }
default:
    if(b1n_havePermission($perm['list']))
    {
        require($page0 . "/" . $page1 . "/list.php");
    }
    else
    {
        if(!$already_denied)
        {
            require(b1n_INCPATH . "/denied.inc.php");
        }
    }
    break;
}

unset($perm);
?>
</body>
</html>
