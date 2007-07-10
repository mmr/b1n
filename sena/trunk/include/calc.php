<?
b1n_getVar('o', $d['o']);
b1n_getVar('ot', $d['ot']);

if(!b1n_calcCheck($d['ini'], $d['fin'])){
  if(!b1n_calcGetMinMax($d['ini'],$d['fin'])){
    die('Danou-se...');
  }
}
$s  = b1n_calcGetStatus($d['ini'], $d['fin'], $d['o'], $d['ot']);
?>
<form name='f' method='get' action='<? b1n_URL ?>'>
<input type='hidden' name='p' value='calc' />
<table border='1'>
  <tr>
    <td>
      De
      <input type='text' name='ini' value='<?= $d['ini'] ?>' /> &agrave;
      <input type='text' name='fin' value='<?= $d['fin'] ?>' />
      <input type='submit' value='Ok' />
    </td>
  </tr>
</table>
</form>
<div>
Estatísticas para <?= b1n_showDate($d['ini']).' e '.b1n_showDate($d['fin']) ?><br />
Média geral: <b><?= $s[sizeof($s)-1] ?></b>
<br />
</div>

<table>
  <tr><td colspan='10'><a name='0'></a>Mega-Sena</td>
<?
$a = array();
foreach($s as $n){
  $a[$n['number']] = $n['class'];
}
$i=1;
for($i=1;$i<=60;$i++){
  if(($i-1)%10==0){
    echo "</tr><tr>\n";
  }
?>
    <td class='<?= $a[$i] ?>' onclick='b1n_show("<?= $i ?>")'><?= sprintf('%02d', $i) ?></td>
<?
}
?>
  </tr>
  <tr>
    <td class='very_below_avg' colspan='2'>vv</td>
    <td class='below_avg' colspan='2'>v</td>
    <td class='avg' colspan='2'>--</td>
    <td class='above_avg' colspan='2'>^</td>
    <td class='very_above_avg' colspan='2'>^^</td>
  </tr>
</table>
<table>
  <tr>
    <td><a href='<?= b1n_URL.'?p=calc&amp;ini='.$d['ini'].'&amp;fin='.$d['fin'].'&amp;o=number'?>'>N</a></td>
    <td><a href='<?= b1n_URL.'?p=calc&amp;ini='.$d['ini'].'&amp;fin='.$d['fin'].'&amp;o=status'?>'>V</a></td>
    <td><a href='<?= b1n_URL.'?p=calc&amp;ini='.$d['ini'].'&amp;fin='.$d['fin'].'&amp;o=last'?>'>Última</a></td>
  </tr>
<?
foreach($s as $n){
  if(!empty($n['number'])){
    echo "<tr>\n";
    echo "<td class='".$n['class']."' onclick='b1n_inf(".$n['number'].")'><a name='".$n['number']."'></a>".$n['number']."</td>\n";
    echo "<td class='".$n['class']."'>".$n['status']."</td>\n";
    echo "<td class='".$n['class']."'>".$n['last']."</td>\n";
    echo "</tr>\n";
  }
}
?>
</table>
<script type='text/javascript'>
<!--
var g_url = "<?= b1n_URL ?>";
var g_ini = "<?= $d['ini'] ?>";
var g_fin = "<?= $d['fin'] ?>";
//-->
</script>
<script type='text/javascript' src='js/sena.js' defer='defer'></script>
