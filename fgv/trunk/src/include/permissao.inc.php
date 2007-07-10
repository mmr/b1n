<?
/* $Id: permissao.inc.php,v 1.36 2002/11/13 22:47:52 binary Exp $ */

function select_login($sql, $login, $senha)
{
    $sqlquery = "
        SELECT
            mem_id,
            mem_nome,
            mem_email
        FROM
            membro_todos
        WHERE
            mem_login = '" . in_bd($login) . "'
            AND mem_senha = '" . in_bd(criptografa($senha)) . "'";

    $rs = $sql->squery($sqlquery);

    if (! is_array($rs))
        return false;

    $retorno = array("id"    => $rs["mem_id"],
                     "nome"  => $rs["mem_nome"],
                     "email" => $rs["mem_email"]);

    return $retorno;
}

/**
 * Retorna as permissoes de acordo com o membro
 */
function select_permissoes($mem_id)
{
    global $sql;

    $perm = array();

    $sqlquery = "
        SELECT
            fnc_nome
        FROM
            membro_funcao
        WHERE
            mem_id = '" . in_bd($mem_id) . "'";

    $rs = $sql->query($sqlquery);

    if (is_array($rs))
    {
        foreach ($rs as $row)
	    array_push($perm, $row["fnc_nome"]);
    }
		
    return $perm;
}
           

/* Adivinha....  ....  ....sim essa funcao faz o logout!!!! */
function logout($sql)
{
    log_fnc($sql, FUNC_LOGOUT);
    session_destroy();
}

/* retorna true se o login eh efetuado */
/* false caso contrario e nesse caso, a variavel $error_msg contem uma lista com as msgs de erro */
function faz_login($sql, &$error_msg, &$logando)
{
    $error_msg = array();

    session_unset(); /* limpa possiveis sujeiras na secao */

    if ((!extract_request_var("pagina", $pagina)) ||
	(!extract_request_var("acao", $acao))     ||
	(!extract_request_var("login", $login))   ||
	(!extract_request_var("senha", $senha))   ||
	($pagina != "login")                      ||
	($acao   != "login"))
            return false;                                 /* nao veio da pagina de login */
    
    $membro = select_login($sql, $login, $senha);
    if (! $membro)
    {
	    array_push($error_msg, "Login e/ou senha inválidos");
	    return false;                                 /* o membro nao esta no BD (pelo menos nao com essa senha) */
    }

    $membro["permissoes"] = select_permissoes($membro["id"]);

    $_SESSION["membro"] = $membro;
    $logando = 1;
    log_fnc($sql, FUNC_LOGIN);
    return true;
}

/* retorna true se o membro esta logado */
function esta_logado ()
{
    return (session_is_registered("membro"));
}

/* retorna true o membro logado tem a permissao requerida */
function tem_permissao($requerida)
{
    if(!session_is_registered("membro"))
        return false;

    if( ! isset( $_SESSION[ 'membro' ][ 'permissoes' ] ) && is_array( $_SESSION[ 'membro' ][ 'permissoes' ] ) )
        $membro["permissoes"] = select_permissoes($_SESSION[ 'membro' ][ 'id' ]);

    return in_array($requerida, $_SESSION["membro"]["permissoes"]);
}

/* EJ-Geral / ADM */
define("FUNC_ADM_ALUNO_GV_APAGAR",          "adm: aluno gv apagar");
define("FUNC_ADM_ALUNO_GV_INSERIR",         "adm: aluno gv inserir");
define("FUNC_ADM_ALUNO_GV_ALTERAR",         "adm: aluno gv alterar");
define("FUNC_ADM_ALUNO_GV_CONSULTAR",       "adm: aluno gv consultar");
define("FUNC_ADM_ALUNO_GV_LISTAR",          "adm: aluno gv listar");

