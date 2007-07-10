<?
// $Id: inf.php,v 1.1 2004/05/09 23:29:47 mmr Exp $
if(!b1n_calcCheck($d['ini'], $d['fin'])){
  if(!b1n_calcGetMinMax($d['ini'],$d['fin'])){
    die('Danou-se...');
  }
}

b1n_getVar('n', $d['n']);

if($d['n']<1 && $d['n']>=60){
  die('Danou-se...');
}
$ret = b1n_calcGetNumberInfo($d['n']);
?>
<img src='<?= b1n_URL."?p=graph&amp;ini=".$d['ini']."&amp;fin=".$d['fin']."&amp;n=".$d['n'] ?>' width='800' height='600' />
<hr />
