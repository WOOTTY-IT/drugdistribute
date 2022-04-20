<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drug VMI</title>
<?php include('java_function.php'); ?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="icon" sizes="30x40" href="images/drug_icon.png" />
<style type="text/css">
html {
	height:100%;
	width:100%;

}
table.center {
    margin-left: auto;
    margin-right: auto;
}
textarea:focus, input:focus{
    outline: 0;
}
body {
	color: #333;
	background-color: #999999;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #999999), color-stop(1, #4C4C4C) );
	background:-moz-linear-gradient( center top, #999999 5%, #4C4C4C 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#999999', endColorstr='#4C4C4C');
}
</style>
</head>

<body>
  <table width="300" border="0" class="center" cellpadding="1" cellspacing="0" style="position:absolute;top:35%; left:40%; border:0px">
  <form id="form1" name="form1" method="post" action="check_login.php">

    <tr>
      <td width="296" height="102" align="center" valign="top"><img src="images/distribution.png" width="243" height="79" /></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="10" class="rounded_top rounded_bottom">
        <tr>
          <td><table width="300" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr class="center">
              <td width="16%" height="32" bgcolor="#FFFFFF" class=" rounded_top_left rounded_bottom_left"><img src="images/icon-video.png" width="29" height="29" /></td>
              <td width="84%" bgcolor="#FFFFFF" class="rounded_top_right rounded_bottom_right" style="padding:0px"><input name="username_log" type="text" id="username_log" placeholder="username" style="border:0px; background-color:transparent; height:100%; width:90%; font-size:20px; font-weight:bolder; color:#999" /></td>
            </tr>
            <tr>
              <td style="padding:0px; height:5px">&nbsp;</td>
              <td style="padding:0px; height:5px">&nbsp;</td>
            </tr>
            <tr class="center">
              <td bgcolor="#FFFFFF" class=" rounded_top_left rounded_bottom_left" height="32"><img src="images/password.png" width="26" height="30" /></td>
              <td bgcolor="#FFFFFF" class="rounded_top_right rounded_bottom_right" style="padding:0px"><input name="password_log" type="password" id="password_log" placeholder="password" style="border:0px; background-color:transparent; height:100%; width:90%; font-size:20px; font-weight:bolder; color:#999" /></td>
            </tr>
            <tr class="center">
              <td colspan="2"></td>
              </tr>
            <tr class="center">
              <td colspan="2" align="right" style="padding-right:0px; padding-left:0px"><input name="chk" type="checkbox" id="chk" value="on" style="width:20px; height:20px" />
                <span class="big_white16">login ตลอด&nbsp;&nbsp;   <span onclick="document.forms['form1'].submit();" class="big_white16 button blue" style="border:0px; cursor:pointer; font-size:20px">เข้าสู่ระบบ</span></td>
            </tr>
            </table></td>
        </tr>
        <tr style="border:0px">
          <td align="right"  class="login big_white16 rounded_bottom rounded_top"  ></td>
        </tr>
      </table></td>
    </tr>
</form>
  </table>

</body>
</html>