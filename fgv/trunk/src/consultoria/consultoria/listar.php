<?
/* $Id: listar.php,v 1.7 2002/12/17 19:23:36 binary Exp $ */

extract_request_var( "busca_campo_cliente",      $busca_campo_cliente );
extract_request_var( "busca_campo_tipo_projeto", $busca_campo_tipo_projeto );
extract_request_var( "busca_campo_status",       $busca_campo_status );
extract_request_var( "busca_campo_operador",     $busca_campo_operador );

extract_request_var( "busca_texto_cliente",      $busca_texto_cliente );
extract_request_var( "busca_texto_tipo_projeto", $busca_texto_tipo_projeto );

extract_request_var( "busca_pagina_num",            $busca_pagina_num );
extract_request_var( "busca_qt_por_pagina",         $busca_qt_por_pagina );

$busca_qt_por_pagina = QT_POR_PAGINA_DEFAULT;

if( ! consis_inteiro( $busca_pagina_num )  || $busca_pagina_num == 0 )
    $busca_pagina_num = 1;

/* Campos possiveis pra busca */

/*
ram_id - ramo
reg_id - regiao
*/

$possiveis_campos[ 'cliente' ]  = array( "Nome"                  => "cli_nome",
                                         "Razão Social"          => "cli_razao",
                                         "Endereço"              => "cli_endereco",
                                         "Bairro"                => "cli_bairro",
                                         "Cidade"                => "cli_cidade",
                                         "Estado"                => "cli_estado",
                                         "CEP"                   => "cli_cep",
                                         "Telefone"              => "cli_telefone",
                                         "Ramal"                 => "cli_ramal",
                                         "Email"                 => "cli_email",
                                         "Homepage"              => "cli_homepage",
                                         "Como conheceu a EJ"    => "cli_conheceu_ej",
                                         "Faturamento"           => "cli_faturamento",
                                         "Contato - Nome"        => "cli_nome_contato",
                                         "Contato - Celular"     => "cli_celular_contato",
                                         "Cobrança - Contato"    => "cli_cob_contato",
                                         "Cobrança - CNPJ"       => "cli_cob_cnpj",
                                         "Cobrança - Endereço"   => "cli_cob_endereco",
                                         "Cobrança - CEP"        => "cli_cob_cep",
                                         "Cobrança - Telefone"   => "cli_cob_telefone",
                                         "Cobrança - Fax"        => "cli_cob_fax" );


$possiveis_campos[ 'tipo_projeto' ]  = array( "Nome"        => "tpj_nome",
                                              "Descrição"   => "tpj_desc" );

$possiveis_campos[ 'status' ] = array(  "Todos"                         => "",  
                                        "Nova Consultoria"              => CST_NOVA_CONSULTORIA,
                                        "Consultoria Não Confirmada"    => CST_CONSULTORIA_NAO_CONFIRMADA,
                                        "Reunião Marcada"               => CST_REUNIAO_MARCADA,
                                        "Proposta Em Andamento"         => CST_PROPOSTA_EM_ANDAMENTO,
                                        "Proposta Concluída"            => CST_PROPOSTA_CONCLUIDA,
                                        "Reuniao Não Gerou Proposta"    => CST_REUNIAO_NAO_GEROU_PROPOSTA,
                                        "Proposta Enviada"              => CST_PROPOSTA_ENVIADA,
                                        "Stand By"                      => CST_STAND_BY,
                                        "Follow Up"                     => CST_FOLLOW_UP,
                                        "Contrato Em Andamento"         => CST_CONTRATO_EM_ANDAMENTO,
                                        "Projeto Em Andamento"          => CST_PROJETO_EM_ANDAMENTO,
                                        "Projeto Finalizado"            => CST_PROJETO_FINALIZADO   );

$possiveis_campos[ 'operador' ] =   array( ">"  => ">",
                                           ">=" => ">=",
                                           "<"  => "<",
                                           "<=" => "<=",
                                           "="  => "=" );
?>

