<?
/* $Id: toctemplate.inc.php,v 1.24 2002/12/21 18:16:32 binary Exp $ */
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?></title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
    <script language="JavaScript">
    function changeTitles()
    {
        var p = parent.statusbar;

        // StatusBar
        if(document.all)
        {
            o = p.document.all.text;
        }
        else if(document.getElementById)
        {
            o = p.document.getElementById('text');
        }
        <?
        if(!empty($_SESSION['user']['usr_name']))
        {
            $aux = "<a href='" . b1n_URL . "' target='_top' class='menu'>" . $_SESSION['user']['usr_name'] . "</a> &gt;&gt; ";
        }
        else
        {
            $aux = "";
        }

        if(isset($_SESSION['user']['bookmark'][$page0]))
        {
            $aux .= "<a href='" . $_SESSION['user']['bookmark'][$page0] . "' target='_blank' class='menu' style='text-decoration: underline'>";
        }
        else
        {
            $aux .= "<a href='" . b1n_URL . '?page0=' . $page0 . "' target='toc' class='menu'>";
        }
        $aux .= $page0_title . "</a>";

        echo 'o.innerHTML = "' . $aux . "\";\n";
        unset($aux);

        b1n_getVar("frame", $frame);
        if(empty($frame))
        {
            echo 'parent.content.location = "' . b1n_URL . '?page0=init&text=' . urlencode($page0_title) . "\"\n";
        }
        unset($frame);
        ?>
    }
    </script>
</head>
<body onLoad='changeTitles();'>
<div class='back'>
    <div class='section'>
        <span align="center" class="toctitle">
        <?
        if(isset($_SESSION['user']['bookmark'][$page0]))
        {
            echo "<a href='" . $_SESSION['user']['bookmark'][$page0] . "' target='_blank' class='menu' style='text-decoration: underline'>" . $page0_title . "</a>";
        }
        else
        {
            echo $page0_title;
        }
        ?>
        </span>
        <br />
        <br />
<?
ksort($toc);

$key_aux = key($toc);
$aux     = array_shift($toc);

if(b1n_USEICONS)
{
    echo "<a class='menu' href='" . $aux['link'] . "' target='" . $aux['target'] . "'><img src='" . $aux['icon'] . "' alt='" . $key_aux . "' border='0'></a>";

    foreach($toc as $t => $i)
    {
        echo "<br /><br /><a class='menu' href='" . $i['link'] . "' target='" . $i['target'] . "'><img src='" . $i['icon'] . "' alt='" . $t . "' border='0'></a></td>\n";
    }
}
else
{
    echo "[ <a class='menu' href='" . $aux['link'] . "' target='" . $aux['target'] . "'>" . $key_aux . "</a> ]\n";

    foreach($toc as $t => $i)
    {
        echo "<hr />[ <a class='menu' href='" . $i['link'] . "' target='" . $i['target'] . "'>". str_replace(" ", "&nbsp;", $t) . "</a> ]\n";
    }
}
unset($toc);
?>
        <br />
        <br />
    </div>
</div>
</body>
</html>
