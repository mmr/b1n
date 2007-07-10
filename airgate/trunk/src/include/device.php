 <!-- PRINCIPAL -->
<?
    extract_request_var("editing", $editing, '');
    extract_request_var("id", $id,0);
    extract_request_var("action", $action,'list');
    extract_request_var("add_done", $add_done,false);
    extract_request_var("dvg_id", $dvg_id,0);
    extract_request_var("name", $name,'');
    extract_request_var("address", $address,'');
    extract_request_var("nat", $nat,'');
    extract_request_var("dvtype", $dvtype,'');
    extract_request_var("iface", $iface,'');
    extract_request_var("company", $company,0);
    extract_request_var("man_code", $man_code,'');
    extract_request_var("ppt_login", $ppt_login,'');
    extract_request_var("ppt_passwd", $ppt_passwd,'');
    extract_request_var("ppt_passwd2", $ppt_passwd2,'');
    extract_request_var("ppt_passwd_old", $ppt_passwd_old,'');
    $colspan=1;
    switch($action) {
        case 'del':
            $tmp=(int)delete_device($sql,$id);
            
            if($tmp == INTEGRITY_VIOLATION)
                $message = array("Integrity violation.",ERROR);
            elseif($tmp)
                $message = array("Device removed",SUCCESS);
            else
                $message = array("Error removing device",ERROR);
            unset($tmp);
            break;
        case 'edit':
            $colspan=2;
            if(edit_device($sql,$name,$company,$address,$nat,$iface,$dvtype,$id,$man_code,$ppt_login,$ppt_passwd,$ppt_passwd_old)) 
                $message = array("Device <tt>".htmlspecialchars($name)."</tt> saved",SUCCESS);
            else
                $message = array("Error editing device <tt>".htmlspecialchars($name)."</tt>",ERROR);
            break;
        case 'editform':
            $colspan=2;
            break;
        case 'add':
            if( (strlen($name)>0) && (strlen($address)>0) && ($company>0) ) { //TODO: checar melhor

                $erro = true;
                if( $dvtype == 'PPTP' )
                {
                    if( strlen( $ppt_login )>0 && strlen( $ppt_passwd )>0 ) 
                    {
                        if( $ppt_passwd == $ppt_passwd2 )
                            $erro = false;
                        else
                            $message = array("Password and Password Confirmation must match",ERROR);
                    }
                    else
                        $message = array("Please, fill all fields",ERROR);
                }
                elseif( $dvtype == 'MAN' )
                {
                    if( ! is_numeric( $man_code ) )
                        $message = array("MAN Code must be a number",ERROR);
                    else
                        $erro = false;
                }
                else
                    $erro = false;

                if( ! $erro )
                {
                    if(add_device($sql,$name,$company,$address,$nat,$iface,$dvtype,0,$man_code,$ppt_login,$ppt_passwd)) 
                        $message = array("Device <tt>".htmlspecialchars($name)."</tt> added",SUCCESS);
                    else
                        $message = array("Error adding device <tt>".htmlspecialchars($name)."</tt>",ERROR);
                }
                else
                    $action = 'addform';
            } else {
                $message = array("Please, fill all fields",ERROR);
                $action = 'addform';
            }
            $colspan=2;
            break;
        case 'addform':
            $colspan=2;
            break;
            /*
        case 'addtogroup';
            if(add_device_to_group($sql,$id,$dvg_id))
                $message = array("Device added to new group",SUCCESS);
            else
                $message = array("Error adding device to new group. $err_msg",ERROR);
            $action="view";
            */
        case 'delform':
        case 'view':
            $colspan=1;
            break;
        case 'list':
        default:
            $action="list";
            $search = 1; 
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
        <td colspan="4" valign="top" bgcolor="#56744E" class="textbwhite"> &nbsp;&nbsp;Device<img src="images/trans.gif" alt="" width="550" height="1" border="0"></td>
    </tr>
    <tr>
        
        <td width="100%" colspan="4" bgcolor="#000000">
            <table width="100%" cellspacing="0" cellpadding="5" class="caixa">
            <?
               if(isset($message))       include( INCPATH . "/message.php");
               if(isset($search))        include( INCPATH . "/search.php");
               if($action == 'delform')  include( INCPATH . "/device/delform.php"); 
            ?>
            <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="item" value="<?=$item?>">
            <? 
                if($action == 'list')       include( INCPATH . "/device/list.php");
                if(($action == 'addform') ||
                   ($action == 'editform'))  include( INCPATH . "/device/edit.php"); 
                   /*
                if(($action == 'view')||
                   ($action == 'delform')||
                   ($action == 'addtogroupform')) include( INCPATH . "/device/view.php");  
                    */
                if(($action == 'view')||
                   ($action == 'delform')) include( INCPATH . "/device/view.php");  
            ?>
                <tr>
                    <td  bgcolor="#FFFFFF">
                </tr>
            </table>
                    
        </td>
    </tr>
</table></form>        
<!-- FIM PRINCIPAL-->
