<?php 
	session_start();
?>
<!doctype html>
<html>
	<head>
		<link href = "style.css" rel="stylesheet" type="text/css">
		<style>
			button {
				width: 49.5%;
				background-color:grey;
			}
		</style>
	</head>
<body>
<?php	
	function find_dept($ldap_con, $dept)
	{
		$filter = "cn=$dept";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$gid = $result[0]["gidnumber"][0];
		
		return $gid;
	}
	
	function get_uidnumber($ldap_con)
	{
		$filter = "objectclass=inetOrgPerson";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$last = $result["count"];
		$lastuid = $result[$last -1]["uidnumber"][0];
		
		return $lastuid+1;		
	}
	
	function encrypt_password($pwd, $encode)
	{
	
		if($encode == 'md5')
		{	
			$encrypted_password = '{MD5}'.base64_encode(md5( $pwd,TRUE));
		}
		elseif($encode == 'sha')
		{
			$encrypted_password = '{SHA}'.base64_encode(sha1( $pwd, TRUE ));
		}
		elseif($encode == 'ssha')
		{
			$encrypted_password = '{SSHA}'.base64_encode(sha1($pwd, TRUE));
		}		
		elseif($encode == 'clear')
		{
			$encrypted_password = $pwd;
		}			
		elseif($encode == 'crypt')
		{
			$encrypted_password = '{crypt}'.crypt( $pwd);
		}		
		return $encrypted_password;
	}
	
	function user_info($ldap_con)
	{
		echo "<div class='container'>";
		echo "<h1>Confirm User Details</h1>";
		
		echo "<table><tr><th>Attributes</th><th>Value</th></tr>";

		$givenname = $_POST['firstname'];
		$sn = $_POST['lastname'];
		$cn = $_POST['cn'];
		$uid = $_POST['uid'];
		$dept = $_POST['dept'];
		$pwd = $_POST['pwd'];
		$encode = $_POST['encode'];
		$mail = $_POST['mail'];
				
		$_SESSION['givenname'] = $givenname;
		$_SESSION['lastname'] = $sn;
		$_SESSION['cn'] = $cn;
		$_SESSION['uid'] = $uid;
		$_SESSION['dept'] = $dept;
		$_SESSION['pwd'] = encrypt_password($pwd, $encode);
		$_SESSION['mail'] = $mail;
		
		$uidnumber = get_uidnumber($ldap_con);
		$gidnumber = find_dept($ldap_con, $dept);
		
		$_SESSION['uidnumber'] = $uidnumber;
		$_SESSION['gidnumber'] = $gidnumber;
				
		echo "<tr><td>Given Name </td><td>";
		echo $givenname."</td></tr>";
		
		echo "<tr><td>Surname </td><td>";
		echo $sn."</td></tr>";
		
		echo "<tr><td>Common Name </td><td>";
		echo $cn."</td></tr>";
		
		echo "<tr><td>Email ID </td><td>";
		echo $mail."</td></tr>";
		
		echo "<tr><td>User ID </td><td>";
		echo $uid."</td></tr>";
		
		echo "<tr><td>Group</td><td>";
		echo $dept."</td></tr>";

		echo "</table>";
		
		echo "</div>";
	}
	
	$ldap_dn = "cn=admin,dc=myldap,dc=com";
	$ldap_pwd = "2success";
	
	$ldap_con = ldap_connect("myldap.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	$bind=ldap_bind($ldap_con, $ldap_dn, $ldap_pwd);
	
	user_info($ldap_con);
?>

<div class = 'container'>
	<button id="back" onclick = "history.go(-1)">Modify</button>
	<button onclick = "location.href='action_adduser.php';">Confirm</button>
</div>

</body>
</html>
