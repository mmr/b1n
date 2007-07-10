<?
/* $Id: list.php,v 1.3 2002/12/21 18:16:32 binary Exp $ */

$search_config = 
    array('possible_fields'     => array('Name' => 'slk_name',
                                         'URL'  => 'slk_url',
                                         'Desc' => 'slk_desc'),
          'select_fields'       => array('Name' => 'slk_name',
                                         'URL'  => 'slk_url',
                                         'Desc' => 'slk_desc'),
          'possible_quantities' => array('10'   => '10',
                                         '20'   => '20',
                                         '30'   => '30',
                                         '50'   => '50',
                                         '100'  => '100'),
          'session_hash_name'   => 'syslink',
          'table'               => 'syslink',
          'id_field'            => 'slk_id');

b1n_getVar('search_text',       $search['search_text']);
b1n_getVar('search_field',      $search['search_field']);
b1n_getVar('search_order',      $search['search_order']);
b1n_getVar('search_order_type', $search['search_order_type']);
b1n_getVar('search_quantity',   $search['search_quantity']);
b1n_getVar('pg_actual',         $search['pg_actual']);

$where_plus = " AND usr_id = '" . b1n_inBd($_SESSION['user']['usr_id']) . "' ";
$search = b1n_search($sql, $search_config, $search, true, $where_plus);
?>
<center>
    <br />
    <br />
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <tr>
                        <td class="box" colspan="2"><?= $page1_title ?> - Search</td>
                    </tr>
                    <tr>
                        <td class='formitem'>Search Field
                            <form method="post"  name="form_search" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="" />
                            <input type="hidden" name="pg_actual" value="1" />
                        </td>
                        <td class='forminput'>
                            <?= b1n_buildSelect($search["possible_fields"], array($search["search"]["search_field"]), array("name" => "search_field")); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Order By</td>
                        <td class='forminput'>
                            <?= b1n_buildSelect($search["select_fields"], array($search["search"]["search_order"]), array("name" => "search_order")); ?>
                            <input type='radio' name='search_order_type' value='ASC' class='noborder' <?= (($search["search"]["search_order_type"] != 'DESC') ?  "checked" : "") ?> /> Asc
                            <input type='radio' name='search_order_type' value='DESC' class='noborder' <?= (($search["search"]["search_order_type"] == 'DESC') ? "checked" : "") ?> /> Desc
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Quantity</td>
                        <td class='forminput'>
                            <?= b1n_buildSelect($search["possible_quantities"], array($search["search"]["search_quantity"]), array("name" => "search_quantity")); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Search</td>
                        <td class='forminput'>
                            <input type='text' name='search_text' value="<?= b1n_inHtml($search["search"]["search_text"])?>" size="<?= b1n_DEFAULT_SIZE ?>" maxlength="<?= b1n_DEFAULT_MAXLEN ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class='forminput' colspan='2' align='center'>
                            <input type="submit" value=' Search >>' />
                            <input type="button" value=' Show All >>' onClick='this.form.search_text.value = ""; this.form.submit();' />
                        </td>
                    </tr>
                    </form>
                    <tr><td class="box" colspan="2">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <br />

<?
/* Colspan = select_fields(?) + checkbox(1) + number(1) + functions(2) */
$colspan = sizeof($search_config["select_fields"]) + 4;

