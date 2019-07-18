<!doctype html>
<html>
<head>
	<title>Active Users</title>
	<link href="style.css" rel = "stylesheet" type = "text/css">
	<meta http-equiv="refresh" content="10"/>
</head>

<body>
<h1> Current Active Users</h1>
<?php
	
	if(array_key_exists('logout', $_POST))
	{
		remove_user();
	}
	
	function remove_user()
	{	
		echo "Came in!!<br>";
		require_once("fSQL.php");
		
		$user = $_SESSION['user'];

		if($user!= "")
		{
			$fsql = new fSQLEnvironment;
		
			$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());
			echo "DB is defined<br>";
			$fsql->select_db("userdb")
				or die("SELECT DB userdb failed: ".$fsql->error());
			echo "DB is set<br>";		
			$results = $fsql->query("DELETE FROM activeusers WHERE Userid='$user'")
  				or die("DELETE failed: ".$fsql->error());
  		
  			echo "Deleted Users<br>";
  		}
  			
  			header("location:index.php");
	}

	require_once("fSQL.php");

	$fsql = new fSQLEnvironment;
	
	$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());

	$fsql->select_db("userdb")
		or die("SELECT DB userdb failed: ".$fsql->error());
				
	$results = $fsql->query("SELECT * FROM activeusers")
  		or die("SELECT failed: ".$fsql->error());	
  			
  	echo "<table class='container' style='width:60%'>";
	echo "</tr>";
		echo "<th>User ID</th>";
		echo "<th>Name</th>";	
		echo "<th>IP</th>";
	echo "</tr>";
	
	while($row = $fsql->fetch_array($results))
	{
	    echo "<tr><td>";
	    echo $row['Userid']."</td><td>";
	    echo $row['Name']."</td><td>";
	    echo $row['IP']."</td></tr>";
	}			
	
	echo "</table>";
?>
<form method="POST">
	<input style="width:25%" type = "submit" name = "logout" id = "logout" value = "Exit">
</form>
</body>
</html>
