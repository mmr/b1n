<?
/* $Id: index.php,v 1.2 2002/04/30 19:59:08 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("are_id",               $dados["are_id"]);
extract_request_var("frm_nome",             $dados["frm_nome"]);
extract_request_var("frm_desc",             $dados["frm_desc"]);
$dados = trim_r($dados);

$mod_titulo = "Ferramentas";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if(! tem_permissao(FUNC_ADM_FERRAMENTA_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if($acao == "go")
    {
        $error_msgs = valida_ferramenta($dados);
        if(!sizeof($error_msgs))
        {
            if(insere_ferramenta($sql, $dados, $error_msgs))
            {
                log_fnc($sql, FUNC_ADM_FERRAMENTA_INSERIR, $dados["id"]);
                if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_ferramenta($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if(! tem_permissao(FUNC_ADM_FERRAMENTA_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if($acao == "go")
    {
        $error_msgs = valida_ferramenta($dados);
        if(!sizeof($error_msgs))
        {
            if(altera_ferramenta($sql, $dados, $error_msgs))
            {
                log_fnc($sql, FUNC_ADM_FERRAMENTA_ALTERAR, $dados["id"]);
                if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if(!carrega_ferramenta($sql, $dados))
        {
            if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if(! tem_permissao(FUNC_ADM_FERRAMENTA_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if($acao == "go")
    {
        if(apaga_ferramenta($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_FERRAMENTA_APAGAR, $dados["id"]);
            if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if(!carrega_ferramenta($sql, $dados))
    {
        if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if(! tem_permissao(FUNC_ADM_FERRAMENTA_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if(!carrega_ferramenta($sql, $dados))
    {
        if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_FERRAMENTA_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if(! tem_permissao(FUNC_ADM_FERRAMENTA_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_FERRAMENTA_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
