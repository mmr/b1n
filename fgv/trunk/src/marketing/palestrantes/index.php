<?
/* $Id: index.php,v 1.5 2002/07/12 18:37:58 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",               $dados["id"]);
extract_request_var("cex_id",           $dados["cex_id"]);
extract_request_var("pal_cargo",    $dados["pal_cargo"]);
extract_request_var("pal_nome",         $dados["pal_nome"]);
extract_request_var("pal_nome_contato", $dados["pal_nome_contato"]);
extract_request_var("pal_ddd",          $dados["pal_ddd"]);
extract_request_var("pal_ddi",          $dados["pal_ddi"]);
extract_request_var("pal_telefone",     $dados["pal_telefone"]);
extract_request_var("pal_ramal",        $dados["pal_ramal"]);
extract_request_var("pal_fax",          $dados["pal_fax"]);
extract_request_var("pal_email",        $dados["pal_email"]);
extract_request_var("pal_celular",      $dados["pal_celular"]);
extract_request_var("pal_apoiador",     $dados["pal_apoiador"]);
extract_request_var("pal_curriculo",    $dados["pal_curriculo"]);

$dados = trim_r($dados);
$mod_titulo = "Palestrantes";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_MKT_PALESTRANTE_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_palestrante($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_palestrante($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_PALESTRANTE_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_palestrante($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_MKT_PALESTRANTE_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_palestrante($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_palestrante($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_PALESTRANTE_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_palestrante($sql, $dados))
        {
            if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_MKT_PALESTRANTE_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_palestrante($sql, $dados))
        {
            log_fnc($sql, FUNC_MKT_PALESTRANTE_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_palestrante($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_MKT_PALESTRANTE_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_palestrante($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_MKT_PALESTRANTE_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_MKT_PALESTRANTE_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_MKT_PALESTRANTE_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
