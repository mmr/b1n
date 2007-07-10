<?
// $Id: sqllink.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

class b1n_sqlLink
{
  var $sqllink = NULL;
  var $db      = NULL;

  function b1n_sqlLink()
  {
    $i = 0;
    while((!$this->sqlConnect()) && ($i < 3))
    {
      $i++;
      sleep(1);
    }
  }

  function sqlConnect()
  {
    if($this->sqlIsConnected())
    { 
      return false; 
    }

    require(b1n_PATH_COMMON_LIB . '/sqlconfig.lib.php');
    return
      $this->sqlSetLink(mssql_connect($db_host, $db_user, $db_pass)) &&
      $this->sqlSetDb($db_name);
  }
  
  function sqlIsConnected()
  {
    return $this->sqlGetLink();
  }

  function sqlSingleQuery($query)
  {
    if(!$query)
    {
      return false;
    } 

    $query = str_replace('SELECT', 'SELECT TOP 1', $query);

    if(b1n_DEBUG)
    {
      echo '<pre>QUERY: ' . $query . '</pre>';
    }

    if(!$this->sqlIsConnected())
    {
      return false;
    }

    $result = mssql_query($query, $this->sqlGetLink());
    if(is_bool($result))
    {
      return $result;
    }

    if((mssql_num_rows($result)> 0) && ($aux = mssql_fetch_array($result, MSSQL_ASSOC)))
    {
      return $aux;
    }
    else
    {
      return true;
    }
  }

  function sqlQuery($query, $type = '')
  {
    if(!$query)
    {
      return false;
    }

    if(b1n_DEBUG)
    {
      echo '<pre>QUERY: ' . $query . '</pre>';
    }

    if(!$this->sqlIsConnected())
    {
      return false;
    }

    $result = mssql_query($query, $this->sqlGetLink());

    if(is_bool($result))
    {
      if($result)
      {
        if(empty($type))
        {
          return mssql_rows_affected($this->sqlGetLink());
        }
        else
        {
          return true;
        }
      }
      else
      {
        return false;
      }
    }

    $num = mssql_num_rows($result);

    if($num > 0)
    {
      for($i=0; $i<$num; $i++)
      {
        $row[$i] = mssql_fetch_array($result, MSSQL_ASSOC);
      }

      return $row;
    }
    return true;
  }

  function sqlSetDb($db_name)
  {
    $this->db_name = $db_name;
    return mssql_select_db($db_name, $this->sqlGetLink());
  }

  function sqlSetLink($link)
  {
    return $this->sqllink = $link;
  }

  function sqlGetLink()
  {
    return $this->sqllink;
  }

  function sqlClose()
  {
    return mssql_close($this->sqlGetLink());
  }
}
?>
