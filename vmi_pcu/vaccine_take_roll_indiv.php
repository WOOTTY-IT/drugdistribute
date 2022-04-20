<?
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
if($_GET['deptcode']!=""){ $condition=" and t.hospcode='".$_GET['deptcode']."'"; }

mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "SELECT d.vaccine_name,d.vaccine_factor,d.doses,sum(t.target) as target,sum(t.remain) as remain,t.vaccine_id FROM vaccine_take t left outer join vaccines d on d.id=t.vaccine_id left outer join hospcode h on h.hospcode=t.hospcode WHERE concat(t.year,'-',t.month)='".$_GET['month']."' ".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' GROUP BY d.id ORDER BY d.vaccine_name";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_function.php'); ?>
<script src="include/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.tbdiv').hide();
});
</script>
</head>

<body>
<span class="big_black16">รายการยาเบิกแยกรายการวัคซีน ประจำเดือน<?php echo monthThai($month); ?></span>
<br />
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small table_collapse">
  <tr class="gray">
    <td height="26" align="center" class=" table_head_small_bord">ลำดับ</td>
    <td align="left" class="table_head_small_bord" style="padding-left:10px">รายการยา</td>
    <td align="center" class="table_head_small_bord">จำนวน</td>
    <td align="center" >&nbsp;</td>
  </tr>
    <?php $i=0; do {
		
	mysql_select_db($database_vmi, $vmi);
$query_rs_drug_indiv = "SELECT d.vaccine_name,d.vaccine_factor,d.doses,t.*,concat(h.hosptype,h.name) as hospname,ceiling((target*vaccine_factor)/doses) as request FROM vaccine_take t left outer join vaccines d on d.id=t.vaccine_id left outer join hospcode h on h.hospcode=t.hospcode WHERE concat(t.year,'-',t.month)='".$_GET['month']."' ".$condition."  and t.vaccine_id='".$row_rs_drug['vaccine_id']."'  and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and h.name !=''  ORDER BY d.vaccine_name";
$rs_drug_indiv = mysql_query($query_rs_drug_indiv, $vmi) or die(mysql_error());
$row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv);
$totalRows_rs_drug_indiv = mysql_num_rows($rs_drug_indiv);

$sum=0;

  do { 
	  if($row_rs_drug_indiv['remain']>=$row_rs_drug_indiv['request']){ $request= 0;} else  { $request= $row_rs_drug_indiv['request']-$row_rs_drug_indiv['remain'];}

	$sum+=$request;
	 } while ($row_rs_drug_indiv = mysql_fetch_assoc($rs_drug_indiv)); 
mysql_free_result($rs_drug_indiv);

		 $i++; ?>
  <tr class=" dashed" >
      <td align="center" class=" table_border_gray"  ><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px" class=" table_border_gray" ><span><?php echo $row_rs_drug['vaccine_name']; ?></span></td>
      <td align="center" class=" table_border_gray"><?php echo $sum; ?></td>
      <td align="center" class=" table_border_gray"><a href="javascript:divhide('<?php echo $row_rs_drug['vaccine_id']; ?>');dosubmit('<?php echo $row_rs_drug['vaccine_id']; ?>&month=<?php echo $_GET['month']; ?>&deptcode=<?php echo $_GET['deptcode']; ?>','show','vaccine_take_roll_indiv_detail.php','<?php echo $row_rs_drug['vaccine_id']; ?>','');" ><img src="images/Icon-Document03-Blue.png" width="24" height="24" border="0" /></a></td>
  </tr>
  <tr id="<?php echo $row_rs_drug['vaccine_id']; ?>1" class="tbdiv">
    <td colspan="4" align="left" ><div id="<?php echo $row_rs_drug['vaccine_id']; ?>"></div></td>
  </tr>
  
      <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
</table></body>
</html><?php
mysql_free_result($rs_drug);
?>
