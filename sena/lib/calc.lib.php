<?
define('b1n_TOL_AVG', 5/100); // Average Tolerance (percentage)
define('b1n_TOL_ABOVE_AVG', 10/100); // Above Average
define('b1n_TOL_BELOW_AVG', 10/100); // Below Average

function b1n_calcCheck($ini, $fin){
  if($ret=preg_match('/(\d{2}).(\d{2}).(\d{4})/',$ini,$match)){
    list(,$d,$m,$y) = $match;
    if(checkdate($m,$d,$y)){
      $ret = true;
    }
  }
  if($ret){
    if($ret=preg_match('/(\d{2}).(\d{2}).(\d{4})/',$fin,$match)){
      list(,$d,$m,$y)=$match;
      if(checkdate($m,$d,$y)){
        return true;
      }
    }
  }
  return $ret;
}

function b1n_calcGetMinMax(&$ini, &$fin){
  global $sql;

  $query = "
    SELECT
      MIN(data) AS min,
      MAX(data) AS max
    FROM
      sena";

  $ret = $sql->sqlSingleQuery($query);

  if($ret){
    if(preg_match('/(\d{4})-(\d{2})-(\d{2})/',$ret['min'],$match)){
      list(,$y,$m,$d)=$match;
      $ini = "$d/$m/$y";
    }
    if(preg_match('/(\d{4})-(\d{2})-(\d{2})/',$ret['max'],$match)){
      list(,$y,$m,$d)=$match;
      $fin = "$d/$m/$y";
    }
  }
  return $ret;
}

function b1n_calcGetStatus($ini, $fin, $order, $order_type){
  global $sql;

  $ini = b1n_formatDate($ini);
  $fin = b1n_formatDate($fin);

  if(!b1n_cmp($order_type, 'ASC')){
    $order_type = 'DESC';
  }

  if(!(b1n_cmp($order, 'number') || b1n_cmp($order, 'status') || b1n_cmp($order, 'last'))){
    $order_type = 'status';
  }

  $ret = array();

  $query = "
    SELECT
      1 AS number,
      COUNT(id) AS status,
      MAX(data) AS last
    FROM
      sena
    WHERE
      data BETWEEN '$ini' AND '$fin' AND (
      d1 = 1 OR d2 = 1 OR d3 = 1 OR d4 = 1 OR d5 = 1 OR d6 = 1)";

  for($i=2;$i<=60;$i++){
    $query .= "
      UNION (
        SELECT
          $i AS number,
          COUNT(id) AS status,
          MAX(data) AS last
        FROM
          sena
        WHERE
          data BETWEEN '$ini' AND '$fin' AND (
          d1 = $i OR d2 = $i OR d3 = $i OR d4 = $i OR d5 = $i OR d6 = $i))";
  }
  $query .= "
    ORDER BY $order $order_type";

  $ret = $sql->sqlQuery($query);

  $avg_total = 0;
  foreach($ret as $aux){
    $avg_total += $aux['status'];
  }
  $ret[sizeof($ret)] = $t = $avg_total/60;

  for($i=0;$i<60;$i++){
    $aux = $ret[$i]['status'];

    if($aux*(1.00-b1n_TOL_AVG)<$t && $aux*(1.00+b1n_TOL_AVG)<$t){
      if($aux*(1.00-b1n_TOL_BELOW_AVG)<$t && $aux*(1.00+b1n_TOL_BELOW_AVG)<$t){
        $ret[$i]['class'] = 'very_below_avg';
      }
      else {
        $ret[$i]['class'] = 'below_avg';
      }
    }
    elseif($aux*(1.00-b1n_TOL_AVG)>$t && $aux*(1.00+b1n_TOL_AVG)>$t){
      if($aux*(1.00-b1n_TOL_ABOVE_AVG)>$t && $aux*(1.00+b1n_TOL_ABOVE_AVG)>$t){
        $ret[$i]['class'] = 'very_above_avg'; 
      }
      else {
        $ret[$i]['class'] = 'above_avg'; 
      }
    }
    else{
      $ret[$i]['class'] = 'avg';
    }
  }

  return $ret;
}

function b1n_calcGetDates($ini, $fin)
{
  global $sql;
  $query = "
    SELECT data FROM sena
    WHERE
      data BETWEEN '".b1n_formatDate($ini)."' AND '".b1n_formatDate($fin)."'
    ORDER BY data ASC";

  if($ret = $sql->sqlQuery($query)){
    $aux = array();
    foreach($ret as $r){
      $aux[] = $r['data'];
    }
    return $aux;
  }
  else {
    die('Erro ao pegar datas');
  }
}

function b1n_calcGetValues($ini, $fin, $dates)
{
  global $sql;

  $ini = b1n_formatDate($ini);
  $fin = b1n_formatDate($fin);

  $ret = array();

  $query = "
    SELECT
      1 AS number,
      data
    FROM
      sena
    WHERE
      data BETWEEN '$ini' AND '$fin' AND (
      d1 = 1 OR d2 = 1 OR d3 = 1 OR d4 = 1 OR d5 = 1 OR d6 = 1)";

  for($i=2;$i<=60;$i++){
    $query .= "
      UNION (
        SELECT
          $i AS number,
          data
        FROM
          sena
        WHERE
          data BETWEEN '$ini' AND '$fin' AND (
          d1 = $i OR d2 = $i OR d3 = $i OR d4 = $i OR d5 = $i OR d6 = $i))";
  }
  $ret = $sql->sqlQuery($query);

  $d = array();
  $aux = $dates;
  $last=array_shift($aux);
  foreach($ret as $x){
    $aux = $x['number'];
    $d[$aux][$last] = 100;
    foreach($dates as $y){
      if(b1n_cmp($x['data'], $y)){
        $v = round((sizeof($d[$aux])/3));
      }
      else {
        $v = round((sizeof($d[$aux])/3))*-1;
      }
      $d[$aux][$y] = $d[$aux][$last] + $v;
      $last = $y;
    }
  }
  return $d;
}

function b1n_calcGetNumberInfo($n)
{
  global $sql;
  $query = "SELECT * FROM n_inf($n) AS (data DATE)";
  return $sql->sqlQuery($query);
}
?>
