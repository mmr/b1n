<?
/* $Id: search.lib.php,v 1.4 2002/12/23 13:57:53 binary Exp $ */

define("b1n_DEFAULT_QUANTITY", 20);

function b1n_checkSearch(&$search, $ret, $session_hash_name, $select_first_if_none = true)
{
    if(!isset($search["pg_actual"])  || 
       !b1n_checkNumeric($search["pg_actual"]) || 
       $search["pg_actual"] <= 0)
    {
	$pg_actual = 1;
    }
    else
    {
	$pg_actual = $search["pg_actual"];
    }

    if(!isset($search["search_quantity"])  ||
       !in_array($search["search_quantity"], $ret["possible_quantities"]))
    {
        if(isset($_SESSION["search"][$session_hash_name]["search_quantity"]))
        {
	    $search["search_quantity"] = $_SESSION["search"][$session_hash_name]["search_quantity"];
        }
        else
        {
	    $search["search_quantity"] = b1n_DEFAULT_QUANTITY;
        }
    }

    if(!$search["search_order_type"] == 'ASC' && 
       !$search["search_order_type"] == 'DESC')
    {
        if(isset($_SESSION["search"][$session_hash_name]["search_order_type"]))
        {
            $search["search_order_type"] = $_SESSION["search"][$session_hash_name]["search_order_type"];
        }
        else
        {
            $search["search_order_type"] = 'ASC';
        }
    }

    if(in_array($search["search_field"], $ret["possible_fields"]) &&
       in_array($search["search_order"], $ret["select_fields"]))
    {
        return true;
    }
    else
    {
        if(isset($_SESSION["search"][$session_hash_name]["search_field"]))
        {
            $search["search_field"] = $_SESSION["search"][$session_hash_name]["search_field"];
        }
        else
        {
            if($select_first_if_none)
            {
                $search["search_field"] = array_shift($ret["possible_fields"]);
            }
            else
            {
                $search["search_field"] = "";
            }
        }

        if(isset($_SESSION["search"][$session_hash_name]["search_order"]))
        {
            $search["search_order"] = $_SESSION["search"][$session_hash_name]["search_order"];
        }
        else
        {
            $search["search_order"] = $search["search_field"];
        }

        if(isset($_SESSION["search"][$session_hash_name]["search_text"]))
        {
            $search["search_text"] = $_SESSION["search"][$session_hash_name]["search_text"];
        }

        $search["pg_actual"] = $pg_actual;
    }

    return true;
}

function b1n_search($sql, $search_config, $search, $select_first_if_none = true, $where_plus = '')
{
    $ret["select_fields"]       = $search_config["select_fields"];
    $ret["possible_fields"]     = $search_config["possible_fields"];
    $ret["possible_quantities"] = $search_config["possible_quantities"];

// ---------------------- Checking and Session storing ----------------

    if(!b1n_checkSearch($search, $ret, $search_config["session_hash_name"], $select_first_if_none))
    {
        $ret["search"] = $search;
	return $ret;
    }

    $ret["search"] = $search;
    $_SESSION["search"][$search_config["session_hash_name"]] = $search;

    if(empty($search["search_text"]))
    {
        $isnull = " OR ". $search["search_field"] . " IS NULL";
    }
    else
    {
        $isnull = "";
    }

// ---------------------- WHERE ---------------------------
    if(!empty($search["search_field"]))
    {
        $where = " WHERE " . $search["search_field"] . " ILIKE '%" . b1n_inBd($search["search_text"]) . "%'" . $where_plus . $isnull;
    }
    else
    {
        $where = $where_plus;
    }

// ---------------------- ORDER BY ---------------------------
    if(!empty($search['search_order']))
    {
        $orderby = " ORDER BY LOWER(" . $search['search_order'] . ") ";
        if(!empty($search['search_order_type']))
        {
            $orderby .= " " . $search['search_order_type'];
        }
    }
    else
    {
        $orderby = '';
    }

// ---------------------- LIMIT & OFFSET ----------------
    if($search["search_quantity"] == 'all')    
    {
        $limit = '';
        $ret['pg_actual']   = 1;
        $ret['pg_pages']    = 1;
    }
    else
    {
        $query = "
                SELECT DISTINCT
                    COUNT("   . $search_config["id_field"] . ")
                FROM
                    \"" . $search_config["table"] . "\"" . 
                $where;

        $rs_count = $sql->singleQuery($query);
        $ret["pg_pages"] = max(1, ceil($rs_count["count"] / $search["search_quantity"]));

        if($search["pg_actual"] > $ret["pg_pages"]) 
        {
            $search["pg_actual"] = $ret["pg_pages"];
        }

        $ret["pg_actual"] = $search["pg_actual"];

        $limit = " LIMIT " . $search['search_quantity'] . ' OFFSET ' . (($search['pg_actual'] - 1) * $search['search_quantity']);
    }

// ---------------------- DB Search ---------------------------
    $select_fields = $search_config['id_field'] . " AS id, " . implode(", ", $search_config['select_fields']);
    
    $query = "
        SELECT
            " . $select_fields . "
        FROM
          \"" . $search_config['table'] . "\" " .
        $where . $orderby . $limit;

    $ret["result"] = $sql->query($query);

    return $ret;    
}
?>
