<?
/* $Id: frame.inc.php,v 1.17 2002/12/18 14:34:32 binary Exp $ */

// Setting default TOC and Content as Blank and WelcomeMessage respectivelly
$frameset_middle_cols = b1n_FRAMESET_MIDDLE_COLS;
$usr_toc = '?page0=blank';
$usr_content = '?page0=init&text=welcome';

// If the user have a start page, use it!
if(isset($_SESSION['user']['usr_toc']))
{
    $usr_toc = '?page0=' . $_SESSION['user']['usr_toc'];

    if(isset($_SESSION['user']['usr_content']))
    {
        $usr_content = $usr_toc . '&page1=' . $_SESSION['user']['usr_content'];

        if(ereg('itinerary|docs', $usr_content))
        {
            $usr_toc = '?page0=blank';
            $frameset_middle_cols = '0, *';
        }
        else
        {
            $usr_toc .= "&frame=true";
        }
    }
}
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?></title>
    <link rel='stylesheet' src='<?= b1n_CSS ?>' />
</head>

<frameset border="0" framespacing="0" rows="<?= b1n_FRAMESET_MAIN_ROWS ?>" frameborder="0">
    <frame name="topmenu" src="<?= b1n_URL ?>?page0=topmenu" noresize />
    <frame name="statusbar" style="border: 1px solid #FFFFFF" src="<?= b1n_URL ?>?page0=statusbar" noresize />
    <frameset border="0" id="manInTheMiddle" framespacing="0" cols="<?= $frameset_middle_cols ?>" frameborder="0">
        <frame name="toc" id="toc" src="<?= b1n_URL . $usr_toc ?>" noresize />
        <frame name="content" src="<?= b1n_URL . $usr_content ?>" noresize />
    </frameset>
    <frame name="footer" style="border: 1px solid #FFFFFF" src="<?= b1n_URL ?>?page0=footer" noresize />
</frameset>
</html>
