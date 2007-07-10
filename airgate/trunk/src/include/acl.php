 <!-- PRINCIPAL -->
<?
    extract_request_var("id", $id,0);
    extract_request_var("dvg1", $dvg1,0);
    extract_request_var("dvg2", $dvg2,0);
    extract_request_var("action", $action,'list');
    $colspan=1;
    switch($action) {
        case 'del':
            $tmp=(int)delete_acl($sql,$id);
            
            if($tmp == INTEGRITY_VIOLATION) //TODO: warning e cascade
                $message = array("Integrity violation",ERROR);
            elseif($tmp)
                $message = array("ACL removed",SUCCESS);
            else
                $message = array("Error removing ACL",ERROR);
            unset($tmp);
            break;
        case 'add':
            if(($dvg1>0)&&($dvg2>0) ) {
                if(add_acl($sql, $dvg1,$dvg2)) 
                    $message = array("ACL added",SUCCESS);
                else
                    $message = array("Error adding ACL. $err_msg.",ERROR);
            } else {
                $message = array("Please, fill all fields",ERROR);
                $action = 'addform';
            }
            $colspan=2;
            break;
        case 'addform':
            $colspan=2;
            break;
        case 'delform':
        case 'view':
            $colspan=1;
            break;
        case 'list':
        default:
            $colspan=2;
            $search = 1; 
    }
?> 
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2" bgcolor="#56744E"><img src="images/borda_a.gif" alt="" width="8" height="10" border="0"></td>
        <td colspan="2" align="right" bgcolor="#56744E"><img src="images/borda_b.gif" alt="" width="27" height="10" border="0"></td>
    </tr>
    <tr>
        <td colspan="4" valign="top" bgcolor="#56744E" class="textbwhite"> &nbsp;&nbsp;ACL<img src="images/trans.gif" alt="" width="550" height="1" border="0"></td>
    </tr>
    <tr>
        
        <td width="100%" colspan="4" bgcolor="#000000">
            <table width="100%" cellspacing="0" cellpadding="5" class="caixa">
               <?
               if(isset($message))       include( INCPATH . "/message.php");
               if(isset($search))        include( INCPATH . "/search.php");
               if($action == 'delform')  include( INCPATH . "/acl/delform.php"); 
               ?> 
            <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
               <input type="hidden" name="id" value="<?=$id?>">
               <input type="hidden" name="item" value="<?=$item?>">
            <? /* **************************************************************************************************** */
               if($action == 'list') include( INCPATH . "/acl/list.php"); 
               if(($action == 'addform') || 
                  ($action == 'editform')) include( INCPATH . "/acl/addform.php");
               if(($action == 'view')||
                  ($action == 'delform')) include( INCPATH . "/acl/view.php");
            ?>
               <tr>
                   <td  bgcolor="#FFFFFF">
               </tr>
            </table>
                    
        </td>
    </tr>
</table></form>        
<!-- FIM PRINCIPAL-->
