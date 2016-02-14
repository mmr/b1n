<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>

<link rel="stylesheet" href="<%= request.getContextPath() %>/css/jirator.css" />

<form action="task.list.logic" method="get">
	De <input type="text" name="startDate" value="${startDate}" /> até <input type="text" name="endDate" value="${endDate}" />
	<input type="submit" value="Go!" />
</form>

<hr />

<table>
  <tr>
    <th>Pessoa</th>
    <th>Tarefa</th>
    <th>Prioridade</th>
    <th>Severidade</th>
    <th>Complexidade</th>
    <th>Pontos</th>
  </tr>

<c:forEach var="e" items="${data}">
  <tr>
    <td rowspan="${e.value.tasksAmount + 1}">
      ${e.key}
    </td>
  </tr>

  <c:forEach var="t" items="${e.value.tasks}">
	<tr${t.trStyle}>
      <td><a href="https://www.tradeware.com.br/jira/browse/${t.jiraKey}">${t.jiraKey}</a></td>
      <td>${t.priority}</td>
      <td>${t.severity}</td>
      <td>${t.complexity}</td>
      <td>${t.pointsWorth}</td>
	</tr>
  </c:forEach>

  <tr>
    <td colspan="4">&nbsp;</td>
    <td>${e.value.totalPoints}</td>
  </tr>
</c:forEach>

</table>
