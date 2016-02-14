<?
/* $Id: data.lib.php,v 1.2 2002/11/12 02:56:01 binary Exp $ */
function b1n_getVar($var, &$dest, $default="")
{
    $dest = $default;

    $ret = isset($_REQUEST[ $var ]);

    if($ret)
    {
        $dest = $_REQUEST[ $var ];
    }

    return $ret;
}

function b1n_retMsg(&$ret_msgs, $status, $msg)
{
    array_push($ret_msgs, array("status" => $status, "msg" => $msg));
}
?>
