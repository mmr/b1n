<?
/* $Id: index.php,v 1.5 2002/07/12 19:16:32 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",               $dados["id"]);
extract_request_var("set_id",           $dados["set_id"]);
extract_request_var("cex_id",           $dados["cex_id"]);
extract_request_var("cla_id",           $dados["cla_id"]);
extract_request_var("pat_nome",         $dados["pat_nome"]);
extract_request_var("pat_nome_contato", $dados["pat_nome_contato"]);
extract_request_var("pat_ddd",     $dados["pat_ddd"]);
extract_request_var("pat_ddi",     $dados["pat_ddi"]);
extract_request_var("pat_telefone",     $dados["pat_telefone"]);
extract_request_var("pat_ramal",        $dados["pat_ramal"]);
extract_request_var("pat_fax",          $dados["pat_fax"]);
extract_request_var("pat_email",        $dados["pat_email"]);
extract_request_var("pat_celular",      $dados["pat_celular"]);
extract_request_var("pat_apoiador",     $dados["pat_apoiador"]);
extract_request_var("pat_texto",        $dados["pat_texto"]);

$dados = trim_r($dados);
$mod_titulo = "Patrocinadores";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_MKT_PATROCINADOR_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_patrocinador($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_patrocinador($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_PATROCINADOR_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_patrocinador($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_MKT_PATROCINADOR_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_patrocinador($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_patrocinador($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_PATROCINADOR_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_patrocinador($sql, $dados))
        {
            if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_MKT_PATROCINADOR_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_patrocinador($sql, $dados))
        {
            log_fnc($sql, FUNC_MKT_PATROCINADOR_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_patrocinador($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_MKT_PATROCINADOR_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_patrocinador($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_MKT_PATROCINADOR_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_MKT_PATROCINADOR_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_MKT_PATROCINADOR_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
