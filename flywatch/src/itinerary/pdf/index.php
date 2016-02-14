<?
// $Id: index.php,v 1.11 2003/02/19 22:51:13 binary Exp $

$ret_msgs = array();
b1n_getVar("action2", $action2);
b1n_getVar("ids", $ids);

if(!is_array($ids))
{
    $ids = explode(':', $ids);
}

$leg_id = $ids[sizeof($ids)-1];

if(!b1n_checkNumeric($leg_id, true))
{
    b1n_regGoBackExit('Could not get ID of last leg Checked.\nAborting PDF Generation.' . $leg_id);
}

switch($action1)
{
case 'config':
    // Config PDF Generation
    switch($action0)
    {
    case 'handler':
    case 'permit':
    case 'gedec':
        $page1_title = '';

        // This require defines the $reg_config hash
        // And the beginning of the $page1_title
        require($page0 . '/' . $page1 . '/' . $action0 . '.php');

        $page1_title .= ' PDF Configuration';

        if($action2 == 'generate')
        {
            $func_check = 'b1n_regPdfCheck' . ucfirst($action0);
            $reg_data = b1n_regExtract($reg_config);

            if($func_check($sql, $ret_msgs, $reg_data, $reg_config))
            {
                // Everything is fine, Generate PDF
                $action2 = 'GO';
            }
        }
        break;
    case 'caterer':
        require($page0 . '/' . $page1 . '/' . $action0 . '.php');
        break;
    default:
        b1n_regGoBackExit('Invalid Request.\nWhat are you trying to do? (0)');
    }
    break;
default:
    b1n_regGoBackExit('Invalid Request.\nWhat are you trying to do? (1)');
}

if($action2 == 'GO')
{
    // Generate PDF
    define('b1n_PDF_LINE_SPACE',  '5');
    define('b1n_PDF_DEFAULT_FONTSIZE', '12');
    require(b1n_LIBPATH . '/pdf.lib.php');

    $pdf = new PDF();
    $pdf->SetTitle($page1_title);
    $pdf->Open();
    $pdf->AliasNbPages();

    switch($action0)
    {
    case 'handler':
    case 'permit':
    case 'gedec':
    case 'caterer':
        require($page0 . '/' . $page1 . '/' . $action0 . '_pdf.php');
        break;
    default:
        b1n_regGoBackExit('Invalid Request.\nWhat are you trying to do? (2)');
    }

    $pdf->Output($action0 . '-' . strtolower(date('dMY')) . '.pdf', true);
    exit;
}

$colspan = 3;
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?> - <?= $page1_title ?> Module</title>
    <link rel="stylesheet" href="<?= b1n_CSS ?>" />
    <script language="JavaScript">
    function changeTitles()
    {
        var p = parent.statusbar;

        if(document.getElementById)
        {
            o = p.document.getElementById('text');
        }
        <?
        $aux  = "<a href='" . b1n_URL . '?page0=' . $page0 . "' target='content' class='menu'>" . $page0_title . "</a>";
        $aux .= " &gt;&gt; ";
        $aux .= "<span class='menu' style='color: #ffffff; font-weight: bold'>" . $page1_title . "</span>";

        if(!empty($_SESSION['user']['usr_name']))
        {
            $aux = "<a href='" . b1n_URL . "' target='_top' class='menu'>" . $_SESSION['user']['usr_name'] . "</a> &gt;&gt; " . $aux;
        }
        echo 'o.innerHTML = "' . $aux . "\";\n";
        unset($aux);
        ?>
    }
    </script>
</head>
<body onLoad='changeTitles();'>
<?
if(sizeof($ret_msgs))
{
?>
<br />
<center>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <tr>
                        <td class="box">System Messages</td>
                    </tr>
                    <? require(b1n_INCPATH . "/ret.inc.php"); ?>
                    <tr>
                        <td class="box">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
<?   
}
?>
<br />
<br />
<center>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <form method="post" action="<?= b1n_URL ?>" onSubmit='return confirm("This proccess can take some time (depeding on the DataBases Size).\nAre you sure you want to proceed?");' >
                    <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                    <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                    <input type="hidden" name="action0" value="<?= $action0 ?>" />
                    <input type="hidden" name="action1" value="<?= $action1 ?>" />
                    <input type="hidden" name="action2" value="generate" />
                    <input type="hidden" name="ids"     value="<?= implode(':', $ids) ?>" />
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?></td>
                    </tr>
                    <tr>
                        <td class="retfizzles" colspan="<?= $colspan ?>" align='center'>
                            <i>Items with the '<b>*</b>' are mandatory</i>
                        </td>
                    </tr>
<?
foreach($reg_config as $title => $reg)
{
    if($reg['type'] == 'none')
    {
        continue;
    }
?>
                    <tr>
                        <td class='formitem' width='1'><?= $reg['mand'] ? "*" : "&nbsp;" ?></td>
                        <td class='formitem'><?= $title ?></td>
                        <td class='forminput'>
<?
    switch($reg['type'])
    {
    case "text":
?>
                            <input type="<?= $reg['type'] ?>" name="<?= $reg['reg_data'] ?>" value="<?= $reg_data[$reg['reg_data']] ?>" size="<?= $reg['extra']['size'] ?>" maxlength="<?= $reg['extra']['maxlen'] ?>" />
<?
        break;
    case "select":
        if(!isset($reg['extra']['params']))
        {
            $reg['extra']['params'] = array();
        }

        switch($reg['extra']['seltype'])
        {
        case "date":
        case "date_check_exp":
        case "date_check_dob":
            echo b1n_buildSelectFromDate($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
            break;
        case "date_hour":
            echo b1n_buildSelectFromDateHour($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
            break;
        case "fk":
            if(!isset($reg['extra']['where']))
            {
                $reg['extra']['where'] = array();
            }

            echo b1n_buildSelectCommon($sql, $reg['extra']['name'], $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['where']);
            break;
        case "defined":
            echo b1n_buildSelect($reg['extra']['options'], $reg_data[$reg['reg_data']], $reg['extra']['params'] + array("name" => $reg['reg_data']));
            break;
        }
        break;
    case "radio":
        foreach($reg['extra']['options'] as $opt_title => $opt_value)
        {
?>
                            <input type="radio" name="<?= $reg['reg_data'] ?>" value="<?= $opt_value ?>" class="noborder"<? if($opt_value == $reg_data[$reg['reg_data']]){ echo " checked"; } ?> /> <?= $opt_title ?><br />
<?
        }
        break;
    case "textarea":
?>
                            <textarea name="<?= $reg['reg_data'] ?>" rows="<?= $reg['extra']['rows'] ?>" cols="<?= $reg['extra']['cols'] ?>" wrap="<?= $reg['extra']['wrap'] ?>"><?= b1n_inHtml($reg_data[$reg['reg_data']]) ?></textarea>
<?
        break;
    }
?>
                        </td>
                    </tr>
<?
}
?>
                    <tr>
                        <td colspan='<?= $colspan ?>' class="forminput" align='center'>
                            <input type="submit" value=" Generate PDF >>" />
                            <input type="button" value=" Back >>" onClick="location='<?= b1n_URL . '?page0=' . $page0 ?>&page1=leg'" />
                        </td>
                    </tr>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
</center>
<br />
<br />
