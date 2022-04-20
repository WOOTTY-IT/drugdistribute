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
mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select substr(DATE_ADD(CURDATE(),interval 1 MONTH),1,7) as nextmonth,substr(CURDATE(),1,7) as thismonth,substr(DATE_ADD(CURDATE(),interval -1 MONTH),1,7) as lastmonth";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$nextmonth=$row_rs_month['nextmonth'];
$thismonth=$row_rs_month['thismonth'];
$lastmonth=$row_rs_month['lastmonth'];
mysql_free_result($rs_month);

if($row_rs_setting['vaccine_method']==1){
	$condition="concat(year,'-',month)='".$nextmonth."'";
	$thismonth=$nextmonth;
	$lastmonth=$thismonth;
	}
else{	
	$condition="concat(year,'-',month)='".date('Y-m')."'";	
	$thismonth=$thismonth;
	$lastmonth=$lastmonth;
	}


if(isset($_POST['save2'])&&$_POST['save2']=="ลบ"){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from vaccine_take  where id='".$_POST['id']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	
echo "<script>parent.$.fn.colorbox.close();</script>";
exit();	

}

if(isset($_POST['save'])&&$_POST['save']=="แก้ไข"){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update vaccine_take set vaccine_project='".$_POST['project']."' ,target='".$_POST['target']."',remain='".$_POST['remain']."',last_visit='".$_POST['visit']."',last_use='".$_POST['use']."',vaccine_take_date='".date('Y-m-d')."',remark='".$_POST['remark']."' where id='".$_POST['id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());
	
echo "<script>parent.$.fn.colorbox.close();</script>";
exit();	

}
if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
	
	
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into vaccine_take (vaccine_id,vaccine_project,target,remain,last_visit,last_use,hospcode,year,month,vaccine_take_date,remark) value ('".$_POST['item']."','".$_POST['project']."','".$_POST['target']."','".$_POST['remain']."','".$_POST['visit']."','".$_POST['use']."','".$_SESSION['hospcode']."','".substr($thismonth,0,4)."','".substr($thismonth,5,2)."','".date('Y-m-d')."','".$_POST['remark']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from vaccine_take where hospcode=''";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	
echo "<script>parent.$.fn.colorbox.close();parent.dosubmit('','show','vaccine_take_list.php','drug_take_list','');</script>";
exit();	

}
if(isset($_GET['do'])&&$_GET['do']=="edit"){
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select t.target,t.remain,t.last_visit,t.last_use,l.id,l.vaccine_id,t.vaccine_project,v.vaccine_name,t.remark from vaccine_take t left outer join  vaccine_list l on l.vaccine_id=t.vaccine_id  left outer join vaccines v on v.id=l.vaccine_id where t.id='".$_GET['id']."' ";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);

/*
mysql_select_db($database_vmi, $vmi);
$query_rs_project2 = "select p.* from vaccine_take t left outer join vaccine_project p on t.vaccine_project=p.id where t.id='".$_GET['id']."'  ";
$rs_project2 = mysql_query($query_rs_project2, $vmi) or die(mysql_error());
$row_rs_project2 = mysql_fetch_assoc($rs_project2);
$totalRows_rs_project2 = mysql_num_rows($rs_project2);
*/

mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select p.* from vaccine_list l left outer join vaccine_project p on p.id=l.vaccine_project where l.vaccine_id='".$row_rs_item['vaccine_id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' ";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);

}
else {
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select l.id,l.vaccine_id,l.vaccine_project,v.vaccine_name from vaccine_list l left outer join vaccines v on v.id=l.vaccine_id where l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' group by l.vaccine_id";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);

	}
	
	
if($_GET['vaccine_id']!=""){
mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select p.* from vaccine_list l left outer join vaccine_project p on p.id=l.vaccine_project where l.vaccine_id='".$_GET['vaccine_id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' and l.vaccine_project not in (select vaccine_project from vaccine_take where vaccine_id='".$_GET['vaccine_id']."' and hospcode='".$_SESSION['hospcode']."' and ".$condition.") ";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);
	}

include('include/function.php');
if($row_rs_setting['vaccine_method']==1){
$today=date('Y-').sprintf("%02d", (date('m')));
}
if($row_rs_setting['vaccine_method']==2){
$today=date('Y-').sprintf("%02d", (date('m')-1));	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #F0F0F0;
	
}
html, body
{
  height: 100%;
}
</style>
<script src="include/jquery.js"></script>
<script type="text/javascript">


