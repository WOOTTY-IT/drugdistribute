<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_datacenter = "203.157.207.76";
$database_datacenter = "datacenter";
$username_datacenter = "root";
$password_datacenter = "App$11444H";
#$datacenter = mysql_pconnect($hostname_datacenter, $username_datacenter, $password_datacenter) or trigger_error(mysql_error(),E_USER_ERROR); 
$datacenter = mysql_connect($hostname_datacenter, $username_datacenter, $password_datacenter) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES UTF8");

?>