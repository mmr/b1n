<?
/* $Id: index.php,v 1.2 2002/12/02 23:10:11 binary Exp $ */

$toc = array(
        "Pax" =>
              array("page1"  => "pax",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=pax",
                    "icon"   => "img/data/pax.png",
                    "target" => "content"),
        "Pax Visa" =>
              array("page1"  => "pax_vst",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=pax_vst",
                    "icon"   => "img/data/pax_vst.png",
                    "target" => "content"),
        "Visa Type" =>
              array("page1"  => "visatype",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=visatype",
                    "icon"   => "img/data/visatype.png",
                    "target" => "content"),
        "Citizenship" =>
              array("page1"  => "citizenship",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=citizenship",
                    "icon"   => "img/data/country.png",
                    "target" => "content"));

$page0_title = "Pax";

require(b1n_INCPATH . "/toc.inc.php");
?>
