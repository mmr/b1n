<?
/* $Id: index.php,v 1.3 2003/03/21 00:28:05 binary Exp $ */

// 4096 = 4Mb
$page1_title = "Upload/Download";

/* Configuration Hash */
$reg_config = 
    array("ID" =>
              array("reg_data"  => "id",
                    "db"        => "fil_id",

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
              array("reg_data"  => "fil_fake_name",
                    "db"        => "fil_fake_name",

                    "check"     => "none",
                    "type"      => "none",

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "File" =>
              array("reg_data"  => "file",
                    "db"        => "none",

                    "check"     => "none",
                    "type"      => "file",

                    "search"    => false,
                    "select"    => false,
                    "load"      => false,
                    "mand"      => false),
          "Type" =>
              array("reg_data"  => "fil_type",
                    "db"        => "fil_type",

                    "check"     => "none",
                    "type"      => "select",
                    "extra"     => array("seltype" => "defined",
                                         "options" => array("Image" => "I",
                                                            "PDF"   => "P",
                                                            "Misc"  => "M")),

                    "search"    => false,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false),
          "Desc" =>
              array("reg_data"  => "fil_desc",
                    "db"        => "fil_desc",

                    "check"     => "none",
                    "type"      => "textarea",

                    "search"    => true,
                    "select"    => true,
                    "load"      => true,
                    "mand"      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . "/reg.inc.php");
?>
