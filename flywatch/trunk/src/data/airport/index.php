<?

/* $Id: index.php,v 1.22 2004/08/04 02:09:24 mmr Exp $ */
$page1_title = "Airport";

$d = date('Y');

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "apt_id",

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
              array("reg_data"  => "apt_name",
                    "db"        => "apt_name",

                    "check"     => "unique",
                    "type"      => "text",
                    "extra"     => array("table"    => $page1,
                                         "size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "apt_desc",
                    "db"        => "apt_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "ICAO" =>
              array("reg_data"  => "apt_icao",
                    "db"        => "apt_icao",

                    "check"     => "unique && exactlength",
                    "type"      => "text",
                    "extra"     => array("table"    => $page1,
                                         "size"     => 4,
                                         "maxlen"   => 4),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "IATA" =>
              array("reg_data"  => "apt_iata",
                    "db"        => "apt_iata",

                    "check"     => "unique && exactlength",
                    "type"      => "text",
                    "extra"     => array("table"    => $page1,
                                         "size"     => 3,
                                         "maxlen"   => 3),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Caterer" =>
              array("reg_data"  => "cat_id",
                    "db"        => "cat_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "caterer",
                                         "text"  => "cat_name",
                                         "value" => "cat_id",
                                         "name"  => "cat_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Handler" =>
              array("reg_data"  => "hdl_id",
                    "db"        => "hdl_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "handler",
                                         "text"  => "hdl_name",
                                         "value" => "hdl_id",
                                         "name"  => "hdl_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Permit" =>
              array("reg_data"  => "pmt_id",
                    "db"        => "pmt_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "permit",
                                         "text"  => "pmt_name",
                                         "value" => "pmt_id",
                                         "name"  => "pmt_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "DST Start" =>
              array("reg_data"  => "apt_dst_start_dt",
                    "db"        => "apt_dst_start_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date",
                                         "year_start"   => $d-3,
                                         "year_end"     => $d+1,
                                         "params"       => array("onChange" => "b1n_updateDstEnd(this)")),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "DST End" =>
              array("reg_data"  => "apt_dst_end_dt",
                    "db"        => "apt_dst_end_dt",

                    "check"     => "date",
                    "type"      => "select",
                    "extra"     => array("seltype"      => "date",
                                         "year_start"   => $d-3,
                                         "year_end"     => $d+1),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "TimeZone" =>
              array("reg_data"  => "apt_timezone",
                    "db"        => "apt_timezone",

                    "check"     => "numeric",
                    "type"      => "text",
                    "extra"     => array("size"     => 6,
                                         "maxlen"   => 6),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Country" =>
              array("reg_data"  => "cty_id",
                    "db"        => "cty_id",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "country",
                                         "text"     => "cty_name",
                                         "value"    => "cty_id",
                                         "name"     => "cty_id"),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "City" =>
              array("reg_data"  => "apt_city",
                    "db"        => "apt_city",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false));

unset($d);

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
