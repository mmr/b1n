<?
// $Id: caterer.php,v 1.2 2003/02/22 13:18:35 binary Exp $
$page1_title = 'Catering Order';

// Getting handler ID of the airport of the Last checked Leg
$query = "
    SELECT
        cat_id
    FROM
        \"leg\"
        JOIN \"airport\" ON (leg.apt_id_arrive = airport.apt_id)
    WHERE
        leg_id = '" . b1n_inBd($leg_id) . "'";

$rs = $sql->singleQuery($query);

if($rs && is_array($rs))
{
    $cat_id = $rs['cat_id'];
}
else
{
    b1n_regGoBackExit('Could not get ID of the Caterer of the checked Leg.\nAborting PDF Generation.');
}

// Configuration Hash
    // Contacts
$reg_config = array(
        'Contacts' => array(
            'reg_data'  => 'contacts',
            'db'        => 'none',
            'check'     => 'fk',
            'mand'      => true));

    // FoodType X Food
$query = '
    SELECT 
        fdt_id,
        fod_id,
        fdt_name,
        fod_name
    FROM
        "fdt_fod"
        NATURAL JOIN "foodtype"
        NATURAL JOIN "food"
    ORDER BY
        fdt_name, fod_name';

$fdt_fod = $sql->query($query);

if($fdt_fod && is_array($fdt_fod))
{
    foreach($fdt_fod as $r)
    {
        $reg_config += array(
            $r['fdt_name'] . ' - ' . $r['fod_name'] => array(
                'reg_data'  => 'qtd_' . $r['fdt_id'] . '_' . $r['fod_id'],
                'name'      => $r['fod_name'],
                'db'        => 'none',
                'check'     => 'numeric',
                'mand'      => false));
    }
}
else
{
    b1n_regGoBackExit('Could not get any FoodType (or Food), did you really add some?.\nAborting PDF Generation.');
}

$reg_config += array(
        'Remarks' => array(
            'reg_data'  => 'remarks',
            'db'        => 'none',
            'check'     => 'none',
            'mand'      => false));

$reg_data = b1n_regExtract($reg_config);

if($action2 == 'generate' && b1n_regPdfCheckCaterer($sql, $ret_msgs, $reg_data, $reg_config))
{
// Generate PDF
    $action2 = 'GO';
}
// Show Form
else
{
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
                        <td class="box" colspan="3">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?></td>
                    </tr>
                    <tr>
                        <td class="retfizzles" colspan="3" align='center'>
                            <i>Items with the '<b>*</b>' are mandatory</i><br />
                            <i>At least, one item must have its quantity greater than 0.</i>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem' width='1'>*</td>
                        <td class='formitem'>Contacts</td>
                        <td class='forminput'>
                                <?= b1n_buildSelectCommon($sql, 'contacts[]', 'ctc_id', 'ctc_name', 'view_cat_ctc', $reg_data['contacts'], array('multiple' => ''), 'cat_id = \'' . b1n_inBd($cat_id) . '\'') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem' colspan='3'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class='formitem' colspan='2' align='center' style='font-weight: bold'>Item</td>
                        <td class='formitem' align='center' style='font-weight: bold'>Quantity</td>
                    </tr>
<?
$last_fdt = '';
foreach($fdt_fod as $f)
{
    $input_name = 'qtd_' . $f['fdt_id'] . '_' . $f['fod_id']; 

    if($last_fdt != $f['fdt_id'])
    {
?>
                    <tr>
                        <td class='formitem' colspan='3' style='font-weight: bold'><?= $f['fdt_name'] ?></td>
                    </tr>
<?
    }
?>
                    <tr>
                        <td class='formitem' width='1'>&nbsp;</td>
                        <td class='formitem'><?= $f['fod_name'] ?></td>
                        <td class='forminput'>
                            <input type='text' name='<?= $input_name ?>' value='<?= $reg_data[$input_name] ?>' size='4' maxlength='4' />
                        </td>
                    </tr>
<?
    $last_fdt = $f['fdt_id'];
}
?>
                    <tr>
                        <td class='formitem' colspan='3'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class='formitem' width='1'>&nbsp;</td>
                        <td class='formitem'>Remarks</td>
                        <td class='forminput'>
                            <input type='text' name='remarks' value='<?= $reg_data['remarks'] ?>' size='<?= b1n_DEFAULT_SIZE ?>' maxlength='<?= b1n_DEFAULT_MAXLEN ?>' />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='3' class="forminput" align='center'>
                            <input type="submit" value=" Generate PDF >>" />
                            <input type="button" value=" Back >>" onClick="location='<?= b1n_URL . '?page0=' . $page0 ?>&page1=leg'" />
                        </td>
                    </tr>
                    <tr>
                        <td class="box" colspan="3">&nbsp;</td>
                    </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
</center>
<br />
<br />
<?
    exit();
}
?>
