<?
/* $Id: index.php,v 1.1 2002/03/18 14:50:55 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("ste_nome",             $dados["ste_nome"]);
extract_request_var("ste_desc",             $dados["ste_desc"]);
$dados = trim_r($dados);

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_status_evento($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_status_evento($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_STATUS_EVENTO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_status_evento($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_status_evento($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_status_evento($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_STATUS_EVENTO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_status_evento($sql, $dados))
        {
            if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_status_evento($sql, $dados))
        {
            log_fnc($sql, FUNC_CAD_STATUS_EVENTO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_status_evento($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_status_evento($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CAD_STATUS_EVENTO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CAD_STATUS_EVENTO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CAD_STATUS_EVENTO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
