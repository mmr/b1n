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
<th>Tests</th>
<th>Deploy</ht>
<th>Tempo</ht>
</tr>
<f:view>
<h:form>
  <t:dataList value="#{lastBuilds.entries}" var="e" styleClass="buildByHour" rowIndexVar="i">
    <f:verbatim><tr><td class="hour" rowspan="</f:verbatim>
    <t:outputText value="#{lastBuilds.numberOfBuilds}" />
    <f:verbatim>"></f:verbatim>
      <t:outputText value="#{e.key}" />
    <f:verbatim></td></tr></f:verbatim>

    <t:dataList value="#{e.value}" var="b">
      <f:verbatim><tr class="</f:verbatim>
      <t:outputText value="#{lastBuilds.trStyleClass}" />
      <f:verbatim>"></f:verbatim>

        <f:verbatim><td class="userName"></f:verbatim>
          <h:commandLink value="#{b.user.userName}@#{b.host.hostName}" action="/buildsByUser.faces">
            <f:param name="userId" value="#{b.user.id}" />
          </h:commandLink>
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="artifactId"></f:verbatim>
          <t:outputText value="#{b.project.artifactId}" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="version"></f:verbatim>
          <t:outputText value="#{b.project.version}" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="tests"></f:verbatim>
          <t:outputText value="#{b.withTests}" converter="b1n.BooleanConverter" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="deploy"></f:verbatim>
          <t:outputText value="#{b.deploy}" converter="b1n.BooleanConverter" />
        <f:verbatim></td></f:verbatim>

        <f:verbatim><td class="buildTime"></f:verbatim>
          <t:outputText value="#{b.buildTime}" converter="b1n.BuildTimeConverter" />
        <f:verbatim></td></f:verbatim>

      <f:verbatim></tr></f:verbatim>
    </t:dataList>
  </t:dataList>
</h:form>
</f:view>
</table>
</p>
</body>
</html>