define("FUNC_ADM_EMPRESA_JUNIOR_APAGAR",    "adm: empresa junior apagar");
define("FUNC_ADM_EMPRESA_JUNIOR_INSERIR",   "adm: empresa junior inserir");
define("FUNC_ADM_EMPRESA_JUNIOR_ALTERAR",   "adm: empresa junior alterar");
define("FUNC_ADM_EMPRESA_JUNIOR_CONSULTAR", "adm: empresa junior consultar");
define("FUNC_ADM_EMPRESA_JUNIOR_LISTAR",    "adm: empresa junior listar");

define("FUNC_ADM_FORNECEDOR_APAGAR",        "adm: fornecedor apagar");
define("FUNC_ADM_FORNECEDOR_INSERIR",       "adm: fornecedor inserir");
define("FUNC_ADM_FORNECEDOR_ALTERAR",       "adm: fornecedor alterar");
define("FUNC_ADM_FORNECEDOR_CONSULTAR",     "adm: fornecedor consultar");
define("FUNC_ADM_FORNECEDOR_LISTAR",        "adm: fornecedor listar");

define("FUNC_ADM_FERRAMENTA_APAGAR",        "adm: ferramenta apagar");
define("FUNC_ADM_FERRAMENTA_INSERIR",       "adm: ferramenta inserir");
define("FUNC_ADM_FERRAMENTA_ALTERAR",       "adm: ferramenta alterar");
define("FUNC_ADM_FERRAMENTA_CONSULTAR",     "adm: ferramenta consultar");
define("FUNC_ADM_FERRAMENTA_LISTAR",        "adm: ferramenta listar");

define("FUNC_ADM_PROJETO_INTERNO_APAGAR",    "adm: projeto interno apagar");
define("FUNC_ADM_PROJETO_INTERNO_INSERIR",   "adm: projeto interno inserir");
define("FUNC_ADM_PROJETO_INTERNO_ALTERAR",   "adm: projeto interno alterar");
define("FUNC_ADM_PROJETO_INTERNO_CONSULTAR", "adm: projeto interno consultar");
define("FUNC_ADM_PROJETO_INTERNO_LISTAR",    "adm: projeto interno listar");

define("FUNC_ADM_FUNCIONARIO_GV_APAGAR",    "adm: funcionario gv apagar");
define("FUNC_ADM_FUNCIONARIO_GV_INSERIR",   "adm: funcionario gv inserir");
define("FUNC_ADM_FUNCIONARIO_GV_ALTERAR",   "adm: funcionario gv alterar");
define("FUNC_ADM_FUNCIONARIO_GV_CONSULTAR", "adm: funcionario gv consultar");
define("FUNC_ADM_FUNCIONARIO_GV_LISTAR",    "adm: funcionario gv listar");

define("FUNC_ADM_ETIQUETAS_CRIAR",          "adm: etiquetas criar" );

/* Cadastros */
define("FUNC_CAD_TIPO_EMAIL_APAGAR",        "cad: tipo email apagar");
define("FUNC_CAD_TIPO_EMAIL_INSERIR",       "cad: tipo email inserir");
define("FUNC_CAD_TIPO_EMAIL_ALTERAR",       "cad: tipo email alterar");
define("FUNC_CAD_TIPO_EMAIL_CONSULTAR",     "cad: tipo email consultar");
define("FUNC_CAD_TIPO_EMAIL_LISTAR",        "cad: tipo email listar");

define("FUNC_CAD_TIPO_TASK_APAGAR",         "cad: tipo task apagar");
define("FUNC_CAD_TIPO_TASK_INSERIR",        "cad: tipo task inserir");
define("FUNC_CAD_TIPO_TASK_ALTERAR",        "cad: tipo task alterar");
define("FUNC_CAD_TIPO_TASK_CONSULTAR",      "cad: tipo task consultar");
define("FUNC_CAD_TIPO_TASK_LISTAR",         "cad: tipo task listar");

