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



if(isset($_GET['do'])&&($_GET['do']=="edit")){
mysql_select_db($database_vmi, $vmi);
$query_rs_edit = "select * from stock_withdraw_dept where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

}
if(isset($_GET['do'])&&($_GET['do']=="delete")){
		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from stock_withdraw_dept where id='".$_GET['id']."'";
		$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
		
		if($rs_delete){
		echo "<script>window.location='stock_withdraw_dept.php';</script>";	
		exit();
		}

}

if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select * from stock_withdraw_dept where dept_name='".$_POST['dept']."' and id!='".$_POST['id']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);	

	if($totalRows_rs_search<>0){
	$msg="ชื่อหน่วยงานที่ท่านตั้งซ้ำกับที่มีอยู่";
	}
	else {
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_withdraw_dept set dept_name='".$_POST['dept']."' where id='".$_POST['id']."'";
		$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());
		
	}
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select * from stock_withdraw_dept where dept_name='".$_POST['dept']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);	

	if($totalRows_rs_search==0){
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into stock_withdraw_dept (hospcode,dept_name) values ('".$_SESSION['hospcode']."','".$_POST['dept']."')";
		$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
		
	}
	
mysql_free_result($rs_search);

}
mysql_select_db($database_vmi, $vmi);
$query_rs_list = "select * from stock_withdraw_dept where hospcode='".$_SESSION['hospcode']."'";
$rs_list = mysql_query($query_rs_list, $vmi) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
$totalRows_rs_list = mysql_num_rows($rs_list);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
</head>

<body>
<div class=" thsan-semibold" style="font-size:25px" >ตั้งค่าหน่วยงานที่เบิก</div>
<form action="stock_withdraw_dept.php" method="post" style="margin-top:10px">
  <table width="500" border="0" cellspacing="0" cellpadding="5" class="thsan-light font16">
    <tr>
      <td width="26%" align="right">ชื่อหน่วยงาน</td>
      <td width="74%"><label for="dept"></label>
      <input name="dept" type="text" class="inputcss1 thsan-light font14" id="dept" value="<?php echo $row_rs_edit['dept_name']; ?>">
      <input type="submit" name="save" id="save" value="<?php if(isset($_GET['do'])&&($_GET['do']=="edit")){ echo "แก้ไข";} else {echo "บันทึก"; } ?>
">
      <input name="id" type="hidden" id="id" value="<?php echo $row_rs_edit['id']; ?>"><?php echo "<span class=\"thsan-light\" style=\"color:red\">".$msg."</span>"; ?></td>
    </tr>
  </table>
</form>
<div style="margin-top:10px" class="thsan-light"><table width="500" border="0" cellspacing="0" cellpadding="3">
  <?php $i=0; do { $i++; ?>
  <tr>
    <td width="63"><?php echo $i; ?></td>
    <td width="425"><a href="javascript:valid(0);" class="thsan-light" style="text-decoration:none; color:#000000"  onClick="window.location='stock_withdraw_dept.php?do=edit&id=<?php echo $row_rs_list['id']; ?>'"><?php echo $row_rs_list['dept_name']; ?></a> <a href="JavaScript:if(confirm('ต้องการลบข้อมูลจริงหรือไม่?')==true){window.location='stock_withdraw_dept.php?id=<?php echo $row_rs_list['id'];?>&do=delete';}"><img src="images/delete.png" width="20" height="20" border="0" align="absmiddle"></a></td>
  </tr>
  <?php } while ($row_rs_list = mysql_fetch_assoc($rs_list)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_list);

if(isset($_GET['do'])&&($_GET['do']=="edit")){

mysql_free_result($rs_edit);

}
?>
