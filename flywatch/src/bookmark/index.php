<?
/* $Id: index.php,v 1.1 2002/12/19 00:06:43 binary Exp $ */

$toc = array(
        "Link" =>
              array("page1"  => "link",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=link",
                    "icon"   => "img/bookmark/link.png",
                    "target" => "content"),
        "Sys Link" =>
              array("page1"  => "syslink",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=syslink",
                    "icon"   => "img/bookmark/syslink.png",
                    "target" => "content"));

$page0_title = "BookMark";

require(b1n_INCPATH . "/toc.inc.php");
?>