define("FUNC_CAD_STATUS_TASK_APAGAR",       "cad: status task apagar");
define("FUNC_CAD_STATUS_TASK_INSERIR",      "cad: status task inserir");
define("FUNC_CAD_STATUS_TASK_ALTERAR",      "cad: status task alterar");
define("FUNC_CAD_STATUS_TASK_CONSULTAR",    "cad: status task consultar");
define("FUNC_CAD_STATUS_TASK_LISTAR",       "cad: status task listar");

define("FUNC_CAD_STATUS_CONTATO_APAGAR",    "cad: status contato apagar");
define("FUNC_CAD_STATUS_CONTATO_INSERIR",   "cad: status contato inserir");
define("FUNC_CAD_STATUS_CONTATO_ALTERAR",   "cad: status contato alterar");
define("FUNC_CAD_STATUS_CONTATO_CONSULTAR", "cad: status contato consultar");
define("FUNC_CAD_STATUS_CONTATO_LISTAR",    "cad: status contato listar");

define("FUNC_CAD_STATUS_EVENTO_APAGAR",     "cad: status cronograma apagar");
define("FUNC_CAD_STATUS_EVENTO_INSERIR",    "cad: status cronograma inserir");
define("FUNC_CAD_STATUS_EVENTO_ALTERAR",    "cad: status cronograma alterar");
define("FUNC_CAD_STATUS_EVENTO_CONSULTAR",  "cad: status cronograma consultar");
define("FUNC_CAD_STATUS_EVENTO_LISTAR",     "cad: status cronograma listar");

define("FUNC_CAD_TIMESHEET_ATIVIDADE_APAGAR",       "cad: timesheet atividade apagar");
define("FUNC_CAD_TIMESHEET_ATIVIDADE_INSERIR",      "cad: timesheet atividade inserir");
define("FUNC_CAD_TIMESHEET_ATIVIDADE_ALTERAR",      "cad: timesheet atividade alterar");
define("FUNC_CAD_TIMESHEET_ATIVIDADE_CONSULTAR",    "cad: timesheet atividade consultar");
define("FUNC_CAD_TIMESHEET_ATIVIDADE_LISTAR",       "cad: timesheet atividade listar");

define("FUNC_CAD_TIMESHEET_SUBATIVIDADE_APAGAR",    "cad: timesheet subatividade apagar");
define("FUNC_CAD_TIMESHEET_SUBATIVIDADE_INSERIR",   "cad: timesheet subatividade inserir");
define("FUNC_CAD_TIMESHEET_SUBATIVIDADE_ALTERAR",   "cad: timesheet subatividade alterar");
define("FUNC_CAD_TIMESHEET_SUBATIVIDADE_CONSULTAR", "cad: timesheet subatividade consultar");
define("FUNC_CAD_TIMESHEET_SUBATIVIDADE_LISTAR",    "cad: timesheet subatividade listar");

define("FUNC_CAD_FERIADO_APAGAR",           "cad: feriado apagar");
define("FUNC_CAD_FERIADO_INSERIR",          "cad: feriado inserir");
define("FUNC_CAD_FERIADO_ALTERAR",          "cad: feriado alterar");
define("FUNC_CAD_FERIADO_CONSULTAR",        "cad: feriado consultar");
define("FUNC_CAD_FERIADO_LISTAR",           "cad: feriado listar");

define("FUNC_CAD_RAMO_APAGAR",              "cad: ramo apagar");
define("FUNC_CAD_RAMO_INSERIR",             "cad: ramo inserir");
define("FUNC_CAD_RAMO_ALTERAR",             "cad: ramo alterar");
define("FUNC_CAD_RAMO_CONSULTAR",           "cad: ramo consultar");
define("FUNC_CAD_RAMO_LISTAR",              "cad: ramo listar");

