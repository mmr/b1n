<%@ taglib uri="http://java.sun.com/jstl/core" prefix="c" %>
<html>
<head>
<title>Build Stats</title>
<link rel='stylesheet' href='css/build.css' />
</head>
<body>
<h1>�ltimos Builds!</h1>
<hr />
<p align="center">
<table class="buildsByHour">
  <tr>
    <th>Hora</th>
    <th>Usu�rio</ht>
    <th>Projeto</ht>
    <th>Tests</th>
    <th>Deploy</ht>
    <th>Tempo</ht>
  </tr>

<c:forEach var="e" items="${lastBuilds.buildsByHour.entrySet}">
  <tr>
    <td class="hour" rowspan="${e.value.size}">
      ${e.key}
    </td>
  </tr>

  <c:forEach var="b" items="${e.value}">
    <tr>
      <td>${b.user.userName}@${b.host.hostName}</td>
      <td>${b.project.artifactId} ${b.project.version}</td>
      <td>${b.withTests}</td>
      <td>${b.deploy}</td>
      <td>${b.withTests}</td>
    </tr>
  </c:forEach>
</c:forEach>

</table>
</p>
</body>
</html>