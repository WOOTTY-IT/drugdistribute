<?php require_once('Connections/vmi.php'); ?>
<?php
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
include('include/function.php');

if(isset($_GET['id'])&&($_GET['id']!="")){
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from iodine_ferrous where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());	
echo "<script>window.open('index.php?sub_page=iodine_ferrous','_parent');</script>";
exit();
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
if($_POST['target']!=""){
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into iodine_ferrous (group_age,hospcode,target,order_date) value ('".$_POST['group_age']."','".$_SESSION['hospcode']."','".$_POST['target']."',NOW())";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
}
}

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_vmi, $vmi);
$query_rs_group_age = "select * from group_age where group_age not in (select group_age from iodine_ferrous where hospcode='".$_SESSION['hospcode']."' and substr(order_date,1,7)='".date('Y-m')."') and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_group_age = mysql_query($query_rs_group_age, $vmi) or die(mysql_error());
$row_rs_group_age = mysql_fetch_assoc($rs_group_age);
$totalRows_rs_group_age = mysql_num_rows($rs_group_age);

mysql_select_db($database_vmi, $vmi);
$query_rs_drug_confirm = "select * from iodine_ferrous where SUBSTR(order_date,1,7)='".date('Y-m')."' and hospcode='".$_SESSION['hospcode']."' limit 1";
$rs_drug_confirm = mysql_query($query_rs_drug_confirm, $vmi) or die(mysql_error());
$row_rs_drug_confirm = mysql_fetch_assoc($rs_drug_confirm);
$totalRows_rs_drug_confirm = mysql_num_rows($rs_drug_confirm);

mysql_select_db($database_vmi, $vmi);
$query_rs_ferrous = "select f.*,g.group_age_detail,g.factor,d.drug_name from iodine_ferrous f left outer join group_age g on g.group_age=f.group_age left outer join drugs d on d.working_code=g.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where f.hospcode='".$_SESSION['hospcode']."' and substr(f.order_date,1,7) = '".date('Y-m')."' group by f.id order by id DESC";
$rs_ferrous = mysql_query($query_rs_ferrous, $vmi) or die(mysql_error());
$row_rs_ferrous = mysql_fetch_assoc($rs_ferrous);
$totalRows_rs_ferrous = mysql_num_rows($rs_ferrous);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>

<style>
table.table_bord1 tr td{border:solid 1px #CCCCCC; }
table.table_bord1{border-collapse:collapse; border-left:0px;}
table.table_bord1 tr.head td{background-color: #CED7E3;}
</style>

</head>

<body>
<div style="height:30px; padding:10px" class="thsan-semibold font20 gray">ระบบเบิกยาเสริมธาตุเหล็กสำหรับเด็ก ประจำเดือน<?php echo monthThai(date('Y-m')); ?></div>
<div style="padding:10px">
  <form id="form1" name="form1" method="post" action="">
 <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_confirm['confirm']==NULL)){ ?>
    <table width="500" border="0" cellspacing="0" cellpadding="3" class="thsan-light font16">
      <tr>
        <td width="118">เลือกกลุ่มอายุ</td>
        <td width="370"><label for="group_age"></label>
          <select name="group_age" id="group_age" class="inputcss1 thsan-light font16">
            <?php
do {  
?>
            <option value="<?php echo $row_rs_group_age['group_age']?>"><?php echo $row_rs_group_age['group_age_detail']?></option>
            <?php
} while ($row_rs_group_age = mysql_fetch_assoc($rs_group_age));
  $rows = mysql_num_rows($rs_group_age);
  if($rows > 0) {
      mysql_data_seek($rs_group_age, 0);
	  $row_rs_group_age = mysql_fetch_assoc($rs_group_age);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td>จำนวนเป้าหมาย</td>
        <td><label for="target"></label>
        <input type="text" name="target" id="target" class="inputcss1 thsan-light font16" style="width:50px"/> 
        คน</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="save" id="save" value="บันทึก" class="thfont" style="font-size:9px; width:50px" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <?php } ?>
  </form>
  <?php if ($totalRows_rs_ferrous > 0) { // Show if recordset not empty ?>
  <table width="800" border="0" cellspacing="0" cellpadding="3" class="thsan-light font16 table_bord1">
    <tr class="head">
      <td width="7%" align="center">ลำดับ</td>
      <td width="21%" align="center">กลุ่มอายุ</td>
      <td width="44%" align="center">รายการยา</td>
      <td width="12%" align="center">เป้าหมาย</td>
      <td width="13%" align="center">จำนวนเบิก</td>
       <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_confirm['confirm']==NULL)){ ?><td width="3%" align="center">&nbsp;</td><?php } ?>
    </tr>
    <?php $i=1; do { ?>
      <tr>
        <td align="center"><?php echo $i; ?></td>
        <td align="center"><?php echo $row_rs_ferrous['group_age_detail']; ?></td>
        <td align="center"><?php echo $row_rs_ferrous['drug_name']; ?></td>
        <td align="center"><?php echo $row_rs_ferrous['target']; ?></td>
        <td align="center"><?php echo ceil($row_rs_ferrous['target']/$row_rs_ferrous['factor']); ?></td>
        <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($row_rs_drug_confirm['confirm']==NULL)){ ?> <td align="center"><img src="images/bin.png" alt="" width="16" height="16" onclick=" if(confirm('ต้องการลบรายการนี้จริงหรือไม่')==true){ window.location='iodine_ferrous.php?id=<?php echo $row_rs_ferrous['id']; ?>'; }" /></td><?php } ?>
      </tr>
      <?php $i++; } while ($row_rs_ferrous = mysql_fetch_assoc($rs_ferrous)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php
mysql_free_result($rs_setting);

mysql_free_result($rs_ferrous);

mysql_free_result($rs_group_age);
?>
