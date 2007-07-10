#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

### pagina de retorno com a confirmacaum ###

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Cliente Removido</title>\n";
print " <meta http-equiv=\"refresh\" content=\"1; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";

print "<center><b>";
print "<br><H1>Cliente Removido</h1><br><br>";
print "</center></b>";

### apendo na base de micros abertos o novo cliente ###

open(NOVO,"../dados/em_aberto");
read(NOVO,$f_abto,1000000);
close NOVO;
@v_abto = split(/\012/,$f_abto);

open(NOVO,"> ../dados/em_aberto");

foreach $t (0..$#v_abto) {
    @reg = split(/;/,$v_abto[$t]);
    if ($reg[1] ne $dados{'micro'}) {
	print NOVO "$v_abto[$t]\n";
    }    
}


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
	print NOVO "$dados{'micro'};off\n";
    } else {
	print NOVO $v_micros[$t]."\n";
    }
    
}
