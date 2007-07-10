<?
/* $Id: index.php,v 1.1 2002/07/30 13:34:33 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("cla_nome",             $dados["cla_nome"]);
extract_request_var("cla_desc",             $dados["cla_desc"]);
$dados = trim_r($dados);

$mod_titulo = "Classificações (Patrocinador)";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_classificacao($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_classificacao($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_CLASSIFICACAO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_classificacao($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_classificacao($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_classificacao($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_CLASSIFICACAO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_classificacao($sql, $dados))
        {
            if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_classificacao($sql, $dados))
        {
            log_fnc($sql, FUNC_CAD_CLASSIFICACAO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_classificacao($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_classificacao($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CAD_CLASSIFICACAO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CAD_CLASSIFICACAO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CAD_CLASSIFICACAO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
