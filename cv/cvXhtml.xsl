<?xml version="1.0" encoding="ISO-8859-1"?>
<!--
/**
 * @author Marcio Ribeiro (mribeiro (a) gmail com)
 * @created 2004-02-17
 * @version $Id: cvXhtml.xsl,v 1.2 2006/04/04 19:54:39 mmr Exp $
 */
-->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:mmr="http://cv.b1n.org">

<xsl:output
    omit-xml-declaration="no"
    method="xml"
    doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
    encoding="iso-8859-1"
    indent="yes" />
<xsl:strip-space elements="*" />

<!-- Functions -->
<xsl:template name="formatDate" match="/">
    <xsl:param name="date" />
    <xsl:param name="format" />

    <xsl:if test="$date">
        <xsl:variable name="year"  select="substring($date, 1, 4)" />
        <xsl:variable name="month" select="substring($date, 6, 2)" />
        <xsl:variable name="day"   select="substring($date, 9, 2)" />
        <xsl:choose>
            <xsl:when test="$format = 'birthday'">
                <xsl:value-of select="concat($day, '.', $month, '.', $year)" />
            </xsl:when>
            <xsl:when test="$format = 'school'">
                <xsl:value-of select="$year" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="$month = 1">
                        <xsl:text>Janeiro</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 2">
                        <xsl:text>Fevereiro</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 3">
                        <xsl:text>Mar�o</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 4">
                        <xsl:text>Abril</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 5">
                        <xsl:text>Maio</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 6">
                        <xsl:text>Junho</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 7">
                        <xsl:text>Julho</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 8">
                        <xsl:text>Agosto</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 9">
                        <xsl:text>Setembro</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 10">
                        <xsl:text>Outubro</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 11">
                        <xsl:text>Novembro</xsl:text>
                    </xsl:when>
                    <xsl:when test="$month = 12">
                        <xsl:text>Dezembro</xsl:text>
                    </xsl:when>
                </xsl:choose>
                <xsl:text> </xsl:text>
                <xsl:value-of select="$year" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:if>
</xsl:template>

<!-- Personal Data -->
<xsl:template match="mmr:cv/mmr:personalData">
    <div class='item'>
        <div class='key'>Nome</div>
        <div class='value specialValue'><xsl:value-of select="concat(mmr:firstName, ' ', mmr:lastName)" /></div>
    </div>
    <div class='item'>
        <div class='key'>Endere�o</div>
        <div class='value specialValue'><xsl:value-of select="mmr:address" /></div>
    </div>
    <div class='item'>
        <div class='key'>Telefone</div>
        <div class='value specialValue'>
            <xsl:for-each select="mmr:phones/mmr:phone">
                <xsl:value-of select="." />
                <xsl:if test="position() &lt; last()"><xsl:text> / </xsl:text></xsl:if>
            </xsl:for-each>
        </div>
    </div>
    <div class='item'>
        <div class='key'>Email</div>
        <div class='value'><xsl:value-of select="mmr:email" /></div>
    </div>
    <div class='item'>
        <div class='key'>Nascimento</div>
        <div class='value'><xsl:call-template name="formatDate"><xsl:with-param name="format" select="'birthday'" /><xsl:with-param name="date" select="mmr:birthday" /></xsl:call-template></div>
    </div>
    <div class='item'>
        <div class='key'>Estado Civil</div>
        <div class='value'><xsl:value-of select="mmr:civilState" /></div>
    </div>
</xsl:template>

<!-- Professional Summary -->
<xsl:template match="mmr:cv/mmr:professionalSummary">
    <xsl:for-each select="mmr:company">
        <div class='subsection'>
            <div class='item'>
                <div class='key'>Per�odo</div>
                <div class='value date'><xsl:call-template name="formatDate"><xsl:with-param name="date" select="mmr:dateIn" /></xsl:call-template> - <xsl:call-template name="formatDate"><xsl:with-param name="date" select="mmr:dateOut" /></xsl:call-template></div>
            </div>
            <div class='item'>
                <div class='key'>Empresa</div>
                <div class='value'><xsl:value-of select="mmr:name" /></div>
            </div>
            <div class='item'>
                <div class='key'>Cargo</div>
                <div class='value'><xsl:value-of select="mmr:position" /></div>
            </div>
            <div class='item'>
                <div class='key'>Principais atividades desempenhadas</div>
                <div class='value'>
                    <ul>
                    <xsl:for-each select="mmr:activities/mmr:item">
                        <li><xsl:value-of select="." /></li>
                    </xsl:for-each>
                    </ul>
                </div>
            </div>
        </div>
    </xsl:for-each>
</xsl:template>

