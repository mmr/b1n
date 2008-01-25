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
<f:view>
  <t:dataTable value="#{lastBuilds.builds}" var="b" styleClass="builds">
    <t:column styleClass="project"><f:facet name="header">Projeto</f:facet><t:outputText value="#{b.project.artifactId}" /></t:column>
    <t:column styleClass="userName"><f:facet name="header">Usuário</f:facet><t:outputText value="#{b.user.userName}" /></t:column>
    <t:column styleClass="buildTime"><f:facet name="header">Tempo</f:facet><t:outputText value="#{b.formattedBuildTime}" /></t:column>
  </t:dataTable>
</f:view>
</body>
</html>