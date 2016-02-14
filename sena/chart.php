<?
// Defines
define('b1n_PATH_LIB', 'lib');
define('b1n_DEBUG', false);
define('b1n_URL',   $_SERVER['SCRIPT_NAME']);

// Libs
require(b1n_PATH_LIB . '/sqllink.lib.php');
require(b1n_PATH_LIB . '/data.lib.php');
require(b1n_PATH_LIB . '/calc.lib.php');
require(b1n_PATH_LIB . '/panachart.php');

// Headers
header('Expires: Wed, 06 Aug 2003 15:50:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  // HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
  // HTTP/1.0
header('Pragma: no-cache');

$sql = new b1n_sqlLink(b1n_PATH_LIB . '/db_conf.lib.php');

b1n_getVar('ini', $d['ini']);
b1n_getVar('fin', $d['fin']);

if(!b1n_calcCheck($d['ini'], $d['fin'])){
  if(!b1n_calcGetMinMax($d['ini'],$d['fin'])){
    die('Danou-se...');
  }
}
$dates  = b1n_calcGetDates($d['ini'], $d['fin']);
$values = b1n_calcGetValues($d['ini'], $d['fin'], $dates);

$c = new chart(800, 600, 5, '#eeeeee');
$c->setTitle('MegaSena', '#000000', 4);
$c->setPlotArea(SOLID, '#444444', '#dddddd');
$c->setXAxis('#000000', SOLID, 1, '');
$c->setYAxis('#000000', SOLID, 2, '');
$c->setFormat(0, ',', '.');
$c->setLabels($dates, '#000000', 1, VERTICAL);

$i=0;
$aux = array();
foreach($values as $k => $v){
  $j=0;
  foreach($v as $d){
    $aux[$i][$j] = $d;
    $j++;
  }
  $c->addSeries($aux[$i], "line", "n".$i."l", SOLID, "#000000", "#0000ff");
  $c->addSeries($aux[$i], "dot",  "n".$i."d", SOLID, "#000000", "#0000ff");
  $i++;
}

$c->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);

  // Img
header("Content-Type: image/png");
$c->plot('');
?>
