<?php
$host_name = '10.0.1.250';
$user_name = 'mhhos';
$pass_word = 'mhhos10967';
$database_name = 'hos';

$conn = mysql_connect($host_name, $user_name, $pass_word) or die ('Error connecting to mysql');
mysql_select_db($database_name);
mysql_query("SET NAMES utf8");

?>