<?
// $Id: handler.php,v 1.7 2003/02/15 18:04:03 binary Exp $
$page1_title = 'Handling';

// Getting handler ID of the airport of the Last checked Leg
$query = "
    SELECT
        hdl_id
    FROM
        \"leg\"
        JOIN \"airport\" ON (leg.apt_id_arrive = airport.apt_id)
    WHERE
        leg_id = '" . b1n_inBd($leg_id) . "'";

$rs = $sql->singleQuery($query);

if($rs && is_array($rs))
{
    $hdl_id = $rs['hdl_id'];
}
else
{
    b1n_regGoBackExit('Could not get ID of the Handler of the last checked Leg.\nAborting PDF Generation.');
}

// Configuration Hash
$reg_config = 
    array("Contacts" =>
              array("reg_data"  => "contacts",
                    "db"        => "none",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_hdl_ctc",
                                         "text"     => "ctc_name",
                                         "value"    => "ctc_id",
                                         "name"     => "contacts[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "hdl_id = '" . b1n_inBd($hdl_id) . "'"),
                    "mand"      => true),
          "Request" =>
              array("reg_data"  => "services",
                    "db"        => "none",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "service",
                                         "text"     => "srv_name",
                                         "value"    => "srv_id",
                                         "name"     => "services[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "srv_provider = 'H'"),
                    "mand"      => true),
          "Remarks" =>
              array("reg_data"  => "remarks",
                    "db"        => "Remarks",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),
                    "mand"      => false));
?>
