<?
/* $Id: login.inc.php,v 1.19 2004/09/28 22:35:22 mmr Exp $ */ 
$colspan = 3;
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?></title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
</head>
<body scroll='no'>

<br />
<div class='back'>
    <div class='section' align='center'>
        <span class='maintitle'><a href='<?= b1n_URL ?>' target='_top'>FlyWatch <?= b1n_VERSION ?></a></span>
    </div>
</div>
<br />
<br />

<center>
    <form method='post' action='<?= b1n_URL ?>'>
        <input type='hidden' name='page0'   value='login' />
        <input type='hidden' name='action0' value='login' />

    <table cellspacing='0' cellpadding='0' width='350' class='maintable' style='width: 350'>
        <tr>
            <td>
                <table cellspacing='1' cellpadding='5' class='inttable'>
                    <tr>
                        <td colspan='<?= $colspan ?>' class='box'>Login</td>
                    </tr>
<? require(b1n_INCPATH . '/ret.inc.php'); ?>
                    <tr>
                        <td class='formitem'>Login</td>
                        <td class='forminput'><input name='login' type='text' size='30' maxlength='255' value='' /></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Password</td>
                        <td class='forminput'><input name='passwd' type='password' size='30' maxlength='255' value='' /></td>
                    </tr>
                    <tr>
                        <td colspan='<?= $colspan ?>' class='forminput' align='center'>
                            <input type='submit' value='  OK  ' />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='<?= $colspan ?>' class='box'>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </form>
</center>

<br />
<br />
<br />
<div class='back'>
    <div class='section'>
        <span class='footer'>
            &copy;2001-2003 &nbsp;<a href='http://b1n.org/' title='b1n.org' target='_blank' class='menu'>b 1 n . o r g</a>. All rights reserved.
        </span>
    </div>
</div>
</body>
</html>
