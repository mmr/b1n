<?
/* $Id: index.php,v 1.9 2003/02/17 10:50:05 binary Exp $ */
$page1_title = "Food Type";



/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "fdt_id",

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
              array("reg_data"  => "fdt_name",
                    "db"        => "fdt_name",

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
              array("reg_data"  => "fdt_desc",
                    "db"        => "fdt_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Foods" =>
              array("reg_data"  => "foods",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "food",
                                         "text"     => "fod_name",
                                         "value"    => "fod_id",
                                         "name"     => "foods[]",
                                         "params"   => array("multiple" => "")),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
