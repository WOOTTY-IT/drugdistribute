<?php 
session_start();
setcookie("username_log","",time()-3600*24*356);
setcookie("password_log","",time()-3600*24*356);
setcookie("member_status","",time()-3600*24*356);
setcookie("hospcode","",time()-3600*24*356);
session_destroy();
session_write_close();
header("location:first.php"); //ไปไปตามหน้าที่คุณต้องการ
?>