<!doctype html>
<html>
	<head>
		<link href = "style.css" rel="stylesheet" type="text/css">
	</head>
<body>
<?php
	
	function get_gidnumber($ldap_con)
	{
		$filter = "objectclass=posixGroup";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$last = $result["count"];
		$lastuid = $result[$last -1]["gidnumber"][0];
		
		return $lastuid+1;		
	}
	
	function print_group_info($ldap_con, $cn)
	{
		echo "<div class='container'>";
		echo "<h1>Created Group $uid</h1>";
		
		echo "<table><tr><th>Attributes</th><th>Value</th></tr>";
		$filter = "(cn=$cn)";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		echo "<tr><td>Group Name </td><td>";
		echo $result[0]["cn"][0]."</td></tr>";
		
		echo "<tr><td>Group ID Number </td><td>";
		echo $result[0]["gidnumber"][0]."</td></tr>";
			
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
		$cn = $_POST['cn'];		
		
		$entry["objectclass"][0] = "posixGroup";
		$entry["objectclass"][1] = "top";
		
		$entry["cn"][0] = $cn;
		
		$entry["gidnumber"][0] = get_gidnumber($ldap_con);
		$bind=ldap_add($ldap_con,"cn=$cn,dc=myldap,dc=com", $entry);
	
		print_group_info($ldap_con, $cn);
	}
	
	else
	{
		echo "Could not bind to LDAP server!";
	}
?>
<a href = "index.php" alt="Link to Home">Exit</a>
</body>
</html>
