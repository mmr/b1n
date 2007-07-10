<?
/* $Id: index.php,v 1.4 2002/07/12 16:06:04 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",               $dados["id"]);
extract_request_var("ram_id",           $dados["ram_id"]);
extract_request_var("cex_id",           $dados["cex_id"]);
extract_request_var("for_nome",         $dados["for_nome"]);
extract_request_var("for_nome_contato", $dados["for_nome_contato"]);
extract_request_var("for_ddi",     $dados["for_ddi"]);
extract_request_var("for_ddd",     $dados["for_ddd"]);
extract_request_var("for_telefone",     $dados["for_telefone"]);
extract_request_var("for_ramal",        $dados["for_ramal"]);
extract_request_var("for_fax",          $dados["for_fax"]);
extract_request_var("for_email",        $dados["for_email"]);
extract_request_var("for_celular",      $dados["for_celular"]);
extract_request_var("for_homepage",     $dados["for_homepage"]);
extract_request_var("for_texto",        $dados["for_texto"]);

$dados = trim_r($dados);
$mod_titulo = "Fornecedores";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_ADM_FORNECEDOR_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_fornecedor($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_fornecedor($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_FORNECEDOR_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_fornecedor($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_ADM_FORNECEDOR_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_fornecedor($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_fornecedor($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_FORNECEDOR_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_fornecedor($sql, $dados))
        {
            if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_ADM_FORNECEDOR_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_fornecedor($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_FORNECEDOR_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_fornecedor($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_ADM_FORNECEDOR_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_fornecedor($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_FORNECEDOR_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_ADM_FORNECEDOR_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_FORNECEDOR_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
