<?
/* $Id: index.php,v 1.10 2002/11/29 01:58:47 binary Exp $ */
$page1_title = "Group";

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "grp_id",

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
              array("reg_data"  => "grp_name",
                    "db"        => "grp_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "grp_desc",
                    "db"        => "grp_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Users" =>
              array("reg_data"  => "users",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "user",
                                         "text"     => "usr_name",
                                         "value"    => "usr_id",
                                         "name"     => "users[]",
                                         "params"   => array("multiple" => "")),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false),
          "Functions" =>
              array("reg_data"  => "functions",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "function",
                                         "text"     => "fnc_name",
                                         "value"    => "fnc_id",
                                         "name"     => "functions[]",
                                         "params"   => array("multiple" => "")),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
