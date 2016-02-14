<?
/* $Id: index.php,v 1.6 2004/08/04 02:02:31 mmr Exp $ */
$page1_title = "Pax";

$d    = date("Y");
$dinc = $d + b1n_DEFAULT_DATE_INC;
$ddec = $d - b1n_DEFAULT_DATE_DEC;

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "pax_id",

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
              array("reg_data"  => "pax_name",
                    "db"        => "pax_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "pax_desc",
                    "db"        => "pax_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

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

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Day Of Birth" =>
              array("reg_data"  => "pax_dob_dt",
                    "db"        => "pax_dob_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_check_dob",
                                         "year_start"   => b1n_DEFAULT_DATE_START_YEAR,
                                         "year_end"     => $d),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Email" =>
              array("reg_data"  => "pax_email",
                    "db"        => "pax_email",

                    "check"     => "email",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Phone Country Code" =>
              array("reg_data"  => "pax_phone_country_code",
                    "db"        => "pax_phone_country_code",

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
              array("reg_data"  => "pax_phone_city_code",
                    "db"        => "pax_phone_city_code",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 4),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone" =>
              array("reg_data"  => "pax_phone",
                    "db"        => "pax_phone",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Mobile" =>
              array("reg_data"  => "pax_mobile",
                    "db"        => "pax_mobile",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 8,
                                         "maxlen"   => 8),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Passport Number" =>
              array("reg_data"  => "pax_ppt_nbr",
                    "db"        => "pax_ppt_nbr",

                    "check"     => "unique",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN,
                                         "table"    => $page1),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Passport Issue Date" =>
              array("reg_data"  => "pax_ppt_issue_dt",
                    "db"        => "pax_ppt_issue_dt",

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
              array("reg_data"  => "pax_ppt_exp_dt",
                    "db"        => "pax_ppt_exp_dt",

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
