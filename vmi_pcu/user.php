<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

</head>

<body onload="dosubmit('','show','user_list.php','user_list','');">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td height="27" class="gray table_head_small_bord"><img src="images/add-user.png" width="35" height="35" align="absmiddle" /> <span class="table_head_small_bord">ระบบผู้ใช้งาน (User Management)</span></td>
  </tr>
  <tr>
    <td style="padding:20px;padding-bottom:5px"><input name="add" type="submit" class="button gray" id="add" value="+ เพิ่มผู้ใช้งาน" onclick="useradd('user_add.php','500','500');" /></td>
  </tr>
  <tr>
    <td style="padding:20px"><div id="user_list"></div></td>
  </tr>
</table>
</body>
</html>