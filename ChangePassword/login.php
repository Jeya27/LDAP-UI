<!doctype html>
<html>
<body>
<?php

	require 'index.php';
	
	function active_user()
	{
		require_once("fSQL.php");

		$fsql = new fSQLEnvironment;
		
		$user = $_SESSION['user'];
		$fullname = $_SESSION['name'];
		
		/*if(mkdir("Active", 0777,true))
		{
			$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());

			$fsql->select_db("userdb")
    			or die("SELECT_DB userdb failed: ".$fsql->error());

			$fsql->query("CREATE TABLE activeusers(Userid VARCHAR(25), Name VARCHAR(50), IP VARCHAR(20)")
    			or die("CREATE TABLE activeusers failed: ".$fsql->error());			
		}*/
		
		if($user != "" && $fullname != "")
		{
			$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());

			$fsql->select_db("userdb")
				or die("SELECT DB userdb failed: ".$fsql->error());
		
			$ip = $_SERVER["REMOTE_ADDR"];
				
			$results = $fsql->query("INSERT into activeusers values
				 ('$user', '$fullname', '$ip')")
  				or die("INSERT failed: ".$fsql->error());
  		}
	}

	$ldap_con = ldap_connect("myldap.com");	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
       
	$user = $_POST['user'];
	$pwd = $_POST['pwd'];
	
	$filter = "cn=$user";
	$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
	$result = ldap_get_entries($ldap_con, $search);
	
	$userdn = $result[0]["dn"];
	$fullname = $result[0]["givenname"][0]." ".$result[0]["sn"][0];
	
	$_SESSION['user'] = $user;
	$_SESSION['name'] = $fullname;
	$_SESSION['pwd'] = $pwd;
	$_SESSION['conn'] = $ldap_con;
    
    if($user != "" && $pwd != "")
    {				
		if(ldap_bind($ldap_con, $userdn, $pwd))
		{
			echo "<script>
			document.getElementById('login_check').style.color='green';
			document.getElementById('login_check').innerHTML = 'Logged in!!!';
			document.getElementById('newpwd').disable=false; 
			document.getElementById('cnfpwd').disable=false;
			document.getElementById('loginform').hidden=true;
			document.getElementById('first').hidden=true;
			document.getElementById('second').hidden=false;
			document.getElementById('chngform').disable=false;
			</script>";
			active_user();
		}
		else
		{
			echo "<script>
			document.getElementById('login_check').style.color='red';
			document.getElementById('login_check').innerHTML = 
				'Invalid Username/Password';
			document.getElementById('newpwd').disable=false; 
			document.getElementById('cnfpwd').disable=false;
			</script>";
		}	
	}
?>
</body>
</html>
