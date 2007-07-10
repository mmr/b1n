<div class='c'>
<?
// $Id: ret.inc.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $
foreach($ret_msgs as $msg)
{
  echo '<div class="' . (($msg['status'] === b1n_SUCCESS)?'retsuccess':'retfizzles') . '">' . $msg['msg'] . '</div>';
}
?>
</div>
