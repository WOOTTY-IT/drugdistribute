<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_datacenter = "61.7.162.20:3361";
$database_datacenter = "datacenter";
$username_datacenter = "root";
$password_datacenter = "inv11444";
$datacenter = mysql_pconnect($hostname_datacenter, $username_datacenter, $password_datacenter) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES UTF8");

?>