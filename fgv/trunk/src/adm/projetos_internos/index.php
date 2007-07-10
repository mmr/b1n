<?
/* $Id: index.php,v 1.1 2002/04/16 20:35:16 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("pin_nome",             $dados["pin_nome"]);
extract_request_var("pin_desc",             $dados["pin_desc"]);
extract_request_var("pin_dt_data",          $dados["pin_dt_data"]);
$dados = trim_r($dados);

$mod_titulo = "Projetos Internos";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_prj_interno($sql, $dados);
        if(!sizeof($error_msgs))
        {
            if (insere_prj_interno($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_prj_interno($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_prj_interno($sql, $dados);
        if(!sizeof($error_msgs))
        {
            if (altera_prj_interno($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_prj_interno($sql, $dados))
        {
            if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_prj_interno($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_prj_interno($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_prj_interno($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_ADM_PROJETO_INTERNO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_PROJETO_INTERNO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