<center>
  <table border='0' cellspacing='0' cellpadding='0' bgcolor='#000000' width='630'>
    <tr>
      <td>
        <table border='0' cellspacing='1' cellpadding='5' width='100%' class='text'>
          <tr>
            <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17">
              <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Busca
            </td>
          </tr>
          <tr>
            <td class='textb' bgcolor="#ffffff">
              <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                <input type="hidden" name="pagina" value="<?= $pagina ?>" />
                <input type="hidden" name="busca_agora" value="yeah" />
                Cliente
            </td>
            <td class='text' bgcolor="#ffffff">
              <? gera_select_from_hash_tv( $possiveis_campos[ 'cliente' ], array( $busca_campo_cliente ), array( "name" => "busca_campo_cliente" ) ); ?>
            </td>
            <td class='text' bgcolor="#ffffff">
              <? gera_select_from_hash_tv( $possiveis_campos[ 'operador' ], array( $busca_campo_operador ), array( "name" => "busca_campo_operador" ) ); ?>
              <input type='text' name='busca_texto_cliente' value="<?= in_html( $busca_texto_cliente ) ?>" />
            </td>
          </tr>
  
          <tr>
            <td class='textb' bgcolor="#ffffff">Tipo de Projeto</td>
            <td class='text' bgcolor="#ffffff">
              <? gera_select_from_hash_tv( $possiveis_campos[ 'tipo_projeto' ], array( $busca_campo_tipo_projeto ), array( "name" => "busca_campo_tipo_projeto" ) ); ?>
            </td>
            <td class='text' bgcolor="#ffffff"><input type='text' name='busca_texto_tipo_projeto' value="<?= in_html( $busca_texto_tipo_projeto ) ?>" /></td>
          </tr>
  
          <tr>
            <td class='textb' bgcolor="#ffffff">Status</td>
            <td class='text' bgcolor="#ffffff" colspan='2'>
          <? gera_select_from_hash_tv( $possiveis_campos[ 'status' ], array( $busca_campo_status ), array( "name" => "busca_campo_status" ) ); ?>
          </td>
          </tr>
  
          <tr>
            <td class='textb' bgcolor="#ffffff" colspan='3' align='center'>
              <input type="submit" value='&nbsp;Buscar&nbsp;' />
            </td>
          </tr>
          </form>
  
          <tr>
            <td class='textb' bgcolor="#ffffff" colspan='3' align='center'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina" value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina" value="inserir" />
              <input type="hidden" name="tipo_inserir" value="consultoria" />
              <input type="submit" name="ok" value="&nbsp;Inserir Novo&nbsp;" />
            </td>
          </tr>
          </form>

          <tr>
            <td class="text" COLSPAN="4" bgColor="#336699">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<br />

<?
extract_request_var( 'busca_agora', $busca_agora );

