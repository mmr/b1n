<?
/* $Id: funcoes.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function mostraDestaques( $sql, $onde )
{
    $dados[ 'destaque' ] = array( );

    switch( $onde )
    {
    case 'home':
        $ids = "SELECT -1";

        for( $i=1; $i<=10; $i++ )
        {
            $query = "     
                SELECT
                    des_nome,
                    mat_id,
                    mat_olho,
                    mat_des_texto,
                    mat_des_imagem
                FROM
                    mat_des
                    NATURAL JOIN materia
                    NATURAL JOIN destaque
                WHERE
                    des_nome = 'home: linha " . $i . "'
                    AND mat_des_dt_ent <= CURRENT_DATE
                    AND mat_id NOT IN
                    (
                        " . $ids . "
                    )
                ORDER BY
                    random( )";

            $rs = $sql->squery( $query );

            if( is_array( $rs ) )
            {
                $imagem = ( $rs[ 'mat_des_imagem' ] != '' ) ?  "<img src='" . $rs[ 'mat_des_imagem' ] . "' height='30' width='30' border='1' hspace='1' vspace='3' class='border-color-preto' />" : '';

                print "<tr>
                    <td align='center'>
                        <table border=0 cellspacing=0 cellpadding=0>
                            <tr>
                                <td width=40 valign='top' style='filter:dropshadow(offX=-1,offY=1,color=#999999)'>
                                    <a href='view.php?mat_id=" . $rs[ 'mat_id' ] . "' target='_self' onFocus='blur()'>" . $imagem . "</a>
                                </td>
                                <td width=95 valign='top' class='font-0'>
                                    <a href='view.php?mat_id=" . $rs[ 'mat_id' ] . "' target='_self' onFocus='blur()'><b>" . $rs[ 'mat_olho' ] . "</b><br />" . $rs[ 'mat_des_texto' ] . "</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height=3></td>
                </tr>
                <tr>
                    <td height=1 bgcolor='#EDEDED' background='/img/hr_cinza_4.gif'>
                        <spacer type=block width=1 height=1><br>
                    </td>
                </tr>";

                $ids .= "UNION SELECT " . $rs[ 'mat_id' ];
            }
        }

        break;
    }
}
?>

