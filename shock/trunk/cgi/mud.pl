#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

### vejo a hora do sistema que sera a hora de entrada ###

open(MIC,"< ../dados/em_aberto");
read(MIC,$f_abto,1000);
close(MIC);
@m_abto = split(/\012/,$f_abto);

foreach $t (0..$#m_abto) {
    @tmp = split(/;/,$m_abto[$t]);
    if ($dados{'m_old'} eq $tmp[1]) {
	$m_cli = $m_abto[$t];
    }
}

@v_cli = split(/;/,$m_cli);


open(VAL,"< ../dados/valores");
read(VAL,$f_val,1000);
close VAL;
@m_val = split(/\012/,$f_val);

foreach $t (0..$#m_val) {
    @tmp = split(/;/,$m_val[$t]);
    if ($dados{'preco'} eq $tmp[0]) {
	$val = $tmp[1];
    }
}


### pagina de retorno com a confirmacaum ###

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Mudar Cliente</title>\n";
print " <meta http-equiv=\"refresh\" content=\"3; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";


### retorno os dados que chegaram do form entrada.pl ###

print "<center><b>";
print "<br><H1>Mudanca de Cliente</h1><br><br>";
print "Codigo: $v_cli[0]<br>Micro Inicial : $v_cli[1]<br> Micro final : $dados{'micro'}<br>Valor Inicial : $v_cli[2]<br>Valor Final : $val<br>Tempo Atual em minutos : $v_cli[4]<br>\n";
print "<br><br><br>";
print "<table align=center border=0><tr>";
print "<td><a href=/cgi-bin/controle/cgi/controle.pl>Controle</a>&nbsp;&nbsp;&nbsp;</td>";
#print "<td><a href=\"/cgi-bin/controle/cgi/reinicia.pl?micro=$v_cli[1]\">Reinicia</a></td>";
print "</tr></table>";
print "</center></b>";

print "</body>\n";
print "</html>\n";


### apendo na base de micros abertos o novo cliente ###

open(NOVO,"../dados/em_aberto");
read(NOVO,$f_abto,1000000);
close NOVO;
@v_abto = split(/\012/,$f_abto);

open(NOVO,"> ../dados/em_aberto");

foreach $t (0..$#v_abto) {
    @reg = split(/;/,$v_abto[$t]);
    if ($reg[1] eq $dados{'m_old'}) {
	print NOVO "$reg[0];$dados{'micro'};$val;$reg[3];$reg[4];$reg[5];$reg[6]\n";
    } else {
	print NOVO "$v_abto[$t]\n";	
    }
}
close NOVO;


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
    } elsif ($micros[0] eq $dados{'m_old'}) {
	print NOVO "$dados{'m_old'};off\n";    
    } else {
	print NOVO $v_micros[$t]."\n";
    }
    
}