define("FUNC_CAD_TIPO_SERVICO_APAGAR",      "cad: tipo servico apagar");
define("FUNC_CAD_TIPO_SERVICO_INSERIR",     "cad: tipo servico inserir");
define("FUNC_CAD_TIPO_SERVICO_ALTERAR",     "cad: tipo servico alterar");
define("FUNC_CAD_TIPO_SERVICO_CONSULTAR",   "cad: tipo servico consultar");
define("FUNC_CAD_TIPO_SERVICO_LISTAR",      "cad: tipo servico listar");

define("FUNC_CAD_AVISO_AUTO_LISTAR",        "cad: aviso auto listar");
define("FUNC_CAD_AVISO_AUTO_ALTERAR",       "cad: aviso auto alterar");

define("FUNC_CAD_BACKUP_CRIAR",             "cad: backup criar");
define("FUNC_CAD_BACKUP_RECUPERAR",         "cad: backup recuperar");

define("FUNC_CAD_AREA_APAGAR",              "cad: area apagar");
define("FUNC_CAD_AREA_INSERIR",             "cad: area inserir");
define("FUNC_CAD_AREA_ALTERAR",             "cad: area alterar");
define("FUNC_CAD_AREA_CONSULTAR",           "cad: area consultar");
define("FUNC_CAD_AREA_LISTAR",              "cad: area listar");

define("FUNC_CAD_PLANO_PGTO_APAGAR",        "cad: plano pgto apagar");
define("FUNC_CAD_PLANO_PGTO_INSERIR",       "cad: plano pgto inserir");
define("FUNC_CAD_PLANO_PGTO_ALTERAR",       "cad: plano pgto alterar");
define("FUNC_CAD_PLANO_PGTO_CONSULTAR",     "cad: plano pgto consultar");
define("FUNC_CAD_PLANO_PGTO_LISTAR",        "cad: plano pgto listar");

define("FUNC_CAD_CLASSIFICACAO_APAGAR",     "cad: classificacao apagar");
define("FUNC_CAD_CLASSIFICACAO_INSERIR",    "cad: classificacao inserir");
define("FUNC_CAD_CLASSIFICACAO_ALTERAR",    "cad: classificacao alterar");
define("FUNC_CAD_CLASSIFICACAO_CONSULTAR",  "cad: classificacao consultar");
define("FUNC_CAD_CLASSIFICACAO_LISTAR",     "cad: classificacao listar");

define("FUNC_CAD_SETOR_APAGAR",             "cad: setor apagar");
define("FUNC_CAD_SETOR_INSERIR",            "cad: setor inserir");
define("FUNC_CAD_SETOR_ALTERAR",            "cad: setor alterar");
define("FUNC_CAD_SETOR_CONSULTAR",          "cad: setor consultar");
define("FUNC_CAD_SETOR_LISTAR",             "cad: setor listar");

define("FUNC_CAD_REGIAO_APAGAR",            "cad: regiao apagar");
define("FUNC_CAD_REGIAO_INSERIR",           "cad: regiao inserir");
define("FUNC_CAD_REGIAO_ALTERAR",           "cad: regiao alterar");
define("FUNC_CAD_REGIAO_CONSULTAR",         "cad: regiao consultar");
define("FUNC_CAD_REGIAO_LISTAR",            "cad: regiao listar");

define("FUNC_CAD_GRUPO_APAGAR",             "cad: grupo apagar");
define("FUNC_CAD_GRUPO_INSERIR",            "cad: grupo inserir");
define("FUNC_CAD_GRUPO_ALTERAR",            "cad: grupo alterar");
define("FUNC_CAD_GRUPO_CONSULTAR",          "cad: grupo consultar");
define("FUNC_CAD_GRUPO_LISTAR",             "cad: grupo listar");

define("FUNC_CAD_CARGO_GV_APAGAR",          "cad: cargo ej apagar");
define("FUNC_CAD_CARGO_GV_INSERIR",         "cad: cargo ej inserir");
define("FUNC_CAD_CARGO_GV_ALTERAR",         "cad: cargo ej alterar");
define("FUNC_CAD_CARGO_GV_CONSULTAR",       "cad: cargo ej consultar");
define("FUNC_CAD_CARGO_GV_LISTAR",          "cad: cargo ej listar");

