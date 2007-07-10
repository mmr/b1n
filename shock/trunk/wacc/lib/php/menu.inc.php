<style>
.menu
{
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 12pt;
	color: #E1E1E1;
	text-decoration: none;
}
.menu:hover
{
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 12pt;
	color: #000000;
}

</style>
<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550">
	<tr>
		<td>
		<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<tr>
			<td>
				<a class='menu' href="<?= $PHP_SELF ?>?item=<?=$item?>&sn_inc=produto">Produtos</a>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a class='menu' href="<?= $PHP_SELF ?>?item=<?=$item?>&sn_inc=consumo&sn_acao=Buscar">Novo Consumo</a>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a class='menu' href="<?= $PHP_SELF ?>?item=<?=$item?>&sn_inc=consumo&sn_acao=Listar+Consumos">Consumo Pendente</a>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a class='menu' href="<?=$PHP_SELF?>?item=<?=$item?>&sn_inc=consumo&sn_acao=Listar+Jogos">Jogo Pendente</a>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a class='menu' href="<?=$PHP_SELF?>?item=<?=$item?>&sn_inc=relatorio">Relatórios</a>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a class='menu' href="<?=$PHP_SELF?>?item=<?=$item?>&sn_inc=config&sn_acao=Listar+Conf">Configuração</a>&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		</table>
		</td>
	</tr>
</table>
<? $msgs = array( "ShockNet :: ".ucwords($sn_inc . ($sn_acao ? " - $sn_acao" : ""))) ?>
