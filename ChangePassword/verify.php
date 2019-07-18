<?php
	session_start();
?>
<!doctype html>
<html>
	<head>
		<link href="style.css" rel = "stylesheet" type = "text/css">
	</head>
<body>
<?php

    header('Content-Type: application/json');

    $aResult = array();

    if( !isset($_POST['functionname']) ) 
    { 
    	$aResult['error'] = 'No function name!'; 
    }

    if( !isset($_POST['arguments']) ) 
    { 
    	$aResult['error'] = 'No function arguments!'; 
    }

    if( !isset($aResult['error']) ) 
    {
        switch($_POST['functionname']) 
        {
            case 'verify_login':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) ) 
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else 
               {
          			$ldap_con = ldap_connect("myldap.com");	
					ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
          			
          			$filter = "cn=$user";
					$search = ldap_search($ldap_con, "dc=myldap,dc=com", $filter);
					$result = ldap_get_entries($ldap_con, $search);
		
					$userdn = $result[0]["dn"];
					
					if(ldap_bind($ldap_con, $userdn, $pwd))
					{
						$aResult['result'] = "Logged in!!!";
					}
					else
					{
						$aResult['result'] = "Invalid Username/Password";
					}
               }
               break;

            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }

    echo json_encode($aResult);

?>
<a id="exit">Exit</a>
</body>
</html>
<?php
	unset($_SESSION['views']);
?>
