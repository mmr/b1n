<?
/* $Id: index.php,v 1.3 2002/07/30 20:22:33 binary Exp $ */

require_once($suppagina . "/" . $pagina . "/funcoes.inc.php");

/* monta uma estrutura com os dados da busca. */

extract_request_var("busca_campo",          $busca["campo"]);
extract_request_var("busca_texto",          $busca["texto"]);
extract_request_var("busca_qt_por_pagina",  $busca["qt_por_pagina"]);
extract_request_var("busca_pagina_num",     $busca["pagina_num"]);
extract_request_var("busca_ordem",          $busca["ordem"]);

extract_request_var("id",                   $dados["id"]);
extract_request_var("agv_vivo",             $dados["agv_vivo"]);
extract_request_var("agv_matricula",        $dados["agv_matricula"]);
extract_request_var("agv_nome",             $dados["agv_nome"]);
extract_request_var("agv_rg",               $dados["agv_rg"]);
extract_request_var("agv_cpf",              $dados["agv_cpf"]);
extract_request_var("agv_endereco",         $dados["agv_endereco"]);
extract_request_var("agv_bairro",           $dados["agv_bairro"]);
extract_request_var("agv_ddd",              $dados["agv_ddd"]);
extract_request_var("agv_ddi",              $dados["agv_ddi"]);
extract_request_var("agv_telefone",         $dados["agv_telefone"]);
extract_request_var("agv_ramal",            $dados["agv_ramal"]);
extract_request_var("agv_cep",              $dados["agv_cep"]);
extract_request_var("agv_celular",          $dados["agv_celular"]);
extract_request_var("agv_email",            $dados["agv_email"]);
extract_request_var("agv_dt_nasci",         $dados["agv_dt_nasci"]);
extract_request_var("agv_dt_saida",         $dados["agv_dt_saida"]);

// pegando dados da matricula
if( strlen( $dados[ 'agv_matricula' ] ) > 0 )
{
    switch( substr( $dados[ 'agv_matricula' ], 0, 2 ) )
    {
    case "11":
        $dados[ 'agv_curso' ]  = "AE";
        $dados[ 'agv_classe' ] = "1";
        break;
    case "12":
        $dados[ 'agv_curso' ]  = "AE";
        $dados[ 'agv_classe' ] = "2";
        break;
    case "13":
        $dados[ 'agv_curso' ]  = "AE";
        $dados[ 'agv_classe' ] = "3";
        break;
    case "14":
        $dados[ 'agv_curso' ]  = "AP";
        $dados[ 'agv_classe' ] = "-";
        break;
    default:
        $dados[ 'agv_curso' ]  = "...";
        $dados[ 'agv_classe' ] = "...";
        break;
    }

    $dados[ 'agv_ano_entrada' ] = substr( $dados[ 'agv_matricula' ], 2, 2 );
    $dados[ 'agv_ano_entrada' ] += ( $dados[ 'agv_ano_entrada' ] > 40 ? 1900 : 2000 );

    $dados[ 'agv_semestre_entrada' ] = substr( $dados[ 'agv_matricula' ], 4, 1 );

    $dados[ 'agv_semestre_atual' ] = ( date( "Y" ) - $dados[ 'agv_ano_entrada' ] ) * 2;
    $dados[ 'agv_semestre_atual' ]++;

    if( $dados[ 'agv_semestre_entrada' ] == 2 )
        $dados[ 'agv_semestre_atual' ]--;

    if( date( "m" ) > 6 )
        $dados[ 'agv_semestre_atual' ]++;
}

$dados = trim_r($dados);

switch ($subpagina)
{
case "inserir":
    if (! tem_permissao(FUNC_ADM_ALUNO_GV_INSERIR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_aluno_gv($dados);
        if(!sizeof($error_msgs))
        {
            if (insere_aluno_gv($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_ALUNO_GV_INSERIR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR)) 
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }        
        } 
    }
    else
    {
        limpa_aluno_gv($dados);
    }
    include($suppagina . "/" . $pagina . "/inserir.php");
    break;
case "alterar":
    if (! tem_permissao(FUNC_ADM_ALUNO_GV_ALTERAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        $error_msgs = valida_aluno_gv($dados);
        if(!sizeof($error_msgs))
        {
            if (altera_aluno_gv($sql, $dados))
            {
                log_fnc($sql, FUNC_ADM_ALUNO_GV_ALTERAR, $dados["id"]);
                if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
                {
                    include(ACESSO_NEGADO);
                    break;
                }
                log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
                include($suppagina . "/" . $pagina . "/listar.php");
                break;
            }
        }
    }
    else
    {
        if (!carrega_aluno_gv($sql, $dados))
        {
            if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    include($suppagina . "/" . $pagina . "/alterar.php");
    break;
case "apagar":
    if (! tem_permissao(FUNC_ADM_ALUNO_GV_APAGAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if ($acao == "go")
    {
        if (apaga_aluno_gv($sql, $dados))
        {
            log_fnc($sql, FUNC_ADM_ALUNO_GV_APAGAR, $dados["id"]);
            if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
            {
                include(ACESSO_NEGADO);
                break;
            }
            log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
            include($suppagina . "/" . $pagina . "/listar.php");
            break;
        }
    }
    if (!carrega_aluno_gv($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }   
    include($suppagina . "/" . $pagina . "/apagar.php");
    break;
case "consultar":
    if (! tem_permissao(FUNC_ADM_ALUNO_GV_CONSULTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    if (!carrega_aluno_gv($sql, $dados))
    {
        if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
        {
            include(ACESSO_NEGADO);
            break;
        }
        log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
        include($suppagina . "/" . $pagina . "/listar.php");
        break;
    }
    log_fnc($sql, FUNC_ADM_ALUNO_GV_CONSULTAR, $dados["id"]);
    include($suppagina . "/" . $pagina . "/consultar.php");
    break;
default:
    if (! tem_permissao(FUNC_ADM_ALUNO_GV_LISTAR))
    {
        include(ACESSO_NEGADO);
        break;
    }
    log_fnc($sql, FUNC_ADM_ALUNO_GV_LISTAR);
    include($suppagina . "/" . $pagina . "/listar.php");
}
?>
