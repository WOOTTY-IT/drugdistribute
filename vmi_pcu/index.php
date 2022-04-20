<?
ob_start();
session_start();
if($_SESSION['username_log']==""){
header("location: first.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" sizes="30x40" href="images/drug_icon.png" /><title>ระบบเบิกยา VMI</title>

</head>
<link rel="icon" sizes="30x40" href="images/drug_icon.png" />

<frameset rows="*" cols="249,*" frameborder="no" border="0" framespacing="0">
  <frame src="left.php" name="leftFrame" scrolling="auto" noresize="noresize" id="leftFrame" title="left" />
  <frameset rows="74,*" cols="*" framespacing="0" frameborder="no" border="0">
    <frame src="top.php?sub_page=<?php if($_GET['sub_page']!=""){ echo $_GET['sub_page']; } ?>" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="top" />    
    <frame src="main.php?sub_page=<?php if($_GET['sub_page']!=""){ echo $_GET['sub_page']; } ?>" name="mainFrame" id="mainFrame" title="main" />

  </frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
