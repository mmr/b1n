<?
class sqlLink {
	var $link_sql = NULL;
	var $Db;
	var $error;
	var $HOST = "";
	var $USER = '';
	var $PASS = '';
	
	function sqlLink($db,$file)
	{
                include($file);
		$this->Db=$db;
                $this->USER=$USER;
                $this->PASS=$PASS;
                $this->HOST=$HOST;
		$i=0;
		while((!$this->connect())&&($i<3))
		{
			$i++;
			sleep(1);
		}
	}
	function getLink()
	{
		return $this->link_sql;
	}
	function getDb()
	{
		return $this->Db;
	}

	function connect()
	{
		if($this->isConnected())
		{ 
			$this->setError("sqLink->connect(0): Already connected.");	
			return false; 
		}
		
                if($this->HOST)
                {
                    $this->HOST = "host=".$this->HOST;
                }
                    
		$this->link_sql = pg_pconnect($this->HOST." dbname=".$this->Db." user=".$this->USER." password=".$this->PASS);
		
		if($this->link_sql) 
		{
			/* TODO: checar se esta no mesmo db, se nao estiver trocar o db */
			return true;
		}
		else
		{ 
			pg_close($this->link_sql);
			$this->setError("Error: sqLink->connect(2) PSQL - ". pg_ErrorMessage($this->link_sql));
			return false; 
		}
	}
	
	function isConnected()
	{
		if($this->link_sql) return true;
		else return false;
	}

	function squery($query)
        {
	   if(!$query) return false;

           //print "<BR><BR>" . $query . "<BR><BR>";

   	   if(!$this->isConnected()) 
	   {
			$this->setError("PostgreSQL NOT CONNECTED");
			return false;
	   }
	   $result = pg_exec($this->link_sql,$query." LIMIT 1" );
	   if (gettype($result) == "boolean")
	   {
			if ($result == false)
			{
		   	// todo: msg de erro ??!?!?! tirar daqui.
//				echo "WARNING: query returned false. :".pg_ErrorMessage($this->link_sql)."<BR>";
		    	return false;
			}
			else return true;
		}
	   if ((pg_numrows($result)>0) &&($aux = pg_fetch_array($result,0))) return $aux;
	   else return true;//array();
	}

	function query($query)
	{
		if(!$query) return false;

           //print "<BR><BR>" . $query . "<BR><BR>";
		if(!$this->isConnected()) 
		{
			$this->setError("PostgreSQLL NOT CONNECTED");
			return false;
		}
// TODO : colocar @ antes da funcao
		$result = pg_exec($this->link_sql,$query);

		if (is_bool($result))
		{
			if ($result == false)
			{
				// todo: msg de erro ??!?!?! tirar daqui.
//				echo "WARNING: query returned false. :".pg_ErrorMessage($this->link_sql)."<BR>";
				return false;
			}
			else return true;
		}



		if ((pg_numrows($result)>0)&&($aux = pg_fetch_array($result,0)))
		{
			$num = pg_numrows($result);

			for($i=0;$i<$num;$i++)
			{
			    $row[] = pg_fetch_array($result,$i);
			}
			return $row;
		}
		return true;//array();
	}

	function getError()
	{
		$e = $this->error;
		$this->error = NULL;
		return $e;
	}
	
	function setError($s)
	{
		print "<h1>$s</h1><br>";
		$this->error = $s;
	}

	function ListTables()
	{
		print "ListTables not supported with PgSQL\n";
		return false;
	$results = mysql_list_tables($this->Db);
	if (!$results) { return false;}

	$i = 0;
	$data[0] = mysql_num_rows($results);
	while ($i < mysql_num_rows($results)) {
		$data[$i+1] = mysql_tablename($results, $i);
		$i++;
	}
	return $data;

	}
}
	?>