define("FUNC_CAD_CARGO_EXT_APAGAR",         "cad: cargo externo apagar");
define("FUNC_CAD_CARGO_EXT_INSERIR",        "cad: cargo externo inserir");
define("FUNC_CAD_CARGO_EXT_ALTERAR",        "cad: cargo externo alterar");
define("FUNC_CAD_CARGO_EXT_CONSULTAR",      "cad: cargo externo consultar");
define("FUNC_CAD_CARGO_EXT_LISTAR",         "cad: cargo externo listar");

/* Consultoria */
define("FUNC_CST_PROFESSOR_APAGAR",         "cst: professor apagar");
define("FUNC_CST_PROFESSOR_INSERIR",        "cst: professor inserir");
define("FUNC_CST_PROFESSOR_ALTERAR",        "cst: professor alterar");
define("FUNC_CST_PROFESSOR_CONSULTAR",      "cst: professor consultar");
define("FUNC_CST_PROFESSOR_LISTAR",         "cst: professor listar");

define("FUNC_CST_DEPARTAMENTO_APAGAR",      "cst: departamento apagar");
define("FUNC_CST_DEPARTAMENTO_INSERIR",     "cst: departamento inserir");
define("FUNC_CST_DEPARTAMENTO_ALTERAR",     "cst: departamento alterar");
define("FUNC_CST_DEPARTAMENTO_CONSULTAR",   "cst: departamento consultar");
define("FUNC_CST_DEPARTAMENTO_LISTAR",      "cst: departamento listar");

define("FUNC_CST_CONSULTORIA_APAGAR",    "cst: consultoria apagar");
define("FUNC_CST_CONSULTORIA_INSERIR",   "cst: consultoria inserir");
define("FUNC_CST_CONSULTORIA_ALTERAR",   "cst: consultoria alterar");
define("FUNC_CST_CONSULTORIA_CONSULTAR", "cst: consultoria consultar");
define("FUNC_CST_CONSULTORIA_LISTAR",    "cst: consultoria listar");

define("FUNC_CST_CONSULTORIA_PROFESSOR_APAGAR",    "cst: consultoria professor apagar");
define("FUNC_CST_CONSULTORIA_PROFESSOR_INSERIR",   "cst: consultoria professor inserir");
define("FUNC_CST_CONSULTORIA_PROFESSOR_ALTERAR",   "cst: consultoria professor alterar");
define("FUNC_CST_CONSULTORIA_PROFESSOR_CONSULTAR", "cst: consultoria professor consultar");
define("FUNC_CST_CONSULTORIA_PROFESSOR_LISTAR",    "cst: consultoria professor listar");

define("FUNC_CST_CONSULTORIA_ATIVIDADE_APAGAR",    "cst: consultoria atividade apagar");
define("FUNC_CST_CONSULTORIA_ATIVIDADE_INSERIR",   "cst: consultoria atividade inserir");
define("FUNC_CST_CONSULTORIA_ATIVIDADE_ALTERAR",   "cst: consultoria atividade alterar");
define("FUNC_CST_CONSULTORIA_ATIVIDADE_CONSULTAR", "cst: consultoria atividade consultar");
define("FUNC_CST_CONSULTORIA_ATIVIDADE_LISTAR",    "cst: consultoria atividade listar");

define("FUNC_CST_CONSULTORIA_ETAPA_APAGAR",    "cst: consultoria etapa apagar");
define("FUNC_CST_CONSULTORIA_ETAPA_INSERIR",   "cst: consultoria etapa inserir");
define("FUNC_CST_CONSULTORIA_ETAPA_ALTERAR",   "cst: consultoria etapa alterar");
define("FUNC_CST_CONSULTORIA_ETAPA_CONSULTAR", "cst: consultoria etapa consultar");
define("FUNC_CST_CONSULTORIA_ETAPA_LISTAR",    "cst: consultoria etapa listar");

