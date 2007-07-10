<?
/* $Id: index.php,v 1.1 2002/07/30 13:28:46 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("grp_nome",             $dados["grp_nome"]);
extract_request_var("grp_desc",             $dados["grp_desc"]);
extract_request_var("grp_membros",          $dados["grp_membros"]);
extract_request_var("grp_funcoes",          $dados["grp_funcoes"]);
$dados = trim_r($dados);

$mod_titulo = "Grupos";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CAD_GRUPO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_grupo($sql, $dados);
        if(!sizeof($error_msgs))
        {
            if (insere_grupo($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_GRUPO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_grupo($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CAD_GRUPO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_grupo($sql, $dados);
        if(!sizeof($error_msgs))
        {
            if (altera_grupo($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_GRUPO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_grupo($sql, $dados))
        {
            if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CAD_GRUPO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_grupo($sql, $dados))
        {
            log_fnc($sql, FUNC_CAD_GRUPO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_grupo($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CAD_GRUPO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_grupo($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CAD_GRUPO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CAD_GRUPO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CAD_GRUPO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
