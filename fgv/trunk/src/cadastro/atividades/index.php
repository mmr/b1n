<?
/* $Id: index.php,v 1.1 2002/07/30 13:06:54 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("tat_nome",             $dados["tat_nome"]);
extract_request_var("tat_desc",             $dados["tat_desc"]);
extract_request_var("ts_subatividades",     $dados["ts_subatividades"]);
$dados = trim_r($dados);

$mod_titulo = "Atividades de TimeSheet";
$colspan    = "4";

switch ($subpagina)
{
case "alterar":
    if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_ts_atividade($sql, $dados);
        if(!sizeof($error_msgs))
        {
            if (altera_ts_atividade($sql, $dados))
            {
                log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_ts_atividade($sql, $dados))
        {
            if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_ts_atividade($sql, $dados))
    {
        if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
