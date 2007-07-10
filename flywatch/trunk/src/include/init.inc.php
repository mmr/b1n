<?
/* $Id: init.inc.php,v 1.11 2003/07/06 05:52:59 mmr Exp $ */

if(!ini_get('safe_mode') && is_executable("/usr/games/fortune"))
{
    $quote = `/usr/games/fortune -s`;
}

b1n_getVar("text", $text);

if($text == "welcome")
{
    if(strlen(trim($_SESSION['user']['usr_name'])) > 0)
    {
        $text = $_SESSION['user']['usr_name'] . ",";
    }
    $text .= "<br/>Welcome to <i><b>FlyWatch</b> " . b1n_VERSION . "</i> !";
}

if(isset($quote) && !empty($quote))
{
    $text .= "<br /><i>" . b1n_inHtml($quote) . "</i><br />";
}
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?></title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
</head>

<body>

<br />
<br />
<center>
<table cellspacing="0" cellpadding="0" class="maintable">
    <tr>
        <td>
            <table border="0" cellspacing="1" cellpadding="5" class="inttable" align="center">
                <tr>
                    <td class='retsuccess'><?= $text ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</center>
<br />
<br />

</body>
</html>
