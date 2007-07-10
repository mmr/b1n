<?
/* $Id: file.lib.php,v 1.7 2004/09/28 22:35:22 mmr Exp $ */

function b1n_regAddFile($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $rs = $sql->singleQuery("SELECT nextval('file_fil_id_seq')");
        if($rs)
        {
            $reg_data['id'] = $rs['nextval'];

            foreach($reg_config as $r)
            {
                $value = $reg_data[$r['reg_data']];
                $aux = "";

                // Fields
                if($r['db'] == 'none')
                {
                    continue;
                }

                // Values
                $aux = $value;

                if(is_numeric($aux) || !empty($aux))
                {
                    $aux = "'" . b1n_inBd($aux) . "'";

                    // Setting values
                    $fields[] = $r['db'];
                    $values[] = $aux;
                }
            }

            $fields = implode(", ", $fields);
            $values = implode(", ", $values);

            $query = "INSERT INTO \"file\" (" . $fields . ") VALUES (" . $values . ")";

            $rs = $sql->query($query);

            if ($rs)
            {
                if(b1n_regAddFilePlus($sql, $ret_msgs, $reg_data, $reg_config))
                {
                    b1n_retMsg($ret_msgs, b1n_SUCCESS, "File added successfully!");
                    return $sql->query("COMMIT TRANSACTION");
                }
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not get the nextval in sequence.");
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not begin transaction.");
    }

    $sql->query("ROLLBACK TRANSACTION");
    return false; 
}

function b1n_regCheckFile($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config) && b1n_regCheckFileUpload($ret_msgs, $reg_data);
}

function b1n_regCheckChangeFile($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config) && b1n_regCheckFileUpload($ret_msgs, $reg_data);
}

function b1n_regChangeFile($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if(isset($reg_data['do_not_upload']))
    {
        return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "file", "File");
    }
    else
    {
        return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "file", "File", "b1n_regAddFilePlus");
    }
}

function b1n_regCheckDeleteFile($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteFile($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "file", "File", "Files", "b1n_regDeleteFilePlus");
}

function b1n_regLoadFile($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "file");
}

/* -------------------- Plus Functions -------------------- */

// Upload File
function b1n_regAddFilePlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if(move_uploaded_file($_FILES['file']['tmp_name'], b1n_UPLOAD_DIR . "/fil_" . $reg_data['id']))
    {
        $query = "
            UPDATE
                \"file\"
            SET
                fil_fake_name = '" . b1n_inBd($reg_data['fil_fake_name']) . "'
            WHERE
                fil_id = '" . b1n_inBd($reg_data['id']) . "'";

        $rs = $sql->query($query);

        if($rs)
        {
            return true;
        }
    }

    b1n_retMsg($ret_msgs, b1n_FIZZLES, "Unexpected Error! Could not update 'file' table...");
    return false;
}

// Delete Uploaded File
function b1n_regDeleteFilePlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if(!is_writeable(b1n_UPLOAD_DIR))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot write to upload directory '" . b1n_UPLOAD_DIR . "'.");
        return false;
    }

    foreach($reg_data["ids"] as $id)
    {
        $file = b1n_UPLOAD_DIR . '/fil_' . $id;

        if(!file_exists($file) || !is_writeable($file))
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot unlink file '" . $file . "'.");
            return false;
        }
    }

    foreach($reg_data["ids"] as $id)
    {
        unlink(b1n_UPLOAD_DIR . '/fil_' . $id);
    }

    return true;
}

// Check Upload File (and set the fake and real name)
function b1n_regCheckFileUpload(&$ret_msgs, &$reg_data)
{
    global $action0;

    if($action0 == 'change' && $_FILES['file']['size'] == 0 && $_FILES['file']['error'] == 0)
    {
        $reg_data['do_not_upload'] = true;
        return true;
    }
    else
    {
        $reg_data['fil_fake_name'] = $_FILES['file']['name'];
    }

    if(is_uploaded_file($_FILES['file']['tmp_name']))
    {
        if($_FILES['file']['error'] == 0)
        {
            if($_FILES['file']['size'] != 0)
            {
                clearstatcache();
                if(is_writable(b1n_UPLOAD_DIR))
                {
                    return true;
                }
                else
                {
                    b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot write to Upload Directory ('" . b1n_UPLOAD_DIR . "').");
                }
            }
            else
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "<b>File</b> is invalid (ie. 0 bytes long).");
            }
        }
        else
        {
            switch($_FILES['file']['error'])
            {
            case 1:
                $msg = "Uploaded <b>File</b> exceeded upload_max_filesize.";
                break;
            case 2:
                $msg = "Uploaded <b>File</b> exceeded MAX_FILE_SIZE.";
                break;
            case 3:
                $msg = "<b>File</b> was not fully uploaded.";
                break;
            case 4:
                $msg = "No <b>File</b> was uploaded.";
                break;
            case 5:
                $msg = "<b>File</b> is Invalid (ie. 0 bytes long).";
                break;
            default:
                $msg = "Unexpected Error! Undefined Error Code (weird indeed)...";
                break;
            }

            b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "<b>File</b> is invalid.");
    }

    return false;
}
?>
