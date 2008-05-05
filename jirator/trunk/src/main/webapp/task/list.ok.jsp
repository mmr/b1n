<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<table>
  <tr>
    <th>Id</th>
    <th>Executor</ht>
    <th>Prioridade</ht>
    <th>Severidade</th>
    <th>Pontos</th>
  </tr>

<c:forEach var="e" items="${data}">
  <tr>
    <td>${e.id}</td>
    <td>${e.participant.name}</td>
    <td>${e.priority}</td>
    <td>${e.severity}</td>
    <td>${e.pointsWorth}</td>
  </tr>
</c:forEach>
</table>