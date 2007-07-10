<?
/* $Id: index.php,v 1.1 2002/07/30 13:34:34 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("set_nome",             $dados["set_nome"]);
extract_request_var("set_desc",             $dados["set_desc"]);
$dados = trim_r($dados);

$mod_titulo = "Setores";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CAD_SETOR_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_setor($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_setor($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_SETOR_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_SETOR_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_setor($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CAD_SETOR_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_setor($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_setor($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_SETOR_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_setor($sql, $dados))
        {
            if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CAD_SETOR_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_setor($sql, $dados))
        {
            log_fnc($sql, FUNC_CAD_SETOR_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_setor($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CAD_SETOR_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_setor($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CAD_SETOR_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CAD_SETOR_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CAD_SETOR_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
