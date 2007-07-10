<?
    /* Pagination */
    define( "QT_PER_PAGE", 20 );

    /* PPTP / QUEUE */
    define( "PPTP_FILE", "x" );
    define( "QUEUE_DONE", "1" );
    define( "QUEUE_CMD_CREATE_SECRET", "pptp_create_secret" );

    /* Error Handling */
    $err_msg = "";

    /* Misc */
    define('INTEGRITY_VIOLATION',-4);
    define('ERROR',1);
    define('SUCCESS',2);
    define('WARNING',3);

    define('M_FULL',4);
    define('M_SIMPLE',5);
    define('M_FULL_NP',6);

    /* acl functions ***********************************/
    function add_acl($sql,$dvg1,$dvg2) {
        global $err_msg;
        $err_msg = "";

        $dvg1 = (int)$dvg1;
        $dvg2 = (int)$dvg2;

        $rs3 = $sql->squery("SELECT COUNT( acl_id ) FROM access_list WHERE ( acl_from = '" . $dvg1 . "' AND acl_to = '" . $dvg2 . "' ) OR ( acl_from = '" . $dvg2 . "' AND acl_to = '" . $dvg1 . "' )");

        if( $rs3[ 'count' ] == 0 )
        {
            $rs1 = $sql->squery("SELECT DISTINCT dvt_id from device_group WHERE dvg_id='" . $dvg1 . "'");
            $rs2 = $sql->squery("SELECT DISTINCT dvt_id from device_group WHERE dvg_id='" . $dvg2 . "'");

            if( is_array( $rs1 ) && is_array( $rs2 ) )
            {
                if( ( ( $rs1['dvt_id'] == 'WLES') && ( $rs2['dvt_id'] == 'FIXO') ) ||
                    ( ( $rs1['dvt_id'] == 'FIXO') && ( $rs2['dvt_id'] == 'WLES') ) )
                {
                    $query = "INSERT into access_list (acl_from,acl_to) values('" . $dvg1 . "','" . $dvg2 . "')";
                    return $sql->query($query);
                }
                else
                    $err_msg = "The device groups cannot have the same type.";
            }
        }
        else
            $err_msg = "There is already an Access List with this two groups involved.";

        return false;
    }

    function delete_acl($sql,$id) {
        $id = (int)$id;
       
        return( $sql->query( "DELETE FROM access_list WHERE acl_id = '$id'" ) );
    }

    function view_acl($sql, $id) {
        $id = (int)$id;
        return $sql->squery("SELECT acl_id,a.dvg_name,b.dvg_name,a.dvg_descr,b.dvg_descr from access_list JOIN device_group a ON (acl_from = a.dvg_id) JOIN device_group  b ON (acl_to = b.dvg_id) WHERE acl_id='$id'");
    }

    function list_acl($sql,$page='1',$simple=M_SIMPLE) {
        switch($simple) {
        case M_SIMPLE:
            $query = "SELECT acl_id,a.dvg_name,b.dvg_name from access_list JOIN device_group a ON (acl_from = a.dvg_id) JOIN device_group  b ON (acl_to = b.dvg_id) ORDER BY a.dvg_name, b.dvg_name";
            break;
        case M_FULL:
            $query = "SELECT acl_id,a.dvg_name,b.dvg_name,a.dvg_descr,b.dvg_descr from access_list JOIN device_group a ON (acl_from = a.dvg_id) JOIN device_group  b ON (acl_to = b.dvg_id)";
            break;
        default:
            user_error("function not implemented yet",E_USER_NOTICE);
            return 0;
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT acl_id ) FROM access_list" );

        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }

    
    /* group functions *********************************/
    function remove_device_from_group($sql,$dev_id,$dvg_id) {
        $dev_id = (int) $dev_id;
        $dvg_id = (int) $dvg_id;
        $query = "DELETE from  device_x_group WHERE dev_id='$dev_id' AND dvg_id='$dvg_id'";
        return $sql->query($query);
    }

    function add_device_to_group($sql,$dev_id,$dvg_id) {
        global $err_msg;
        $err_msg = "";
        
        $dev_id = (int) $dev_id;
        $dvg_id = (int) $dvg_id;

        $query = "SELECT dvt_id,dvt_descr  from device_x_group NATURAL JOIN device NATURAL JOIN device_type WHERE dvg_id='$dvg_id' LIMIT 1";
        $group_info = $sql->query($query);
        if($group_info[0]) {
            $device_info=$sql->query("SELECT dvt_id,dvt_descr from device NATURAL JOIN device_type WHERE dev_id='$dev_id'");
            if($group_info[0][0] != $device_info[0][0]) {
                $err_msg = "Device and group type mismatch (&quot;".$device_info[0][1]."&quot;,&quot;".$group_info[0][1]."&quot;)."; 
                return false;
            }
        }

        $query = "INSERT INTO device_x_group (dev_id,dvg_id) values('$dev_id','$dvg_id')";
        return $sql->query($query);
    }

    function view_group($sql,$id) {
        $id=(int)$id;
        $query = "SELECT dvg_id,dvg_name,cpy_name,dvg_descr,cpy_id,dvt_id from device_group NATURAL JOIN company WHERE dvg_id=$id";

        $result = $sql->squery($query);
        return $result;
    }

    function list_group_by_type($sql,$type) {
        $type = AddSlashes($type);
        $query = "SELECT DISTINCT dvg_id, cpy_name || ' - ' || dvg_name as name FROM device_group NATURAL JOIN company WHERE dvt_id = '$type' ORDER BY name";

        return $sql->query( $query );
    }

    function list_group($sql,$page='1',$simple=M_FULL) {
        switch($simple) {
        case M_FULL:
            $query = "SELECT dvg_id,dvg_name,cpy_name,dvg_descr from device_group NATURAL JOIN company ORDER BY cpy_name, dvg_name";
            break;
        case M_SIMPLE:
            return $sql->query( "SELECT dvg_id,cpy_name || ' - ' || dvg_name as name from device_group NATURAL JOIN company ORDER BY name" );
            break;
        default:
            user_error("function not implemented yet",E_USER_NOTICE);
            return 0;
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT dvg_id ) FROM device_group" );

        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }

    function list_group_by_company($sql,$cpy_id) {
        $cpy_id = (int) $cpy_id;
        $query = "SELECT dvg_id,dvg_name,cpy_name,dvg_descr from device_group NATURAL JOIN company WHERE cpy_id='$cpy_id' ORDER BY cpy_name, dvg_name";
        $result = $sql->query($query);
        return $result;
    }

    function list_group_by_device($sql,$dev_id) {
        $dev_id = (int) $dev_id;
        $query = "SELECT dvg_id,dvg_name,cpy_name,dvg_descr from device_group NATURAL JOIN company WHERE dev_id='$dev_id' ORDER BY dvg_name";
        $result = $sql->query($query);
        return $result;
    }

    function edit_group($sql, $name, $descr, $company, $devices, $dvtype, $id) {
        return add_group($sql,$name,$descr,$company,$devices,$dvtype,$id);
    }

    function delete_group($sql,$id) {
        $id = (int) $id;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if( $sql->query( "DELETE from device_x_group WHERE dvg_id = '$id'" ) )
            {
                if( $sql->query( "DELETE from access_list WHERE acl_from = '$id' or acl_to = '$id'" ) )
                {
                    if( $sql->query( "DELETE from device_group WHERE dvg_id = '$id'" ) )
                    {
                        return $sql->query( "COMMIT TRANSACTION" );
                    } 
                    else
                    {
                        print "Can't delete device_group (dvg_id: '" . $id . "')"; 
                    }
                }
                else
                {
                    print "Can't delete access_list (dvg_id: '" . $id . "')";
                }
            }
            else
            {
                print "Can't delete device_x_group (dvg_id: '" . $id . "')";
            }
        }
        else
        {
            print "Can't begin transaction";
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        return false;
    }

    function add_group($sql,$name,$descr,$company,$devices,$dvtype,$id=0) {
        $id = (int) $id;
        $name = AddSlashes($name);
        $descr = AddSlashes($descr);
        $company = (int) $company;
        $dvtype = AddSlashes( $dvtype ); 

        $erro = true;
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if($id)
            {
                if( $sql->query( "UPDATE device_group set dvg_name = '$name', dvg_descr = '$descr',cpy_id='$company', dvt_id = '$dvtype'  WHERE dvg_id='$id'" ) )
                    $erro = false;
            }
            else
            {
                $rs = $sql->squery( "SELECT NEXTVAL( 'device_group_dvg_id_seq' )" ); 
                if( $rs )
                {
                    $id = $rs[ 'nextval' ];
                    if( $sql->query( "INSERT INTO device_group (dvg_id,dvt_id,dvg_name,dvg_descr,cpy_id) values('$id', '$dvtype', '$name','$descr','$company');" ) )
                        $erro = false;
                }
            }
        }

        if( ! $erro )
        {
            $erro = true;
            if( $sql->query( "DELETE FROM device_x_group WHERE dvg_id = '" . $id . "'" ) )
            {
                $erro = false;

                if( is_array( $devices ) )
                {
                    foreach( $devices as $dev_id )
                    {
                        if( ! $sql->query( "INSERT INTO device_x_group ( dvg_id, dev_id ) VALUES ( '" . $id . "', '" . $dev_id . "' )" ) )
                        {
                            $erro = true;
                            break;
                        }
                    }
                }
            }

            if( ! $erro )
                return $sql->query( "COMMIT TRANSACTION" ); 
        }

        $sql->query( "ROLLBACK TRANSACTION" ); 
        return false;
    }

    /* company functions ********************************/
    function delete_company($sql,$id) {
        $id = (int) $id;
        $query = "SELECT * from device WHERE cpy_id = '$id'";
        $result = $sql->query($query);
        if(is_array($result)) return INTEGRITY_VIOLATION;

        $query = "SELECT * from device_group WHERE cpy_id = '$id'";
        $result = $sql->query($query);
        if(is_array($result)) return INTEGRITY_VIOLATION;

        $query = "DELETE from company WHERE cpy_id = '$id'";
        $result = $sql->query($query);
        return $result;
    }

    function add_company($sql,$name,$id=0) {
        $id = (int) $id;
        $name = AddSlashes($name);
        if($id) $query =  "UPDATE company set cpy_name = '$name' WHERE cpy_id='$id'";
        else $query =  "INSERT INTO company (cpy_name) values('$name');";
        $result = $sql->query($query);
        return $result;
    }

    function edit_company($sql, $name, $id) {
        return add_company($sql,$name,$id);
    }

    function view_company($sql,$id) {
        $id=(int)$id;
        $query = "SELECT cpy_id,cpy_name from company WHERE cpy_id=$id";
        $result = $sql->squery($query);
        return $result;
    }

    function list_company($sql,$page='1',$mode=M_FULL) {
        switch($mode) {
        case M_SIMPLE:
            return $sql->query( "SELECT cpy_id, cpy_name FROM company ORDER BY cpy_name" );
            break;
        case M_FULL:
            $query = "SELECT cpy_id,cpy_name FROM company ORDER BY cpy_name";
            break;
        default:
            user_error("function not implemented yet (MODE = $mode)",E_USER_NOTICE);
            return array();
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT cpy_id ) FROM company" );
        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }


    /* device functions ********************************/

    function delete_device($sql,$id)
    {
        $id = (int) $id;


        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if( $sql->query( "DELETE FROM device_x_group WHERE dev_id = '$id'" ) )
            {
                if( $sql->query( "DELETE FROM device WHERE dev_id = '$id'" ) )
                {
                    if( $sql->query( "DELETE FROM man WHERE dev_id ='$id'" ) )
                    {
                        if( $sql->query( "DELETE FROM pptp WHERE dev_id = '$id'" ) )
                        {
                            if( $sql->query( "INSERT INTO queue ( que_command ) VALUES ( '" . QUEUE_CMD_CREATE_SECRET . "' )" ) )
                            {
                                return $sql->query( "COMMIT TRANSACTION" );
                            }
                            else
                            {
                                print "Can't insert into queue.";
                            }
                        }
                        else
                        {
                            print "Can't delete from pptp.";
                        }
                    }
                    else
                    {
                        print "Can't delete from man.";
                    }
                }
                else
                {
                    print "Can't delete from device."; 
                }
            }
            else
            {
                print "Can't delete from device_x_group.";
            }
        }
        else
        {
            print "Can't Begin Transaction";
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        return false;
    }

    function list_device_by_company($sql,$page,$simple=M_FULL,$id) {
        switch($simple) {
        case M_FULL:
            $query = "SELECT dev_id,cpy_name,dev_name,dev_address,dev_nat,dev_if,dvt_descr from device NATURAL JOIN company NATURAL JOIN device_type ORDER BY cpy_name, dev_name";
            break;
        case M_SIMPLE:
            $query = "SELECT dev_id, cpy_name || ' - ' || dev_name as name from device NATURAL JOIN company NATURAL JOIN device_x_group ORDER BY name";
            return $sql->query( $query );
            break;
        case M_FULL_NP:
            $query = "SELECT dev_id,cpy_name,dev_name,dev_address,dev_nat,dev_if,dvt_descr from device NATURAL JOIN company NATURAL JOIN device_type WHERE cpy_id = '" . $id . "' ORDER BY cpy_name, dev_name";
            return $sql->query( $query );
            break;
        default:
            user_error("function not implemented yet",E_USER_NOTICE);
            return 0;
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT dev_id ) FROM device" );
        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }


    function list_device($sql,$page='1',$simple=M_FULL,$id='') {
        switch($simple) {
        case M_FULL:
            $query = "SELECT dev_id,cpy_name,dev_name,dev_address,dev_nat,dev_if,dvt_descr from device NATURAL JOIN company NATURAL JOIN device_type ORDER BY cpy_name, dev_name";
            break;
        case M_SIMPLE:
            return $sql->query( "SELECT dev_id,cpy_name || ' - ' || dev_name as name from device NATURAL JOIN company ORDER BY name" );
            break;
        default:
            user_error("function not implemented yet",E_USER_NOTICE);
            return 0;
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT dev_id ) FROM device" );
        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }

    function view_device($sql,$dev_id) {
        $dev_id=(int)$dev_id;
        $query = "SELECT dev_id,cpy_name,dev_name,dev_address,dev_nat,dev_if,dvt_descr,cpy_id,dev_if,dvt_id from device NATURAL JOIN company NATURAL JOIN device_type WHERE dev_id='$dev_id'";
        $result = $sql->squery($query);
        return $result;
    }

    function edit_device($sql,$name,$company,$address,$nat,$iface,$dvtype,$id,$ppt_login,$ppt_passwd,$ppt_passwd_old) {
        return add_device($sql,$name,$company,$address,$nat,$iface,$dvtype,$id,$ppt_login,$ppt_passwd,$ppt_passwd_old);
    }

    function add_device($sql,$name,$company,$address,$nat,$iface,$dvtype,$id=0,$man_code,$ppt_login='',$ppt_passwd='',$ppt_passwd_old='') {
        $id = (int) $id;
        $name = AddSlashes($name);
        $iface = AddSlashes($iface);
        $address = AddSlashes($address); //TODO: validar IP
        $nat = AddSlashes($nat); //TODO: validar IP
        $company = (int)$company;
        $dvtype = AddSlashes($dvtype);
        $man_code = AddSlashes($man_code);
        $ppt_login = AddSlashes($ppt_login);
        $ppt_passwd = AddSlashes($ppt_passwd); 
        $ppt_passwd_old = AddSlashes($ppt_passwd_old); 

        if($nat)
            $nat = "'$nat'";
        else
            $nat = 'NULL';


        $erro = true;
        $rs = $sql->query( "BEGIN TRANSACTION" );

        if( $rs )
        {
            if($id != '0')
            {
                $insert = false;

                if( $sql->query( "DELETE FROM man WHERE dev_id = '" . $id . "'" ) )
                {
                    if( $sql->query( "DELETE FROM pptp WHERE dev_id = '" . $id . "'" ) )
                    {
                        if( $sql->query( "INSERT INTO queue ( que_command ) VALUES ( '" . QUEUE_CMD_CREATE_SECRET . "' )" ) ) 
                        {
                            if( $sql->query( "UPDATE device set dvt_id='$dvtype', dev_if='$iface',dev_nat=$nat,dev_name='$name',dev_address='$address', cpy_id='$company' WHERE dev_id='$id'" ) )
                            {
                                $erro = false;
                            }
                        }
                    }
                }
            }
            else
            {
                $rs = $sql->squery( "SELECT NEXTVAL( 'device_dev_id_seq' )" );
                if( $rs )
                {
                    $insert = true;

                    $id = $rs[ 'nextval' ];

                    if( $sql->query( "INSERT INTO device (dev_id, dvt_id,dev_if,dev_nat,dev_name,dev_address,cpy_id) values('$id','$dvtype','$iface',$nat,'$name','$address','$company')" ) )
                    {
                        $erro = false;
                    }
                }
            }
        }

        if( ! $erro )
        {
            $erro = true;
            if( $dvtype == 'PPTP' )
            {
                if( $ppt_login != '' )
                {
                    if( $insert == false )
                    {
                        if( $ppt_passwd == '' )
                        {
                            $ppt_passwd = $ppt_passwd_old;
                        }
                    }

                    if( $sql->query( "INSERT INTO pptp ( dev_id, ppt_login, ppt_passwd ) VALUES ( '$id', '$ppt_login', '$ppt_passwd' )" ) ) 
                    {
                        if( $sql->query( "INSERT INTO queue ( que_command ) VALUES ( '" . QUEUE_CMD_CREATE_SECRET . "' )" ) ) 
                        {
                            $erro = false; 
                        }
                    } 
                }
            }
            elseif( $dvtype == 'MAN' )
            {
                if( is_numeric( $man_code ) )
                {
                    if( $sql->query( "INSERT INTO man ( dev_id, man_code ) VALUES ( '$id', '$man_code' )" ) ) 
                    {
                        $erro = false; 
                    } 
                }
            }
            else
            {
                $erro = false;
            }

            if( ! $erro )
            {
                return $sql->query( "COMMIT TRANSACTION" );
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        return false;
    }
    /* other list functions *******************************************/
    function list_iface($sql,$page='1',$mode=M_FULL) {
        switch($mode) {
        case M_SIMPLE:
            return $sql->query( "SELECT if_id,if_desc FROM interface ORDER BY if_desc" );
        case M_FULL:
            $query = "SELECT if_id,if_desc FROM interface ORDER BY if_desc";
            break;
        default:
            user_error("function not implemented yet (MODE = $mode)",E_USER_NOTICE);
            return array();
        }

        $rs = $sql->squery( "SELECT COUNT( DISTINCT if_id ) FROM interface" );

        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }
    function list_dvtype($sql,$page='1',$mode=M_FULL) {
        switch($mode) {
        case M_SIMPLE:
            return $sql->query( "SELECT dvt_id,dvt_descr FROM device_type ORDER BY dvt_descr" );
            break;
        case M_FULL:
            $query = "SELECT dvt_id,dvt_descr FROM device_type ORDER BY dvt_descr";
            break;
        default:
            user_error("function not implemented yet (MODE = $mode)",E_USER_NOTICE);
            return array();
        }

        $query = "
        SELECT
            COUNT( DISTINCT dvt_id )
        FROM
            device_type";

        $rs = $sql->squery( $query );

        $qt_pages = ( $rs ? ceil( $rs[ 'count' ] / QT_PER_PAGE ) : 1 );

        if( $page > $qt_pages ) 
            $page = $qt_pages;
        elseif( $page < 1 )
            $page = 1;

        $query .= " LIMIT " . QT_PER_PAGE . " OFFSET " . ( abs( ( $page - 1 ) * QT_PER_PAGE ) );

        return array( $qt_pages, $sql->query($query) );
    }
    /* html functions *******************************************/
    function build_line($array,$view='0',$edit='0',$del='0',$remove='0',$item='') {
        $remove=(int)$remove;
        $ret='<tr>';
        if(is_array($array) && ($array[0])) {
            if((($view!=0) || ($edit!=0) || ($del!=0)) && ($item == '')) {
                    return "<td><b>build_line() error, item=''</b></td>";
            }
            if($view!=0) $ret.='<td bgcolor="#FFFFFF" class="text" width="1%"><a href="'.$_SERVER["SCRIPT_NAME"].'?item='.$item.'&action=view&id='.$array[0].'"><img src="images/view.png" alt="view" border="0"></a></td>';
            if($edit!=0) $ret.='<td bgcolor="#FFFFFF" class="text" width="1%"><a href="'.$_SERVER["SCRIPT_NAME"].'?item='.$item.'&action=editform&id='.$array[0].'"><img src="images/edit.png" alt="edit" border="0"></a></td>';
            if($del!=0) $ret.='<td bgcolor="#FFFFFF" class="text" width="1%"><a href="'.$_SERVER["SCRIPT_NAME"].'?item='.$item.'&action=delform&id='.$array[0].'"><img src="images/trash.png" alt="delete" border="0"></a></td>';
            if($remove!=0) $ret.='<td bgcolor="#FFFFFF" class="text" width="1%"><a href="'.$_SERVER["SCRIPT_NAME"].'?item='.$item.'&action=removeform&remove='.$remove.'&id='.$array[0].'"><img src="images/remove.png" alt="remove" border="0"></a></td>';
            for($i=1;$i<sizeof($array);$i++) 
                    $ret .= '<td class="text" bgcolor="#FFFFFF">'.$array[$i].'</td>';
            return($ret.'</tr>');
        }
    }

    function build_select_device($sql,$dvtype, $id, $disabled='') {
        $id = (int)$id;

        $dev    = $sql->query( "SELECT dev_id, dev_name FROM device WHERE dvt_id = '" . $dvtype . "'" ); 
        $dvg    = $sql->query( "SELECT dev_id FROM device_x_group WHERE dvg_id = '" . $id . "'" ); 

        $devices = array( );

        if( is_array( $dvg ) )
            foreach( $dvg as $d )
                array_push( $devices, $d[ 'dev_id' ] ); 

        print "<select name='devices[]' multiple " . $disabled . ">";
        foreach( $dev as $d )
        {
            print "<option value='" . $d[ 'dev_id' ] . "'";
            if( in_array( $d[ 'dev_id' ], $devices ) )
                print " selected";
            print ">" . $d[ 'dev_name' ] . "</option>";
        }
        print "</select>";
    }

    function html_option($array,$default='') {
        $ret='<option value="">---</option>';

        for($i=0;$i<sizeof($array);$i++) {
            $ret.= '<option value="'.htmlspecialchars($array[$i][0]).'"';

            if( gettype( $array[ $i ][ 0 ] ) == gettype( $default ) &&  $array[ $i ][ 0 ] == $default )
                    $ret.= " SELECTED ";

            $ret.= ">".htmlspecialchars($array[$i][1])."</option>\n";
        }
        return $ret;
    }

    function message($text,$type='0') {
        print "<table><tr><td><img src=\"images/";
        switch($type) {
            case SUCCESS:
                print "ok.png";
                break;
            case ERROR:
                print "error.png";
                break;
            case WARNING:
                print "warning.png";
                break;
            default:
                print ""; //TODO: fix this
                break;
        }
        print "\"></td><td valign=center class=text>".($text)."</td></tr></table>";
    }

/* Misc */
    function build_pagination( $item, $page=1, $qt_pages=1, $colspan=999 )
    {
        $ret = "";

        if( $item != "" )
        {
            if( $qt_pages > 1 )
            {
                if( $page > $qt_pages )
                    $page = $qt_pages;
                elseif( $page < 1 ) 
                    $page = 1; 

                $ret .= "<tr><td colspan='" . $colspan . "' bgcolor='#ffffff'>";

                if( $page > 1 )
                    $ret .= "<a href='" . $_SERVER["SCRIPT_NAME"] . "?item=" . $item . "&page=" . ( $page - 1 ) . "'>&lt;&lt;</a>";
                
                for($i = 1; $i <= $qt_pages; $i++)
                { 
                    if ($i == $page) 
                        $ret .= "<font face='verdana, arial, helvetica, sens-serif' size='2' color='#cc3300'> <b>" . $i . "</b> </font>";
                    else
                        $ret .= " <a href='" . $_SERVER["SCRIPT_NAME"] . "?item=" . $item . "&page=" . $i . "'>" . $i . "</a> ";
                }

                if( $qt_pages > $page )
                    $ret .= "<a href='" . $_SERVER["SCRIPT_NAME"] . "?item=" . $item . "&page=" . ( $page + 1 ) . "'>&gt;&gt;</a>";

                $ret .= "</td></tr>";
            }
        }
        else
            $ret = "<b>build_pagination() error, item=''</b>";

        return $ret;
    }
?>

