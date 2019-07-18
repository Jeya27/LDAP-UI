<?php
	session_start();
?>
<!doctype html>
<html>
	<head>
	<link href="style.css" rel = "stylesheet" type = "text/css">
	</head>
<body>

<button onclick="location.href='logpage.php';" style="width:10%;position:absolute;top:2%;right:2%;">Log</button>

<button onclick="location.href='active.php';" style="width:10%;position:absolute;top:15%;right:2%;">Active Users</button>
<?php	

	echo "<div class = 'container'>";
	
	function write_to_log()
	{
		require_once("fSQL.php");

		$fsql = new fSQLEnvironment;
		
		$today = date("d-M-Y");

		if(mkdir("Logs/$today", 0777,true))
		{
			$fsql->define_db("logdb", "Logs/$today")
    			or die("DEFINE_DB logdb failed: ".$fsql->error());

			$fsql->select_db("logdb")
    			or die("SELECT_DB logdb failed: ".$fsql->error());

			$fsql->query("CREATE TABLE log(Userid VARCHAR(25), Name VARCHAR(50), MailId VARCHAR(70), IP VARCHAR(20), Time VARCHAR(30))")
    			or die("CREATE TABLE log failed: ".$fsql->error());			
		}
		
		$fsql->define_db("logdb", "Logs/$today")
    			or die("DEFINE_DB logdb failed: ".$fsql->error());

		$fsql->select_db("logdb")
				or die("SELECT DB logdb failed: ".$fsql->error());
		$timestamp = time();
		$datentime = date("F d, Y h:i:s A", $timestamp);
		
		$ip = $_SERVER["REMOTE_ADDR"];
		$root = $_SERVER["DOCUMENT_ROOT"];
		$userid = $_SESSION["user"];
		$mail = $_SESSION["mail"];
		$name = $_SESSION["name"];
				
		$results = $fsql->query("INSERT into log values ('$userid', '$name', '$mail', '$ip', '$datentime')")
  			or die("INSERT failed: ".$fsql->error());
  		
  		$results = $fsql->query("SELECT * FROM log")
    		or die("SELECT failed: ".$fsql->error());
	}
	
	if(array_key_exists('logout', $_POST))
	{
		remove_user();
	}
	
	function remove_user()
	{	
		require_once("fSQL.php");
		
		$user = $_SESSION['user'];

		$fsql = new fSQLEnvironment;
		
		if($user != "")
		{
		
		$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());

		$fsql->select_db("userdb")
				or die("SELECT DB userdb failed: ".$fsql->error());
		
		$results = $fsql->query("DELETE FROM activeusers WHERE Userid='$user'")
  			or die("DELETE failed: ".$fsql->error());
  	
  		}
  			
  		header("location:index.php");
	}
	
	function change_password($userdn, $ldap_con,$newpassword)
	{
		$newval = array();
		
		$npwd = '{MD5}'.base64_encode(md5($newpassword,true));
		$newval["userpassword"][0] = $npwd;
		
		ldap_modify($ldap_con, $userdn, $newval);
		echo "<h1 class='message'>Password Modified!!!</h1>";
	}
	
	function find_dept($ldap_con, $user)
	{
		$filter = "cn=$user";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$gid = $result[0]["gidnumber"][0];
		
		return $gid;
	}
	
	$ldap_con = ldap_connect("myldap.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	$user = $_SESSION["user"];
	$fullname = $_SESSION["name"];	
	$pwd = $_SESSION['pwd'];
	
	if($user == "" && $pwd == "")
	{
		$user = $_POST['user'];
		$pwd = $_POST['pwd'];
	}
	
	$newpassword = $_POST['newpwd'];
	$cnfpassword = $_POST['cnfpwd'];

	$filter = "cn=$user";
	$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
	$result = ldap_get_entries($ldap_con, $search);
		
	$userdn = $result[0]["dn"];
	$mailid = $result[0]["mail"][0];
	$fullname = $result[0]["givenname"][0]." ".$result[0]["sn"][0];
	
	$_SESSION["mail"] = $mailid;
	
	if(ldap_bind($ldap_con, $userdn, $pwd))
	{
		change_password($userdn, $ldap_con,$newpassword);
		write_to_log();
	}
	else
	{
		echo "<h1 class='message' style='color:blue'>Invalid Username/Password</h1>";
	}
	
	echo "<div class = 'container'>";
?>
<form method="POST">
	<input type = "submit" name = "logout" id = "logout" value = "Logout">
</form>
</body>
</html>
