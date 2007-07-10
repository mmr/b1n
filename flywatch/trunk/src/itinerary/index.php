<?
/* $Id: index.php,v 1.17 2003/02/06 21:33:26 binary Exp $ */

$page0_title = 'Itinerary';

if($page1 != 'pdf')
{
    $page1 = 'leg';
}

require(b1n_REGLIBPATH . '/' . $page1 . '.lib.php');
require($page0 . '/' . $page1 . '/index.php');
?>
