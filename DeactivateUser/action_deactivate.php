<!doctype html>
<html>
<body>
<?php
	
	$ldap_dn = "cn=admin,dc=myldap,dc=com";
	$ldap_pwd = "2success";
	
	$uid = $_POST['uid'];
	
	$filter = "uid=$uid";
	$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
	$result = ldap_get_entries($ldap_con, $search);
	
	$ldap_con = ldap_connect("myldap.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	$bind=ldap_bind($ldap_con, $ldap_dn, $ldap_pwd);
	
	if($bind) 
	{
		//echo "Connection established successfully!!!<br><br>";
		
		$filter = "uid=$uid";
		$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$dn = $result[0]["dn"];
		
		$entry["userpassword"] = "";
		ldap_modify($ldap_con, $dn, $entry);

	}	
	else
	{
		echo "Could not bind to LDAP server!";
	}
?>
</body>
</html>
