<?php require_once('Connections/vmi.php'); ?>
<?
ob_start();
session_start();
?>
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

mysql_select_db($database_vmi, $vmi);
$query_drug_last = "SELECT concat(d.year,'-',d.month) as 'month'  FROM vaccine_take d left OUTER JOIN hospcode h on h.hospcode=d.hospcode where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' GROUP BY concat(year,'-',month) order by concat(year,month) DESC limit 1";
$drug_last = mysql_query($query_drug_last, $vmi) or die(mysql_error());
$row_drug_last = mysql_fetch_assoc($drug_last);
$totalRows_drug_last = mysql_num_rows($drug_last);

if($_GET['monthyear']!=""){
	$monthyear=$_GET['monthyear'];
}
else{
	$monthyear=$row_drug_last['month'];
}

if(isset($_GET['action_do'])){
if($_GET['action_do']=="show"){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,vaccine_take_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,concat(d.year,'-',d.month) as 'month'  FROM vaccine_take d left OUTER JOIN hospcode h on h.hospcode=d.hospcode WHERE concat(d.year,'-',d.month)='".$row_drug_last['month']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'  GROUP BY hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}
if($_GET['action_do']=="search"){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,vaccine_take_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,concat(d.year,'-',d.month) as 'month' FROM vaccine_take d left OUTER JOIN hospcode h on h.hospcode=d.hospcode WHERE concat(d.year,'-',d.month)='".$_GET['monthyear']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' ".$condition." GROUP BY hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}

mysql_select_db($database_vmi, $vmi);
$query_rs_notsend = "select concat(hosptype,' ',name) as hospname from user u left outer join hospcode h on h.hospcode=u.hospcode where u.hospcode not in (select v.hospcode from vaccine_take v where concat(year,'-',month)='".$monthyear."' group by v.hospcode) and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by u.hospcode ";
$rs_notsend = mysql_query($query_rs_notsend, $vmi) or die(mysql_error());
$row_rs_notsend = mysql_fetch_assoc($rs_notsend);
$totalRows_rs_notsend = mysql_num_rows($rs_notsend);

}

	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from vaccine_take where hospcode=''";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());


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
  <table width="850" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="5%" align="center">ลำดับ</td>
      <td width="22%" align="left" style="padding-left:10px">ชื่อหน่วยงาน</td>
      <td width="11%" align="center">จำนวนรายการ</td>
      <td width="27%" align="center">ช่วงวันที่เบิก</td>
      <td width="16%" align="center">สถานะ</td>
      <td width="11%" align="center">เดือนนี้</td>
      <td width="8%" align="center">เดือนที่แล้ว</td>
    </tr>
    <?php $i=0; do { $i++; 
	mysql_select_db($database_vmi, $vmi);
$query_rs_date_period = "SELECT max(vaccine_take_date),min(vaccine_take_date) from vaccine_take where concat(year,'-',month)='".$row_drug_roll['month']."' and hospcode='".$row_drug_roll['hospcode']."'";
$rs_date_period = mysql_query($query_rs_date_period, $vmi) or die(mysql_error());
$row_rs_date_period = mysql_fetch_assoc($rs_date_period);
$totalRows_rs_date_period = mysql_num_rows($rs_date_period);

mysql_select_db($database_vmi, $vmi);
$query_rs_lastmonth = "SELECT * from vaccine_take where concat(year,month)<'".str_replace('-','',$row_drug_roll['month'])."' and hospcode='".$row_drug_roll['hospcode']."' order by vaccine_take_date DESC limit 1";
$rs_lastmonth = mysql_query($query_rs_lastmonth, $vmi) or die(mysql_error());
$row_rs_lastmonth = mysql_fetch_assoc($rs_lastmonth);
$totalRows_rs_lastmonth = mysql_num_rows($rs_lastmonth);


	?>
    <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px"><span><?php echo $row_drug_roll['hospname']; ?></span></td>
      <td align="center"><span><?php echo $row_drug_roll['count(*)']; ?></span></td>
      <td align="center"><span><?php echo dateThai($row_rs_date_period['min(vaccine_take_date)']); ?> - <?php echo dateThai($row_rs_date_period['max(vaccine_take_date)']); ?></span></td>
      <td align="right"><a href="javascript:poppage('confirm_vaccine_take.php?confirm=<?php if($row_drug_roll['confirm']=="Y"){ echo "N"; } else{echo "Y"; } ?>&deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo $row_drug_roll['month']; ?>','300','210','','show','vaccine_roll_list.php','drug_roll_list','');" class="<?php if($row_drug_roll['confirm']=="Y"){ echo "small_red_bord"; } else { echo "small_blue2"; }?>">
        <span><?php if($row_drug_roll['confirm']=="Y"){ echo "<img src=\"images/right_icon.png\" width=\"23\" height=\"23\" border=\"0\" align=\"absmiddle\" />&nbsp;ยกเลิก"; } else{echo "ยืนยัน"; } ?>รับใบเบิก</span></a></td>
      <td align="center"><?php if($row_drug_roll['confirm']=="Y"){?>
        <a href="vaccine_take_roll_print.php?deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo $row_drug_roll['month']; ?>" target="_blank"><img src="images/Printer and Fax.png" width="27" height="27" border="0"></a>        <?php } ?></td>
      <td align="center"><?php if($row_drug_roll['confirm']=="Y"){?>
        <a href="vaccine_take_roll_print.php?deptcode=<?php echo $row_drug_roll['hospcode']; ?>&amp;month=<?php echo $row_rs_lastmonth['year']."-".$row_rs_lastmonth['month']; ?>" target="_blank"><img src="images/Printer and Fax.png" width="27" height="27" border="0" /></a>
      <?php } ?></td>
    </tr>
    <?php mysql_free_result($rs_date_period);
	mysql_free_result($rs_lastmonth);

} while ($row_drug_roll = mysql_fetch_assoc($drug_roll)); ?>
  </table>
  <br />
  <?php if ($totalRows_rs_notsend > 0) { // Show if recordset not empty ?>
  <table width="600" border="0" cellspacing="0" cellpadding="5" class="thsan-light font16">
    <tr>
      <td colspan="2" class="font_bord">หน่วยงานที่ยังไม่ส่ง</td>
      </tr>
    <?php $i=0; do{ $i++; ?>  <tr>
      <td width="5%"><?php echo $i; ?></td>
      <td width="95%"><?php echo $row_rs_notsend['hospname']; ?></td>
      </tr>
    <? 
	 } while ($row_rs_notsend = mysql_fetch_assoc($rs_notsend)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rs_notsend);
mysql_free_result($drug_last);

if(isset($_GET['action_do'])&&($_GET['action_do']=="show")){
mysql_free_result($drug_roll);
}
?>
