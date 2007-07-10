<?
/* $Id: sqllink.lib.php,v 1.15 2003/09/02 04:59:18 mmr Exp $ */

require(b1n_LIBPATH . '/.config.lib.php');

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
        $this->db_name = b1n_DB_NAME;
        $this->db_user = b1n_DB_USER;
        $this->db_pass = b1n_DB_PASS;
        $this->db_host = b1n_DB_HOST;

        $i = 0;
        while((! $this->connect())&& ($i < 3))
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
            user_error('sqLink->connect(0): Already connected.');    
            return false; 
        }

        if($this->db_host)
        {
            $this->db_host = "host = " . $this->db_host;
        }

        $this->db_link = pg_connect($this->db_host . " dbname = " . $this->db_name . " user = " . $this->db_user . " password = " . $this->db_pass);
        
        if($this->db_link)
            return true;
        else
        { 
            pg_close($this->db_link);
            user_error("Error: sqLink->connect(2)PSQL - ". pg_ErrorMessage($this->db_link));
            return false; 
        }
    }
    
    function isConnected()
    {
        return $this->db_link;
    }

    function singleQuery($query)
    {
        if(! $query)
        {
            return false;
        } 

        if(b1n_DEBUG_MODE)
        {
            echo "<pre class='debug'>SINGLE: $query LIMIT 1</pre>";
        }

        if(! $this->isConnected())
        {
            user_error("PostgreSQL NOT CONNECTED");
            return false;
        }

        $result = pg_query($this->db_link, $query . " LIMIT 1");
        if(is_bool($result))
        {
            return $result;
        }

        if((pg_num_rows($result)> 0) && ($aux = pg_fetch_array($result, 0, PGSQL_ASSOC)))
        {
            return $aux;
        }
        else
        {
            return true;
        }
    }

    function query($query)
    {
        if(!$query)
        {
            return false;
        } 

        if(b1n_DEBUG_MODE)
        {
            echo "<pre class='debug'>$query</pre>";
        }

        if(!$this->isConnected())
        {
            user_error("PostgreSQLL NOT CONNECTED");
            return false;
        }

        $result = pg_query($this->db_link, $query);

        if(is_bool($result))
        {
            return $result;
        }

        $num = pg_num_rows($result);
        if($num > 0)
        {
            for($i = 0; $i<$num; $i++)
            {
                $row[] = pg_fetch_array($result, $i, PGSQL_ASSOC);
            }

            return $row;
        }
        return true;
    }

    function closeLink()
    {
        if(!is_null($this->db_link))
        {
            return pg_close($this->db_link);
        }

        return false;
    }
}

?>
