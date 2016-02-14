<?
/* $Id: index.php,v 1.9 2002/12/12 07:33:03 binary Exp $ */
$page1_title = "Service";


/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "srv_id",

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
              array("reg_data"  => "srv_name",
                    "db"        => "srv_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "srv_desc",
                    "db"        => "srv_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Type" =>
              array("reg_data"  => "srv_provider",
                    "db"        => "srv_provider",

                    "check"     => "radio",
                    "type"      => "radio",
                    "extra"     => array("options" => array("Handler" => "H",
                                                            "Permit"  => "P")),

                    "search"    => false,
                    "select"    => false,
                    "load"      => true,
                    "mand"      => true));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
