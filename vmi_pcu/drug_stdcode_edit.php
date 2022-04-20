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
if(isset($_POST['button'])&&$_POST['button']=="บันทึก"){
mysql_select_db($database_vmi, $vmi);
$query_rs_drug1 = "select working_code from drug_stdcode  where working_code='".$_POST['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_drug1 = mysql_query($query_rs_drug1, $vmi) or die(mysql_error());
$row_rs_drug1 = mysql_fetch_assoc($rs_drug1);
$totalRows_rs_drug1 = mysql_num_rows($rs_drug1);

		if($totalRows_rs_drug1<>0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update drug_stdcode set stdcode='".$_POST['stdcode']."' where working_code='".$_POST['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
		}
		else {
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into drug_stdcode (working_code,stdcode,hospcode,chwpart,amppart) values ('".$_POST['working_code']."','".$_POST['stdcode']."','".$_SESSION['hospcode']."','".$_SESSION['chwpart']."','".$_SESSION['amppart']."')";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
			
			}
	mysql_free_result($rs_drug1);
	
echo "<script>parent.$.fn.colorbox.close();parent.page_load('stdcode_list','drug_stdcode.php?item_type=".$_POST['item_type']."')</script>";
exit();	

	}
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select d.drug_name,s.stdcode from drugs d left outer join drug_stdcode s on s.working_code=d.working_code and d.hospcode=s.hospcode where d.working_code='".$_GET['working_code']."' and d.hospcode='".$_SESSION['hospcode']."' and d.item_type='".$_GET['item_type']."'";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="big_red16"><?php echo $row_rs_drug['drug_name']; ?></span><br />
<br />
<form id="form1" name="form1" method="post" action="">
  <span class="table_head_small_bord">รหัสมาตรฐาน 
  24 หลัก </span><br />
  <input name="stdcode" type="text" id="stdcode" style="width:200px" value="<?php echo $row_rs_drug['stdcode']; ?>" />
  <input type="submit" name="button" id="button" value="บันทึก" />
  <input name="working_code" type="hidden" id="working_code" value="<?php echo $working_code; ?>" />
  <input name="item_type" type="hidden" id="item_type" value="<?php echo $_GET['item_type']; ?>" />
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