define("FUNC_CST_CONSULTORIA_TIPO_PROJETO_APAGAR",    "cst: consultoria tipo projeto apagar");
define("FUNC_CST_CONSULTORIA_TIPO_PROJETO_INSERIR",   "cst: consultoria tipo projeto inserir");
define("FUNC_CST_CONSULTORIA_TIPO_PROJETO_ALTERAR",   "cst: consultoria tipo projeto alterar");
define("FUNC_CST_CONSULTORIA_TIPO_PROJETO_CONSULTAR", "cst: consultoria tipo projeto consultar");
define("FUNC_CST_CONSULTORIA_TIPO_PROJETO_LISTAR",    "cst: consultoria tipo projeto listar");

define("FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_APAGAR",    "cst: consultoria consultor reuniao apagar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_INSERIR",   "cst: consultoria consultor reuniao inserir");
define("FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_ALTERAR",   "cst: consultoria consultor reuniao alterar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_CONSULTAR", "cst: consultoria consultor reuniao consultar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_LISTAR",    "cst: consultoria consultor reuniao listar");

define("FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_APAGAR",    "cst: consultoria consultor projeto apagar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_INSERIR",   "cst: consultoria consultor projeto inserir");
define("FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_ALTERAR",   "cst: consultoria consultor projeto alterar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_CONSULTAR", "cst: consultoria consultor projeto consultar");
define("FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_LISTAR",    "cst: consultoria consultor projeto listar");

define("FUNC_CST_CONSULTORIA_COBRANCA_APAGAR",    "cst: consultoria cobranca apagar");
define("FUNC_CST_CONSULTORIA_COBRANCA_INSERIR",   "cst: consultoria cobranca inserir");
define("FUNC_CST_CONSULTORIA_COBRANCA_ALTERAR",   "cst: consultoria cobranca alterar");
define("FUNC_CST_CONSULTORIA_COBRANCA_CONSULTAR", "cst: consultoria cobranca consultar");
define("FUNC_CST_CONSULTORIA_COBRANCA_LISTAR",    "cst: consultoria cobranca listar");

define("FUNC_CST_CONSULTORIA_BRINDE_APAGAR",    "cst: consultoria brinde apagar");
define("FUNC_CST_CONSULTORIA_BRINDE_INSERIR",   "cst: consultoria brinde inserir");
define("FUNC_CST_CONSULTORIA_BRINDE_ALTERAR",   "cst: consultoria brinde alterar");
define("FUNC_CST_CONSULTORIA_BRINDE_CONSULTAR", "cst: consultoria brinde consultar");
define("FUNC_CST_CONSULTORIA_BRINDE_LISTAR",    "cst: consultoria brinde listar");

define("FUNC_CST_CLIENTE_APAGAR",    "cst: cliente apagar");
define("FUNC_CST_CLIENTE_INSERIR",   "cst: cliente inserir");
define("FUNC_CST_CLIENTE_ALTERAR",   "cst: cliente alterar");
define("FUNC_CST_CLIENTE_CONSULTAR", "cst: cliente consultar");
define("FUNC_CST_CLIENTE_LISTAR",    "cst: cliente listar");

define("FUNC_CST_TIPO_PROJETO_APAGAR",    "cst: tipo projeto apagar");
define("FUNC_CST_TIPO_PROJETO_INSERIR",   "cst: tipo projeto inserir");
define("FUNC_CST_TIPO_PROJETO_ALTERAR",   "cst: tipo projeto alterar");
define("FUNC_CST_TIPO_PROJETO_CONSULTAR", "cst: tipo projeto consultar");
define("FUNC_CST_TIPO_PROJETO_LISTAR",    "cst: tipo projeto listar");

