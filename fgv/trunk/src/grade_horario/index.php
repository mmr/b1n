<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
<?
/* $Id: index.php,v 1.5 2002/05/13 19:50:41 binary Exp $ */

extract_request_var( 'mem_id', $dados[ 'mem_id' ] );
extract_request_var( 'alterar', $dados[ 'alterar' ] );

if( $dados[ 'mem_id' ] == '' )
{
    ?>
    <script language='JavaScript'>
        window.alert( 'Algum dos dados necessários não foi passado.\n Processo de visualização/edição da grade de horários abortado' );
        window.close();
    </script>
    <?
    exit;
}

if( $acao == "go" )
{
    extract_request_var( 'gho_0_0', $dados[ 'gho_0_0' ] );
    extract_request_var( 'gho_1_0', $dados[ 'gho_1_0' ] );
    extract_request_var( 'gho_2_0', $dados[ 'gho_2_0' ] );
    extract_request_var( 'gho_3_0', $dados[ 'gho_3_0' ] );
    extract_request_var( 'gho_4_0', $dados[ 'gho_4_0' ] );
    extract_request_var( 'gho_5_0', $dados[ 'gho_5_0' ] );
    extract_request_var( 'gho_6_0', $dados[ 'gho_6_0' ] );
    extract_request_var( 'gho_0_1', $dados[ 'gho_0_1' ] );
    extract_request_var( 'gho_1_1', $dados[ 'gho_1_1' ] );
    extract_request_var( 'gho_2_1', $dados[ 'gho_2_1' ] );
    extract_request_var( 'gho_3_1', $dados[ 'gho_3_1' ] );
    extract_request_var( 'gho_4_1', $dados[ 'gho_4_1' ] );
    extract_request_var( 'gho_5_1', $dados[ 'gho_5_1' ] );
    extract_request_var( 'gho_6_1', $dados[ 'gho_6_1' ] );
    extract_request_var( 'gho_0_2', $dados[ 'gho_0_2' ] );
    extract_request_var( 'gho_1_2', $dados[ 'gho_1_2' ] );
    extract_request_var( 'gho_2_2', $dados[ 'gho_2_2' ] );
    extract_request_var( 'gho_3_2', $dados[ 'gho_3_2' ] );
    extract_request_var( 'gho_4_2', $dados[ 'gho_4_2' ] );
    extract_request_var( 'gho_5_2', $dados[ 'gho_5_2' ] );
    extract_request_var( 'gho_6_2', $dados[ 'gho_6_2' ] );
    extract_request_var( 'gho_0_3', $dados[ 'gho_0_3' ] );
    extract_request_var( 'gho_1_3', $dados[ 'gho_1_3' ] );
    extract_request_var( 'gho_2_3', $dados[ 'gho_2_3' ] );
    extract_request_var( 'gho_3_3', $dados[ 'gho_3_3' ] );
    extract_request_var( 'gho_4_3', $dados[ 'gho_4_3' ] );
    extract_request_var( 'gho_5_3', $dados[ 'gho_5_3' ] );
    extract_request_var( 'gho_6_3', $dados[ 'gho_6_3' ] );
    extract_request_var( 'gho_0_4', $dados[ 'gho_0_4' ] );
    extract_request_var( 'gho_1_4', $dados[ 'gho_1_4' ] );
    extract_request_var( 'gho_2_4', $dados[ 'gho_2_4' ] );
    extract_request_var( 'gho_3_4', $dados[ 'gho_3_4' ] );
    extract_request_var( 'gho_4_4', $dados[ 'gho_4_4' ] );
    extract_request_var( 'gho_5_4', $dados[ 'gho_5_4' ] );
    extract_request_var( 'gho_6_4', $dados[ 'gho_6_4' ] );


    $query = "
        UPDATE grade_horario
        SET
            gho_seg_1 = '" . in_bd( $dados[ 'gho_0_0' ] ) . "',
            gho_ter_1 = '" . in_bd( $dados[ 'gho_0_1' ] ) . "',
            gho_qua_1 = '" . in_bd( $dados[ 'gho_0_2' ] ) . "',
            gho_qui_1 = '" . in_bd( $dados[ 'gho_0_3' ] ) . "',
            gho_sex_1 = '" . in_bd( $dados[ 'gho_0_4' ] ) . "',

            gho_seg_2 = '" . in_bd( $dados[ 'gho_1_0' ] ) . "',
            gho_ter_2 = '" . in_bd( $dados[ 'gho_1_1' ] ) . "',
            gho_qua_2 = '" . in_bd( $dados[ 'gho_1_2' ] ) . "',
            gho_qui_2 = '" . in_bd( $dados[ 'gho_1_3' ] ) . "',
            gho_sex_2 = '" . in_bd( $dados[ 'gho_1_4' ] ) . "',

            gho_seg_3 = '" . in_bd( $dados[ 'gho_2_0' ] ) . "',
            gho_ter_3 = '" . in_bd( $dados[ 'gho_2_1' ] ) . "',
            gho_qua_3 = '" . in_bd( $dados[ 'gho_2_2' ] ) . "',
            gho_qui_3 = '" . in_bd( $dados[ 'gho_2_3' ] ) . "',
            gho_sex_3 = '" . in_bd( $dados[ 'gho_2_4' ] ) . "',

            gho_seg_4 = '" . in_bd( $dados[ 'gho_3_0' ] ) . "',
            gho_ter_4 = '" . in_bd( $dados[ 'gho_3_1' ] ) . "',
            gho_qua_4 = '" . in_bd( $dados[ 'gho_3_2' ] ) . "',
            gho_qui_4 = '" . in_bd( $dados[ 'gho_3_3' ] ) . "',
            gho_sex_4 = '" . in_bd( $dados[ 'gho_3_4' ] ) . "',

            gho_seg_5 = '" . in_bd( $dados[ 'gho_4_0' ] ) . "',
            gho_ter_5 = '" . in_bd( $dados[ 'gho_4_1' ] ) . "',
            gho_qua_5 = '" . in_bd( $dados[ 'gho_4_2' ] ) . "',
            gho_qui_5 = '" . in_bd( $dados[ 'gho_4_3' ] ) . "',
            gho_sex_5 = '" . in_bd( $dados[ 'gho_4_4' ] ) . "',

            gho_seg_6 = '" . in_bd( $dados[ 'gho_5_0' ] ) . "',
            gho_ter_6 = '" . in_bd( $dados[ 'gho_5_1' ] ) . "',
            gho_qua_6 = '" . in_bd( $dados[ 'gho_5_2' ] ) . "',
            gho_qui_6 = '" . in_bd( $dados[ 'gho_5_3' ] ) . "',
            gho_sex_6 = '" . in_bd( $dados[ 'gho_5_4' ] ) . "',

            gho_seg_7 = '" . in_bd( $dados[ 'gho_6_0' ] ) . "',
            gho_ter_7 = '" . in_bd( $dados[ 'gho_6_1' ] ) . "',
            gho_qua_7 = '" . in_bd( $dados[ 'gho_6_2' ] ) . "',
            gho_qui_7 = '" . in_bd( $dados[ 'gho_6_3' ] ) . "',
            gho_sex_7 = '" . in_bd( $dados[ 'gho_6_4' ] ) . "'
        WHERE
            mem_id = '" . in_bd( $dados[ 'mem_id' ] )   . "'";

    $rs = $sql->query( $query );
    
    if( $rs )
    {
    ?>
        <script languge='javascript'>
            opener.focus();
            window.close();
        </script>
    <?
    }
}
else
{
    $colspan = 6;

    if( $dados[ 'alterar' ] == 'yeah' )
        $inc = 'alterar.php';
    else
        $inc = 'consultar.php';

    $query = "
        SELECT
            gho_seg_1,
            gho_seg_2,
            gho_seg_3,
            gho_seg_4,
            gho_seg_5,
            gho_seg_6,
            gho_seg_7,
            gho_ter_1,
            gho_ter_2,
            gho_ter_3,
            gho_ter_4,
            gho_ter_5,
            gho_ter_6,
            gho_ter_7,
            gho_qua_1,
            gho_qua_2,
            gho_qua_3,
            gho_qua_4,
            gho_qua_5,
            gho_qua_6,
            gho_qua_7,
            gho_qui_1,
            gho_qui_2,
            gho_qui_3,
            gho_qui_4,
            gho_qui_5,
            gho_qui_6,
            gho_qui_7,
            gho_sex_1,
            gho_sex_2,
            gho_sex_3,
            gho_sex_4,
            gho_sex_5,
            gho_sex_6,
            gho_sex_7
        FROM
            grade_horario
        WHERE
            mem_id = '" . in_bd( $dados[ 'mem_id' ] ) . "'";

    $rs = $sql->squery( $query );

    if( ! $rs )
    {
        ?>
        <script language='JavaScript'>
            window.alert( 'Erro inesperado!\nNão conseguiu pegar os dados da grade de horários do membro' );
            window.close();
        </script>
        <?
        exit;
    }

    if( ! is_array( $rs ) )
    {
        ?>
        <script language='JavaScript'>
            window.alert( 'Esse membro não tem grade de horário' );
            window.close();
        </script>
        <?
        exit;
    }

    $gho =  array
            (
                array
                (
                    $rs[ 'gho_seg_1' ],
                    $rs[ 'gho_ter_1' ],
                    $rs[ 'gho_qua_1' ],
                    $rs[ 'gho_qui_1' ],
                    $rs[ 'gho_sex_1' ]
                ),
                array
                (
                    $rs[ 'gho_seg_2' ],
                    $rs[ 'gho_ter_2' ],
                    $rs[ 'gho_qua_2' ],
                    $rs[ 'gho_qui_2' ],
                    $rs[ 'gho_sex_2' ]
                ),
                array
                (
                    $rs[ 'gho_seg_3' ],
                    $rs[ 'gho_ter_3' ],
                    $rs[ 'gho_qua_3' ],
                    $rs[ 'gho_qui_3' ],
                    $rs[ 'gho_sex_3' ]
                ),
                array
                (
                    $rs[ 'gho_seg_4' ],
                    $rs[ 'gho_ter_4' ],
                    $rs[ 'gho_qua_4' ],
                    $rs[ 'gho_qui_4' ],
                    $rs[ 'gho_sex_4' ]
                ),
                array
                (
                    $rs[ 'gho_seg_5' ],
                    $rs[ 'gho_ter_5' ],
                    $rs[ 'gho_qua_5' ],
                    $rs[ 'gho_qui_5' ],
                    $rs[ 'gho_sex_5' ]
                ),
                array
                (
                    $rs[ 'gho_seg_6' ],
                    $rs[ 'gho_ter_6' ],
                    $rs[ 'gho_qua_6' ],
                    $rs[ 'gho_qui_6' ],
                    $rs[ 'gho_sex_6' ]
                ),
                array
                (
                    $rs[ 'gho_seg_7' ],
                    $rs[ 'gho_ter_7' ],
                    $rs[ 'gho_qua_7' ],
                    $rs[ 'gho_qui_7' ],
                    $rs[ 'gho_sex_7' ]
                )
            );

    unset( $rs );
?>
<form method="post" action="<?= $_SERVER[ "SCRIPT_NAME" ] ?>">
  <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
  <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
  <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
  <input type="hidden" name="mem_id"      value="<?= in_html( $dados[ "mem_id" ] ) ?>" />
  <input type="hidden" name="acao"        value="go" />

          <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
            <tr>
              <td>
                <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
                  <tr>
                    <td class="textwhitemini" bgcolor="#336699" colspan='<?= $colspan ?>' height="17" align='left'>
                      <img src="images/icone.gif" width="23" height="17" align="absbottom" />
                      Grade de Horários
                    </td>
                  </tr>
                  <tr>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>&nbsp;</td>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>Segunda</td>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>Terça</td>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>Quarta</td>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>Quinta</td>
                    <td class="textwhitemini" bgcolor="#808080" height="17" align='center'>Sexta</td>
                  </tr>

                  <?
                  include( $inc );
                  ?>

                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
</form>
                </table>
              </td>
            </tr>
          </table>
<?
}
?>
        </td>
      </tr>
   </table>
</center>
<br />
<br />
