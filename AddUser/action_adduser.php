<?php 
	session_start();
?>
<!doctype html>
<html>
	<head>
		<link href = "style.css" rel="stylesheet" type="text/css">	
		<style>			
			a{
				text-align:center;
				display:block;
			}
		</style>
	</head>
<body>
<?php

	function print_user_info($ldap_con,$uid,$cn,$dept)
	{
		echo "<div class='container'>";
		echo "<h1>Created User $uid</h1>";
		
		echo "<table><tr><th>Attributes</th><th>Value</th></tr>";
		$filter = "(cn=$cn)";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		echo "<tr><td>Given Name </td><td>";
		echo $result[0]["givenname"][0]."</td></tr>";
		
		echo "<tr><td>Surname </td><td>";
		echo $result[0]["sn"][0]."</td></tr>";
		
		echo "<tr><td>Common Name</td><td>";
		echo $result[0]["cn"][0]."</td></tr>";
		
		echo "<tr><td>Email ID </td><td>";
		echo $result[0]["mail"][0]."</td></tr>";
		
		echo "<tr><td>User ID </td><td>";
		echo $result[0]["uid"][0]."</td></tr>";
		
		echo "<tr><td>Unique ID Number </td><td>";
		echo $result[0]["uidnumber"][0]."</td></tr>";
		
		echo "<tr><td>Group</td><td>";
		echo $dept."</td></tr>";
		
		echo "<tr><td>Group ID Number </td><td>";
		echo $result[0]["gidnumber"][0]."</td></tr>";
		
		echo "<tr><td>Homedirectory </td><td>";
		echo $result[0]["homedirectory"][0]."</td></tr>";
		
		echo "<tr><td>Login Shell</td><td>";
		echo $result[0]["loginshell"][0]."</td></tr>";	
		echo "</table>";
		
		echo "</div>";
	}
	
	$ldap_dn = "cn=admin,dc=myldap,dc=com";
	$ldap_pwd = "2success";
	
	$ldap_con = ldap_connect("myldap.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	$bind=ldap_bind($ldap_con, $ldap_dn, $ldap_pwd);
	
	if($bind) 
	{	
		$givenname = $_SESSION['givenname'];
		$sn = $_SESSION['lastname'];
		$cn = $_SESSION['cn'];
		$uid = $_SESSION['uid'];
		$dept = $_SESSION['dept'];
		$pwd = $_SESSION['pwd'];
		$uidnumber = $_SESSION['uidnumber'];
		$gidnumber = $_SESSION['gidnumber'];
		$mail = $_SESSION['mail'];
		
		$entry["objectclass"][0] ="inetOrgPerson";
		$entry["objectclass"][1] = "posixAccount";
		$entry["objectclass"][2] = "top";
		
		$entry["cn"][0] = $cn;
		$entry["sn"][0] = $sn;
		$entry["givenname"][0] = $givenname;
		
		$entry["uid"] = $uid;
		$entry["uidnumber"][0] = $uidnumber;
		$entry["gidnumber"][0] = $gidnumber;
		
		//$pwd = '{MD5}'.base64_encode(md5($pwd,true));
		$entry["userpassword"][0] = $pwd;
		$entry["mail"][0] = $mail;
		
		$entry["homedirectory"][0] = "/home/users/$uid";
		$entry["loginshell"][0] = "/bin/bash"; 
		
		$bind=ldap_add($ldap_con,"cn=$cn,cn=$dept,dc=myldap,dc=com", $entry);
	
		print_user_info($ldap_con,$uid,$cn,$dept);
	}
	else
	{
		echo "<div class='container'>";
		echo "Could not bind to LDAP server!";
		echo "</div>";
	}
?>
<a href = "index.php" alt="Link to Home">Exit</a>
</body>
</html>
