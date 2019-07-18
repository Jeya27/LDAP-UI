# LDAP-UI

UI to make changes in LDAP server. PhpLdapAdmin is accessed using PHP to make changes. This UI allows a user to change password,create a new user, create a new group and deactivate a user.

1) Change Password :
       It modifies the user password.It logs the users whose passwords are changed day wise.The logging is stored day wise in flat file database using fSQL(Flat File SQL).The queries of fSQL are similar to SQL. Active Users display who are currently logged in LDAP.

2) AddUser:
       Adds a new user to LDAP server.Takes all the necessary requirements and add the user to a particular group.The group name is displayed based on the available groups in LDAP.The Uidnumber ang gidnumber are provided automatically.

3) AddGroup:
       Creates a new group by taking only the name of the group as input.Assigns gidnumber of the group.

4) DeactivateUser:
       Deactivate sets the user password to null.When the password is null the user cannot connect to the LDAP server.
       
AddMail:
      This is to add e-mail address to all the users and updating it in LDAP server.It reads data from csv file and to a particular username it updates the mail Id.