</script>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="3" >
  <tr>
    <td class="blue thsan-semibold font18" style="padding-left:10px"><img src="images/vaccine_white.png" width="30" height="30" align="absmiddle" />&nbsp; <?php if($_GET['do']=="edit"){ echo "แก้ไข"; } else{ echo "เพิ่ม"; } ?>รายการวัคซีน</td>
  </tr>
  <tr style="height:100%;">    
  <td style="height:100%;" ><form id="form1" name="form1" method="post" action="">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
      <tr>
        <td width="31%" height="36" align="right" valign="bottom" class="table_head_small_bord">เลือกรายการยา</td>
        <td width="69%" valign="bottom"><select name="item" id="item" class="item table_head_small" style="width:90%" onChange="window.location.href='vaccine_take_add.php?vaccine_id='+this.value">
          <?php if(!isset($_GET['do'])||($_GET['do']!="edit")){ ?><option value="">-- เลือกรายการยา --</option>
          <?php }
do {  
?>
          <option value="<?php echo $row_rs_item['vaccine_id']?>"<?php if ($_GET['do']=="edit"&&!(strcmp($row_rs_item['id'], $row_rs_item['id']))) {echo "selected=\"selected\"";} else if(!(strcmp($_GET['vaccine_id'], $row_rs_item['vaccine_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item['vaccine_name']?></option>
          <?php
} while ($row_rs_item = mysql_fetch_assoc($rs_item));
  $rows = mysql_num_rows($rs_item);
  if($rows > 0) {
      mysql_data_seek($rs_item, 0);
	  $row_rs_item = mysql_fetch_assoc($rs_item);
  }
?>
          </select>
          <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" /></td>
      </tr>
 <?php if($totalRows_rs_project<>0||(isset($_GET['do'])&&($_GET['do']=="edit"))){ ?>
      <tr class="among1">
        <td align="right">เลือกโครงการ</td>
        <td align="left"><label for="project"></label>
          <select name="project" id="project">
            <?php
do {  
?>
            <option value="<?php echo $row_rs_project['id']?>"<?php if (!(strcmp($row_rs_project['id'], $row_rs_item['vaccine_project']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_project['vaccine_project']?></option>
            <?php
} while ($row_rs_project = mysql_fetch_assoc($rs_project));
  $rows = mysql_num_rows($rs_project);
  if($rows > 0) {
      mysql_data_seek($rs_project, 0);
	  $row_rs_project = mysql_fetch_assoc($rs_project);
  }
?>
          </select></td>
      </tr>
      <tr class="among1">
        <td align="right">เป้าหมาย</td>
        <td align="left"><label for="target"></label>
          <input name="target" type="text" id="target" style="width:50px" value="<?php echo $row_rs_item['target']; ?>" />
          คน</td>
      </tr>
      <tr class="among1">
        <td align="right">ยอดคงเหลือ</td>
        <td align="left"><input name="remain" type="text" id="remain" style="width:50px" value="<?php echo $row_rs_item['remain']; ?>" />
          vial</td>
      </tr>
      <tr class="among1">
        <td align="right">ผู้มารับบริการในเดือน<?php echo monthThai($today); ?></td>
        <td align="left"><input name="visit" type="text" id="visit" style="width:50px" value="<?php echo $row_rs_item['last_visit']; ?>" />
          คน</td>
      </tr>
      <tr class="among1">
        <td align="right">จำนวนที่เปิดใช้ในเดือน<?php echo monthThai($today); ?></td>
        <td align="left"><input name="use" type="text" id="use" style="width:50px" value="<?php echo $row_rs_item['last_use']; ?>" />
          vial</td>
      </tr>
      <tr class="among1">
        <td align="right">หมายเหตุ</td>
        <td align="left"><label for="remark"></label>
          <input type="text" name="remark" id="remark" style="width:90%" value="<?php echo $row_rs_item['remark']; ?>" /></td>
      </tr>
      <tr class="among1">
        <td align="right">&nbsp;</td>
        <td align="left"><input type="submit" name="save" id="save" value="<?php if(isset($_GET['do'])&&$_GET['do']=="edit"){
echo "แก้ไข"; } else {echo "บันทึก"; } ?>" class="button_black_bar"  style="width:150px"/>
         <?php if(isset($_GET['do'])&&$_GET['do']=="edit"){
?> <input type="submit" name="save2" id="save2" value="ลบ" class="button_black_bar"  style="width:150px"/><?php } ?></td>
      </tr>
      <?php } ?>
    </table>
  </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_item);
if($_GET['vaccine_id']!=""){
mysql_free_result($rs_project);
}
?>