/* Marketing */
define("FUNC_MKT_EVENTO_APAGAR",        "mkt: evento apagar");
define("FUNC_MKT_EVENTO_INSERIR",       "mkt: evento inserir");
define("FUNC_MKT_EVENTO_LISTAR",        "mkt: evento listar");
define("FUNC_MKT_EVENTO_PO_ALTERAR",    "mkt: evento alterar parte organizacional");
define("FUNC_MKT_EVENTO_PP_ALTERAR",    "mkt: evento alterar parte publica");

define("FUNC_MKT_PATROCINADOR_APAGAR",      "mkt: patrocinador apagar");
define("FUNC_MKT_PATROCINADOR_INSERIR",     "mkt: patrocinador inserir");
define("FUNC_MKT_PATROCINADOR_ALTERAR",     "mkt: patrocinador alterar");
define("FUNC_MKT_PATROCINADOR_CONSULTAR",   "mkt: patrocinador consultar");
define("FUNC_MKT_PATROCINADOR_LISTAR",      "mkt: patrocinador listar");

define("FUNC_MKT_PALESTRANTE_APAGAR",       "mkt: palestrante apagar");
define("FUNC_MKT_PALESTRANTE_INSERIR",      "mkt: palestrante inserir");
define("FUNC_MKT_PALESTRANTE_ALTERAR",      "mkt: palestrante alterar");
define("FUNC_MKT_PALESTRANTE_CONSULTAR",    "mkt: palestrante consultar");
define("FUNC_MKT_PALESTRANTE_LISTAR",       "mkt: palestrante listar");

define("FUNC_MKT_TIPO_PRODUTO_APAGAR",      "mkt: tipo produto apagar");
define("FUNC_MKT_TIPO_PRODUTO_INSERIR",     "mkt: tipo produto inserir");
define("FUNC_MKT_TIPO_PRODUTO_ALTERAR",     "mkt: tipo produto alterar");
define("FUNC_MKT_TIPO_PRODUTO_CONSULTAR",   "mkt: tipo produto consultar");
define("FUNC_MKT_TIPO_PRODUTO_LISTAR",      "mkt: tipo produto listar");

define("FUNC_MKT_TIPO_EVENTO_APAGAR",       "mkt: tipo evento apagar");
define("FUNC_MKT_TIPO_EVENTO_INSERIR",      "mkt: tipo evento inserir");
define("FUNC_MKT_TIPO_EVENTO_ALTERAR",      "mkt: tipo evento alterar");
define("FUNC_MKT_TIPO_EVENTO_CONSULTAR",    "mkt: tipo evento consultar");
define("FUNC_MKT_TIPO_EVENTO_LISTAR",       "mkt: tipo evento listar");

define("FUNC_MKT_CRITERIO_APAGAR",      "mkt: criterio apagar");           /* evento premio gestao */
define("FUNC_MKT_CRITERIO_INSERIR",     "mkt: criterio inserir");
define("FUNC_MKT_CRITERIO_ALTERAR",     "mkt: criterio alterar");
define("FUNC_MKT_CRITERIO_CONSULTAR",   "mkt: criterio consultar");
define("FUNC_MKT_CRITERIO_LISTAR",      "mkt: criterio listar");

define("FUNC_MKT_CATEGORIA_APAGAR",     "mkt: categoria apagar");
define("FUNC_MKT_CATEGORIA_INSERIR",    "mkt: categoria inserir");
define("FUNC_MKT_CATEGORIA_ALTERAR",    "mkt: categoria alterar");
define("FUNC_MKT_CATEGORIA_CONSULTAR",  "mkt: categoria consultar");
define("FUNC_MKT_CATEGORIA_LISTAR",     "mkt: categoria listar");

