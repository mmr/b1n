<?
$query = (isset($_REQUEST['query'])?$_REQUEST['query']:'');
echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' '/comum/dtd/xhtml11.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' >
<head>
  <title>Query Builder</title>
  <link rel='stylesheet' href='/comum/css/css.css' />
  <script type='text/javascript'>
  <!--
  var tab = false;
  //-->
  </script>
</head>
<body>
<h1>Query Builder 0.1</h1>
<form method='post' action='<?= $_SERVER['SCRIPT_NAME'] ?>'>
<table>
  <tr>
    <td>Query</td>
    <td>
      <textarea name='query' rows='20' cols='70' wrap='virtual' onblur='if(tab){this.focus();this.value+="  ";}' onkeydown='//tab = (window.event.keyCode == 9);'><?= htmlspecialchars($query) ?></textarea>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
      <input type='submit' value=' Go! ' />
    </td>
  </tr>
</table>
</form>
<hr />
<?
if(!empty($query))
{
  $query = trim($query);
  $sql = pg_connect('dbname=tosto3 user=tosto password=tospass');
  #$sql = pg_connect('dbname=mel user=mel password=melpass');
  #$sql = pg_connect('dbname=vamp user=vamp password=vampass');
  $ret = pg_query($sql, $query);

  if(is_bool($ret))
  {
    echo pg_affected_rows($ret) . ' afetados.';
  }

  $num = pg_num_rows($ret);

  if($num>0)
  {
    echo '<table border=1><tr>';

    $first = pg_fetch_array($ret, 0, PGSQL_ASSOC);
    $i = 0;
    foreach($first as $k => $v)
    {
      echo '<td><b>'.$k.'</b></td>';
      $i++;
    }
    echo '</tr>';

    for($i=0; $i<$num; $i++)
    {
      echo '<tr>';
      $x = pg_fetch_array($ret, $i, PGSQL_ASSOC);
      foreach($x as $v)
      {
        echo '<td>' . (empty($v)?'&nbsp;':$v) . '</td>';
      }
      echo '</tr>';
    }
    echo '</table>';
  }
  pg_close($sql);
}
?>
</body>
</html>
