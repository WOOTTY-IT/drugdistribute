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
$query_rs_dept = "select concat(h.hosptype,h.name) as deptname,u.* from user u left outer join hospcode h on h.hospcode=u.hospcode and h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' order by u.id DESC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>
<script type="text/javascript">
function buttonchange(){
	$('#save').val("บันทึก");
	$('#save').attr('onclick', 'formSubmit("save","form1","spending_report_list.php","spending_list","indicator");clearForm("sub_po_no","id","do");');
	}
function edit(a,b,c,d,e,f){
	$('#id').val(a);
	$('#sub_po_no').val(b);
	$('#month').val(c);
	$('#year').val(d);
	$('#dept').val(e);
	$('#save').val("แก้ไข");
	$('#save').attr('onclick', 'formSubmit("edit","form1","spending_report_list.php","spending_list","indicator",'+a+');clearForm("sub_po_no","id","do");buttonchange();');
	
	}
</script>
</head>

<body onload="formSubmit('load','form1','spending_report_list.php','spending_list','indicator');">
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
      <td class="gray table_head_small_bord" ><img src="images/markdown-icon.png" width="34" height="34" align="absmiddle" />&nbsp;&nbsp;บันทึกรายงานการจ่ายยา (เมนูนี้ยังไม่เปิดให้ใช้)</td>
    </tr>
    <tr>
      <td><span class="table_head_small">เลขใบตัดเบิก</span>        <input name="sub_po_no" type="text" class="inputcss1" id="sub_po_no" />
        &nbsp;<span class="table_head_small">ประจำเดือน 
        <select name="month" class="inputcss1" id="month">
          <option value="01" <?php if (!(strcmp("01", date('m')))) {echo "selected=\"selected\"";} ?>>มกราคม</option>
          <option value="02" <?php if (!(strcmp("02", date('m')))) {echo "selected=\"selected\"";} ?>>กุมภาพันธ์</option>
          <option value="03" <?php if (!(strcmp("03", date('m')))) {echo "selected=\"selected\"";} ?>>มีนาคม</option>
          <option value="04" <?php if (!(strcmp("04", date('m')))) {echo "selected=\"selected\"";} ?>>เมษายน</option>
          <option value="05" <?php if (!(strcmp("05", date('m')))) {echo "selected=\"selected\"";} ?>>พฤษภาคม</option>
          <option value="06" <?php if (!(strcmp("06", date('m')))) {echo "selected=\"selected\"";} ?>>มิถุนายน</option>
          <option value="07" <?php if (!(strcmp("07", date('m')))) {echo "selected=\"selected\"";} ?>>กรกฎาคม</option>
          <option value="08" <?php if (!(strcmp("08", date('m')))) {echo "selected=\"selected\"";} ?>>สิงหาคม</option>
          <option value="09" <?php if (!(strcmp("09", date('m')))) {echo "selected=\"selected\"";} ?>>กันยายน</option>
          <option value="10" <?php if (!(strcmp("10", date('m')))) {echo "selected=\"selected\"";} ?>>ตุลาคม</option>
          <option value="11" <?php if (!(strcmp("11", date('m')))) {echo "selected=\"selected\"";} ?>>พฤศจิกายน</option>
          <option value="12" <?php if (!(strcmp("12", date('m')))) {echo "selected=\"selected\"";} ?>>ธันวาคม</option>
        </select>
        <select name="year" class="inputcss1" id="year">
          <?php for($y=date('Y')-10;$y<=date('Y');$y++){ ?>
          <option value='<?php echo $y; ?>' <?php if (!(strcmp($y, date('Y')))) {echo "selected=\"selected\"";} ?>><?php echo ($y+543); ?></option>
          <?php } ?>
        </select> 
        หน่วยงาน</span>
<select name="dept" class="inputcss1" id="dept">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_dept['hospcode']?>"><?php echo $row_rs_dept['deptname']?></option>
          <?php
} while ($row_rs_dept = mysql_fetch_assoc($rs_dept));
  $rows = mysql_num_rows($rs_dept);
  if($rows > 0) {
      mysql_data_seek($rs_dept, 0);
	  $row_rs_dept = mysql_fetch_assoc($rs_dept);
  }
?>
        </select>
&nbsp; <input type="button" name="save" id="save" value="บันทึก" class="button gray2" onclick="formSubmit('save','form1','spending_report_list.php','spending_list','indicator');clearForm('sub_po_no','id','do');" />
<span style="padding-left:30px">
<input type="hidden" name="id" id="id" />
<input type="hidden" name="do" id="do" />
</span></td>
    </tr>
    <tr>
      <td><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/loader.gif" hspace="10" align="absmiddle" /></div>
  <div id="spending_list"></div></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($rs_dept);
?>
