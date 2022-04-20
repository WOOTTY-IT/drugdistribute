<?
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php include('include/function.php'); ?>
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
$query_rs_drug_list = "SELECT d.drug_name,g.factor,d.pack_ratio,d.sale_unit,t.*,SUBSTR(order_date,1,7) as 'month',g.group_age_detail,t.target FROM iodine_ferrous t left outer join group_age g on g.group_age =t.group_age left OUTER JOIN drugs d on d.working_code=g.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' WHERE t.hospcode='".$_GET['deptcode']."' and SUBSTR(order_date,1,7)='".$_GET['month']."' ";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select concat(hosptype,name) as hospname from hospcode where hospcode='".$_GET['deptcode']."'";
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
</head>

<body onload="print();">
<span class=" thsan-semibold" style="font-size:20px">ใบเบิกเวชภัณฑ์ยาโครงการส่งเสริมธาตุเหล็กสำหรับเด็กประจำเดือน <?php echo monthThai($row_rs_drug_list['month']); ?></span><br />
<span class="thsan-light font16">สถานบริการ&nbsp; <?php echo $row_rs_dept['hospname']; ?></span><br />

<table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thsan-light font16 table_collapse" >
  <tr>
    <td width="4%" align="center" class="table_border_black thfont" style="font-size:12px">ลำดับ</td>
    <td width="30%" align="left"  class="table_border_black thfont" style="padding-left:10px;font-size:12px"">กลุ่ม</td>
    <td width="30%" align="left"  class="table_border_black thfont" style="padding-left:10px;font-size:12px"">รายการเวชภัณฑ์</td>
    <td width="12%" align="center" class="table_border_black thfont" style="font-size:12px"">เป้า</td>
    <td width="12%" align="center" class="table_border_black thfont" style="font-size:12px"">จำนวนเบิก</td>
    <td width="14%" align="center" class="table_border_black thfont" style="font-size:12px"">ขนาดบรรจุ</td>
    <td width="10%" align="center" class="table_border_black thfont" style="font-size:12px"">หน่วย</td>
    <td width="16%" align="center" class="table_border_black thfont" style="font-size:12px"">จำนวนจ่าย</td>
    <td width="14%" align="center" class="table_border_black thfont" style="font-size:12px"">หมายเหตุ</td>
  </tr>
     <?php $i=0; do { $i++; ?>
 <tr>
    <td align="center" class="table_border_black thfont" style="font-size:12px"><?php echo $i; ?></td>
    <td align="left" class="table_border_black thfont" style="padding-left:10px;font-size:12px"><span class="table_border_black thfont" style="padding-left:10px;font-size:12px"><?php echo $row_rs_drug_list['group_age_detail']; ?></span></td>
      <td align="left" class="table_border_black thfont" style="padding-left:10px;font-size:12px"><?php echo $row_rs_drug_list['drug_name']; ?></td>
      <td align="center" class="table_border_black thfont" style="font-size:12px"><span class="table_border_black thfont" style="padding-left:10px;font-size:12px"><?php echo $row_rs_drug_list['target']; ?></span></td>
    <td align="center" class="table_border_black thfont" style="font-size:12px"><?php echo ceil($row_rs_drug_list['target']/$row_rs_drug_list['factor']); ?></td>
      <td align="center" class="table_border_black thfont" style="font-size:12px"><?php echo $row_rs_drug_list['pack_ratio']; ?></td> 
      <td align="center" class="table_border_black thfont" style="font-size:12px"><?php print $row_rs_drug_list['sale_unit']; ?></td>
      <td align="center" class="table_border_black">&nbsp;</td>
      <td align="center" class="table_border_black">&nbsp;</td>
  </tr>      <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>

</table>
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thfont font12">
  <tr>
    <td width="50%" align="center">ลงชื่อ _________________ ผู้รับ<br />
(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; )<br />
___/_______/________</td>
    <td width="50%" align="center">ลงชื่อ _________________ ผู้จ่าย<br />
    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; )<br />
    ___/_______/________</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($rs_drug_list);

mysql_free_result($rs_dept);
?>
