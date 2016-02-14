<?
$SOURCE = "bot.s";

if(!is_readable($SOURCE))
{
    exit("Nao pode ler arquivo de Bot. (verificar se o arquivo existe e se tem permissoes para acessa-lo)");
}
?>

<html>
<head>
    <title>Assembly IRC Bot</title>
    <style>
    body
    {
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 13px;
        color: black;
        background-color: white;
    }

    code
    {
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 13px;
        color: #ffffff;
        background-color: white;
    }

    a:link 
    {
        text-decoration: none;
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: bold
        color: #0033CC;
    }

    a:visited 
    {
        text-decoration:none;
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: bold
        color: #0033CC;
    }

    a:active 
    {
        text-decoration: none;
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: bold
        color: #00CC33;
    }

    a:hover 
    {
        text-decoration: none;
        font-family: Verdana, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: bold
        color: #55AAFF;
    }
    </style>
</head>

<body bgcolor="white" text="black">
<?
if(isset($_GET['source']))
{
    show_source("index.php");
}
else
{
    $cor = array("hexa"        => "orange",
                 "registrador" => "blue",  
                 "mnemonico1"  => "green",
                 "mnemonico2"  => "blue",
                 "label"       => "red",
                 "variavel"    => "green",
                 "comentario"  => "gray");

    $cont = implode("", @file($SOURCE));

    // HTML
    $cont = htmlspecialchars($cont);

    // Syntax HighLight
    $cont = preg_replace("/(\s*##?\s[^\n]*)/", "<font color='" . $cor[ 'comentario' ] . "'>$1</font>", $cont);
    $cont = ereg_replace('(\$[[:digit:]]x?[[:alnum:]]*)', '<font color="' . $cor[ 'hexa' ] . '">\1</font>', $cont);
    $cont = ereg_replace('(%e[[:alpha:]]+)', '<font color="' . $cor[ 'registrador' ] . '">\0</font>', $cont);
    $cont = ereg_replace('[[:space:]]+(mov[bwl]|push[bwl]|add[bwl]|cmp)[[:space:]]', '<font color="' . $cor[ 'mnemonico1' ]  . '">\0</font>', $cont);
    $cont = ereg_replace('([[:space:]]+)([a-zA-Z_]+:)', '<font color="' . $cor[ 'label' ]  . '">\0</font>', $cont);
    $cont = ereg_replace('([[:space:]]+)(j[[:alpha:]]+|ret|repe|call|int)([[:space:]]*[a-zA-Z_]*)', '\1<font color="' . $cor[ 'mnemonico2' ]  . '">\2</font><font color="' . $cor[ 'label' ] . '">\3</font>', $cont);
    $cont = ereg_replace('\$[[:alpha:]][a-zA-Z_0-9]*', '<font color="' . $cor[ 'variavel' ]  . '">\0</font>', $cont);
?>
    <p align='center'><a href='?source=bla'>Source dessa página</a></p>
    <pre>
    <?= $cont ?>
    </pre>
    <p align='center'><a href='?source=bla'>Source dessa página</a></p>
<?
}
?>
</body>
</html>