define("FUNC_MKT_LOGO_APAGAR",      "mkt: logo apagar");
define("FUNC_MKT_LOGO_INSERIR",     "mkt: logo inserir");
define("FUNC_MKT_LOGO_ALTERAR",     "mkt: logo alterar");
define("FUNC_MKT_LOGO_CONSULTAR",   "mkt: logo consultar");
define("FUNC_MKT_LOGO_LISTAR",      "mkt: logo listar");

define("FUNC_MKT_BRINDE_APAGAR",            "mkt: brinde apagar");
define("FUNC_MKT_BRINDE_INSERIR",           "mkt: brinde inserir");
define("FUNC_MKT_BRINDE_ALTERAR",           "mkt: brinde alterar");
define("FUNC_MKT_BRINDE_CONSULTAR",         "mkt: brinde consultar");
define("FUNC_MKT_BRINDE_LISTAR",            "mkt: brinde listar");

define("FUNC_MKT_TIPO_CONVIDADO_APAGAR",    "mkt: tipo convidado apagar");
define("FUNC_MKT_TIPO_CONVIDADO_INSERIR",   "mkt: tipo convidado inserir");
define("FUNC_MKT_TIPO_CONVIDADO_ALTERAR",   "mkt: tipo convidado alterar");
define("FUNC_MKT_TIPO_CONVIDADO_CONSULTAR", "mkt: tipo convidado consultar");
define("FUNC_MKT_TIPO_CONVIDADO_LISTAR",    "mkt: tipo convidado listar");

/* RH */
define("FUNC_RH_PROCESSO_SELETIVO_APAGAR",      "rh: processo seletivo apagar");
define("FUNC_RH_PROCESSO_SELETIVO_INSERIR",     "rh: processo seletivo inserir");
define("FUNC_RH_PROCESSO_SELETIVO_ALTERAR",     "rh: processo seletivo alterar");
define("FUNC_RH_PROCESSO_SELETIVO_CONSULTAR",   "rh: processo seletivo consultar");
define("FUNC_RH_PROCESSO_SELETIVO_LISTAR",      "rh: processo seletivo listar");

define("FUNC_RH_MEMBRO_APAGAR",     "rh: membro apagar");              /* Controle de membro/ex-membro */
define("FUNC_RH_MEMBRO_INSERIR",    "rh: membro inserir");
define("FUNC_RH_MEMBRO_ALTERAR",    "rh: membro alterar");
define("FUNC_RH_MEMBRO_CONSULTAR",  "rh: membro consultar");
define("FUNC_RH_MEMBRO_LISTAR",     "rh: membro listar");

/* Relatorios */
define("FUNC_REL_CLIENTE",          "rel: cliente consultar" );
define("FUNC_REL_CONSULTORIA",      "rel: consultoria consultar" );
define("FUNC_REL_EVENTO",           "rel: evento consultar" );
define("FUNC_REL_PREMIO",           "rel: premio gestao consultar" );
define("FUNC_REL_MEMBRO",           "rel: membro e ex-membro consultar" );
define("FUNC_REL_EMPRESA_JUNIOR",   "rel: empresa junior consultar" );
define("FUNC_REL_FORNECEDOR",       "rel: fornecedor consultar" );
define("FUNC_REL_PATROCINADOR",     "rel: patrocinador consultar" );
define("FUNC_REL_PALESTRANTE",      "rel: palestrante consultar" );
define("FUNC_REL_PROFESSOR",        "rel: professor consultar" );
define("FUNC_REL_ALUNO_GV",         "rel: aluno da gv consultar" );
define("FUNC_REL_ALUNO_NAO_GV",     "rel: aluno nao gv consultar" );
define("FUNC_REL_TIMESHEET",        "rel: timesheet consultar" );
define("FUNC_REL_P_SELETIVO",       "rel: processo seletivo consultar" );


/* MISC */
define("FUNC_LOGIN",                "login");
define("FUNC_LOGOUT",               "logout");

/* Acesso Negado */
define("ACESSO_NEGADO",             "main/denied.php");

?>
