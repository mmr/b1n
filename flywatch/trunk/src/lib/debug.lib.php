<?
/* $Id: debug.lib.php,v 1.4 2003/04/13 22:15:36 binary Exp $ */

function showDebug()
{
    switch(b1n_DEBUG_MODE)
    {
        case 2:
            $debug = prettyDebug($_SESSION, "_SESSION");
            break;
        case 3:
            global $reg_data;
            $debug = prettyDebug($reg_data, "reg_config");
            break;
        case 4:
            global $reg_config;
            $debug = prettyDebug($reg_config, "reg_data");
            break;
        case 5:
            global $search_config;
            $debug = prettyDebug($search_config, "search_config");
            break;
        case 6:
            $vars   = get_defined_vars();
            $consts = get_defined_constants();

            foreach($consts as $k => $v)
            {
                if(substr($k, 0, 3) != 'b1n')
                {
                    unset($consts[$k]);
                }
            }

            $debug  = prettyDebug($vars,   "ALL VARS");
            $debug .= prettyDebug($consts, "ALL CONSTANTS");
            break;
        default:
            error_reporting(E_ALL);
            return;
    }

    error_reporting(E_ALL);
    echo "<div class='debug'>DEBUG MODE = '" . b1n_DEBUG_MODE . "'</div>";
    echo $debug;
}

function prettyDebug($vInput, $vHash = '', $iLevel = 1)
{
    $bg[1] = "#DDDDDD";
    $bg[2] = "#C4F0FF";
    $bg[3] = "#BDE9FF";
    $bg[4] = "#FFF1CA";

    $return = "<table border='0' cellpadding='5' cellspacing='1' style='font-size:10px'><tr><td align='left' bgcolor='" . $bg[$iLevel] . "'>";

    if(is_int($vInput))
    {
        $return .= "int (<b>".intval($vInput)."</b>) </td>";
    }
    elseif(is_float($vInput))
    {
        $return .= "float (<b>".doubleval($vInput)."</b>) </td>";
    }
    elseif(is_string($vInput))
    {
        $return .= "string (" . strlen($vInput) . ") \"<b>" . $vInput . "</b>\"</td>";
    }
    elseif(is_bool($vInput))
    {
        $return .= "bool(<b>" . $vInput ? "true" : "false" . "</b>)</td>";
    }
    elseif(is_array($vInput))
    {
        if($iLevel == 1 && !empty($vHash))
        {
            $return .= " <b>\"" . strtoupper($vHash) . "\" =&gt;</b> ";
        }
        $return .= "array count = [<b>" . count($vInput) . "</b>] dimension = [<b>{$iLevel}</b>]</td></tr><tr><td>";
        $return .= "<table border='0' cellpadding='5' cellspacing='1' style='font-size:10px'>";

        while(list($vKey, $vVal) = each($vInput))
        {
            $return .= "<tr><td align='left' bgcolor='".$bg[$iLevel]."'><b>";
            $return .= (is_int($vKey)) ? "" : "\"";
            $return .= $vKey;
            $return .= (is_int($vKey)) ? "" : "\"";
            $return .= "</b></td><td bgcolor='".$bg[$iLevel]."'>=&gt;</td><td bgcolor='".$bg[$iLevel]."'><b>";

            $return .= prettyDebug($vVal, $vHash, ($iLevel + 1)) . "</b></td></tr>";
        }

        $return .= "</table>";
    }
    else 
    {
        $return .= "NULL</td>";
    }

    $return .= "</table>";
    return $return;
}
?>
