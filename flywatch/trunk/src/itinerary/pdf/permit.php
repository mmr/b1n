<?
// $Id: permit.php,v 1.3 2004/09/29 21:23:40 mmr Exp $
$page1_title = 'Handling';

// Getting permit ID of the airport of the Last checked Leg
$query = "
    SELECT
        pmt_id
    FROM
        \"leg\"
        JOIN \"airport\" ON (leg.apt_id_arrive = airport.apt_id)
    WHERE
        leg_id = '" . b1n_inBd($leg_id) . "'";

$rs = $sql->singleQuery($query);

if(is_array($rs) && !empty($rs['pmt_id'])){
    $pmt_id = $rs['pmt_id'];
}
else
{
    b1n_regGoBackExit('Could not get ID of the Permit of the last checked Leg.\nAborting PDF Generation.');
}

// Configuration Hash
$reg_config = 
    array("Contacts" =>
              array("reg_data"  => "contacts",
                    "db"        => "none",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_pmt_ctc",
                                         "text"     => "ctc_name",
                                         "value"    => "ctc_id",
                                         "name"     => "contacts[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "pmt_id = '" . b1n_inBd($pmt_id) . "'"),
                    "mand"      => true),
          "Request" =>
              array("reg_data"  => "service",
                    "db"        => "none",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "service",
                                         "text"     => "srv_name",
                                         "value"    => "srv_id",
                                         "name"     => "service",
                                         //"params"   => array("multiple" => ""),
                                         "where"    => "srv_provider = 'P'"),
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
