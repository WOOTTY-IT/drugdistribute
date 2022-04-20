<?php
ob_start();
session_start();
?>
<?php if(($_SESSION['member_status']!="admin"||$_SESSION['member_status']!="super_user")&&($_SESSION['user_item_type']!=1)){
	echo "<script>window.open('index.php','_parent');</script>";
	exit();
	}
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

if(isset($_GET['id'])&&($_GET['id']!="")){
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from group_age where group_age='".$_GET['id']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' ";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());	
echo "<script>window.open('index.php?sub_page=iodine_setting','_parent');</script>";
exit();

}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into group_age (group_age_detail,working_code,factor,chwpart,amppart) value ('".$_POST['group_age_detail']."','".$_POST['drug']."','".$_POST['factor']."','".$_SESSION['chwpart']."','".$_SESSION['amppart']."')";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
}
	
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select * from drugs where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and item_type=1 order by drug_name asc";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_vmi, $vmi);
$query_rs_group_age = "select d.drug_name,g.* from group_age g left outer join drugs d on d.working_code=g.working_code where hospcode='".$_SESSION['hospcode']."'";
$rs_group_age = mysql_query($query_rs_group_age, $vmi) or die(mysql_error());
$row_rs_group_age = mysql_fetch_assoc($rs_group_age);
$totalRows_rs_group_age = mysql_num_rows($rs_group_age);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style>
table.table_bord1 tr td{border:solid 1px #CCCCCC; background-color:#FFF}
table.table_bord1{border-collapse:collapse; border-left:0px;}
table.table_bord1 tr.head td{background-color: #CED7E3;}
</style>
</head>

<body>
<div style="height:30px; padding:10px" class="thsan-semibold font20 gray">ตั้งค่ายาเสริมไอโอดีนสำหรับเด็ก</div>
<div style="padding:10px">
  <form id="form1" name="form1" method="post" action="">
    <table width="500" border="0" cellspacing="0" cellpadding="3" class="thsan-light font16"> 
      <tr>
        <td width="129">ชื่อกลุ่ม</td>
        <td width="359"><label for="group_age_detail"></label>
        <input type="text" name="group_age_detail" id="group_age_detail" class="inputcss1 thsan-light font16" style="width:100%"  /></td>
      </tr>
      <tr>
        <td>รายการยาที่ใช้</td>
        <td><label for="drug"></label>
          <select name="drug" id="drug" class="inputcss1 thsan-light font16">
            <option value="">=== เลือกรายการ ===</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_drug['working_code']?>"><?php echo $row_rs_drug['drug_name']?></option>
            <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td>ตัวหาร(factor)</td>
        <td><label for="factor"></label>
        <input type="text" name="factor" id="factor" class="inputcss1 thsan-light font16" />
        /เดือน</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="save" id="save" value="บันทึก" class=" cssbotton2 thsan-light font16" style="width:80px; height:30px; border: solid 1px #DFDFDF" /></td>
      </tr>
    </table>
  </form>
</div>
<div style="padding:10px">
  <?php if ($totalRows_rs_group_age > 0) { // Show if recordset not empty ?>
  <table width="800" border="0" cellspacing="0" cellpadding="3" class="thsan-light font16 table_bord1">
    <tr class="head">
      <td width="6%" align="center">ลำดับ</td>
      <td width="37%" align="center">กลุ่มอายุ</td>
      <td width="37%" align="center">รายการที่ใช้</td>
      <td width="15%" align="center">ตัวหาร</td>
      <td width="5%" align="center">&nbsp;</td>
    </tr>
    <?php $i=1; do { ?>
      <tr>
        <td align="center"><?php echo $i; ?></td>
        <td align="center"><?php echo $row_rs_group_age['group_age_detail']; ?></td>
        <td align="center"><?php echo $row_rs_group_age['drug_name']; ?></td>
        <td align="center"><?php echo $row_rs_group_age['factor']; ?></td>
        <td align="center"><img src="images/bin.png" width="16" height="16" onclick=" if(confirm('ต้องการลบรายการนี้จริงหรือไม่')==true){ window.location='iodine_setting.php?id=<?php echo $row_rs_group_age['group_age']; ?>'; }" style="cursor:pointer" /></td>
      </tr>
      <?php $i++; } while ($row_rs_group_age = mysql_fetch_assoc($rs_group_age)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_group_age);
?>
