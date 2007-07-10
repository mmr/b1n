<?
/* $Id: statusbar.inc.php,v 1.7 2002/11/23 05:12:58 binary Exp $ */

$date = date('l, F d, Y');
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?></title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
    <script language="JavaScript">
    <!--
    function setClock()
    {
        var myDate = new Date()

        // converting date to GMT String and slicing ":.. GMT" out
        var aux = myDate.toGMTString().slice(0, -7);

        document.getElementById('clock').innerHTML = aux;
    }
    //-->
    </script>
</head>

<body noWrap scroll='no' class='statusbar'>
<div class='back'>
    <div class='section'>
        <table width='95%'>
            <tr>
                <td align='left' class='statusbar' style='text-align: left'>
                    <span align='left' id='text' class='statusbar'><a href='<?= b1n_URL ?>' target='_top' class='menu'><?= $_SESSION['user']['usr_name'] ?></a></span>
                </td>
                <td align='right' class='statusbar' style='text-align: right'>
                    <a href='http://www.timeanddate.com/worldclock/' target='_blank' title='World Time' class='menu'><span align='right' id='clock'></span></a>
                </td>
            </tr>
        </table>
    </div>
</div>
<script language="JavaScript">setClock(); self.setInterval('setClock()', 60000);</script>
</body>
</html>
