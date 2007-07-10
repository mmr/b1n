#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

### vejo a hora do sistema que sera a hora de entrada ###
($sec,$min,$hour) = localtime(time);

### verifico o codigo atual ###

open(COD,"< ../dados/codigo");
read(COD,$v_cod,1000);
close(COD);

### pagina de retorno com a confirmacaum ###

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Entrada de cliente</title>\n";
print " <meta http-equiv=\"refresh\" content=\"3; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";


open(VAL,"../dados/valores");
read(VAL,$f_val,100000);
close VAL;
@v_val = split(/\012/,$f_val);

foreach $c (0..$#v_val) {
    @tmp = split(/;/,$v_val[$c]);
    if ($dados{'preco'} eq $tmp[0]) {
	$valor = $tmp[1];
    }
}

### retorno os dados que chegaram do form entrada.pl ###
print "<center><b>";
print "<br><H1>Novo Cliente</h1><br><br>";
print "<br>Codigo: $v_cod<br>Micro : $dados{'micro'}<br>Valor : $valor<br>Hora : $hour:$min:$sec\n";
print "</center></b>";

### apendo na base de micros abertos o novo cliente ###

open(NOVO,">> ../dados/em_aberto");
print NOVO "$v_cod;$dados{'micro'};$valor;$hour:$min:$sec;0;$hour:$min\n";
close NOVO;

print "<br><center><a href=/cgi-bin/controle/cgi/controle.pl>VOLTA PARA O CONTROLE</a>\n";
print "</body>\n";
print "</html>\n";

### atualizo o arquivo micros ###

open(MICROS,"../dados/micros");
read(MICROS,$m_micros,1000000);
close MICROS;
@v_micros = split(/\012/,$m_micros);

open(NOVO,"> ../dados/micros");

foreach $t (0..$#v_micros) {
    @micros = split(/;/,$v_micros[$t]);
    if ($micros[0] eq $dados{'micro'}) {
	print NOVO "$dados{'micro'};on\n";
    } else {
	print NOVO $v_micros[$t]."\n";
    }
    
}
