 <!-- PRINCIPAL -->
<?
    extract_request_var("id", $id,0);
    extract_request_var("name", $name,'');
    extract_request_var("action", $action,'list');
    $colspan=1;
    switch($action) {
        case 'del':
            $tmp=(int)delete_company($sql,$id);
            
            if($tmp == INTEGRITY_VIOLATION) //TODO: warning e cascade
                $message = array("Integrity violation",ERROR);
            elseif($tmp)
                $message = array("Company removed",SUCCESS);
            else
                $message = array("Error removing company",ERROR);
            unset($tmp);
            break;
        case 'edit':
            $colspan=2;
            if(edit_company($sql, $name, $id))
                $message = array("Company <tt>".htmlspecialchars($name)."</tt> saved",SUCCESS);
            else
                $message = array("Error editing company <tt>".htmlspecialchars($name)."</tt>",ERROR);
            break;
        case 'editform':
            $colspan=2;
            break;
        case 'add':
            if( (strlen($name)>0) ) {
                if(add_company($sql, $name))
                    $message = array("Company <tt>".htmlspecialchars($name)."</tt> added",SUCCESS);
                else
                    $message = array("Error adding company <tt>".htmlspecialchars($name)."</tt>",ERROR);
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
            $colspan=4;
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
        <td colspan="4" valign="top" bgcolor="#56744E" class="textbwhite"> &nbsp;&nbsp;Company<img src="images/trans.gif" alt="" width="550" height="1" border="0"></td>
    </tr>
    <tr>
        
        <td width="100%" colspan="4" bgcolor="#000000">
            <table width="100%" cellspacing="0" cellpadding="5" class="caixa">
               <?
               if(isset($message))       include( INCPATH . "/message.php");
               if(isset($search))        include( INCPATH . "/search.php");
               if($action == 'delform')  include( INCPATH . "/company/delform.php"); 
               ?> 
            <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
               <input type="hidden" name="id" value="<?=$id?>">
               <input type="hidden" name="item" value="<?=$item?>">
            <? /* **************************************************************************************************** */
               if($action == 'list') include( INCPATH . "/company/list.php"); 
               if(($action == 'addform') || 
                  ($action == 'editform')) include( INCPATH . "/company/edit.php");
               if(($action == 'view')||
                  ($action == 'delform')) include( INCPATH . "/company/view.php");
            ?>
               <tr>
                   <td  bgcolor="#FFFFFF">
               </tr>
            </table>
                    
        </td>
    </tr>
</table></form>        
<!-- FIM PRINCIPAL-->
