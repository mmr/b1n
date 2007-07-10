<?
// $Id: graph.php,v 1.1 2004/05/09 23:29:46 mmr Exp $

require(b1n_PATH_LIB.'/panachart.php');

b1n_getVar('n', $d['n']);

if(!b1n_calcCheck($d['ini'], $d['fin'])){
  if(!b1n_calcGetMinMax($d['ini'],$d['fin'])){
    die('Danou-se...');
  }
}

if($d['n']<1 || $d['n']>60){
  die('Danou-se...');
}

// TODO: implementar a porra do intervalo ini->fin em udf n_inf
$ret  = b1n_calcGetNumberInfo($d['n']);
$dates= b1n_calcGetDates($d['ini'], $d['fin']);

$c = new chart(800, 600, 5, '#eeeeee');
$c->setTitle('MegaSena', '#000000', 4);
$c->setPlotArea(SOLID, '#444444', '#dddddd');
$c->setXAxis('#000000', SOLID, 2, '');
$c->setYAxis('#000000', SOLID, 2, '');
$c->setFormat(0, ',', '.');
$c->setLabels($dates, '#000000', 1, VERTICAL);

$i = 1;
$aux = array();
$aux[0] = 0;
$v = 0;
foreach($ret as $r){
  if(in_array($r['data'], $dates)){
    $v = 10;
  }
  else {
    $v = -1;
  }
  if($aux[$i-1]<=0){
    $v = 0;
  }
  $aux[] = $aux[$i-1]+$v;
  $i++;
}

$c->addSeries($aux, "line", "nl", SOLID, "#000000", "#0000ff");
$c->addSeries($aux, "dot", "nl", SOLID, "#000000", "#0000ff");

$c->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);

  // Img
header('Content-Type: image/png');
$c->plot('');
?>
