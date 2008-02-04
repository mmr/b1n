<%@ page import="java.util.List" %>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
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
    <th>Tests</th>
    <th>Deploy</ht>
    <th>Tempo</ht>
  </tr>

<c:forEach var="e" items="${buildsByHour}">
  <jsp:useBean id="e" type="java.util.Map.Entry" />
  <tr>
    <td class="hour" rowspan="<%= ((List) e.getValue()).size() + 1 %>">
      ${e.key}
    </td>
  </tr>

  <c:forEach var="b" items="${e.value}">
    <tr>
      <td>${b.user.userName}@${b.host.hostName}</td>
      <td>${b.project.artifactId} ${b.project.version}</td>
      <td>${b.withTests}</td>
      <td>${b.deploy}</td>
      <td>${b.buildTime}</td>
    </tr>
  </c:forEach>
</c:forEach>

</table>
</p>
</body>
</html>