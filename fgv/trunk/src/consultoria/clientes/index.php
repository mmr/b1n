<?
/* $Id: index.php,v 1.8 2002/07/12 18:08:36 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",              $busca["campo"]);
extract_request_var("busca_texto",              $busca["texto"]);
extract_request_var("busca_qt_por_pagina",      $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",         $busca["pagina_num"]);
extract_request_var("busca_ordem",              $busca["ordem"]);

extract_request_var("cex_id",                   $dados["cex_id"]);
extract_request_var("ram_id",                   $dados["ram_id"]);
extract_request_var("reg_id",                   $dados["reg_id"]);
extract_request_var("id",                       $dados["id"]);
extract_request_var("cli_nome",                 $dados["cli_nome"]);
extract_request_var("cli_razao",                $dados["cli_razao"]);
extract_request_var("cli_faturamento",          $dados["cli_faturamento"]);
extract_request_var("cli_endereco",             $dados["cli_endereco"]);
extract_request_var("cli_bairro",               $dados["cli_bairro"]);
extract_request_var("cli_cidade",               $dados["cli_cidade"]);
extract_request_var("cli_estado",               $dados["cli_estado"]);
extract_request_var("cli_cep",                  $dados["cli_cep"]);
extract_request_var("cli_nome_contato",         $dados["cli_nome_contato"]);
extract_request_var("cli_celular_contato",      $dados["cli_celular_contato"]);
extract_request_var("cli_ddd",                  $dados["cli_ddd"]);
extract_request_var("cli_ddi",                  $dados["cli_ddi"]);
extract_request_var("cli_telefone",             $dados["cli_telefone"]);
extract_request_var("cli_ramal",                $dados["cli_ramal"]);
extract_request_var("cli_email",                $dados["cli_email"]);
extract_request_var("cli_fax",                  $dados["cli_fax"]);
extract_request_var("cli_homepage",             $dados["cli_homepage"]);
extract_request_var("cli_conheceu_ej",          $dados["cli_conheceu_ej"]);

/* Cobranca */
extract_request_var("cli_cob_cnpj",             $dados["cli_cob_cnpj"]);
extract_request_var("cli_cob_resp",             $dados["cli_cob_resp"]);
extract_request_var("cli_cob_contato",          $dados["cli_cob_contato"]);
extract_request_var("cli_cob_endereco",         $dados["cli_cob_endereco"]);
extract_request_var("cli_cob_cep",              $dados["cli_cob_cep"]);
extract_request_var("cli_cob_ddd",              $dados["cli_cob_ddd"]);
extract_request_var("cli_cob_ddi",              $dados["cli_cob_ddi"]);
extract_request_var("cli_cob_telefone",         $dados["cli_cob_telefone"]);
extract_request_var("cli_cob_fax",              $dados["cli_cob_fax"]);

$dados = trim_r($dados);
$mod_titulo = "Cliente";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CST_CLIENTE_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_cliente($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_cliente($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_CLIENTE_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_cliente($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CST_CLIENTE_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_cliente($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_cliente($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_CLIENTE_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_cliente($sql, $dados))
        {
            if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CST_CLIENTE_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_cliente($sql, $dados))
        {
            log_fnc($sql, FUNC_CST_CLIENTE_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_cliente($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CST_CLIENTE_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_cliente($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CST_CLIENTE_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CST_CLIENTE_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CST_CLIENTE_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
