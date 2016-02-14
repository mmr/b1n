<?
/* $Id: add.php,v 1.5 2002/12/23 13:57:53 binary Exp $ */ 
$colspan = 3;
$title_msg = "Negative <b>TimeZone</b> Values are supported too. (i.e. -3 [Brazil-East])<br />Partial <b>Timezone</b> Values are supported also. (i.e. +12.75 [New Zealand - Chatham Island])";

require(b1n_REGINCPATH . "/add.inc.php");
?>
<script language='JavaScript'>
function b1n_updateDstEnd(o)
{
    var name = o.name.substr(o.name.indexOf('['));
    o.form.elements['apt_dst_end_dt' + name].selectedIndex = o.selectedIndex
}
</script>
