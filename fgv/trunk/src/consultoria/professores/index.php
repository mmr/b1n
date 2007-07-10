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
extract_request_var("dpt_id",               $dados["dpt_id"]);
extract_request_var("prf_nome",             $dados["prf_nome"]);
extract_request_var("prf_ddd",         $dados["prf_ddd"]);
extract_request_var("prf_ddi",         $dados["prf_ddi"]);
extract_request_var("prf_telefone",         $dados["prf_telefone"]);
extract_request_var("prf_ramal",            $dados["prf_ramal"]);
extract_request_var("prf_fax",              $dados["prf_fax"]);
extract_request_var("prf_celular",          $dados["prf_celular"]);
extract_request_var("prf_email",            $dados["prf_email"]);
extract_request_var("prf_dt_nasci",         $dados["prf_dt_nasci"]);
extract_request_var("prf_ajuda_ej",         $dados["prf_ajuda_ej"]);
$dados = trim_r($dados);

$mod_titulo = "Professores";
$colspan    = "4";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_CST_PROFESSOR_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_professor($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_professor($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_PROFESSOR_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_professor($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_CST_PROFESSOR_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_professor($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_professor($sql, $dados))
            {
                log_fnc($sql, FUNC_CST_PROFESSOR_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_professor($sql, $dados))
        {
            if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_CST_PROFESSOR_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_professor($sql, $dados))
        {
            log_fnc($sql, FUNC_CST_PROFESSOR_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_professor($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CST_PROFESSOR_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_professor($sql, $dados))
    {
        if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CST_PROFESSOR_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CST_PROFESSOR_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CST_PROFESSOR_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
