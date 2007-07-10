<?
/* $Id */

define("b1n_UPLOAD_DIR", $page0 . "/upload");

$page0_title = "Docs";

if(empty($page1))
{
    $page1 = "file";
}

if($page1 == "file")
{
    // Page1 Functions
    require(b1n_REGLIBPATH . "/" . $page1 . ".lib.php");
}

// Page1 Content
require($page0 . "/" . $page1 . "/index.php");
?>
