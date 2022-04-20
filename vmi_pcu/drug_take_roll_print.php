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
$query_rs_drug_list = "SELECT d.drug_name,d.pack_ratio,d.sale_unit,d.item_type,t.*,SUBSTR(drug_take_date,1,7) as 'month',i.item_type_name FROM drug_take t left OUTER JOIN drugs d on d.working_code=t.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join item_type i on i.id=d.item_type WHERE t.hospcode='".$_GET['deptcode']."' and SUBSTR(drug_take_date,1,7)='".$_GET['month']."' and d.item_type='".$_GET['item_type']."' ";
$rs_drug_list = mysql_query($query_rs_drug_list, $vmi) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select concat(hosptype,name) as hospname,hospcode from hospcode where hospcode='".$_GET['deptcode']."'";
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
<div class=" thfont font_border" style="font-size:20px"  align="center">ใบเบิก<?php echo $row_rs_drug_list['item_type_name']; ?>ประจำเดือน <?php echo monthThai($row_rs_drug_list['month']); ?></div>
<div class="thfont" align="center" style="padding-top:10px;">สถานบริการ&nbsp; <?php echo $row_rs_dept['hospname']; ?></div>
<div align="center" class=" thfont font16">เลขที่ใบเบิก&nbsp;:&nbsp;<?php echo "ODM-".$row_rs_dept['hospcode']."/".$row_rs_drug_list['item_type']."-".date('Ym'); ?></div>


<table width="100%" border="0" cellpadding="3" cellspacing="0" class=" thfont font16 table_collapse" >
  <tr>
    <td width="4%" align="center" class="table_border_black thfont">ลำดับ</td>
    <td width="40%" align="left"  class="table_border_black thfont" style="padding-left:10px;">รายการเวชภัณฑ์</td>
    <td width="12%" align="center" class="table_border_black thfont" style="">จำนวนเบิก</td>
    <td width="14%" align="center" class="table_border_black thfont" style="">ขนาดบรรจุ</td>
    <td width="10%" align="center" class="table_border_black thfont" style="">หน่วย</td>
    <td width="20%" align="center" class="table_border_black thfont" style="">หมายเหตุ</td>
  </tr>
     <?php $i=0; do { $i++; ?>
 <tr>
    <td align="center" class="table_border_black thfont" style=""><?php echo $i; ?></td>
      <td align="left" class="table_border_black thfont" style="padding-left:10px;"><?php echo $row_rs_drug_list['drug_name']; ?></td>
    <td align="center" class="table_border_black thfont" style=""><?php if($row_rs_drug_list['pack_ratio']==""||$row_rs_drug_list['among']==""){ echo 0; } else {echo ($row_rs_drug_list['among']/$row_rs_drug_list['pack_ratio']);} ?></td>
      <td align="center" class="table_border_black thfont" style=""><?php if($row_rs_drug_list['pack_ratio']!=""){ echo $row_rs_drug_list['pack_ratio']; } ?></td> 
      <td align="center" class="table_border_black thfont" style=""><?php print $row_rs_drug_list['sale_unit']; ?></td>
      <td align="center" class="table_border_black">&nbsp;</td>
  </tr>      <?php } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>

</table>
<br />
<div class="thfont" align="center" style="padding:10px;">ลงชื่อ _________________ ผู้เบิก</div>
<div class="thfont" align="center" style="padding:10px;">(............................................)</div>
<div class="thfont" align="center" >วันที่......../............/.............</div>    
<div class="thfont" align="center" >ตำแหน่ง...........................................</div>    
</body>
</html>
<?php
mysql_free_result($rs_drug_list);

mysql_free_result($rs_dept);
?>