<!-- School Summary -->
<xsl:template match="mmr:cv/mmr:schoolSummary">
    <xsl:for-each select="mmr:school">
        <div class='subsection'>
            <div class='item'>
                <div class='key'>Institui��o</div>
                <div class='value'><xsl:value-of select="mmr:name" /></div>
            </div>
            <div class='item'>
                <div class='key'>Curso</div>
                <div class='value'><xsl:value-of select="mmr:course" /> (<xsl:call-template name="formatDate"><xsl:with-param name="format" select="'school'" /><xsl:with-param name="date" select="mmr:dateOut" /></xsl:call-template>)</div>
            </div>
        </div>
    </xsl:for-each>
</xsl:template>

<!-- Languages -->
<xsl:template match="mmr:cv/mmr:languages">
    <xsl:for-each select="mmr:language">
        <div class='item'>
            <div class='key'><xsl:value-of select="mmr:name" /></div>
            <div class='value'><xsl:value-of select="mmr:level" /></div>
        </div>
    </xsl:for-each>
</xsl:template>

<!-- Qualifications -->
<xsl:template match="mmr:cv/mmr:qualifications">
    <xsl:for-each select="mmr:qualification">
        <div class='subsection'>
        <div class='item'>
            <div class='key'><xsl:value-of select="@name" /></div>
            <div class='value'>
                <ul>
                <xsl:for-each select="mmr:item">
                    <li><xsl:value-of select="." /></li>
                </xsl:for-each>
                </ul>
            </div>
        </div>
        </div>
    </xsl:for-each>
</xsl:template>

<!-- Certifications -->
<xsl:template match="mmr:cv/mmr:certifications">
    <div class='section'>
        <div class='title'>Certifica��es</div>
        <div class='item'>
            <div class='key'></div>
            <div class='value'>
                <ul>
                <xsl:for-each select="mmr:certification">
                    <li><xsl:value-of select="." /></li>
                </xsl:for-each>
                </ul>
            </div>
        </div>
    </div>
</xsl:template>

<!-- Misc -->
<xsl:template match="mmr:cv/mmr:misc">
    <div class='section'>
        <div class='title'>Outras Atividades</div>
        <div class='item'>
            <div class='key'></div>
            <div class='value'>
                <ul>
                <xsl:for-each select="mmr:item">
                    <li><xsl:value-of select="." /></li>
                </xsl:for-each>
                </ul>
            </div>
        </div>
    </div>
</xsl:template>

<!-- Cv -->
<xsl:template match="/">
<html xml:lang='pt-br'>
    <head>
        <meta http-equiv='pragma' content='no-cache'/>
        <meta http-equiv='content-type' content='text/html; charset=ISO-8859-1'/>
        <meta name='description' content='Marcio Ribeiro'/>
        <meta name='keywords' content='Marcio Ribeiro, mmr, Curriculum Vitae'/>
        <meta name='robots' content='ALL'/>
        <meta name='language' content='pt-br'/>
        <link rel='shortcut icon' href='/comum/img/favicon.ico'/>
        <link rel='author' href='Marcio Ribeiro'/>
        <link rel='home' href='http://cv.b1n.org'/>
        <style type='text/css'> @import 'css/cv.css'; </style>
        <title>Marcio Ribeiro Curriculum Vitae</title>
    </head>
    <body>
    <div id='cv'>
        <div id='divisor'>
            <div id='title'>Curriculum Vitae</div>

            <div class='section personalData'>
                <div class='title'>Informa��es Pessoais</div>
                <xsl:apply-templates select="mmr:cv/mmr:personalData" />
            </div>

            <div class='section'>
                <div class='title'>Experi�ncia Profissional</div>
                <xsl:apply-templates select="mmr:cv/mmr:professionalSummary" />
            </div>

            <div class='section'>
                <div class='title'>Forma��o Acad�mica</div>
                <xsl:apply-templates select="mmr:cv/mmr:schoolSummary" />
            </div>

            <div class='section'>
                <div class='title'>Idiomas</div>
                <xsl:apply-templates select="mmr:cv/mmr:languages" />
            </div>

            <div class='section'>
                <div class='title'>Conhecimento T�cnico</div>
                <xsl:apply-templates select="mmr:cv/mmr:qualifications" />
            </div>

            <xsl:apply-templates select="mmr:cv/mmr:certifications" />

            <xsl:apply-templates select="mmr:cv/mmr:misc" />
        </div>
    </div>
    <div id='ext'>
        <div>
            Esse documento em outros formatos
            <ul>
                <li><a href='cv.pdf'>PDF</a></li>
                <li><a href='cv.ps'>PS</a></li>
            </ul>
        </div>

        <div>
            Arquivos usados para gerar esses documentos
            <ul>
                <li><a href='cv.xml.txt'>XML</a></li>
                <li><a href='cv.xsd'>XSD</a></li>
                <li><a href='cvTex.xsl'>XSLT : XML para LaTeX</a></li>
                <li><a href='cvXhtml.xsl'>XSLT : XML para XHTML 1.0 Strict</a></li>
                <li><a href='Makefile'>Makefile</a></li>
            </ul>
        </div>
    </div>
    </body>
</html>
</xsl:template>
</xsl:stylesheet>
