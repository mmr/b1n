<?
/* $Id: upload.lib.php,v 1.1 2002/12/18 05:37:32 binary Exp $ */
function b1n_regFileUpload($sql, &$ret_msgs, $fil_id)
{
    clearstatcache();
    if(!is_writable(b1n_UPLOAD_DIR))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not write to the Upload Direct ('" . b1n_UPLOAD_DIR . "').");
        return false;
    }

    if($_FILES['file']['tmp_name'] == 'none')
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "File is either invalid or too big for Upload.");
        return false;
    }

    // Write to the filesystem
    if(!copy($_FILES['file']['tmp_name'], b1n_UPLOAD_DIR . "/" . $real_name))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Unexpected Error! Could not complete upload...");
        return false;
    }

    $real_name = 'file_' . $fil_id;

    $query = "
        UPDATE
            \"file\"
        SET
            fil_real_name = '" . b1n_inBd($real_name) . "',
            fil_fake_name = '" . b1n_inBd($_FILES['file']['name']) . "'
        WHERE
            fil_id = '" b1n_inBd($fil_id) . "'";

    $rs = $sql->query($query);

    if($rs)
    {
        return true;
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Unexpected Error! Could not complete upload...");
    }

    return false;
}

?>
