<?
/* $Id: index.php,v 1.6 2002/12/02 23:10:11 binary Exp $ */

$toc = array(
        "Airport" =>
              array("page1"  => "airport",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=airport",
                    "icon"   => "img/data/airport.png",
                    "target" => "content"),
        "Handler" =>
              array("page1"  => "handler",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=handler",
                    "icon"   => "img/data/handler.png",
                    "target" => "content"),
        "Caterer" =>
              array("page1"  => "caterer",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=caterer",
                    "icon"   => "img/data/caterer.png",
                    "target" => "content"),
        "Service" =>
              array("page1"  => "service",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=service",
                    "icon"   => "img/data/service.png",
                    "target" => "content"),
        "Contact" =>
              array("page1"  => "contact",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=contact",
                    "icon"   => "img/data/contact.png",
                    "target" => "content"),
        "Food Type" =>
              array("page1"  => "foodtype",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=foodtype",
                    "icon"   => "img/data/foodtype.png",
                    "target" => "content"),
        "Permit" =>
              array("page1"  => "permit",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=permit",
                    "icon"   => "img/data/permit.png",
                    "target" => "content"));

$page0_title = "Agent";

require(b1n_INCPATH . "/toc.inc.php");
?>
