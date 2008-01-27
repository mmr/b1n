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
<center>
<f:view>
  <t:dataTable value="#{lastBuilds.builds.entrySet}" var="e" styleClass="buildPerHour">
    <t:column styleClass="hour">
      <f:facet name="header">Hora</f:facet>
      <t:outputText value="#{e.key}" />
    </t:column>
    <t:column styleClass="builds">
      <f:facet name="header">Builds</f:facet>
      <t:dataTable value="#{e.value}" var="b" styleClass="builds">
        <t:column styleClass="userName"><f:facet name="header">Usuário</f:facet><t:outputText value="#{b.user.userName}" /></t:column>
        <t:column styleClass="project"><f:facet name="header">Projeto</f:facet><t:outputText value="#{b.project.artifactId}" /></t:column>
        <t:column styleClass="project"><f:facet name="header">Projeto</f:facet><t:outputText value="#{b.project.version}" /></t:column>
        <t:column styleClass="buildTime"><f:facet name="header">Tempo</f:facet><t:outputText value="#{b.formattedBuildTime}" /></t:column>
      </t:dataTable>
    </t:column>
  </t:dataTable>
</f:view>
</center>
</body>
</html>