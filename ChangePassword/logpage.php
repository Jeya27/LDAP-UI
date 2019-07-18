<?php
	session_start();
?>
<!doctype html>
<html>
<head>
	<title>Log Page</title>
	<link href="style.css" rel = "stylesheet" type = "text/css">
	<style>
		.container{
			padding:10%;
		}
	</style>
	<meta http-equiv="refresh" content="10"/>
</head>
<body>
<h1> Users Accessed </h1>
<?php

	if(array_key_exists('logout', $_POST))
	{
		remove_user();
	}
	
	function remove_user()
	{	
		require_once("fSQL.php");
		
		$user = $_SESSION['user'];

		if($user!= "")
		{
			$fsql = new fSQLEnvironment;
		
			$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());

			$fsql->select_db("userdb")
				or die("SELECT DB userdb failed: ".$fsql->error());
		
			$results = $fsql->query("DELETE FROM activeusers WHERE Userid='$user'")
  				or die("DELETE failed: ".$fsql->error());
  		}
  			
  			header("location:index.php");
	}

	$today = date("d-M-Y");
	require_once("fSQL.php");

	$fsql = new fSQLEnvironment;
	
	$fsql->define_db("logdb", "Logs/$today")
		or die("DEFINE_DB logdb failed: ".$fsql->error());

	$fsql->select_db("logdb")
		or die("SELECT_DB logdb failed: ".$fsql->error());
    			
   	$results = $fsql->query("SELECT * FROM log")
   		or die("SELECT failed: ".$fsql->error());
	
	echo "<table class='container' style='width:75%'>";
	echo "</tr>";
		echo "<th>User ID</th>";
		echo "<th>Name</th>";
		echo "<th>Mail Id</th>";	
		echo "<th>IP</th>";
		echo "<th>Time</th>";
	echo "</tr>";
	
	while($row = $fsql->fetch_array($results))
	{
	    echo "<tr><td>";
	    echo $row['Userid']."</td><td>";
	    echo $row['Name']."</td><td>";
	    echo $row['MailId']."</td><td>";
	    echo $row['IP']."</td><td>";
	    echo $row['Time']."</td></tr>";
	}			
	
	echo "</table>";
?>
<form method="POST">
	<input style="width:25%" type = "submit" name = "logout" id = "logout" value = "Exit">
</form>
</body>
</html>
