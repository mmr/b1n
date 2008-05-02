<%@ taglib uri="http://tiles.apache.org/tags-tiles" prefix="tiles" %>
<html>
<head>
    <title><tiles:getAsString name="title"/></title>
	<link rel="stylesheet" href="<%= request.getContextPath() %>/css/jirator.css" />
</head>
<body>
<div id="container">
	<div id="header">
		<tiles:attribute name="header"/>
	</div>

	<div id="menu">
		<tiles:attribute name="menu"/>
	</div>

	<div id="body">
    	<tiles:attribute name="body"/>
	</div>
	
	<div id="footer">
	   	<tiles:attribute name="footer"/>
	</div>
</body>
</html>