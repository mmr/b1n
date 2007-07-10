<?
/* $Id: index.php,v 1.5 2002/07/12 16:06:15 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                       $dados["id"]);
extract_request_var("cex_id",                   $dados["cex_id"]);
extract_request_var("reg_id",                   $dados["reg_id"]);
extract_request_var("eju_nome",                 $dados["eju_nome"]);
extract_request_var("eju_razao",                $dados["eju_razao"]);
extract_request_var("eju_endereco",             $dados["eju_endereco"]);
extract_request_var("eju_bairro",               $dados["eju_bairro"]);
extract_request_var("eju_cidade",               $dados["eju_cidade"]);
extract_request_var("eju_estado",               $dados["eju_estado"]);
extract_request_var("eju_cep",                  $dados["eju_cep"]);
extract_request_var("eju_nome_contato",         $dados["eju_nome_contato"]);
extract_request_var("eju_celular_contato",      $dados["eju_celular_contato"]);
extract_request_var("eju_ddd",             $dados["eju_ddd"]);
extract_request_var("eju_ddi",             $dados["eju_ddi"]);
extract_request_var("eju_telefone",             $dados["eju_telefone"]);
extract_request_var("eju_ramal",                $dados["eju_ramal"]);
extract_request_var("eju_fax",                  $dados["eju_fax"]);
extract_request_var("eju_email",                $dados["eju_email"]);
extract_request_var("eju_homepage",             $dados["eju_homepage"]);
extract_request_var("eju_faculdade",            $dados["eju_faculdade"]);
extract_request_var("eju_rel_estreita",         $dados["eju_rel_estreita"]);

$dados = trim_r($dados);
$mod_titulo = "Empresas Juniores";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_empresa_junior($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_empresa_junior($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_empresa_junior($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_empresa_junior($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_empresa_junior($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_empresa_junior($sql, $dados))
        {
            if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_empresa_junior($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_empresa_junior($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_empresa_junior($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_ADM_EMPRESA_JUNIOR_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_EMPRESA_JUNIOR_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
