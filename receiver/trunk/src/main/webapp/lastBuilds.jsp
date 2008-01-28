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
  <h:dataTable value="#{lastBuilds.entries}" styleClass="buildsByHour" var="e">
    <h:column>
      <f:facet name="header"><h:outputText value="Hora" /></f:facet>
      <h:outputText value="#{e.key}" />
    </h:column>
    <h:column>
      <f:facet name="header"><h:outputText value="Builds" /></f:facet>
      <h:dataTable value="#{e.value}" var="b">
        <h:column>
          <h:outputText value="#{b.user.userName}" />
        </h:column>
        <h:column>
          <h:outputText value="#{b.project.artifactId}" />
        </h:column>
        <h:column>
          <h:outputText value="#{b.project.version}" />
        </h:column>
        <h:column>
          <h:outputText value="#{b.formattedBuildTime}" />
        </h:column>
      </h:dataTable>
    </h:column>
  </h:dataTable>
</f:view>
</center>
</body>
</html>