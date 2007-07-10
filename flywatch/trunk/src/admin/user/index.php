<?
/* $Id: index.php,v 1.40 2004/08/04 02:02:31 mmr Exp $ */
$page1_title = "User";

$d    = date("Y");
$dinc = $d + b1n_DEFAULT_DATE_INC;
$ddec = $d - b1n_DEFAULT_DATE_DEC;

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "usr_id",

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
              array("reg_data"  => "usr_name",
                    "db"        => "usr_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Login" =>
              array("reg_data"  => "usr_login",
                    "db"        => "usr_login",

                    "check"     => "unique",
                    "type"      => "text",
                    "extra"     => array("table"    => $page1,
                                         "size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Password" =>
              array("reg_data"  => "usr_passwd",
                    "db"        => "usr_passwd",

                    "check"     => "none",
                    "type"      => "password",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => 32),

                    "search"    => false,
                    "select"    => false,
                    "load"      => false,
                    "mand"      => true),
          "Password Confirmation" =>
              array("reg_data"  => "usr_passwd2",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "password",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => 32),

                    "search"    => false,
                    "select"    => false,
                    "load"      => false,
                    "mand"      => true),
          "Day Of Birth" =>
              array("reg_data"  => "usr_dob_dt",
                    "db"        => "usr_dob_dt",

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
              array("reg_data"  => "usr_email",
                    "db"        => "usr_email",

                    "check"     => "email",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "NickName" =>
              array("reg_data"  => "usr_nick",
                    "db"        => "usr_nick",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Phone Country Code" =>
              array("reg_data"  => "usr_phone_country_code",
                    "db"        => "usr_phone_country_code",

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
              array("reg_data"  => "usr_phone_city_code",
                    "db"        => "usr_phone_city_code",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 4),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone" =>
              array("reg_data"  => "usr_phone",
                    "db"        => "usr_phone",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Mobile" =>
              array("reg_data"  => "usr_mobile",
                    "db"        => "usr_mobile",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 8,
                                         "maxlen"   => 8),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Start Page" =>
              array("reg_data"  => "usr_start_page",
                    "db"        => "usr_start_page",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "defined",
                                         "options"  => b1n_regPagesUser($sql)),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Active" =>
              array("reg_data"  => "usr_active",
                    "db"        => "usr_active",

                    "check"     => "boolean",
                    "type"      => "radio",
                    "extra"     => array("options" => array("Yes"  => 1,
                                                            "No"   => 0)),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Expiration Date" =>
              array("reg_data"  => "usr_expire_dt",
                    "db"        => "usr_expire_dt",

                    "check"     => "date_hour",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date_hour",
                                         "year_start"   => $d,
                                         "year_end"     => $dinc),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false));

unset($d);
unset($dinc);
unset($ddec);

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
