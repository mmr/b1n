<?
/* $Id: index.php,v 1.8 2004/09/29 20:45:56 mmr Exp $ */
$page1_title = "Handler";

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "hdl_id",

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
              array("reg_data"  => "hdl_name",
                    "db"        => "hdl_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "hdl_desc",
                    "db"        => "hdl_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Email" =>
              array("reg_data"  => "hdl_email",
                    "db"        => "hdl_email",

                    "check"     => "email",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "SITA" =>
              array("reg_data"  => "hdl_sita",
                    "db"        => "hdl_sita",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "AFTN" =>
              array("reg_data"  => "hdl_aftn",
                    "db"        => "hdl_aftn",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Arinc (VHF)" =>
              array("reg_data"  => "hdl_arinc",
                    "db"        => "hdl_arinc",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 8,
                                         "maxlen"   => 8),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone Country Code" =>
              array("reg_data"  => "hdl_phone_country_code",
                    "db"        => "hdl_phone_country_code",

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
              array("reg_data"  => "hdl_phone_city_code",
                    "db"        => "hdl_phone_city_code",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 4,
                                         "maxlen"   => 4),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Phone" =>
              array("reg_data"  => "hdl_phone",
                    "db"        => "hdl_phone",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Mobile" =>
              array("reg_data"  => "hdl_mobile",
                    "db"        => "hdl_mobile",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Fax" =>
              array("reg_data"  => "hdl_fax",
                    "db"        => "hdl_fax",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Contacts" =>
              array("reg_data"  => "contacts",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "contact",
                                         "text"     => "ctc_name",
                                         "value"    => "ctc_id",
                                         "name"     => "contacts[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "ctc_provider = 'H'"),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
