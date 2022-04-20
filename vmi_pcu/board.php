<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<style type="text/css">
body {
	margin-left: 20px;
	margin-top: 20px;
	margin-right: 20px;
	margin-bottom: 20px;
}
</style>
</head>

<body onload="dosubmit('','show','board_list.php','board_list','');">
<table width="100%" border="0" cellspacing="0" cellpadding="20">
  <tr>
    <td class="gray table_head_small_bord"><img src="images/comment.png" width="31" height="29" align="absmiddle" />&nbsp; กระดานส่งข้อความ</td>
  </tr>
  <tr>
    <td style="padding-bottom:5px">

      <table width="500" border="0" cellpadding="0" cellspacing="0" class="table_head_small">
        <tr>
          <td width="120" align="center"><a href="javascript:poppage('message_board.php','80%','80%','','show','board_list.php','board_list','');" class="button orange" style="width:100px"><img src="images/mail_new.png" width="26" height="26" border="0" align="absmiddle" /> ส่งข้อความ</a></td>
          <td width="269" align="center">&nbsp;</td>
          <td width="54" align="center">&nbsp;</td>
          <td width="57" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
        </tr>
      </table>
      <div id="board_list"></div></td>
  </tr>
</table>

</body>
</html>