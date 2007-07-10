<?
/* $Id: toc.inc.php,v 1.5 2002/12/10 22:11:59 binary Exp $ */

$page1_is_valid = false;

if(empty($page1))
{
    require(b1n_INCPATH . "/toctemplate.inc.php");
}
else
{
    foreach($toc as $t)
    {
        if($page1 == $t['page1'])
        {
           $page1_is_valid = true;
           break;
        }
    }

    if($page1_is_valid)
    {
        // Page1 Functions
        require(b1n_REGLIBPATH . "/" . $page1 . ".lib.php");

        // Page1 Content
        require($page0 . "/" . $page1 . "/index.php");
    }
}
?>
