<?
/* $Id: index.php,v 1.1 2002/07/30 13:41:47 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("fgv_nome",             $dados["fgv_nome"]);
extract_request_var("fgv_funcao",             $dados["fgv_funcao"]);
$dados = trim_r($dados);

$mod_titulo = "Funcionários da FGV";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_funcionario_gv($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_funcionario_gv($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_funcionario_gv($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_funcionario_gv($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_funcionario_gv($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_funcionario_gv($sql, $dados))
        {
            if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_funcionario_gv($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_funcionario_gv($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_funcionario_gv($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_ADM_FUNCIONARIO_GV_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_FUNCIONARIO_GV_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
