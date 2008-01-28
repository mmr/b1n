<%@ taglib uri="http://java.sun.com/jsf/core" prefix="f" %>
<%@ taglib uri="http://java.sun.com/jsf/html" prefix="h" %>
<%@ taglib uri="http://myfaces.apache.org/tomahawk" prefix="t"%>
<html>
<head>
<title>Build Stats</title>
<link rel='stylesheet' href='css/build.css' />
</head>
<body>
<h1>Últimos Builds!</h1>
<hr />
<p align="center">
<table class="buildsByHour">
<tr>
<th>Hora</th>
<th>Usuário</ht>
<th>Projeto</ht>
<th>Versão</ht>
<th>Tempo</ht>
</tr>
<f:view>
  <t:dataList value="#{lastBuilds.entries}" var="e" styleClass="buildByHour" rowIndexVar="i">
    <f:verbatim><tr><td class="hour" rowspan="</f:verbatim>
    <t:outputText value="#{lastBuilds.numberOfBuilds}" />
    <f:verbatim>"></f:verbatim>
      <t:outputText value="#{e.key}" />
    <f:verbatim></td></tr></f:verbatim>

    <t:dataList value="#{e.value}" var="b">
      <f:verbatim><tr></f:verbatim>

        <f:verbatim><td class="userName"></f:verbatim>
          <t:outputText value="#{b.user.userName}" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="artifactId"></f:verbatim>
          <t:outputText value="#{b.project.artifactId}" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="version"></f:verbatim>
          <t:outputText value="#{b.project.version}" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="buildTime"></f:verbatim>
          <t:outputText value="#{b.formattedBuildTime}" />
        <f:verbatim></td></f:verbatim>

      <f:verbatim></tr></f:verbatim>
    </t:dataList>
  </t:dataList>
</f:view>
</table>
</p>
</body>
</html>