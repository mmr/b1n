<?
// $Id: gedec.php,v 1.1 2003/06/20 19:44:13 mmr Exp $
$page1_title = 'General Declaration';

// Configuration Hash
$reg_config = 
    array("Illness" =>
              array("reg_data"  => "illness",
                    "db"        => "none",

                    "check"     => "fk",
                    "type"      => "select",
                    "extra"     => array("seltype"  => "fk",
                                         "table"    => "view_leg_pax",
                                         "text"     => "pax_cts_name",
                                         "value"    => "pax_id",
                                         "name"     => "illness[]",
                                         "params"   => array("multiple" => ""),
                                         "where"    => "leg_id = '" . b1n_inBd($leg_id) . "'"),
                    "mand"      => false),
          "Conditions" =>
              array("reg_data"  => "condition",
                    "db"        => "Condition",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),
                    "mand"      => false),
          "Disinsecting" =>
              array("reg_data"  => "disinsecting",
                    "db"        => "Disinsecting",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),
                    "mand"      => false),
          "Remarks" =>
              array("reg_data"  => "remarks",
                    "db"        => "Remarks",

                    "check"     => "none",
                    "type"      => "text",
                    "extra"     => array("size"     => b1n_DEFAULT_SIZE,
                                         "maxlen"   => b1n_DEFAULT_MAXLEN),
                    "mand"      => false));
?>