if(isset($search["result"]))
{
?>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
<?
    if(is_array($search["result"]))
    {
?>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>"><?= $page1_title ?></td>
                    </tr>
                    <tr>
                        <td class='searchtitle' width='1'>
                            <form method="post" name="form_delete" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="delete" />
                            <input type="hidden" name="action1" value="" />
                        </td>
                        <td class='searchtitle' width='1'>&nbsp;</td>
                        <script language="JavaScript">
                        function b1n_orderBy(col)
                        {
                            var f=document.form_search;
                            f.search_order.value = col;

                            if(col == '<?= $search['search']['search_order'] ?>')
                            {
                                for(i=0; i<f.search_order_type.length; i++)
                                {
                                    if(f.search_order_type[i].checked)
                                    {
                                        break;
                                    }
                                }

                                if(f.search_order_type[i].value == 'ASC')
                                {
                                    f.search_order_type[i].checked = false; 
                                    f.search_order_type[i+1].checked = true;
                                }
                                else
                                {
                                    f.search_order_type[i].checked = false; 
                                    f.search_order_type[i-1].checked = true;
                                }
                            }

                            f.submit();
                        }
                        </script>

                        <td class="searchtitle"><a href='#' onClick="b1n_orderBy('slk_name');">Name</a></td>
                        <td class="searchtitle"><a href='#' onClick="b1n_orderBy('slk_url');">Url</a></td>
                        <td class="searchtitle"><a href='#' onClick="b1n_orderBy('slk_desc');">Desc</a></td>
                        <td class="searchtitle" colspan="2">Functions</td>
                    </tr>
<?
        $i   = ($search['pg_actual'] * $search['search']['search_quantity']) - $search['search']['search_quantity'] + 1;

        foreach($search["result"] as $item)
        {
?>
                    <tr>
                        <td class='searchtitle' width='1'><input type="checkbox" name="ids[]" value="<?= $item["id"] ?>" class="noborder" onClick="if(!this.checked){ this.form.checkall.checked = false; }" /></td>
                        <td class='searchtitle' width='1'><?= $i ?></td>
                        <td class="searchitem">&nbsp;<a href='<?= $item['slk_url'] ?>' target='_blank'><?= ucwords($item['slk_name']) ?></a></td>
                        <td class="searchitem">&nbsp;<?= b1n_inHtmlLimit($item['slk_url']) ?></td>
                        <td class="searchitem">&nbsp;<?= b1n_inHtmlLimit($item['slk_desc']) ?></td>
                        <td class='searchitem'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=view&id=" . $item["id"] ?>">View</a></td>
                        <td class='searchitem'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=change&id=" . $item["id"] ?>">Change</a></td>
                    </tr>
<?
            $i++;
        }
?>
<?

        if(sizeof($search["result"]) > 1)
        {
?>
                    <tr>
                        <td colspan='<?= $colspan ?>' class='formitem'>
                            <script language='JavaScript'>
                            function b1n_checkAll(f)
                            {
                                var i;
                            
                                for(i=0; i<f.elements["ids[]"].length; i++)
                                {
                                    f.elements["ids[]"][i].checked = f.elements["checkall"].checked;
                                }
                            }
                            </script>
                            <input type='checkbox' name='checkall' class='noborder' onClick='b1n_checkAll(this.form)' />
                            <a href='#' onClick='var x = document.form_delete.checkall; x.checked = !x.checked; b1n_checkAll(x.form);'>Check All</a>
                        </td>
                    </tr>
<?
        }
?>
                    </form>
                    <tr>
                        <td class='forminput' colspan='<?= $colspan ?>' align='center'>
                            <form method="post" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="add" />

                            <input type="submit" name="ok" value=" Add New >>" />
                            <input type="button" value=" Delete >> " onClick='if(confirm("Do you really want to delete this Registry(ies)?")){ document.form_delete.submit(); }' />
                        </td>
                    </tr>
                    </form>
<?
/* Pagination System */
        if($search['pg_pages'] > 1)
        {
?>
                    <tr>
                        <td colspan="<?= $colspan ?>" class='searchtitle'>
<?
            /* Show left arrow if necessary */
            if($search['pg_actual'] > 1)
            {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . ($search["pg_actual"] - 1) ?>"> &lt;&lt; </a>
<?
            }
    
            /* Show numbered pages */
            for($i = 1; $i <= $search["pg_pages"]; $i++)
            { 
                if($i == $search["pg_actual"]) 
                {
                    echo " " . $i . " ";
                }
                else
                {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . $i ?>"> <?= $i ?> </a>
<?
                } 
            }

            /* Show left arrow if necessary */
            if($search['pg_pages'] > $search['pg_actual'])
            {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . ($search["pg_actual"] + 1) ?>"> &gt;&gt; </a>
<?
            }
?>
                        </td>
                    </tr>
<?
        }
?>

                    <tr>
                        <td class='box' colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
<?
    }
    else
    {
?>

                    <tr>
                        <td class="box"><?= $page1_title ?></td>
                    </tr>
<?
        if(isset($_SESSION['search'][$search_config['session_hash_name']]))
        {
?>
                    <tr>
                        <td class='searchitem' align="center">No registries</td>
                    </tr>
<?
        }
?>
                    <tr>
                        <td class='forminput' colspan='<?= $colspan ?>' align='center'>
                            <form method="post" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="add" />

                            <input type="submit" name="ok" value=" Add New >>" />
                        </td>
                    </tr>
                    </form>
                    <tr>
                        <td class='box'>&nbsp;</td>
                    </tr>
<?
    }
?>
                </table>
            </td>
        </tr>
    </table>
<?
}
?>
    <br />
    <br />
</center>
