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
if(isset($_POST['action_do'])&&$_POST['action_do']=="delete"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from vaccine_list where id='".$_POST['action_id']."'";
	echo $query_delete;
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
}

if(isset($_POST['do'])&&($_POST['do']=="save")){
if($_POST['vaccine']!=""){
mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select * from vaccine_list where vaccine_id='".$_POST['vaccine']."' and vaccine_project='".$_POST['project']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

if($totalRows_rs_search<>0){
	$msg="** รายการที่คุณบันทึกซ้ำกัน";
	}
else{
mysql_select_db($database_vmi, $vmi);
$query_rs_insert = "insert into vaccine_list (vaccine_id,vaccine_project,hospcode,chwpart,amppart,vaccine_factor) value ('".$_POST['vaccine']."','".$_POST['project']."','".$_SESSION['hospcode']."','".$_SESSION['chwpart']."','".$_SESSION['amppart']."','".$_POST['factor']."')";
$rs_insert = mysql_query($query_rs_insert, $vmi) or die(mysql_error());	

if($rs_insert){
	echo "<script>test();</script>";
	}

	}
mysql_free_result($rs_search);

}
}
mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select * from vaccine_project";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);



?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php if($msg!=""){ ?><div class="thsan-light" style="color:#F00; font-size:20px; padding-left:50px"><?php echo $msg; ?></div><?php } ?>

<table width="600" border="0" cellspacing="0" cellpadding="5" style="margin-left:20px; margin-top:10px" >
      <?php do {
mysql_select_db($database_vmi, $vmi);
$query_vaccine_list = "select l.id,v.vaccine_name,l.vaccine_project,l.vaccine_id,l.vaccine_factor from vaccine_list l left outer join vaccines v on v.id=l.vaccine_id where hospcode='".$_SESSION['hospcode']."' and l.vaccine_project ='".$row_rs_project['id']."'";
$vaccine_list = mysql_query($query_vaccine_list, $vmi) or die(mysql_error());
$row_vaccine_list = mysql_fetch_assoc($vaccine_list);
$totalRows_vaccine_list = mysql_num_rows($vaccine_list);
		   ?>
<?php if($totalRows_vaccine_list<>0){ ?>
<tr class=" thsan-bold font20">
    <td><?php echo $row_rs_project['vaccine_project']; ?></td>
  </tr>
 <?php $i=0; do { $i++; ?>
 <tr class="thsan-light font16 dashed">
    <td style="padding-left:40px"><?php echo $i.". ".$row_vaccine_list['vaccine_name']; ?>&nbsp;(อัตราคูณการสูญเสีย <?php echo $row_vaccine_list['vaccine_factor']; ?>)&nbsp;<a href="javascript:dosubmit('<?php echo $row_vaccine_list['id']; ?>','delete','vaccine_list.php','vaccine_list','form1');"><img src="images/delete.png" width="21" height="20" border="0" align="absmiddle" style="cursor:pointer" ></a></td>
  </tr>
  <?php  } while ($row_vaccine_list = mysql_fetch_assoc($vaccine_list)); ?>

      <?php 
	  mysql_free_result($vaccine_list);
}
	  } while ($row_rs_project = mysql_fetch_assoc($rs_project)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_project);



?>
