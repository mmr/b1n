<?
/* $Id: index.php,v 1.1 2002/08/05 13:30:21 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("dpt_nome",             $dados["dpt_nome"]);
extract_request_var("dpt_desc",             $dados["dpt_desc"]);
extract_request_var("dpt_andar",            $dados["dpt_andar"]);
extract_request_var("dpt_ramal",            $dados["dpt_ramal"]);
$dados = trim_r($dados);

$mod_titulo = "Departamentos";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CST_DEPARTAMENTO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_departamento($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_departamento($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_DEPARTAMENTO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_departamento($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CST_DEPARTAMENTO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_departamento($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_departamento($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_DEPARTAMENTO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_departamento($sql, $dados))
        {
            if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CST_DEPARTAMENTO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_departamento($sql, $dados))
        {
            log_fnc($sql, FUNC_CST_DEPARTAMENTO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_departamento($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CST_DEPARTAMENTO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_departamento($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CST_DEPARTAMENTO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CST_DEPARTAMENTO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CST_DEPARTAMENTO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>