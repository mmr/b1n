<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<table>
  <tr>
    <th>Id</th>
    <th>Login</ht>
    <th>Nome</th>
  </tr>

<c:forEach var="e" items="${data}">
  <tr>
    <td>${e.id}</td>
    <td>${e.jiraLogin}</td>
    <td>${e.userName}</td>
  </tr>
</c:forEach>
</table>