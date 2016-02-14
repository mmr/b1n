<?
/* $Id: index.php,v 1.23 2004/09/28 22:35:22 mmr Exp $ */
$page1_title = "Leg";

$d    = date("Y");
$dinc = $d + b1n_DEFAULT_DATE_INC;
$ddec = $d - b1n_DEFAULT_DATE_DEC;

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "leg_id",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => false,
                    "mand"      => false),
          "Delete IDs" =>
              array("reg_data"  => "ids",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => false,
                    "mand"      => false),
          "Last Leg Id" =>
              array("reg_data"  => "last_leg_id",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => false,
                    "mand"      => false),
          "KeepTrack" =>
              array("reg_data"  => "leg_keeptrack_dt",
                    "db"        => "leg_keeptrack_dt",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => true,
                    "mand"      => false),
          "Aircraft" =>
              array("reg_data"  => "acf_id",
                    "db"        => "acf_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "aircraft",
                                         "text"  => "acf_model",
                                         "value" => "acf_id",
                                         "name"  => "acf_id"),

                    "load"      => true,
                    "mand"      => true),
          "Depart Airport" =>
              array("reg_data"  => "apt_id_depart",
                    "db"        => "apt_id_depart",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "airport",
                                         "text"  => "apt_name",
                                         "value" => "apt_id",
                                         "name"  => "apt_id_depart",
                                         "params"   => array("onChange" => "b1n_verifyAirports(this.form)")),

                    "load"      => true,
                    "mand"      => true),
          "Arrive Airport" =>
              array("reg_data"  => "apt_id_arrive",
                    "db"        => "apt_id_arrive",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "airport",
                                         "text"     => "apt_name",
                                         "value"    => "apt_id",
                                         "name"     => "apt_id_arrive",
                                         "params"   => array("onChange" => "b1n_verifyAirports(this.form)")),

                    "load"      => true,
                    "mand"      => true),
          "ETD" =>
              array("reg_data"  => "leg_etd_dt",
                    "db"        => "leg_etd_dt",

                    "check"     => "date_hour",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_hour",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "load"      => true,
                    "mand"      => false),
          "ETE" =>
              array("reg_data"  => "leg_ete_i",
                    "db"        => "leg_ete_i",

                    "check"     => "hour",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "hour",
                                         "max_hour" => "16"),

                    "load"      => true,
                    "mand"      => true),
          "Ground Time" =>
              array("reg_data"  => "leg_groundtime_i",
                    "db"        => "leg_groundtime_i",

                    "check"     => "hour",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "defined",
                                         "options"  => array("45min" => "00:45", "60min" => "01:00")),

                    "load"      => true,
                    "mand"      => false),
          "Distance" =>
              array("reg_data"  => "leg_distance",
                    "db"        => "leg_distance",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 10),

                    "load"      => true,
                    "mand"      => false),
          "Wind" =>
              array("reg_data"  => "leg_wind",
                    "db"        => "leg_wind",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 10),

                    "load"      => true,
                    "mand"      => false),
          "Fuel" =>
              array("reg_data"  => "leg_fuel",
                    "db"        => "leg_fuel",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 5,
                                         "maxlen"   => 5),

                    "load"      => true,
                    "mand"      => false),
          "Pax List" =>
              array("reg_data"  => "paxs",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_pax",
                                         "text"     => "pax_cts_name",
                                         "value"    => "pax_id",
                                         "name"     => "paxs[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "pax_ppt_exp_dt > CURRENT_TIMESTAMP"),

                    "load"      => true,
                    "mand"      => false),
          "PIC" =>
              array("reg_data"  => "cmb_id_pic",
                    "db"        => "cmb_id_pic",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_cmb",
                                         "text"     => "cmb_occ_name",
                                         "value"    => "cmb_id",
                                         "name"     => "cmb_id_pic",
                                         "where"    => "cmb_ppt_exp_dt > CURRENT_TIMESTAMP"),

                    "load"      => true,
                    "mand"      => true),
          "SIC" =>
              array("reg_data"  => "cmb_id_sic",
                    "db"        => "cmb_id_sic",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_cmb",
                                         "text"     => "cmb_occ_name",
                                         "value"    => "cmb_id",
                                         "name"     => "cmb_id_sic",
                                         "where"    => "cmb_ppt_exp_dt > CURRENT_TIMESTAMP"),

                    "load"      => true,
                    "mand"      => true),
          "Extra 1" =>
              array("reg_data"  => "cmb_id_extra1",
                    "db"        => "cmb_id_extra1",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_cmb",
                                         "text"     => "cmb_occ_name",
                                         "value"    => "cmb_id",
                                         "name"     => "cmb_id_extra1",
                                         "where"    => "cmb_ppt_exp_dt > CURRENT_TIMESTAMP"),

                    "load"      => true,
                    "mand"      => false),
          "Extra 2" =>
              array("reg_data"  => "cmb_id_extra2",
                    "db"        => "cmb_id_extra2",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_cmb",
                                         "text"     => "cmb_occ_name",
                                         "value"    => "cmb_id",
                                         "name"     => "cmb_id_extra2",
                                         "where"    => "cmb_ppt_exp_dt > CURRENT_TIMESTAMP"),

                    "load"      => true,
                    "mand"      => false),
          "Remarks" =>
              array("reg_data"  => "leg_remarks",
                    "db"        => "leg_remarks",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "load"      => true,
                    "mand"      => false));

unset($d);
unset($dinc);
unset($dend);

// getVars from $_REQUEST and put them in $reg_data hash

// action1
if($action1 == 'print')
{
    // getVars from $_REQUEST and put them in $reg_data hash
    $reg_data = b1n_regExtract($reg_config);

    if(b1n_havePermission(b1n_FUNC_LIST_LEG)) 
    {
        require($page0 . "/" . $page1 . "/print.php");
    }
    exit;
}
// If we are going to change, include the 'Trip' input
if($action1 == 'change' || $action0 == 'change'){
    $aux = $reg_config;
    $reg_config = array();
    $reg_config["Trip"] =
              array("reg_data"  => "leg_trip",
                    "db"        => "leg_trip",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 3,
                                         "maxlen"   => 3),

                    "load"      => true,
                    "mand"      => true);
    $reg_config += $aux;
    $reg_data = b1n_regExtract($reg_config);
}
elseif($action0 == 'caterer')
{
    $reg_data = b1n_regExtract($reg_config);
    $aux = count($reg_data['ids']);

    if($aux <= 0)
    {
        b1n_regGoBackExit('You have to check, at least, one leg.');
    }
    elseif($aux > 1)
    {
        b1n_regGoBackExit('You cannot check more than one leg for Catering Order');
    }
}
else {
    $reg_data = b1n_regExtract($reg_config);
}

require(b1n_INCPATH . "/reg.inc.php");
?>