if( $busca_agora == 'yeah' || isset( $_SESSION[ 'busca' ][ 'consultoria' ] ) )
{
/* Ja passou pelo form de busca, buscar agora */
    $colspan = 10;
    $where = "";

    if( $busca_agora != "yeah" && isset( $_SESSION[ "busca" ][ "consultoria" ] ) && $_SESSION[ "busca" ][ "consultoria" ] != "" )
        $where = $_SESSION[ "busca" ][ "consultoria" ];
    else
    {
        if( trim( $busca_texto_cliente ) != "" && $busca_campo_cliente != "" )
        {
            if( $busca_campo_cliente == 'cli_faturamento' )
            {
                switch( $busca_campo_operador )
                {
                case ">":
                case ">=":
                case "<":
                case "<=":
                case "=":
                    break;
                default:
                    $busca_campo_operador = ">";
                }

                if( consis_dinheiro( reconhece_dinheiro( $busca_texto_cliente ) ) )
                    $where .= " AND cli_faturamento " . $busca_campo_operador . " '" . in_bd( reconhece_dinheiro( $busca_texto_cliente ) ) . "'";
            }
            else
                $where .= " AND " . $busca_campo_cliente . " ILIKE '%" . in_bd( $busca_texto_cliente ) . "%'";
        }

        if( trim( $busca_texto_tipo_projeto ) != "" && $busca_campo_tipo_projeto != "" )
            $where .= " AND " . $busca_campo_tipo_projeto . " ILIKE '%" . in_bd( $busca_texto_tipo_projeto ) . "%'";

        if( $busca_campo_status != "" )
            $where .= " AND cst_status = '" . in_bd( $busca_campo_status ) . "'";

        $_SESSION[ "busca" ][ "consultoria" ] = $where;
    }

    $query = "
    SELECT 
        COUNT( DISTINCT cst_id )
    FROM
        (
            consultoria
            NATURAL JOIN cliente
        )
        NATURAL LEFT OUTER JOIN
        (
            cst_tpj
            NATURAL JOIN tipo_projeto
        )
    WHERE
        cst_id IS NOT NULL" . $where;

    $rs = $sql->squery( $query );

    $dados[ 'qt_paginas' ] = ( $rs ? ceil( $rs[ 'count' ] / $busca_qt_por_pagina ) : 1 );

    if( $busca_pagina_num > $dados[ 'qt_paginas' ] ) 
        $busca_pagina_num = $dados[ 'qt_paginas' ];

    $query = "
    SELECT
        DISTINCT cst_id,
        cst_nome,
        cli_id,
        cli_nome,
        cli_telefone,
        cli_homepage,
        cst_status
    FROM
        (
            consultoria
            NATURAL JOIN cliente
        )
        NATURAL LEFT OUTER JOIN
        (
            cst_tpj
            NATURAL JOIN tipo_projeto
        )
    WHERE
        cst_id IS NOT NULL" . $where . "
    ORDER BY 
        cst_nome,
        cst_status,
        cli_nome
    LIMIT
        " . $busca_qt_por_pagina . "
    OFFSET
        " . ( abs( ( $busca_pagina_num - 1 ) * $busca_qt_por_pagina ) );

    $rs = $sql->query( $query );
?>

<br />
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
        <tr>
          <td class="textwhitemini" COLSPAN="<?= $colspan ?>" bgColor="#336699" HEIGHT="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
          </td>
        </tr>
<?
        if( is_array( $rs ) )
        {
        ?>
            <tr>
                <td bgcolor="#ffffff" class="textb">Consultoria</td>
                <td bgcolor="#ffffff" class="textb">Cliente</td>
                <td bgcolor="#ffffff" class="textb">Tipo Proj</td>
                <td bgcolor="#ffffff" class="textb">Telefone</td>
                <td bgcolor="#ffffff" class="textb">HomePage</td>
                <td bgcolor="#ffffff" class="textb">Status</td>
                <td bgcolor="#ffffff" class="textb" colspan='3'>Funções</td>
            </tr>

            <?
            foreach( $rs as $cara )
            {
            ?>
                <tr>
                  <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'cst_nome' ] ?></td>
                  <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'cli_nome' ] ?></td>
                  <td bgcolor="#ffffff" class="text">
                  &nbsp;
                <?
                $busca_tipo_projeto = $sql->query( "
                    SELECT DISTINCT
                        tpj_nome
                    FROM
                        cst_tpj
                        NATURAL JOIN tipo_projeto
                    WHERE
                        cst_id = '" . $cara[ 'cst_id' ] . "'
                    ORDER BY
                        tpj_nome" );

                if( is_array( $busca_tipo_projeto ) )
                {
                    print $busca_tipo_projeto[0][ 'tpj_nome' ];
                    array_shift( $busca_tipo_projeto );

                    foreach( $busca_tipo_projeto as $tipo_projeto )
                        print ", " . $tipo_projeto[ 'tpj_nome' ];
                }

                unset( $busca_tipo_projeto );
                unset( $tipo_projeto );
                ?>
                </td>

                <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'cli_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'cli_homepage' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= ucwords( $cara[ 'cst_status' ] ) ?></td>

                <td bgcolor="#ffffff" class="text">
                  <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=consultar&tipo_consultar=consultoria&cst_id=<?= $cara[ 'cst_id' ] ?>">Consultar</a>
                </td>
                <td bgcolor='#ffffff' class='text'>
                  <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=alterar&tipo_alterar=consultoria&cst_id=<?= $cara[ 'cst_id' ] ?>">Editar</a>
                </td>
                <td bgcolor='#ffffff' class='text'>
                  <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=apagar&tipo_apagar=consultoria&cst_id=<?= $cara[ 'cst_id' ] ?>">Excluir</a>
                </td>
            </tr>
            <?
            }

            /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
            if( $dados[ 'qt_paginas' ] > 1 )
            {
            ?>
                <tr>
                    <td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff">
            <?
                /* se a pagina atual for maior que 1, mostrar seta pra voltar */
                if( $busca_pagina_num > 1 )
                {
                ?>
                    <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ( $busca_pagina_num - 1 ) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
                }

                for( $i = 1; $i <= $dados[ 'qt_paginas' ]; $i++ )
                { 
                    if( $i == $busca_pagina_num )
	                print "<font color='#000000' face='verdana, arial, helvetica, sans-serif' size='1'>" . $i . "</font> ";
                    else
                    {
                    ?>
                        <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
                    } 
                }

                /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
                if( $dados[ 'qt_paginas' ] > $busca_pagina_num )
                {
                ?>
                    <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ( $busca_pagina_num + 1 ) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
                }
            ?>
                  </td>
                </tr>
            <?
            }
        }
        else
        {
        ?>
            <tr>
            <td bgcolor='#ffffff' class="textb" align="center">Não há registros para esta busca.</td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
</center>
<?
}
?>
