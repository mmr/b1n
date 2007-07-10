<?
/* $Id: index.php,v 1.3 2002/12/23 13:57:53 binary Exp $ */
b1n_getVar("id", $id);

if(!b1n_checkNumeric($id))
{
    ?>
    <script language='JavaScript'>
        history.go(-1);
        window.alert('ID is missing.\nDownload aborted.');
    </script>
    <?
    exit;
}

$query = "SELECT fil_fake_name FROM \"file\" WHERE fil_id = '" . b1n_inBd($id) . "'";
$rs = $sql->singleQuery( $query );

if(!$rs)
{
    ?>
    <script language='JavaScript'>
        history.go(-1);
        window.alert('ID Not Registered.\nDownload Aborted.');
    </script>
    <?
    exit;
}

$fil_fake = $rs['fil_fake_name'];
$fil_real = b1n_UPLOAD_DIR . "/fil_" . $id;

if(is_readable($fil_real))
{
    header("Content-Type: octet/stream");
    //header("Content-Length: ...");
    header("Content-Disposition: attachment; filename=" . $fil_fake);
    readfile($fil_real);
    exit;
}
?>
<script language='JavaScript'>
    history.go(-1);
    window.alert('The file you\'re trying to Download is either non-existent or unaccessible.');
</script>
