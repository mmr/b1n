<?
/* $Id: alterar.php,v 1.2 2002/04/19 15:43:58 binary Exp $ */

define( "GHO_MENOR_HORA", 7 );
define( "GHO_INCREMENTO", 2 );
define( "GHO_MAIOR_HORA", 21 );

$s = sizeof( $gho[ 0 ] );

for( $i=GHO_MENOR_HORA, $j=0; $i<GHO_MAIOR_HORA; $i+=GHO_INCREMENTO, $j++ )
{
?>
    <tr>
      <td class="textwhitemini" bgcolor="#808080" height="17" width='50' align='center'>
        <?= $i ?>:00 às <?= ( $i+2 ) ?>:00
      </td>
    <?
        for( $k=0; $k < $s; $k++ )
        {
        ?>
          <td bgcolor='#ffffff' class='text'>
            &nbsp;<input type='text' name='gho_<?= $j . "_" . $k ?>' value='<?= in_html( $gho[ $j ][ $k ] ) ?>' size='15' />
          </td>
        <?
        }
    ?>
    </tr>
<?
}
?>
<tr>
  <td colspan="<?= $colspan ?>" bgcolor='#ffffff' align='center'>
    <input type="submit" value=" Alterar " />
    <input type="button" value=" Fechar " onClick="window.close();" />
  </td>
</tr>
