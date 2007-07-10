<?
/* $Id: permissao.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function select_login($sql, $login, $senha)
{
    /*
    $sqlquery = "
        SELECT
            mem_id,
            mem_nome,
            mem_email
        FROM
            membro_vivo
        WHERE
            mem_login = '" . in_bd($login) . "'
            AND mem_senha = '" . in_bd(criptografa($senha)) . "'";

    $rs = $sql->squery($sqlquery);

    if (! is_array($rs))
        return false;

    $retorno = array("id"    => $rs["mem_id"],
                     "nome"  => $rs["mem_nome"],
                     "email" => $rs["mem_email"]);
    */

    if( $login == 'binary' && $senha == '123123' )
    {
        return array( 'id'      => 666,
                      'nome'    => 'Marcio Ribeiro',
                      'email'   => 'b1n@b1n.org' );
    }

    return false;
}


/* Adivinha....  ....  ....sim essa funcao faz o logout!!!! */
function logout($sql)
{
    //log_fnc($sql, FUNC_LOGOUT);
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
	    return false;
    }

#    $membro["permissoes"] = select_permissoes($sql, $membro["id"]);

    $_SESSION["membro"] = $membro;
    $logando = 1;
#    log_fnc($sql, FUNC_LOGIN);
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
    if (!session_is_registered("membro"))
	    return false;
    
    return in_array($requerida, $_SESSION["membro"]["permissoes"]);
}
