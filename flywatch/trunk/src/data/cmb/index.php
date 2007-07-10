<?
/* $Id: index.php,v 1.17 2004/08/04 02:02:31 mmr Exp $ */
$page1_title = "Crew Member";

$d    = date("Y");
$dinc = $d + b1n_DEFAULT_DATE_INC;
$ddec = $d - b1n_DEFAULT_DATE_DEC;

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "cmb_id",

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
          "Name" =>
              array("reg_data"  => "cmb_name",
                    "db"        => "cmb_name",

                    "check"     => "unique",
                    "type"      => "text",
                    "extra"     => array("table"    => $page1,
                                         "size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "NickName" =>
              array("reg_data"  => "cmb_nick",
                    "db"        => "cmb_nick",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Citizenship" =>
              array("reg_data"  => "cts_id",
                    "db"        => "cts_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "citizenship",
                                         "text"     => "cts_name",
                                         "value"    => "cts_id",
                                         "name"     => "cts_id"),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => true),
          "Occupation" =>
              array("reg_data"  => "occ_id",
                    "db"        => "occ_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "occupation",
                                         "text"  => "occ_name",
                                         "value" => "occ_id",
                                         "name"  => "occ_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Day Of Birth" =>
              array("reg_data"  => "cmb_dob_dt",
                    "db"        => "cmb_dob_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_dob",
                                         "year_start"   => b1n_DEFAULT_DATE_START_YEAR,
                                         "year_end"     => $ddec),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Email" =>
              array("reg_data"  => "cmb_email",
                    "db"        => "cmb_email",

                    "check"     => "email",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Phone Country Code" =>
              array("reg_data"  => "cmb_phone_country_code",
                    "db"        => "cmb_phone_country_code",

                    "check"     => "numeric",
                    "type"      => "text",
                    "size"      => 2,
                    "extra"     => array("size"     => 2,
                                         "maxlen"   => 2),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone City Code" =>
              array("reg_data"  => "cmb_phone_city_code",
                    "db"        => "cmb_phone_city_code",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 4),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone" =>
              array("reg_data"  => "cmb_phone",
                    "db"        => "cmb_phone",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Mobile" =>
              array("reg_data"  => "cmb_mobile",
                    "db"        => "cmb_mobile",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 8,
                                         "maxlen"   => 8),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "CDAC" =>
              array("reg_data"  => "cmb_cdac",
                    "db"        => "cmb_cdac",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 10,
                                         "maxlen"   => 10),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => true),
          "ATP" =>
              array("reg_data"  => "cmb_atp",
                    "db"        => "cmb_atp",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 10,
                                         "maxlen"   => 10),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "CP" =>
              array("reg_data"  => "cmb_cp",
                    "db"        => "cmb_cp",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 10,
                                         "maxlen"   => 10),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Passport Number" =>
              array("reg_data"  => "cmb_ppt_nbr",
                    "db"        => "cmb_ppt_nbr",

                    "check"     => "unique",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN,
                                         "table"    => $page1),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Passport Issue Date" =>
              array("reg_data"  => "cmb_ppt_issue_dt",
                    "db"        => "cmb_ppt_issue_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date",
                                         "year_start"   => b1n_DEFAULT_DATE_START_YEAR,
                                         "year_end"     => $d),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Passport Expire Date" =>
              array("reg_data"  => "cmb_ppt_exp_dt",
                    "db"        => "cmb_ppt_exp_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_exp",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Medical Expire Date" =>
              array("reg_data"  => "cmb_med_exp_dt",
                    "db"        => "cmb_med_exp_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_exp",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "IFR Expire Date" =>
              array("reg_data"  => "cmb_ifr_exp_dt",
                    "db"        => "cmb_ifr_exp_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_exp",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Type Expire Date" =>
              array("reg_data"  => "cmb_type_exp_dt",
                    "db"        => "cmb_type_exp_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_exp",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Cat2 Expire Date" =>
              array("reg_data"  => "cmb_cat2_exp_dt",
                    "db"        => "cmb_cat2_exp_dt",

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
