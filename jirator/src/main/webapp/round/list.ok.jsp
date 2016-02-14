<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>

<table>
  <tr>
    <th>Id</th>
    <th>Projeto</ht>
    <th>Inicio</ht>
    <th>Fim</th>
  </tr>

<c:forEach var="e" items="${data}">
  <tr>
    <td>${e.id}</td>
    <td>${e.project.name}</td>
    <td>${e.startDate}</td>
    <td>${e.endDate}</td>
  </tr>
</c:forEach>
</table>