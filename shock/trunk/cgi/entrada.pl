#!/usr/bin/perl

require "cgi-lib.pl";

print &PrintHeader;


#if(!open(TMP,"../dados/micros_livres")) {
#	open(TMP,"../dados/micros");
#}

### Abro o a base de micros ###

open(MICROS,"../dados/micros");
read(MICROS,$m_micros,1000000);
close MICROS;
#@v_micros = split(/\012/,$m_micros);
@v_livres = split(/\012/,$m_micros);

### Abro o arquivo micros livres mas verifico antes 
### Se naum existe eu uso a matriz do micros mesmo.

#if(!open(LIVRES,"../dados/micros_livres")) {
#    @v_livres = @v_micros;
#} else {
#    read(LIVRES,$m_livres,1000000);
#    @v_livres = split(/\012/,$m_livres);
#}
close(LIVRES);

### Abro a base de controle e somo um registro ###

open(COD,"../dados/codigo");
read(COD,$v_cod,1000);
close COD;
if (!$v_cod) { $v_cod = 00000; } 
$v_cod++;

open(COD1,"> ../dados/codigo");
print COD1 $v_cod;
close COD1;

### Pagina de entrada ###
print "<HTML>\n";
print "<HEAD>\n";
print "<TITLE>UNNAMED</TITLE>\n";
print "<META name=\"description\" content=\"\">\n";
print "<META name=\"keywords\" content=\"\">\n";
print "</HEAD>\n";
print "<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\" LINK=\"#0000F0\">\n";
print "<H1 align=center><FONT color=#ff0000 face=Verdana>UNNAMED GAME CONTROL</FONT></H1>\n";
print "<H2>Incluir novo Cliente :</H2>\n";

print "<form action=\"inclui.pl\" method=\"get\">\n"; 
print "<TABLE align=center height=34 style=\"HEIGHT: 34px; WIDTH: 731px\" width=731 border=1 731px\" WIDTH: 94px;>\n";
print "    <TR>\n";
print "        <TD>Cod.:<B> <FONT color=#ff0000 300px\" TOP: 791px; >$v_cod</FONT></B>\n";
print "        <TD><B>Micro : </B>\n";
print "		<SELECT name=micro>\n";

### Coloco os options com base no micros abertos ###

#print "		<option selected>$v_livres[0]</option>\n";

foreach $i (0..$#v_livres) {
	@status = split(/;/,$v_livres[$i]);
	if ($status[1] eq "off") {
#    	    print "<option>$v_livres[$i]</option>\n";
	    print "<option>$status[0]</option>\n";
	}    
}

print "		</SELECT>\n";

### leitura dos valores ! ###

open(VAL,"../dados/valores");
read(VAL,$f_val,1000);
close VAL;
@m_val = split(/\012/,$f_val);


#print "        <TD><b>Pre&ccedil;o : <input type=\"text\" name=\"preco\" value=\"5\" size=\"5\" maxlength=\"5\"></b>\n";
print "        <TD><b>Pre&ccedil;o : <select name=\"preco\" ></b>\n";
foreach $t (0..$#m_val) {
    @val = split(/;/,$m_val[$t]);
    print "<option>$val[0]</option>\n";
}

print "		</SELECT>\n";
print "</TD>
         </TR></TABLE>\n";
print "<P></P>\n";
print "    <br>\n";
print "    <input type=submit value=\"Cadastrar\">\n";
print "</form>\n";
print "</BODY>\n";
print "</HTML>\n";




