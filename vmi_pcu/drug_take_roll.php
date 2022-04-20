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


if($_SESSION['user_item_type']==""){
if(!isset($_GET['item_type'])){
	$item_type=1;
	$_GET['item_type'];
	}
}
else{
if(!isset($_GET['item_type'])){
	$item_type=$_SESSION['user_item_type'];
	$_GET['item_type']=$item_type;
	}	
$cond_item_type=" where id='".$_SESSION['user_item_type']."'";
}

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type = "select * from item_type ".$cond_item_type;
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type2 = "select * from item_type where id='".$_GET['item_type']."'";
$rs_item_type2 = mysql_query($query_rs_item_type2, $vmi) or die(mysql_error());
$row_rs_item_type2 = mysql_fetch_assoc($rs_item_type2);
$totalRows_rs_item_type2 = mysql_num_rows($rs_item_type2);

mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select substr(t.drug_take_date,1,7) as monthyear from drug_take t left outer join hospcode h on h.hospcode=t.hospcode where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by substr(t.drug_take_date,1,7) order by substr(t.drug_take_date,1,7) DESC";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$totalRows_rs_month = mysql_num_rows($rs_month);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select d.hospcode from drug_take d left outer join hospcode h on h.hospcode=d.hospcode where d.hospcode !='' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' group by d.hospcode";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

if($_GET['dept']!=""){
	$condition=" and d.hospcode='".$_GET['dept']."'";
	}
if(!isset($_GET['action'])){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,drug_take_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,SUBSTR(drug_take_date,1,7) as 'month'  FROM drug_take d left OUTER JOIN hospcode h on h.hospcode=d.hospcode left outer join drugs ds on ds.working_code=d.working_code and ds.chwpart='".$_SESSION['chwpart']."' and ds.amppart='".$_SESSION['amppart']."' WHERE SUBSTR(drug_take_date, 1,7)=SUBSTR(CURDATE(), 1,7) and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and ds.item_type='".$item_type."'  GROUP BY hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}
if(isset($_GET['action'])&&($_GET['action']=="show")){
mysql_select_db($database_vmi, $vmi);
$query_drug_roll = "SELECT d.hospcode,drug_take_date,count(*),concat(h.hosptype,h.name) as hospname,confirm,SUBSTR(drug_take_date,1,7) as 'month' FROM drug_take d left OUTER JOIN hospcode h on h.hospcode=d.hospcode  left outer join drugs ds on ds.working_code=d.working_code and ds.chwpart='".$_SESSION['chwpart']."' and ds.amppart='".$_SESSION['amppart']."' WHERE SUBSTR(drug_take_date, 1,7)='".$_GET['monthyear']."'".$condition." and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."' and ds.item_type='".$item_type."' GROUP BY hospcode";
$drug_roll = mysql_query($query_drug_roll, $vmi) or die(mysql_error());
$row_drug_roll = mysql_fetch_assoc($drug_roll);
$totalRows_drug_roll = mysql_num_rows($drug_roll);
}
?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />


<script type="text/javascript">
$(document).ready(function() {
    $('.link1').click(function(){
		var mm=$('#monthyear').val();
		var dd=$('#dept').val();
		poppage2('drug_take_roll_indiv.php?month='+mm+'&deptcode='+dd+'&item_type_name=<?php echo $row_rs_item_type2['item_type_name']; ?>&item_type=<?php echo $item_type; ?>','90%','90%');
		});
});

function show_ondemand(){
	page_load('drug_take_roll','drug_take_roll.php?item_type=<?php echo $_GET['item_type']; ?>&monthyear='+$('#monthyear').val()+'&dept='+$('#dept').val()+'&action=show');	
	}
function alertload(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url+"?"+str+"="+queue,onOpen : function () {
$('body, html').css('overflowY','hidden');},
onCleanup :function(){$('body, html').css('overflowY','auto');}
		});

	}			

</script>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: #111;
}

li .nav_select {
	background-color: #111;
}
</style>

</head>

<body >
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
<div id="drug_take_roll">
<form id="form1" name="form1" method="post" action="">
<ul>
<?php do{ ?>
  <li><a class="active <?php if($row_rs_item_type['id']==$item_type){ echo "nav_select"; } ?>" href="javascript:page_load('drug_take_roll','drug_take_roll.php?item_type=<?php echo $row_rs_item_type['id']; ?>');"  ><?php echo $row_rs_item_type['item_type_name']; ?></a></li>
 <?php }while($row_rs_item_type = mysql_fetch_assoc($rs_item_type)); ?>
