<!doctype html>
<html>
<body>
<?php

	function find_dept($ldap_con, $deptid)
	{
		$filter = "gidnumber=$deptid";
		$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
		$result = ldap_get_entries($ldap_con, $search);
		
		$grp = $result[0]["cn"][0];
		
		return $grp;
	}

	$ldap_dn = "cn=admin,dc=myldap,dc=com";
	$ldap_pwd = "2success";
	
	$ldap_con = ldap_connect("myldap.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	$bind = ldap_bind($ldap_con, $ldap_dn, $ldap_pwd);
	
	$row = 1;
	if (($handle = fopen("test.csv", "r")) !== FALSE)
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
   		{
   		    $num = count($data);
   		    $row++;
      
			$user = $data[0];
			$email = $data[1];
							
			$filter = "cn=$user";
			$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
			$result = ldap_get_entries($ldap_con, $search);
				
			$deptid = $result[0]["gidnumber"][0];
			$dept = find_dept($ldap_con, $deptid);
				
			$newval["mail"] = $email;
			ldap_modify($ldap_con, "cn=$user,cn=$dept,dc=myldap,dc=com", $newval);		
  		}
   		fclose($handle);
	}
?>
</body>
</html>
