<?
/* $Id: select.inc.php,v 1.25 2002/07/15 14:10:34 binary Exp $ */

function gera_select_from_hash_tv($hash, $selected, $params)
{
    if (!is_array($selected))
        $selected = array();

    print "<select";
    foreach($params as $param => $value)
    {
	    print (" ".$param);
	    if ($value != "") 
	        print ("='".in_html($value)."'");
    }
    print ">\n";

    print "<option value=''>---</option>";

    if (is_array($hash))
    {
        foreach ($hash as $texto => $value)
        {
	        print "  <option value='".in_html($value)."'";
	        if (in_array($value, $selected))
                    print " selected";
	        print ">".in_html($texto)."</option>\n";
        }
    }
    print "</select>";
}

function gera_select_from_hash_vt($hash, $selected, $params)
{
    if (!is_array($selected))
        $selected = array();

    print "<select";
    foreach($params as $param => $value)
    {
	    print (" ".$param);
	    if ($value != "") 
	        print ("='".in_html($value)."'");
    }
    print ">\n";

    print "<option value=''>---</option>";

    if (is_array($hash))
    {
        foreach ($hash as $value => $texto)
        {
	        print "  <option value='".in_html($value)."'";
	        if (in_array($value, $selected))
                    print " selected";
	        print ">".in_html($texto)."</option>\n";
        }
    }
    print "</select>";
}

function gera_select_estado($selected, $params=array())
{
    if(!is_array($selected) && $selected != "")
        $selected = array($selected);
    //else
    //    $selected = array("SP");

    $estados = array
    (
        "AC" => "Acre",
        "AL" => "Alagoas", 
        "AM" => "Amazonas",
        "AP" => "Amapá",
        "BA" => "Bahia",
        "CE" => "Ceará",
        "DF" => "Distrito Federal",
        "ES" => "Espírito Santo",
        "GO" => "Goiás",
        "MA" => "Maranhão",
        "MG" => "Minas Gerais",
        "MT" => "Mato Grosso",
        "MS" => "Mato Grosso do Sul",
        "PA" => "Pára",
        "PB" => "Paraíba",
        "PE" => "Pernambuco",
        "PI" => "Piauí",
        "PR" => "Paraná",
        "RJ" => "Rio de Janeiro",
        "RN" => "Rio Grande do Norte",
        "RO" => "Rondônia",
        "RR" => "Roraima",
        "RS" => "Rio Grande do Sul",
        "SC" => "Santa Catarina",
        "SE" => "Sergipe",
        "SP" => "São Paulo",
        "TO" => "Tocantins"
    );

    gera_select_from_hash_vt($estados, $selected, $params);
}

function gera_select_from_list($list, $selected, $params)
{
    if (!is_array($selected))
        $selected = array();

    print "<select";

    foreach($params as $param => $value)
    {
	    print (" ".$param);
	    if ($value != "") 
	        print ("='".in_html($value)."'");
    }
    print ">\n";

    print "<option value=''>---</option>";

    if (is_array($list))
    {
        foreach ($list as $value)
        {
	        print "  <option value='".in_html($value)."'";
	        if (in_array($value, $selected))
                    print " selected";
	        print ">".in_html($value)."</option>\n";
        }
    }
    print "</select>";
}

function gera_select_from_result($result, $selected, $params, $multiple=0)
{
    if (!is_array($selected))
        $selected = array($selected);

    print "<select";
    if (is_array($params))
    {
        foreach($params as $param => $value)
        {
	        print (" ".$param);

	        if ($value != "") 
	            print ("='".in_html($value)."'");
        }
    }
    print ">\n";

    if( $multiple == 0 )
        print " <option value=''>---</option>\n";

    if (is_array($result))
    {
        foreach ($result as $item)
        {
	        print "  <option value='".in_html($item["value"])."'";
	        if (in_array($item["value"], $selected))
                    print " selected";
	        print ">".in_html($item["texto"])."</option>\n";
        }
    }
    print "</select>";
}

function gera_select_g($sql, $value, $texto, $tabela, $selected="", $params="", $multiple=0)
{
    $query = "
        SELECT
            DISTINCT " . $value . " AS value,
            " . $texto . " AS texto
        FROM
            " . $tabela . "
        ORDER BY
            " . $texto;

    gera_select_from_result($sql->query($query), $selected, $params, $multiple);
}

function consulta_select_g($sql, $value, $texto, $tabela, $selected)
{
    $query = "
        SELECT
            " . $value . ",
            " . $texto . "
        FROM
            " . $tabela . "
        WHERE 
            " . $value . " = '" . $selected . "'";
    
    $res = $sql->squery($query);

    return $res["$texto"];
}

/* selected recebe array com dd, mm e aaaa */
function gera_select_data($nome, $array_data=array(), $ano_inicio = "", $ano_fim = "")
{
    $ano_atual = date("Y", time());

    if($ano_inicio == "")
        $ano_inicio = $ano_atual - 2;

    if($ano_fim == "")
        $ano_fim = $ano_atual + 6;

    if (sizeof($array_data) < 2)
    {
        list( $array_data[ 'dia' ], $array_data[ 'mes' ], $array_data[ 'ano' ] ) = split( ':', date( "d:m:Y" ) );
    }
 
    $params = array("name" => $nome.'[dia]', "class" => "text");
    $hash = array();

    for($i=1; $i<32; $i++) 
        $hash[$i] = $i;
    gera_select_from_hash_vt($hash, array($array_data["dia"]), $params);
    print "&nbsp;/&nbsp;";

    $params = array("name" => $nome.'[mes]', "class" => "text");
    $hash = array();

    for($i=1; $i<13; $i++) 
        $hash[$i] = $i;
    gera_select_from_hash_vt($hash, array($array_data["mes"]), $params);
    print "&nbsp;/&nbsp;";
     
    $params = array("name" => $nome.'[ano]', "class" => "text");
    $hash = array();

    for($i=$ano_inicio; $i <= $ano_fim; $i++) 
        $hash[$i] = $i;
    gera_select_from_hash_vt($hash, array($array_data["ano"]), $params);
}

function gera_select_hora($nome, $array_hora=array())
{
    if (sizeof($array_hora) <= 1 )
        $array_hora = array("hor" => date("H",time()), "min" => date("i",time()));

    $params = array("name" => $nome.'[hor]', "class" => "text");
    $hash = array();

    for($i=0; $i<=23; $i++) 
        $hash[$i] = sprintf( "%02d", $i );

    gera_select_from_hash_vt($hash, array($array_hora["hor"]), $params);
    print "&nbsp;:&nbsp;";

    $params = array("name" => $nome.'[min]', "class" => "text");
    $hash = array();

    for($i=0; $i<=59; $i++) 
        $hash[$i] = sprintf( "%02d", $i );

    gera_select_from_hash_vt($hash, array($array_hora["min"]), $params);
}

?>
