<?
function msex_r($var, $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $endline = "<br>", $tabr = "")
{
    if (!is_array($var)) {print("[".$var."]"); return;}

    $tabaux = $tab.$tabr;

    print ("Array (".$endline);

    foreach ($var as $key => $value) {
	print($tabaux."[".$key."] => ");
	msex_r($value, $tab, $endline, $tabaux);
	print ($endline);
    }
    print ($tabr.")");
}
?>