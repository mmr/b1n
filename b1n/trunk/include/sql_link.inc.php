<?
define('DB_NAME', 'b1n');
define('DB_USER', 'b1n');
define('DB_PASS', 'b1npass');
define('DB_HOST', '');

class sqlLink
{
    var $db_link = NULL;
    var $db_name = NULL;
    var $db_host = NULL;
    var $db_user = NULL;
    var $db_pass = NULL;
    var $error   = NULL;

    function sqlLink()
    {
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASS;
        $this->db_host = DB_HOST;

        $i = 0;
        while((! $this->connect()) && ($i < 3))
        {
            $i++;
            sleep(1);
        }
    }

    function getLink()
    {
        return $this->db_link;
    }

    function getDb()
    {
        return $this->db_name;
    }

    function connect()
    {
        if($this->isConnected())
        { 
            $this->setError("sqLink->connect(0): Already connected.");    
            return false; 
        }

        if($this->db_host)
        {
            $this->db_host = "host = " . $this->db_host;
        }

        $this->db_link = pg_connect($this->db_host . ' dbname = ' . $this->db_name . ' user = ' . $this->db_user . ' password = ' . $this->db_pass);
        
        if($this->db_link)
            return true;
        else
        { 
            pg_close($this->db_link);
            $this->setError("Error: sqLink->connect(2) PSQL - ". pg_ErrorMessage($this->db_link));
            return false; 
        }
    }

    function isConnected()
    {
        if($this->db_link) return true;
        else return false;
    }

    function squery($query)
    {
        if(! $query)
            return false;

        //print "<br>" . $query . "<br>";

        if(! $this->isConnected()) 
        {
            $this->setError("PostgreSQL NOT CONNECTED");
            return false;
        }
        $result = @pg_exec($this->db_link, $query . " LIMIT 1");
        if(is_bool($result))
            return $result;

        if((pg_numrows($result) > 0) && ($aux = pg_fetch_array($result, 0)))
            return $aux;
        else
            return true;
    }

    function query($query)
    {
        if(!$query)
            return false;

        //print "<br>" . $query . "<br>";

        if(!$this->isConnected())
        {
            $this->setError("PostgreSQLL NOT CONNECTED");
            return false;
        }

        $result = @pg_exec($this->db_link, $query);

        if(is_bool($result))
            return $result;

        if((pg_numrows($result) > 0))
        {
            $num = pg_numrows($result);

            for($i = 0;$i<$num;$i++)
                $row[ ] = pg_fetch_array($result,$i);

            return $row;
        }
        return true;//array();
    }

    function closeLink()
    {
        if(! is_null($this->db_link))
            return pg_close($this->db_link);

        return false;
    }

    function getError()
    {
        $e = $this->error;
        $this->error = NULL;
        return $e;
    }

    function setError($e)
    {
        print "<h1>" . $e . "</h1><br>";
        $this->error = $e;
    }
}

?>
