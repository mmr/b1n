<?
/* $Id: listar.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

define( 'MAXTEXTO', 20 );

$list_data = buscar($sql, $busca);
?>
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
            <td class='text' bgcolor="#ffffff">
              <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                <input type="hidden" name="pagina" value="<?= $pagina ?>" />
                <input type="hidden" name="busca_pagina_num" value="1" />
                <b>Busca:</b>
<? if (isset($list_data["possiveis_campos"])) 
      gera_select_from_hash_tv($list_data["possiveis_campos"], array($busca["campo"]), array("name" => "busca_campo")); ?>

  <input type='text' name='busca_texto' value="<?= in_html($busca["texto"])?>" />

                <b>Ordenar:</b>
<? if (isset($list_data["possiveis_campos"])) 
      gera_select_from_hash_tv($list_data["possiveis_ordens"], array($busca["ordem"]), array("name" => "busca_ordem")); ?>
                &nbsp;
                <b>Qtd:</b>
<? if (isset($list_data["possiveis_quantidades"]))
      gera_select_from_list($list_data["possiveis_quantidades"], array($busca["qt_por_pagina"]), array("name" => "busca_qt_por_pagina")); ?>
                <input type="submit" value='&nbsp;Buscar&nbsp;' />
            </td>
          </tr>
          </form>

<!-- Inserir -->

          <tr>
            <td bgcolor='#ffffff' align="center">
              <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                <input type="hidden" name="pagina" value="<?= $pagina ?>" />
                <input type="hidden" name="subpagina" value="inserir" />
                <input type="submit" name="ok" value="&nbsp;Inserir Novo&nbsp;" />
            </td>
          </tr></form>

<!-- Apagar -->

          <tr><td class="text" bgColor="#336699">
              <script name='JavaScript'>
                function confirma( f )
                {
                    j = false;
                    for( var i=0; i<f.elements.length; i++ )
                    {
                        if( f.elements[ i ].checked )
                        {
                            j = true;
                            break;
                        }
                    }

                    if( j )
                        return confirm( 'Tem certeza que deseja apagar essa matéria e todas suas filhas (se existirem)?' );
                    else
                        return false;
                }
              </script>

              <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return confirma( this );'>
                <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                <input type="hidden" name="pagina" value="<?= $pagina ?>" />
                <input type="hidden" name="subpagina" value="apagar" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
<? if (isset($list_data["result"])) { ?>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <tr>
            <td class="textwhitemini" COLSPAN="<?= $colspan ?>" bgColor="#336699" HEIGHT="17">
              <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?>
            </td>
          </tr>
<?
if (is_array($list_data["result"]) && sizeof($list_data["result"]))
{
?>
          <tr>
            <td bgcolor='#ffffff' class="textb">&nbsp;</td>
            <td bgcolor='#ffffff' class="textb">Título</td>
            <td bgcolor='#ffffff' class="textb">Olho</td>
            <td bgcolor='#ffffff' class="textb">Fonte</td>
            <td bgcolor='#ffffff' class="textb">Status</td>
            <td bgcolor='#ffffff' class="textb" colspan="3">Funções</td>
          </tr>
        
<?
    foreach ($list_data["result"] as $item)
    {
?>
          <tr>
            <td bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' value='<?= $item[ 'mat_id' ] ?>' /></td>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html( strlen( $item[ 'mat_titulo' ] ) > MAXTEXTO ? substr( $item["mat_titulo"], 0, MAXTEXTO ) . "..." : $item[ 'mat_titulo' ] ) ?></td>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html( strlen( $item[ 'mat_olho' ] )   > MAXTEXTO ? substr( $item["mat_olho"],   0, MAXTEXTO ) . "..." : $item[ 'mat_olho' ] ) ?></td>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html( strlen( $item[ 'mat_fonte' ] )  > MAXTEXTO ? substr( $item["mat_fonte"],  0, MAXTEXTO ) . "..." : $item[ 'mat_fonte' ] ) ?></td>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html( $item["mat_status"] ) ?></td>
 
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=destacar&mat_id=<?= $item["mat_id"] ?>">Destacar</a></td>
            <!--
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=preview&mat_id=<?= $item["mat_id"] ?>">Preview</a></td>
            //-->
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=alterar&mat_id=<?= $item["mat_id"] ?>">Editar</a></td>
          </tr>

<?
    }
?>
      <tr>
        <td bgcolor='#ffffff' align='center' class="textb" colspan='<?= $colspan ?>' ><input type='submit' name='ok' value=' Apagar ' /></td>
      </tr>
<?

/*
 *
 * PAGINACAO
 *
 */
 

/* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
    if( $list_data['qt_paginas'] > 1 )
    {
?>
<tr>
    <td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff">
<?
/* se a pagina atual for maior que 1, mostrar seta pra voltar */
        if( $list_data['pagina_num'] > 1 )
        {
?>
<a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ($list_data["pagina_num"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
<?
        }
    
        for ($i = 1; $i <= $list_data["qt_paginas"]; $i++)
        { 
            if ($i == $list_data["pagina_num"]) 
	            print ($i);
            else
            {
?>
<a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
<? 
            } 
        }

/* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
        if( $list_data['qt_paginas'] > $list_data['pagina_num'] )
        {
?>
<a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ($list_data["pagina_num"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
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
    </tr></form>
  </table>
<? } ?>
