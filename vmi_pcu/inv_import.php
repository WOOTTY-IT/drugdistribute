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
 
if(isset($_POST['import'])&&$_POST['import']=="นำเข้า"){
	if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"],"upload/".$_FILES["fileUpload"]["name"]))
	{
	/*mysql_select_db($database_vmi, $vmi);
$query_truncate = "delete from drugs where hospcode='".$_SESSION['hospcode']."'";
$rs_truncate = mysql_query($query_truncate, $vmi) or die(mysql_error());
	*/
	$filename = $_FILES["fileUpload"]["name"];
$objFopen = @fopen("upload/".$filename, 'r'); 

if ($objFopen) {
    while (!feof($objFopen)) {
        $file = fgets($objFopen);
       	$text=explode("|",$file);

mysql_select_db($database_vmi, $vmi);
$query_rs_select = "select * from drugs where working_code='".$text[0]."' and hospcode='".$_SESSION['hospcode']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_select = mysql_query($query_rs_select, $vmi) or die(mysql_error());
$row_rs_select = mysql_fetch_assoc($rs_select);
$totalRows_rs_select = mysql_num_rows($rs_select);

if($totalRows_rs_select<>0){
mysql_select_db($database_vmi, $vmi);
$query_insert = "update drugs set drug_name='".$text[1]."' , pack_ratio='".$text[2]."',buy_unit_cost='".$text[3]."',remain='".$text[4]."',vendor_code='".$text[5]."',sale_unit='".$text[6]."',supply_type='".$text[7]."',istatus='Y' where working_code='".$text[0]."' and hospcode='".$_SESSION['hospcode']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
}
else{
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drugs (working_code,drug_name, pack_ratio,buy_unit_cost,remain,vendor_code,sale_unit,supply_type,hospcode,chwpart,amppart,istatus) value ('".$text[0]."','".$text[1]."','".$text[2]."','".$text[3]."','".$text[4]."','".$text[5]."','".$text[6]."','".$text[7]."','".$_SESSION['hospcode']."','".$_SESSION['chwpart']."','".$_SESSION['amppart']."','Y') ";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
}
if($rs_insert){
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from drugs where drug_name='drug_name' or working_code=''";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}
mysql_free_result($rs_select);

		
    }
    fclose($objFopen);
}
	}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" >
  <tr>
    <td width="15" height="32" class="gray" style="padding-left:10px"><img src="images/002.png" width="36" height="36" align="absmiddle" />&nbsp; <span class=" thsan-semibold" style="font-size:24px">นำเข้าข้อมูลยาจาก INV</span></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
  <tr>
    <td><form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input type="file" name="fileUpload" id="fileUpload" />
      <input type="submit" name="import" id="import" value="นำเข้า" class="button red" />
    </form><br />
<?php if($rs_delete){
	echo "<span class=\"big_red16\">อัพโหลดเรียบร้อยแล้ว...</span>";

	}
?>
</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
?>
