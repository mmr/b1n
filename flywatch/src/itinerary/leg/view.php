<?
/* $Id: view.php,v 1.4 2003/03/15 12:30:51 binary Exp $ */
$colspan = 2;
?>
<center>
    <br />
    <br />
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <input type="hidden" name="action0" value="<?= $action1 ?>" />
                    <input type="hidden" name="action1" value="<?= $action1 ?>" />
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Aircraft</td>
                        <td class='forminput'>
                            <?= b1n_viewSelected($sql, 'acf_id', 'acf_model', 'aircraft', $reg_data['acf_id']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Depart Airport</td>
                        <td class='forminput'>
                            <?= b1n_viewSelected($sql, 'apt_id', 'apt_name', 'airport', $reg_data['apt_id_depart']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Arrive Airport</td>
                        <td class='forminput'>
                            <?= b1n_viewSelected($sql, 'apt_id', 'apt_name', 'airport', $reg_data['apt_id_arrive']) ?>
                        </td>
                    </tr>

<!--
                    <tr>
                        <td class='formitem'>ETD</td>
                        <td class='forminput'>&nbsp;<?= ?></td>
                    </tr>
-->
                    <tr>
                        <td class='formitem'>ETE</td>
                        <td class='forminput'><?= b1n_formatHour($reg_data['leg_ete_i']) ?></td>
                    </tr>

<!--
                    <tr>
                        <td class='formitem'>Ground Time</td>
                        <td class='forminput'>&nbsp;<?= ?></td>
                    </tr>
-->
                    <tr>
                        <td class='formitem'>Distance</td>
                        <td class='forminput'>&nbsp;<?= $reg_data['leg_distance'] ?></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Wind</td>
                        <td class='forminput'>&nbsp;<?= $reg_data['leg_wind'] ?></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Fuel</td>
                        <td class='forminput'>&nbsp;<?= $reg_data['leg_fuel'] ?></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Pax List</td>
                        <td class='forminput'>
                            <?= b1n_buildSelectCommon($sql, 'paxs[]', 'pax_id', 'pax_cts_name', 'view_pax', $reg_data['paxs'], array("multiple" => ""), "pax_ppt_exp_dt > CURRENT_TIMESTAMP") ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>PIC</td>
                        <td class='forminput'>
                            <?= b1n_viewSelected($sql, 'cmb_id', 'cmb_occ_name', 'view_cmb', $reg_data['cmb_id_pic']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>SIC</td>
                        <td class='forminput'>
                            <?= b1n_viewSelected($sql, 'cmb_id', 'cmb_occ_name', 'view_cmb', $reg_data['cmb_id_sic']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Extra 1</td>
                        <td class='forminput'>
                            <?
                            if(!empty($reg_data['cmb_id_extra1']) && b1n_checkNumeric($reg_data['cmb_id_extra1']))
                            {
                                b1n_viewSelected($sql, 'cmb_id', 'cmb_occ_name', 'view_cmb', $reg_data['cmb_id_extra1']);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Extra 2</td>
                        <td class='forminput'>
                            <?
                            if(!empty($reg_data['cmb_id_extra2']) && b1n_checkNumeric($reg_data['cmb_id_extra2']))
                            {
                                b1n_viewSelected($sql, 'cmb_id', 'cmb_occ_name', 'view_cmb', $reg_data['cmb_id_extra2']);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Remarks</td>
                        <td class='forminput'><?= b1n_inHtml($reg_data['leg_remarks']) ?></td>
                    </tr>
                    <tr>
                        <td colspan='<?= $colspan ?>' class="forminput" align='center'>
                            <form method="post" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                            <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="" />
                            <input type="submit" value=" << Back" />
                        </td>
                    </tr>
                    </form>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <br />
</center>
