<?php
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


mysql_select_db($database_vmi, $vmi);
$query_rs_login = "select u.*,t.chwpart,t.amppart,t.tmbpart from user u left outer join hospcode t on t.hospcode=u.hospcode where u.username='".$_POST['username_log']."' and u.password='".$_POST['password_log']."'";
$rs_login = mysql_query($query_rs_login, $vmi) or die(mysql_error());
$row_rs_login = mysql_fetch_assoc($rs_login);
$totalRows_rs_login = mysql_num_rows($rs_login);

if($totalRows_rs_login<>0){
if($chk == "on") { // ถ้าติ๊กถูก Login ตลอดไป ให้ทำการสร้าง cookie
setcookie("username_log",$username_log,time()+3600*24*356);
setcookie("password_log",$password_log,time()+3600*24*356);
setcookie("member_status",$row_rs_login['member_status'],time()+3600*24*356);
setcookie("hospcode",$row_rs_login['hospcode'],time()+3600*24*356);
setcookie("chwpart",$row_rs_login['chwpart'],time()+3600*24*356);
setcookie("amppart",$row_rs_login['amppart'],time()+3600*24*356);
setcookie("tmbpart",$row_rs_login['tmbpart'],time()+3600*24*356);
setcookie("user_item_type",$row_rs_login['item_type'],time()+3600*24*356);

setcookie("hospcode_type_id",$row_rs_login['tmbpart'],time()+3600*24*356);

header("location:index.php"); //ไปไปตามหน้าที่คุณต้องการ
} 
else {
// ถ้าไม่ติ๊กถูก Login
//session_register("username_log");
//session_register("password_log");
//session_register("member_status");
//session_register("hospcode");
//session_register("chwpart");
//session_register("amppart");
//session_register("tmbpart");


$_SESSION['username_log']=$_POST['username_log'];
$_SESSION['password_log']=$_POST['password_log'];
$_SESSION['member_status']=$row_rs_login['member_status'];
$_SESSION['hospcode']=$row_rs_login['hospcode'];
$_SESSION['chwpart']=$row_rs_login['chwpart'];
$_SESSION['amppart']=$row_rs_login['amppart'];
$_SESSION['tmbpart']=$row_rs_login['tmbpart'];
$_SESSION['hospcode_type_id']=$row_rs_login['hospcode_type_id'];
$_SESSION['user_item_type']=$row_rs_login['item_type'];

header("location:index.php"); //ไปไปตามหน้าที่คุณต้องการ
}
} 
else {
header("location: first.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
}

?>
<?php
mysql_free_result($rs_login);
?>
