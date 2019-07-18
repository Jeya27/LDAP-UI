<!doctype html>
<html>
<head>
	<title>Deactivate User</title>	
	<link href = "style.css" rel="stylesheet" type="text/css">	
</head>
	
<body>
<div class="container">
	<h1>Deactivate a User</h1>
	
	<form action="action_deactivate.php" method="POST">
		Common Name: <br>
		<input type = "text" name="cn" id="cn" placeholder="Enter your User ID" required><br>
		<button type="submit" id="adduser">Deactivate</button>		
	</form>
</div>
</body>
</html>
