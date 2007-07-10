<?
/* $Id: index.php,v 1.5 2003/02/20 14:04:36 binary Exp $ */
$page1_title = "Pax Visa";

$d    = date("Y");
$dinc = $d + b1n_DEFAULT_DATE_INC;
$ddec = $d;

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "pvs_id",

                    "check"     => "none",
                    "type"      => "none",

                    "search"    => false,
                    "select"    => false,
                    "load"      => false,
                    "mand"      => false),
          "Delete IDs" =>
              array("reg_data"  => "ids",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "none",

                    "search"    => false,
                    "select"    => false,
                    "load"      => false,
                    "mand"      => false),
          "Pax" =>
              array("reg_data"  => "pax_id",
                    "db"        => "pax_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_pax",
                                         "text"     => "pax_cts_name",
                                         "value"    => "pax_id",
                                         "name"     => "pax_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Visa Type" =>
              array("reg_data"  => "vst_id",
                    "db"        => "vst_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "visatype",
                                         "text"     => "vst_name",
                                         "value"    => "vst_id",
                                         "name"     => "vst_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Visa Issue Date" =>
              array("reg_data"  => "pvs_issue_dt",
                    "db"        => "pvs_issue_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date",
                                         "year_start"   => b1n_DEFAULT_DATE_START_YEAR,
                                         "year_end"     => $ddec),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Visa Expire Date" =>
              array("reg_data"  => "pvs_exp_dt",
                    "db"        => "pvs_exp_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_exp",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false));

unset($d);
unset($dinc);
unset($ddec);

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
