<?
/* $Id: index.php,v 1.13 2002/12/11 02:31:08 binary Exp $ */

$toc = array(
        "Aircraft" =>
              array("page1"  => "aircraft",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=aircraft",
                    "icon"   => "img/data/aircraft.png",
                    "target" => "content"),
        "Crew Member" =>
              array("page1"  => "cmb",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=cmb",
                    "icon"   => "img/data/cmb.png",
                    "target" => "content"),
        "Airport" =>
              array("page1"  => "airport",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=airport",
                    "icon"   => "img/data/airport.png",
                    "target" => "content"),
        "Country" =>
              array("page1"  => "country",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=country",
                    "icon"   => "img/data/country.png",
                    "target" => "content"),
        "Contact" =>
              array("page1"  => "contact",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=contact",
                    "icon"   => "img/data/contact.png",
                    "target" => "content"),
        "Operator" =>
              array("page1"  => "operator",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=operator",
                    "icon"   => "img/data/operator.png",
                    "target" => "content"),
        "Food" =>
              array("page1"  => "food",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=food",
                    "icon"   => "img/data/food.png",
                    "target" => "content"),
        "Food Type" =>
              array("page1"  => "foodtype",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=foodtype",
                    "icon"   => "img/data/foodtype.png",
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
                    "target" => "content"),
        "Service" =>
              array("page1"  => "service",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=service",
                    "icon"   => "img/data/service.png",
                    "target" => "content"),
        "Occupation" =>
              array("page1"  => "occupation",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=occupation",
                    "icon"   => "img/data/occupation.png",
                    "target" => "content"));

$page0_title = "Data";

require(b1n_INCPATH . "/toc.inc.php");
?>
