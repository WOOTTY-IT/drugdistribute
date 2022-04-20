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
	
	if(isset($_POST['save2'])&&($_POST['save2']=="ยกเลิกลบ")){
mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_receive_c set action=NULL where id='".$_POST['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());		
	$state="success";	
	}
	if(isset($_POST['save2'])&&($_POST['save2']=="ลบ")){
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drug = "select * from temp_stock_receive_c where id='".$_POST['id']."' and c_id is not NULL";
	$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
	$row_rs_drug = mysql_fetch_assoc($rs_drug);
	$totalRows_rs_drug = mysql_num_rows($rs_drug);
	
	if($totalRows_rs_drug==0){
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from temp_stock_receive_c  where id='".$_POST['id']."'";
	$delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	$state="success";
	}
	else{
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_receive_c set action='D' where id='".$_POST['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());		
	$state="success";

	}
	mysql_free_result($rs_drug);
	}
	
	if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
		if($_POST['date1']!=""){
		$date11=explode("/",$_POST['date1']);
		$edate2="'".(($date11[2]-543)."-".$date11[1]."-".$date11[0])."'";
		}
		else{
		$edate2="NULL";	
		}
		if($_POST['lot']!=""){
		$lot="'".$_POST['lot']."'";	
		}
		else{
		$lot="NULL";	
		}
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_receive_c set qty='".($_POST['qty']*$_POST['pack_ratio'])."',pack_ratio='".$_POST['pack_ratio']."',lot=".$lot.",exp_date=".$edate2.",buy_unit_cost='".$_POST['buy_unit_cost']."' where id='".$_POST['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	$state="success";
	
	}
	
	if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	if($_POST['date1']!=""){
	$date11=explode("/",$_POST['date1']);
	$edate2="'".(($date11[2]-543)."-".$date11[1]."-".$date11[0])."'";
	}
	else{
	$edate2="NULL";	
	}
	if($_POST['lot']!=""){
	$lot="'".$_POST['lot']."'";	
	}
	else{
	$lot="NULL";	
	}
		//ค้นหา lot ใน temp
	mysql_select_db($database_vmi, $vmi);
	$query_rs_search = "select * from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."' and working_code='".$_POST['item']."' and lot=".$lot." and exp_date=".$edate2."";
	$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
	$row_rs_search = mysql_fetch_assoc($rs_search);
	$totalRows_rs_search = mysql_num_rows($rs_search);

	if($totalRows_rs_search<>0){
	$msg="รายการที่คุณกำลังจะบันทึก มีอยู่ในระบบอยู่แล้ว กรุณาบันทึก lot อื่น";	
	}
	else{
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into temp_stock_receive_c (hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost) value ('".$_SESSION['hospcode']."','".$_POST['item']."','".($_POST['qty']*$_POST['pack_ratio'])."','".$_POST['pack_ratio']."',".$lot.",".$edate2.",'".$_POST['buy_unit_cost']."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
		/// finish
	$state="success";
	}
	}
	if($state=="success"){
	echo "<script>parent.$.fn.colorbox.close();parent.formSubmit('".$_POST['get_do']."','form1','stock_receive_list.php','divload','indicator','edit');</script>";
	}

if($_GET['do']=="edit"&&$_GET['id']!=""){
	$condition="and d.working_code='".$_GET['working_code']."'";
	mysql_select_db($database_vmi, $vmi);

$query_rs_edit = "select * from temp_stock_receive_c where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

	$date11=explode("-",$row_rs_edit['exp_date']);
	$edate2=$date11[2]."/".$date11[1]."/".($date11[0]+543);

	}
else {
	$condition="";

	}
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "select s.drug_name,d.working_code from drug_dept_group d left outer join dept_group g on g.dept_group_id=d.dept_group_id left outer join dept_list l on l.dept_group=g.dept_group_id  left outer join drugs s on s.working_code=d.working_code and s.chwpart='".$_SESSION['chwpart']."' and s.amppart='".$_SESSION['amppart']."' where l.dept_code='".$_SESSION['hospcode']."' ".$condition." GROUP BY s.working_code ORDER BY s.drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
</head>

<body>
<form name="form1" method="post" action="">
  <label for="item"></label>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td>รายการยา</td>
      <td><select name="item" id="item" onChange="resutName(this.value,'','pack_ratio','buy_unit_cost');">
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
      </select>
      <label for="textfield">
        <input name="get_do" type="hidden" id="get_do" value="<?php echo $_GET['do']; ?>">
      </label></td>
    </tr>
    <tr>
      <td>จำนวน</td>
      <td><input name="qty" type="text" id="qty" style="width:50px" value="<?php if($totalRows_rs_edit<>0){ echo ($row_rs_edit['qty']/$row_rs_edit['pack_ratio']); } ?>"  onkeypress="return isNumberKey(event);" ></td>
    </tr>
    <tr>
      <td>ขนาดบรรจุ</td>
      <td><input name="pack_ratio" type="text" id="pack_ratio" style="width:50px" value="<?php echo $row_rs_edit['pack_ratio']; ?>"  onkeypress="return isNumberKey(event);"></td>
    </tr>
    <tr>
      <td>lot</td>
      <td><input name="lot" type="text" id="lot" style="width:100px" value="<?php echo $row_rs_edit['lot']; ?>"></td>
    </tr>
    <tr>
      <td>วันหมดอายุ</td>
      <td><input name="date1" type="text" id="date1" style="width:100px" value="<?php echo $edate2; ?>"></td>
    </tr>
    <tr>
      <td>ราคา/หน่วย</td>
      <td><label for="buy_unit_cost"></label>
      <input type="text" name="buy_unit_cost" id="buy_unit_cost" value="<?php echo $row_rs_edit['buy_unit_cost']; ?>"  onkeypress="return isNumberKey(event);"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php if($row_rs_edit['action']==NULL){  ?><input type="submit" name="save" id="save" value="<?php if($_GET['do']=="edit"){ echo "แก้ไข"; } else { echo "บันทึก";} ?>"><?php } ?>
       <?php if($_GET['do']=="edit"&&$_GET['id']!=""){
?> <input type="submit" name="save2" id="save2" value="<?php if($row_rs_edit['action']=="D"){ echo "ยกเลิกลบ"; } else { echo "ลบ"; } ?>" onClick="return confirm('ต้องการ<?php if($row_rs_edit['action']=="D"){ echo "ยกเลิกลบ"; } else { echo "ลบ"; } ?>ข้อมูลนี้จริงหรือไม่');"><? } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>"></td>
    </tr>
  </table>
</form>
<?php echo "<span style=\"color:red\" class=\"thsan-light font20\">".$msg."</span>"; ?>
</body>
</html>
<?php
mysql_free_result($rs_drug);

if($_GET['do']=="edit"&&$_GET['id']!=""){
mysql_free_result($rs_edit);
}
?>
