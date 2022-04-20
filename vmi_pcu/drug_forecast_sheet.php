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
if($data_type=="2"){

mysql_select_db($database_vmi, $vmi);
$query_rs_report = "select concat(h.hosptype,h.`name`) as hospname,i.drug_name,v.qty,v.pack_ratio  from vmi_order v left outer join drug_stdcode s on s.stdcode=v.stdcode left outer join hospcode h on h.hospcode=v.hospcode left outer join drugs i on i.working_code=s.working_code  where v.order_month ='$order_date' ORDER BY i.drug_name,v.hospcode";
}
else {
$query_rs_report = "select concat(h.hosptype,h.`name`) as hospname,i.drug_name,(v.qty/i.pack_ratio) as qty,i.pack_ratio 
from month_rate v left outer join drug_stdcode s on s.stdcode=v.didstd left outer join hospcode h on h.hospcode=v.hospcode left outer join drugs i on i.working_code=s.working_code 
where concat(v.year_rate,'-',v.month_rate) ='$order_date' and drug_name is not NULL ORDER BY i.drug_name,v.hospcode";
	
}
$rs_report = mysql_query($query_rs_report, $vmi) or die(mysql_error());
$row_rs_report = mysql_fetch_assoc($rs_report);
$totalRows_rs_report = mysql_num_rows($rs_report);
$month_year=$order_date.'--';
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบเบิกยา VMI</title>
<?php include('java_function.php'); ?>

</head>

<body>
<span class="big_black16">รายการเบิกประจำเดือน <?php echo monthThai($month_year); ?>
</span><br />
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr class="table_head_small_bord">
    <td height="28" align="center" class="table_border_black"> ลำดบ</td>
    <td align="center" class="table_border_black">รายการยา</td>
    <td align="center" class="table_border_black">จำนวน</td>
    <td align="center" class="table_border_black">ขนาดบรรจุ</td>
    <td align="center" class="table_border_black">หน่วยงาน</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr>
      <td height="32" align="center" class="table_border_black"><?php echo $i; ?></td>
      <td class="table_border_black"><?php echo $row_rs_report['drug_name']; ?></td>
      <td align="center" class="table_border_black"><?php echo $row_rs_report['qty']; ?></td>
      <td align="center" class="table_border_black"><?php echo $row_rs_report['pack_ratio']; ?></td>
      <td class="table_border_black"><?php echo $row_rs_report['hospname']; ?></td>
  </tr>      <?php } while ($row_rs_report = mysql_fetch_assoc($rs_report)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($rs_report);
?>
