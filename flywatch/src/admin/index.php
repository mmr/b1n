<?
/* $Id: index.php,v 1.5 2002/12/02 23:10:11 binary Exp $ */

$toc = array(
        "User" =>
              array("page1"  => "user",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=user",
                    "icon"   => "img/data/user.png",
                    "target" => "content"),
        "Group" =>
              array("page1"  => "group",
                    "link"   => b1n_URL . "?page0=" . $page0 . "&page1=group",
                    "icon"   => "img/data/group.png",
                    "target" => "content"));

$page0_title = "Admin";

require(b1n_INCPATH . "/toc.inc.php");
?>
