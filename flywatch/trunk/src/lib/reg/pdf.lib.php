<?
// $Id: pdf.lib.php,v 1.5 2003/02/22 13:18:35 binary Exp $

function b1n_regPdfCheckHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regPdfCheckPermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regPdfCheckGedec($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regPdfCheckCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $ret = false;

        $aux = $reg_data;
        if(is_array($aux))
        {
            // Contacts
            array_pop($aux);

            // Remarks
            array_shift($aux);

            foreach($aux as $x)
            {
                if($x > 0) 
                {
                    $ret = true;
                    break;
                }
            }

            if(!$ret)
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "At least, one item must have its quantity greater than 0.");
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "No items were found.");
        }
    }

    return $ret;
}
?>
