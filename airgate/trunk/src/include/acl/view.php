<?
    $result = view_acl( $sql, $id );
?>
<tr>
    <td bgcolor="#FFFFFF">
    <table width="100%" cellspacing="0" cellpadding="5">
    <?
        print build_line(array($result[0],$result[1]." &lt;--&gt; ".$result[2]),0,0,0,0,'acl');
    ?>
    </table>
</tr>
<?
    unset($result);
?>
