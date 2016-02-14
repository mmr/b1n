<?
header("content-Type: image/png");
require("./lib/panachart.php");

$vCht4 = array(60,40,20,34,5,52,41,20,34,43,64,40);
$vCht5 = array(12,21,36,27,14,23,3,5,29,23,12,5);
$vCht6 = array(5,7,3,15,7,8,2,2,2,11,22,3);
$vLabels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

$o = new chart(640,480,5, '#eeeeee');
$o->setTitle("MegaSena","#000000",4);
$o->setPlotArea(SOLID,"#444444", '#dddddd');
$o->setFormat(0,',','.');
$o->addSeries($vCht4,'dot','n1', SOLID,'#000000', '#0000ff');
$o->addSeries($vCht4,'line','n1', SOLID,'#000000', '#0000ff');
$o->addSeries($vCht5,'dot', 'n2', SOLID,'#000000', '#0000ff');
$o->addSeries($vCht5,'line','n2', SOLID,'#000000', '#0000ff');
$o->addSeries($vCht6,'dot','n3', SOLID,'#000000', '#0000ff');
$o->addSeries($vCht6,'line','n3', SOLID,'#000000', '#0000ff');
$o->setXAxis('#000000', SOLID, 1, "X Axis");
$o->setYAxis('#000000', SOLID, 2, "");
$o->setLabels($vLabels, '#000000', 1, VERTICAL);
$o->setGrid("#bbbbbb", DASHED, "#bbbbbb", DOTTED);		
$o->plot('');	
?>
