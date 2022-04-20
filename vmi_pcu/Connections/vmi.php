<?php
 date_default_timezone_set('Asia/bangkok');
if (!ini_get('register_globals')) {
    $superglobals = array($_SERVER, $_ENV,
        $_FILES, $_COOKIE, $_POST, $_GET);
    if (isset($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
    }
    foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
    }
}
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_vmi = "10.0.0.4";
$database_vmi = "vmi_pcu";
$username_vmi = "root";
// $password_vmi = "inv11444";
$password_vmi = "App$11444H";
$vmi = mysql_connect($hostname_vmi, $username_vmi, $password_vmi) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES UTF8");

?>