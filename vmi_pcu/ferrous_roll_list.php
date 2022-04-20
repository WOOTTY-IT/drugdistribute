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
if($_GET['dept']!=""){
	$condition=" and d.hospcode='$dept'";
	}
if(isset($_GET['action_do'])&&($_GET['action_do']=="show")){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,order_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,SUBSTR(order_date,1,7) as 'month'  FROM iodine_ferrous d left OUTER JOIN hospcode h on h.hospcode=d.hospcode WHERE SUBSTR(order_date, 1,7)=SUBSTR(CURDATE(), 1,7)  GROUP BY d.hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}
if(isset($_GET['action_do'])&&($_GET['action_do']=="search")){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,order_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,SUBSTR(order_date,1,7) as 'month' FROM iodine_ferrous d left OUTER JOIN hospcode h on h.hospcode=d.hospcode WHERE SUBSTR(order_date, 1,7)='".$monthyear."' ".$condition." GROUP BY d.hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}

?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_drug_roll > 0) { // Show if recordset not empty ?>
  <table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="6%" align="center">ลำดับ</td>
      <td width="31%" align="left" style="padding-left:10px">ชื่อหน่วยงาน</td>
      <td width="12%" align="center">จำนวนรายการ</td>
      <td width="30%" align="center">ช่วงวันที่เบิก</td>
      <td width="15%" align="center">สถานะ</td>
      <td width="6%" align="center">&nbsp;</td>
    </tr>
    <?php $i=0; do { $i++; 
	mysql_select_db($database_vmi, $vmi);
$query_rs_date_period = "SELECT max(order_date),min(order_date) from iodine_ferrous where SUBSTR(order_date, 1,7)='".$row_drug_roll['month']."' and hospcode='".$row_drug_roll['hospcode']."'";
$rs_date_period = mysql_query($query_rs_date_period, $vmi) or die(mysql_error());
$row_rs_date_period = mysql_fetch_assoc($rs_date_period);
$totalRows_rs_date_period = mysql_num_rows($rs_date_period);

	?>
    <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px"><span><?php echo $row_drug_roll['hospname']; ?></span></td>
      <td align="center"><span><?php echo $row_drug_roll['count(*)']; ?></span></td>
      <td align="center"><span><?php echo dateThai($row_rs_date_period['min(order_date)']); ?> - <?php echo dateThai($row_rs_date_period['max(order_date)']); ?></span></td>
      <td align="right"><a href="javascript:poppage('confirm_ferrous_take.php?confirm=<?php if($row_drug_roll['confirm']=="Y"){ echo "N"; } else{echo "Y"; } ?>&deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo substr($row_drug_roll['order_date'],0,7); ?>','300','210','','show','ferrous_roll_list.php','drug_roll_list','');" class="<?php if($row_drug_roll['confirm']=="Y"){ echo "small_red_bord"; } else { echo "small_blue2"; }?>">
        <span><?php if($row_drug_roll['confirm']=="Y"){ echo "<img src=\"images/right_icon.png\" width=\"23\" height=\"23\" border=\"0\" align=\"absmiddle\" />&nbsp;ยกเลิก"; } else{echo "ยืนยัน"; } ?>รับใบเบิก</span></a></td>
      <td align="center"><?php if($row_drug_roll['confirm']=="Y"){?>
        <a href="javascript:poppage2('ferrous_take_roll_print.php?deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo $row_drug_roll['month']; ?>','90%','90%');"><img src="images/Printer and Fax.png" width="27" height="27" border="0"></a>        <?php } ?></td>
    </tr>
    <?php mysql_free_result($rs_date_period);
} while ($row_drug_roll = mysql_fetch_assoc($drug_roll)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
if(isset($_GET['action_do'])&&($_GET['action_do']=="show")){
mysql_free_result($drug_roll);
}
?>
