#!/usr/bin/perl

require "cgi-lib.pl";

print &PrintHeader;

&ReadParse(dados);

### Abro o a base de micros ###

open(MICROS,"../dados/micros");
read(MICROS,$m_micros,1000000);
close MICROS;
@v_micros = split(/\012/,$m_micros);

### Pagina de entrada ###
print "<HTML>\n";
print "<HEAD>\n";
print "<TITLE>UNNAMED</TITLE>\n";
print "<META name=\"description\" content=\"\">\n";
print "<META name=\"keywords\" content=\"\">\n";
print "</HEAD>\n";
print "<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\" LINK=\"#0000F0\">\n";
print "<H1 align=center><FONT color=#ff0000 face=Verdana>UNNAMED GAME CONTROL</FONT></H1>\n";
print "<H2>Mudar Micro do Cliente :</H2>\n";

print "<form action=\"mud.pl\" method=\"get\">\n"; 
print "<TABLE align=center height=34 style=\"HEIGHT: 34px; WIDTH: 731px\" width=731 border=1 731px\" WIDTH: 94px;>\n";
print "    <TR>\n";
print "        <td><b>Micro Anterior : $dados{'micro'}</b>";
print "		<input type=hidden name=m_old value=$dados{'micro'}>";
print "        <TD><B>Micro novo: </B>\n";
print "		<SELECT name=micro>\n";

### Coloco os options com base no micros abertos ###

#print "		<option selected>$v_livres[0]</option>\n";

foreach $i (0..$#v_micros) {
	@status = split(/;/,$v_micros[$i]);
	if ($status[1] eq "off") {
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
print "    <input type=submit value=\"Mudar\">\n";
print "</form>\n";
print "</BODY>\n";
print "</HTML>\n";




