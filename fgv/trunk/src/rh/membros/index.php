<?
/* $Id: index.php,v 1.5 2002/07/30 20:22:27 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("agv_id",               $dados["agv_id"]);
extract_request_var("cgv_id",               $dados["cgv_id"]);
extract_request_var("id",                   $dados["id"]);
extract_request_var("mem_login",            $dados["mem_login"]);
extract_request_var("mem_senha",            $dados["mem_senha"]);
extract_request_var("mem_senha2",           $dados["mem_senha2"]);
extract_request_var("mem_vivo",             $dados["mem_vivo"]);
extract_request_var("mem_dt_entrada",       $dados["mem_dt_entrada"]);
extract_request_var("mem_dt_saida",         $dados["mem_dt_saida"]);
extract_request_var("mem_apelido",          $dados["mem_apelido"]);
extract_request_var("mem_cod_banco",        $dados["mem_cod_banco"]);
extract_request_var("mem_ag_banco",         $dados["mem_ag_banco"]);
extract_request_var("mem_cc_banco",         $dados["mem_cc_banco"]);

$dados = trim_r($dados);

$mod_titulo = "Membros / Ex-Membros";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_RH_MEMBRO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_membro($sql, $dados, $subpagina);
        if(!sizeof($error_msgs))
        {
            if (insere_membro($sql, $dados))
            {
                log_fnc($sql, FUNC_RH_MEMBRO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_membro($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_RH_MEMBRO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_membro($sql, $dados, $subpagina);
        if(!sizeof($error_msgs))
        {
            if (altera_membro($sql, $dados))
            {
                log_fnc($sql, FUNC_RH_MEMBRO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_membro($sql, $dados))
        {
            if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_RH_MEMBRO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_membro($sql, $dados))
        {
            log_fnc($sql, FUNC_RH_MEMBRO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_membro($sql, $dados))
    {
        if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_RH_MEMBRO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_membro($sql, $dados))
    {
        if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_RH_MEMBRO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_RH_MEMBRO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_RH_MEMBRO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
