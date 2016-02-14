<?
/* $Id: ret.inc.php,v 1.6 2002/12/21 18:16:32 binary Exp $ */

if(!isset($colspan) || empty($colspan))
{
    $colspan = 1;
}

if(isset($ret_msgs) && is_array($ret_msgs) && sizeof($ret_msgs))
{
    echo "<tr><td colspan='" . $colspan . "' class='retbox'>";
    foreach($ret_msgs as $msg)
    {
        echo '<div class="' . (($msg['status'] === b1n_SUCCESS)? 'retsuccess' : 'retfizzles') . '">';
        echo $msg['msg'];
        echo '</div>';
    }
    echo "</td></tr>";
}
?>
