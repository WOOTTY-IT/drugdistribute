<?php 
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php require_once('Connections/datacenter.php'); ?>

<?php

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

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
if(!isset($_POST['dept'])&&$_POST['dept']==""){
	$dept_sel=$_SESSION['hospcode'];
	}
else{
	$dept_sel=$_POST['dept'];	
	}

 $type_check=array('1','2','3','4','5');
if (in_array($_SESSION['hospcode_type_id'], $type_check, true)) {
 $condition=" 1 ";
 }
 else{
 $condition=" l.dept_code = '".$_SESSION['hospcode']."'";	 
	}
	
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "select l.dept_code from drug_dept_group v left outer join dept_list l on l.dept_group=v.dept_group_id left outer join hospcode h on h.hospcode=l.dept_code  where ".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by l.dept_code";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);

mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "SELECT g.max_level,d.*,s.stdcode FROM dept_list l left outer join drug_dept_group g on g.dept_group_id=l.dept_group left outer join drugs d on d.working_code=g.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' left outer join drug_stdcode s on s.working_code=d.working_code and s.hospcode=d.hospcode WHERE l.dept_code='".$dept_sel."' and d.drug_name !='' ORDER BY drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>

<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
      <td class="gray table_head_small_bord" ><img src="images/maqq-icon.png" width="34" height="45" align="absmiddle" />&nbsp; <span class="big_black16">บัญชียาประจำ รพ.สต.</span></td>
    </tr>
    <tr>
      <td><span class="table_head_small">
        <select name="dept" id="dept"  class="thsan-light  font18 inputcss1" style="height:35px">
        <?php
do {  
mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode1 = "select hosptype,name,hospcode from hospcode where hospcode = '".$row_rs_hospcode['dept_code']."' and name !=''";
$rs_hospcode1 = mysql_query($query_rs_hospcode1, $vmi) or die(mysql_error());
$row_rs_hospcode1 = mysql_fetch_assoc($rs_hospcode1);
$totalRows_rs_hospcode1 = mysql_num_rows($rs_hospcode1);
?>
          <option value="<?php echo $row_rs_hospcode['dept_code']?>"<?php if (!(strcmp($row_rs_hospcode['dept_code'],$dept_sel))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_hospcode1['hosptype']." ".$row_rs_hospcode1['name']?></option>
          <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
?>
        </select>
        <input type="submit" name="button12" id="button12" value="ค้นหา" class=" thsan-light font18" style="margin-top:5px; height:35px; padding-left:10px; padding-right:10px; background-color:#666666; color:#FFF; border:0px;"/>
      </span></td>
    </tr>
    <tr>
      <td style="padding-left:0px"><?php if ($totalRows_rs_drug > 0) { // Show if recordset not empty ?>
        <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
          <tr class="table_head_small_bord">
            <td width="5%" height="28" align="center" style="border-bottom:solid 1px #CCCCCC">ลำดับ</td>
            <td width="20%" align="center" style="border-bottom:solid 1px #CCCCCC">รายการยา</td>
            <td width="20%" align="center" style="border-bottom:solid 1px #CCCCCC">รายการ mapping</td>
            <td width="20%" align="center" style="border-bottom:solid 1px #CCCCCC">รหัส 24 หลัก</td>
            <td width="15%" align="center" style="border-bottom:solid 1px #CCCCCC">max stock</td>
            <td width="10%" align="center" style="border-bottom:solid 1px #CCCCCC">ราคา/หน่วย</td>
            <td width="10%" align="center" style="border-bottom:solid 1px #CCCCCC">หน่วย</td>
          </tr>
          <?php $i=0; do { 

if($row_rs_setting['dbcenter']=="N"){
mysql_select_db($database_vmi, $vmi);
$query_rs_drug1 = "SELECT dname from drug_import where didstd='".$row_rs_drug['stdcode']."' and hospcode='".$dept_sel."' group by didstd order by date_serv DESC limit 1";
$rs_drug1 = mysql_query($query_rs_drug1, $vmi) or die(mysql_error());
$row_rs_drug1 = mysql_fetch_assoc($rs_drug1);
$totalRows_rs_drug1 = mysql_num_rows($rs_drug);
}
else{
mysql_select_db($database_datacenter, $datacenter);
$query_rs_drug1 = "SELECT drug_name as dname from datacenter_drug_opd where did='".$row_rs_drug['stdcode']."' and hospcode='".$dept_sel."' group by did order by vstdate DESC limit 1";
$rs_drug1 = mysql_query($query_rs_drug1, $datacenter) or die(mysql_error());
$row_rs_drug1 = mysql_fetch_assoc($rs_drug1);
$totalRows_rs_drug1 = mysql_num_rows($rs_drug);
	
}
		  $i++; ?>
          <tr class="grid">
            <td height="35" align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $i; ?></td>
            <td align="left" style=" padding-left:5px;border-bottom:solid 1px #CCCCCC" ><?php echo $row_rs_drug['drug_name']; ?></td>
            <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug1['dname']; ?></td>
            <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug['stdcode']; ?></td>
            <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo ($row_rs_drug['max_level']/$row_rs_drug['pack_ratio'])."X".$row_rs_drug['pack_ratio']; ?></td>
            <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug['buy_unit_cost']; ?></td>
            <td align="center" style="border-bottom:solid 1px #CCCCCC"><?php echo $row_rs_drug['sale_unit']; ?></td>
          </tr>
          <?php
		  mysql_free_result($rs_drug1);
		   } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
        </table>
        <?php } // Show if recordset not empty ?></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_setting);

?>
