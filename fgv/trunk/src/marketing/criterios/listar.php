<?
/* $Id: listar.php,v 1.6 2002/07/31 21:10:47 binary Exp $ */

$list_data = busca_criterio($sql, $busca);
?>
<? $busca = $list_data[ "busca" ]; ?>

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
          </tr></form>
          <tr>
            <td bgcolor='#ffffff' align="center">
              <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                <input type="hidden" name="pagina" value="<?= $pagina ?>" />
                <input type="hidden" name="subpagina" value="inserir" />
                <input type="submit" name="ok" value="&nbsp;Inserir Novo&nbsp;" />
              
            </td>
          </tr></form>
          <tr><td class="text" bgColor="#336699">&nbsp;</td></tr>
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
            <td bgcolor='#ffffff' class="textb">Nome</td>
            <td bgcolor='#ffffff' class="textb">Peso</td>
            <td bgcolor='#ffffff' class="textb" colspan="3">Funções</td>
          </tr>
        
<?
    foreach ($list_data["result"] as $item)
    {
?>
          <tr>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html($item["cri_nome"]) ?></td>
            <td bgcolor='#ffffff'>&nbsp;<?= in_html($item["cri_peso"]) ?></td>
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=consultar&id=<?= $item["cri_id"] ?>">Consultar</a></td>
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=alterar&id=<?= $item["cri_id"] ?>">Editar</a></td>
            <td bgcolor='#ffffff'><a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&subpagina=apagar&id=<?= $item["cri_id"] ?>">Excluir</a></td>
          </tr>

<?
    }

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
                print "<font color='#000000' face='verdana, arial, helvetica, sans-serif' size='1'>" . $i . "</font> ";
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
    </tr>
  </table>
  <br />
  <br />
<? } ?>
</center>