</ul>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="gray thsan-bold" style="font-size:20px; padding:10px" > ทะเบียนเบิกจ่าย<?php echo $row_rs_item_type2['item_type_name']; ?>ประจำเดือน <?php echo monthThai(date('Y-m')); ?></td>
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
          <option value="<?php echo $row_rs_month['monthyear']?>" <?php if(!isset($_GET['monthyear'])){ if (!(strcmp($row_rs_month['monthyear'],$thismonth))) {echo "selected=\"selected\"";}}else {if (!(strcmp($row_rs_month['monthyear'],$_GET['monthyear']))) {echo "selected=\"selected\"";}} ?>><?php echo monthThai($row_rs_month['monthyear']);?></option>
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
          <option value="<?php echo $row_rs_dept['hospcode']?>" <?php if (!(strcmp($row_rs_dept['hospcode'], $_GET['dept']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_dept2['hospname']?></option>
          <?php
} while ($row_rs_dept = mysql_fetch_assoc($rs_dept));
  $rows = mysql_num_rows($rs_dept);
  if($rows > 0) {
      mysql_data_seek($rs_dept, 0);
	  $row_rs_dept = mysql_fetch_assoc($rs_dept);
  }
?>
      </select>
        &nbsp; <input type="button" name="search" id="search" value="ค้นหา" class="button gray2" onclick="show_ondemand();" /></td>
    </tr>
    <tr>
      <td style="padding-left:20px"><?php if ($totalRows_drug_roll > 0) { // Show if recordset not empty ?>
  <table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_bord">
      <td width="6%" align="center">ลำดับ</td>
      <td width="31%" align="left" style="padding-left:10px">ชื่อหน่วยงาน</td>
      <td width="12%" align="center">จำนวนรายการ</td>
      <td width="30%" align="center">ช่วงวันที่เบิก</td>
      <td width="15%" align="center">สถานะ</td>
      <td width="6%" align="center">&nbsp;</td>
    </tr>
    <?php $i=0; do { $i++; 
	mysql_select_db($database_vmi, $vmi);
$query_rs_date_period = "SELECT max(drug_take_date),min(drug_take_date) from drug_take where SUBSTR(drug_take_date, 1,7)='$row_drug_roll[month]' and hospcode='$row_drug_roll[hospcode]'";
$rs_date_period = mysql_query($query_rs_date_period, $vmi) or die(mysql_error());
$row_rs_date_period = mysql_fetch_assoc($rs_date_period);
$totalRows_rs_date_period = mysql_num_rows($rs_date_period);

	?>
    <tr class="dashed">
      <td align="center"><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px"><span><?php echo $row_drug_roll['hospname']; ?></span></td>
      <td align="center"><span><?php echo $row_drug_roll['count(*)']; ?></span></td>
      <td align="center"><span><?php echo dateThai($row_rs_date_period['min(drug_take_date)']); ?> - <?php echo dateThai($row_rs_date_period['max(drug_take_date)']); ?></span></td>
      <td align="right"><a href="javascript:alertload('confirm_drug_take.php?confirm=<?php if($row_drug_roll['confirm']=="Y"){ echo "N"; } else{echo "Y"; } ?>&deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo substr($row_drug_roll['drug_take_date'],0,7); ?>&action=1','300','210');" class="<?php if($row_drug_roll['confirm']=="Y"){ echo "small_red_bord"; } else { echo "small_blue2"; }?>">
        <span><?php if($row_drug_roll['confirm']=="Y"){ echo "<img src=\"images/right_icon.png\" width=\"23\" height=\"23\" border=\"0\" align=\"absmiddle\" />&nbsp;ยกเลิก"; } else{echo "ยืนยัน"; } ?>รับใบเบิก</span></a></td>
      <td align="center"><?php if($row_drug_roll['confirm']=="Y"){?>
        <a href="javascript:window.open('drug_take_roll_print_admin.php?deptcode=<?php echo $row_drug_roll['hospcode']; ?>&month=<?php echo $row_drug_roll['month']; ?>&item_type=<?php echo $_GET['item_type']; ?>','_new');"><img src="images/Printer and Fax.png" width="27" height="27" border="0"></a>        <?php } ?></td>
    </tr>
    <?php mysql_free_result($rs_date_period);
} while ($row_drug_roll = mysql_fetch_assoc($drug_roll)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</td>
    </tr>
    <tr>
      <td align="left" style="padding-left:70px"><span class="button blue link1" style="width:120px; padding:10px">แบบเบิกแยกรายการ </span></td>
    </tr>
  </table>
</form>
</div>
</body>
</html><?php
mysql_free_result($rs_month);

mysql_free_result($rs_dept);

mysql_free_result($rs_item_type);

mysql_free_result($rs_item_type2);

?>
<?php
if(isset($_GET['action_do'])&&($_GET['action_do']=="show")){
mysql_free_result($drug_roll);
}
?>

