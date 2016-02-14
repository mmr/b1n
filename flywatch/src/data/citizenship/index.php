<?
/* $Id: index.php,v 1.7 2002/11/27 19:09:14 binary Exp $ */
$page1_title = "Citizenship";


/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "cts_id",

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
              array("reg_data"  => "cts_name",
                    "db"        => "cts_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "cts_desc",
                    "db"        => "cts_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
