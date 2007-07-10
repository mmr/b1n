<?
/* $Id: index.php,v 1.3 2002/06/21 14:15:25 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("cri_nome",             $dados["cri_nome"]);
extract_request_var("cri_peso",             $dados["cri_peso"]);
extract_request_var("cri_desc",             $dados["cri_desc"]);
$dados = trim_r($dados);

$mod_titulo = "Critérios do Prêmio Gestão";
$colspan    = "5";

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_MKT_CRITERIO_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_criterio($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_criterio($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_CRITERIO_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        limpa_criterio($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_MKT_CRITERIO_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_criterio($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_criterio($sql, $dados))
            {
                log_fnc($sql, FUNC_MKT_CRITERIO_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_criterio($sql, $dados))
        {
            if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_MKT_CRITERIO_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_criterio($sql, $dados))
        {
            log_fnc($sql, FUNC_MKT_CRITERIO_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_criterio($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_MKT_CRITERIO_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_criterio($sql, $dados))
    {
        if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_MKT_CRITERIO_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_MKT_CRITERIO_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_MKT_CRITERIO_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
