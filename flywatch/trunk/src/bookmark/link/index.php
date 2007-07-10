<?
/* $Id: index.php,v 1.3 2002/12/18 23:29:24 binary Exp $ */
$page1_title = "Link";

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "lnk_id",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => false,
                    "mand"      => false),
          "Delete IDs" =>
              array("reg_data"  => "ids",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "none",

                    "load"      => false,
                    "mand"      => false),
          "Name" =>
              array("reg_data"  => "lnk_name",
                    "db"        => "lnk_name",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "load"      => true,
                    "mand"      => true),
          "URL" =>
              array("reg_data"  => "lnk_url",
                    "db"        => "lnk_url",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),

                    "load"      => true,
                    "mand"      => true),
          "Desc" =>
              array("reg_data"  => "lnk_desc",
                    "db"        => "lnk_desc",

                    "check"     => "none",
                    "type"      => "textarea",
                    "extra"     => array("rows"     => b1n_DEFAULT_ROWS,
                                         "cols"     => b1n_DEFAULT_COLS,
                                         "wrap"     => b1n_DEFAULT_WRAP),

                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
