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
$thismonth=date('Y-m');
mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select substr(t.order_date,1,7) as monthyear from iodine_ferrous t left outer join hospcode h on h.hospcode=t.hospcode where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by substr(t.order_date,1,7) order by substr(t.order_date,1,7) DESC";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$totalRows_rs_month = mysql_num_rows($rs_month);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select d.hospcode from iodine_ferrous d left outer join hospcode h on h.hospcode=d.hospcode where d.hospcode !='' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by d.hospcode";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.link1').click(function(){
		var mm=$('#monthyear').val();
		var dd=$('#dept').val();
		poppage2('ferrous_take_roll_indiv.php?month='+mm+'&deptcode='+dd,'90%','90%');
		});
});
</script>
</head>

<body onload="dosubmit('','show','ferrous_roll_list.php','drug_roll_list','');">

<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="gray thsan-bold" style="font-size:20px; padding:10px" > ทะเบียนเบิกยาเสริมธาตุเหล็ก ประจำเดือน <?php echo monthThai(date('Y-m')); ?></td>
  </tr>
    <tr>
      <td style="padding-left:20px"><span class="table_head_small_bord">คนหาทะเบียนการเบิกเวชภัณฑ์ 
        &nbsp; </span>
        <select name="monthyear" class="table_head_small" id="monthyear">
          <option value="">-- เลือกเดือน --
            <?php
do {  
?>
          </option>
          <option value="<?php echo $row_rs_month['monthyear']?>" <?php if (!(strcmp($row_rs_month['monthyear'],$thismonth))) {echo "selected=\"selected\"";} ?>><?php echo monthThai($row_rs_month['monthyear']);?></option>
          <?php
} while ($row_rs_month = mysql_fetch_assoc($rs_month));
  $rows = mysql_num_rows($rs_month);
  if($rows > 0) {
      mysql_data_seek($rs_month, 0);
	  $row_rs_month = mysql_fetch_assoc($rs_month);
  }
?>
        </select>
        <select name="dept" class="table_head_small" id="dept">
          <option value="">-- เลือกหน่วยงาน -- </option>
          <?php
do {  
mysql_select_db($database_vmi, $vmi);
$query_rs_dept2 = "select concat(hosptype,name) as hospname from hospcode where hospcode ='".$row_rs_dept['hospcode']."'";
$rs_dept2 = mysql_query($query_rs_dept2, $vmi) or die(mysql_error());
$row_rs_dept2 = mysql_fetch_assoc($rs_dept2);

?>
          <option value="<?php echo $row_rs_dept['hospcode']?>"><?php echo $row_rs_dept2['hospname']?></option>
          <?php
} while ($row_rs_dept = mysql_fetch_assoc($rs_dept));
  $rows = mysql_num_rows($rs_dept);
  if($rows > 0) {
      mysql_data_seek($rs_dept, 0);
	  $row_rs_dept = mysql_fetch_assoc($rs_dept);
  }
?>
        </select>
        &nbsp; <input type="button" name="search" id="search" value="ค้นหา" class="button gray2" onclick="dosubmit('','search','ferrous_roll_list.php','drug_roll_list','form1');" /></td>
    </tr>
    <tr>
      <td style="padding-left:20px"><div id="drug_roll_list">&nbsp;</div></td>
    </tr>
    <tr>
      <td align="left" style="padding-left:70px"><span class="button blue link1" style="width:120px; padding:10px">แบบเบิกแยกรายการ </span></td>
    </tr>
  </table>
</form>
<br />
</body>
</html><?php
mysql_free_result($rs_month);

mysql_free_result($rs_dept);
?>
