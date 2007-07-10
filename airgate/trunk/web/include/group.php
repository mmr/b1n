<!-- PRINCIPAL -->
<?
    extract_request_var("editing", $editing, '');
    extract_request_var("id", $id,0);
    extract_request_var("devices", $devices, array());
    extract_request_var("dev_id", $dev_id,0);
    extract_request_var("dvg_id", $dvg_id,0);
    extract_request_var("dvtype", $dvtype,0);
    extract_request_var("remove", $remove,'');
    extract_request_var("action", $action,'list');
    extract_request_var("add_done", $add_done,false);
    extract_request_var("name", $name,'');
    extract_request_var("descr", $descr,'');
    extract_request_var("company", $company,0);

    switch($action) {
       case 'del':
            $tmp=(int)delete_group($sql,$id);

            if($tmp == INTEGRITY_VIOLATION)
                $message = array("Integrity violation. This group is in use by ACL system, delete ACLs before deleting group",ERROR);
            elseif($tmp)
                $message = array("Group removed",SUCCESS);
            else
                $message = array("Error removing group",ERROR);
            unset($tmp);
            $colspan = 1;
            break;
        case 'edit':
            $colspan=2;
            if(edit_group($sql, $name, $descr, $company, $devices, $dvtype, $id ))
                $message = array("Group <tt>".htmlspecialchars($name)."</tt> saved",SUCCESS);
            else
                $message = array("Error editing group <tt>".htmlspecialchars($name)."</tt>",ERROR);
            $colspan = 1;
            break;
        case 'add':
            if( (strlen($name)>0) && (strlen($descr)>0) && ($company>0) ) {
                if(add_group($sql, $name, $descr, $company,$devices,$dvtype))
                    $message = array("Group <tt>".htmlspecialchars($name)."</tt> added",SUCCESS);
                else
                    $message = array("Error adding group <tt>".htmlspecialchars($name)."</tt>",ERROR);
            } else {
                $message = array("Please, fill all fields",ERROR);
                $action = 'addform';
            }
            $colspan=2;
            break;
        case 'editform':
        case 'addform':
            $colspan=2;
            break;
        case 'removeform':
        case 'delform':
        case 'view':
            $colspan=1;
            break;
        case 'list':
            $search = 1; 
        default:
            $colspan=6;
    }
?> 
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2" bgcolor="#56744E"><img src="images/borda_a.gif" alt="" width="8" height="10" border="0"></td>
        <td colspan="2" align="right" bgcolor="#56744E"><img src="images/borda_b.gif" alt="" width="27" height="10" border="0"></td>
    </tr>
    <tr>
        <td colspan="4" valign="top" bgcolor="#56744E" class="textbwhite"> &nbsp;&nbsp;Groups<img src="images/trans.gif" alt="" width="550" height="1" border="0"></td>
    </tr>
    <tr>
        
        <td width="100%" colspan="4" bgcolor="#000000">
            <table width="100%" cellspacing="0" cellpadding="5" class="caixa">
            <? 
               if(isset($message))          include( INCPATH . "/message.php");
               if(isset($search))           include( INCPATH . "/search.php");
               if($action == 'delform')     include( INCPATH . "/group/delform.php");
               if($action == 'removeform')  include( INCPATH . "/group/removeform.php");
            ?>
                <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
                    <input type="hidden" name="id" value="<?=$id?>">
                    <input type="hidden" name="item" value="<?=$item?>">
                <? 
                    if($action == 'list')        include( INCPATH . "/group/list.php"); 
                    //TODO: o que isso estava fazendo aqui:            </table> 
                    if(($action == 'addform') ||
                       ($action == 'editform'))  include( INCPATH . "/group/edit.php"); 
                    if(($action == 'view')||
                        ($action == 'delform'))  include( INCPATH . "/group/view.php");  
                ?>
                </form>
                <tr>
                    <td  bgcolor="#FFFFFF">
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- FIM PRINCIPAL-->
