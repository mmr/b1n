<?
/*
$Id: list.inc.php,v 1.37 2002/12/21 18:16:32 binary Exp $ 

Search Config Structure

Structure used for searching, based on $reg_config.
So, if $reg_config is misconfigured, it will be too :P

$search_config = array('possible_fields'    => array,
                       'possible_quantites' => array,
                       'select_fields'      => array,
                       'session_hash_name'  => string,
                       'table'              => string,
                       'id_field'           => string);

The array format is: "title" => "name_of_the_column_on_the_database"        

possible_fields => Field used for filtering the search (WHERE field ILIKE '%...%').
possible_quantities => Act with the pagination system, the default is defined on b1n_LIBPATH/search.lib.php:b1n_DEFAULT_QUANTITY
select_fields   => Fields to show after the search.
session_hash_name => Name of the key in the $_SESSION['search'] hash to store the last search made.
    The default value is $page1
table           => Table to search
id_field        => ID Field from the table, got from $reg_config["ID"]["db"]
    The default value is $page1

Well... you can override the default values in the specific list.php file in the module directory.
*/

// If $search_config is not set, do it!
if(!isset($search_config))
{
    $search_config = array();
}

// Merging initialized arrays.
$search_config += array("possible_fields"    => array(),
                        "select_fields"      => array(),
                        "id_field"           => $reg_config["ID"]["db"]);

// Default Values
    // Possible Quantities (10, 20, 30, 50, 100)
if(!isset($search_config['possible_quantities']))
{
    $search_config['possible_quantities'] = array('10'  => '10', '20'  => '20', '30'  => '30', '50'  => '50', '100' => '100');
}

    // session_hash_name ($page1)
if(!isset($search_config['session_hash_name']))
{
    $search_config['session_hash_name'] = $page1;
}

    // table ($page1)
if(!isset($search_config['table']))
{
    $search_config['table'] = $page1;
}

// Getting Values from reg_config and putting them in $search_config.
foreach($reg_config as $t => $r)
{
    if($r['type'] == 'select' && $r['extra']['seltype'] == 'fk')
    {
        $v = array($t => $r['extra']['text']);
    }
    else
    {
        $v = array($t => $r['db']);
    }


    if($r['search'])
    {
        $search_config['possible_fields'] += $v;
    }

    if($r['select'])
    {
        $search_config['select_fields'] += $v;
    }
}

b1n_getVar('search_text',       $search['search_text']);
b1n_getVar('search_field',      $search['search_field']);
b1n_getVar('search_order',      $search['search_order']);
b1n_getVar('search_order_type', $search['search_order_type']);
b1n_getVar('search_quantity',   $search['search_quantity']);
b1n_getVar('pg_actual',         $search['pg_actual']);

$search = b1n_search($sql, $search_config, $search);
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
<?
        foreach($search_config["select_fields"] as $field_name => $field_column)
        {
?>
                        <td class="searchtitle"><a href='#' onClick="b1n_orderBy('<?= $field_column ?>');"><?= $field_name ?></a></td>
<?
        }
?>
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
<?
            foreach($search_config["select_fields"] as $t => $f)
            {
            ?>
                <td class="searchitem">&nbsp;
            <?
                $r = $reg_config[$t];
                $v = $item[$f];
                if(!empty($v))
                {
                    switch($r['type'])
                    {
                    case "text":
                    case "textarea":
                        if($r['check'] == 'email')
                        {
                            echo "&nbsp;<a href='mailto:" . $v . "'>" . b1n_inHtmlLimit($v) . "</a>";
                        }
                        else
                        {
                            echo b1n_inHtmlLimit($v);
                        }
                        break;
                    case "select":
                        switch($r['extra']['seltype'])
                        {
                        case "date":
                            echo b1n_formatDateShow(b1n_formatDateFromDb($v));
                            break;
                        case "date_hour":
                            echo b1n_formatDateHourShow(b1n_formatDateHourFromDb($v));
                            break;
                        case "date_check_exp":
                            echo b1n_formatDateCheckExpShow(b1n_formatDateFromDb($v));
                            break;
                        case "date_check_dob":
                            echo b1n_formatDateCheckDobShow(b1n_formatDateFromDb($v));
                            break;
                        case "defined":
                            foreach($r['extra']['options'] as $opt_title => $opt_value)
                            {
                                if($v == $opt_value)
                                {
                                    $v = $opt_title;
                                    break;
                                }
                            }
                            echo b1n_inHtmlLimit($v);
                            break;
                        default:
                            echo b1n_inHtmlLimit($v);
                            break;
                        }
                        break;
                    case "radio":
                        foreach($r['extra']['options'] as $opt_title => $opt_value)
                        {
                            if($v == $opt_value)
                            {
                                $v = $opt_title;
                                break;
                            }
                        }
                        echo b1n_inHtmlLimit($v);
                        break;
                    default:
                        echo b1n_inHtmlLimit($v);
                    }
                }
            ?>
            </td>
<?
            }
?>
                        <td class='searchitem'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=view&id=" . $item["id"] ?>">View</a></td>
                        <td class='searchitem'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=change&id=" . $item["id"] ?>">Change</a></td>
                    </tr>
<?
            $i++;
        }

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
