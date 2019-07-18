<!doctype html>
<html>
<head>
	<title>Add New Group</title>
	<link href = "style.css" rel="stylesheet" type="text/css">	

</head>
	
<body>
<div class="container">
	<h1>Add New Group</h1>
	
	<form action="action_addGroup.php" method="POST">
		Group Name: <br>
		<input type = "text" name="cn" id="cn" 
			placeholder="Enter the group name" required><br>
		
		<button type="submit" id="adduser">Create New Group</button>	
	</form>
</div>
</body>
</html>
