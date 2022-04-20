<?
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
 <script type="text/javascript">

</script>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

</head>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr>
    <td class="gray" style="padding:5px"><img src="images/department-icon.png" width="34" height="34" align="absmiddle" />&nbsp;<span class=" thsan-semibold " style="font-size:24px"> กำหนดกลุ่มของหน่วยงานเบิก</span></td>
  </tr>
  <tr>
    <td class="gray" style="padding:5px"><form id="dept_form" name="dept_form" method="post" action="dept_group_list.php">
     <span class=" thsan-light font18" style="color:#0033FF">ชื่อกลุ่มของหน่วยงานเบิก&nbsp; </span>
     <input name="dept" type="text" class="inputcss1" id="dept"  style="height:35px"/>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-danger"  />
<input type="hidden" name="id" id="id" />
<input type="hidden" name="do" id="do" />
    </form></td>
  </tr>
</table><div id="preview"></div>
</body>
</html>