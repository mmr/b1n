<?
//$Id: fasciculo.html.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

  // Pegando Artigos
$query = "
  SELECT
    SUBSTRING(i.idi_nome, 1, 4) AS idi_nome,
    s.sec_nome,
    a.art_id,
    a.art_ordem,
    a.art_pag_ini,
    a.art_pag_fin,
    a.art_pdf,
    a.art_html
  FROM
    artigo a JOIN
    idioma i ON (a.idi_id = i.idi_id) LEFT JOIN
    secao  s ON (a.sec_id = s.sec_id)
  WHERE
    a.art_ativo = 1 AND
    s.sec_ativo = 1 AND
    i.idi_ativo = 1 AND
    a.fas_id = '" . $data['id'] . "'
  ORDER BY
    a.art_ordem,
    a.sec_id";

$sql->sqlQuery($query);
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="img/shim.gif" width="15" height="1"></td>
          <td width="100%">
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="tabverdeclaro"><img src="img/shim.gif" width="1" height="1"></td>
                <td class="tabverdeclaro" width="100%"><span class="secao">Edi&ccedil;&atilde;o Atual - 25 (1/2), 2003</span></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0">
              <tr>
                <td valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="img/shim.gif" width="1" height="5"></td>
                    </tr>
                    <tr>
                      <td><img src="img/capas/25_1.gif" width="110" height="130"></td>
                    </tr>
                  </table>
                  
                </td>
                <td width="100%" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr> 
                      <td width="100%"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="tabverdeescuro"><img src="img/shim.gif" width="1" height="1"></td>
                            <td class="tabverdeescuro" width="100%"><span class="secao">Editorial</span></td>
                          </tr>
                        </table>
                      </td>
                      <td><img src="img/shim.gif" width="4" height="1"></td>
                      <td class="tabverdeescuro"><img src="img/shim.gif" width="96" height="1"></td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td valign="top"><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Uso 
                        de macrol&iacute;deos nas infec&ccedil;&otilde;es de vias 
                        a&eacute;reas superiores da crian&ccedil;a</span><br>
                        <span class="autores">Alfredo Elias Gilio, Denise Swei 
                        Lo</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="tabverdeescuro"><img src="img/shim.gif" width="1" height="1"></td>
                            <td class="tabverdeescuro" width="100%"><span class="secao">Artigos 
                              Originais</span></td>
                          </tr>
                        </table>
                      </td>
                      <td>&nbsp;</td>
                      <td class="tabverdeescuro"><img src="img/shim.gif" width="96" height="1"></td>
                    </tr>
                    <tr> 
                      <td width="100%"><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td valign="top"><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Rela&ccedil;&atilde;o 
                        entre o adolescente e o tabaco: estudo de fatores s&oacute;ciodemogr&aacute;ficos 
                        de escolares em Santa Maria, RS</span><br>
                        <span class="autores">Alessandro Comar&uacute; Pasqualotto, 
                        Gilberto Comar&uacute; Pasqualotto, Rodrigo Pires dos 
                        Santos, Fabiano Mendoza Segat, Steneo Guillande, Lu&iacute;s 
                        Ant&ocirc;nio Benvegn&uacute;</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Infec&ccedil;&atilde;o 
                        nosocomial pelo v&iacute;rus respirat&oacute;rio sincicial 
                        em enfermaria de pediatria</span><br>
                        <span class="autores">Sandra Elisabete Vieira, Alfredo 
                        Elias Gilio, Cristina Riyoka Miyao, M&aacute;rcia Melo 
                        Campos Pahl, Jo&atilde;o Paulo Becker Lotufo, Noely Hein, 
                        Selma Lopes Betta, &Eacute;dison Lu&iacute;s Durigon, 
                        Viviane Botoso, Bernardo Ejzenberg, Yassuhiko Okay</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Conhecimento 
                        e conduta de pediatras frente &agrave; defici&ecirc;ncia 
                        auditiva</span><br>
                        <span class="autores">Ana Carolina Tasso Barros, Maria 
                        Ang&eacute;lica Castelane Galindo, Regina Tangerino Souza 
                        Jacob</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Efeito 
                        de dietas ricas em fibras sobre ratos em crescimento: 
                        estudo experimental</span><br>
                        <span class="autores">Solange Assunci&oacute;n Villagra 
                        Fernandez, Uenis Tannuri, Giancarlo Domingues, Dina Yaeko 
                        Uehara, Francisco Roque Carrazza</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="tabverdeescuro"><img src="img/shim.gif" width="1" height="1"></td>
                            <td class="tabverdeescuro" width="100%"><span class="secao">Relato 
                              de Caso</span></td>
                          </tr>
                        </table>
                      </td>
                      <td>&nbsp;</td>
                      <td class="tabverdeescuro"><img src="img/shim.gif" width="96" height="1"></td>
                    </tr>
                    <tr> 
                      <td width="100%"><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td valign="top"><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Cistinose 
                        nefrop&aacute;tica: diferentes apresenta&ccedil;&otilde;es 
                        cl&iacute;nicas</span><br>
                        <span class="autores">Danielle Dias Brunn, Maria Helena 
                        Vaisbich, Lu&iacute;s Carlos Ferreira de S&aacute;, Vera 
                        Hermina Koch</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                    <tr> 
                      <td width="100%">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td width="100%"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="tabverdeescuro"><img src="img/shim.gif" width="1" height="1"></td>
                            <td class="tabverdeescuro" width="100%"><span class="secao">Cartas 
                              ao Editor</span></td>
                          </tr>
                        </table>
                      </td>
                      <td>&nbsp;</td>
                      <td class="tabverdeescuro"><img src="img/shim.gif" width="96" height="1"></td>
                    </tr>
                    <tr> 
                      <td width="100%"><img src="img/shim.gif" width="1" height="8"></td>
                      <td><img src="img/shim.gif" width="1" height="8"></td>
                      <td valign="top"><img src="img/shim.gif" width="1" height="8"></td>
                    </tr>
                    <tr> 
                      <td width="100%" valign="top"><span class="titulosbold">Sazonalidade 
                        do v&iacute;rus respirat&oacute;rio sincicial na Cidade 
                        de S&atilde;o Paulo, SP</span><br>
                        <span class="autores">Sandra Elisabete Vieira, Alfredo 
                        Elias Gilio, Cristina Riyoka Miyao, Noely Hein, Selma 
                        Lopes<br>
                        Betta, Jo&atilde;o Paulo Becker Lotufo, Viviane Botoso, 
                        &Eacute;dison Lu&iacute;s Durigon, Bernardo Ejzenberg,<br>
                        Yassuhiko Okay</span></td>
                      <td>&nbsp;</td>
                      <td valign="top"><span class="titulosbold">&#149;HTML</span> 
                        <span class="textomenor">(Port)</span><br>
                        <span class="titulosbold">&#149;PDF</span> <span class="textomenor">(Port)</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="img/shim.gif" width="1" height="15"></td>
        </tr>
      </table>
