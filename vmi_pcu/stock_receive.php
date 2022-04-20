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

	//ตรวจสอบวันที่
	mysql_select_db($database_vmi, $vmi);
	$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' ";
	$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
	$row_rs_setting = mysql_fetch_assoc($rs_setting);
	$totalRows_rs_setting = mysql_num_rows($rs_setting);	
	////check_date
	$check_date=date('Y-m-').sprintf("%02d", $row_rs_setting['stock_date']);
	$today=date('Y-m-d');
	////
	mysql_free_result($rs_setting);
	
	mysql_select_db($database_vmi, $vmi);
	$query_rs_date = "select substr(DATE_ADD(CURDATE(),INTERVAL -1 MONTH),1,7) as datemysql ";
	$rs_date = mysql_query($query_rs_date, $vmi) or die(mysql_error());
	$row_rs_date = mysql_fetch_assoc($rs_date);
	$totalRows_rs_date = mysql_num_rows($rs_date);	
	//////////
	$datemysql=str_replace('-','',$row_rs_date['datemysql']);
	mysql_free_result($rs_date);			
	$msg="";
	
	
///////////===================================/////////////////
if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	$date11=explode("/",$_POST['date1']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);
	/// จัดให้อยู่ในรูปแบบ YYYY-MM
	$datesub=substr($edate2,0,7);

	
if($edate2>$today){
	//ถ้าวันที่ที่เลือกมากกว่าวันที่ปัจจุบัน

	$msg="ท่านไม่สามารถเลือกวันที่ล่วงหน้าได้ครับ กรุณาทำรายการใหม่";
	echo "<script>alert('".$msg."');return false; </script>";
	}
else{
	//แต่ถ้าเลือกวันที่น้อยกว่าหรือเท่ากับต้องตรวจสอบกับวันที่ใน setting
	if($check_date<$today){
		//ถ้าเดือนและปีไม่ตรงกับปัจจุบัน
		if($datesub!=substr($today,0,7)){
				$msg="ท่านไม่สามารถย้อนวันที่ไปเดือนก่อนหน้าได้ กรุณาทำรายการใหม่";
				echo "<script>alert('".$msg."');return false; </script>";

			}	
	}
	else{
	//ถ้าเดือนและปีไม่ตรงกับปัจจุบัน
		if($datesub!=substr($today,0,7)){
			//ถ้าปีปัจจุบันแต่เดือนมากกว่า -2
			if(str_replace('-','',$datesub)<$datemysql){
					$msg="ท่านไม่สามารถย้อนหลังได้เกิน 1 เดือน กรุณาทำรายการใหม่";
					echo "<script>alert('".$msg."');return false; </script>";

				}
			}	

	}
}
/// ถ้ามีการเตือน
if($msg!=""){
echo "<div style=\"margin-top:10px; color:#F00; margin-left:40px\" class=\"thsan-semibold font20\">".$msg."</div><input type=\"button\" value=\"ย้อนกลับ\" class=\"button_gray\" style=\"margin-left:40px;margin-top:10px;\" onclick=\"history.back(-1)\"/>";
exit();
}
if($msg==""){
//ถ้าเป็นการบันทึกใหม่
if($_POST['do']=="save"){
	
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_receive (hospcode,receive_no,receive_date,dept_id) value ('".$_SESSION['hospcode']."','".$_POST['pr']."','".$edate2."','".$_POST['dept']."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	
		if($insert){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_serial set receive_no=receive_no+1 where hospcode='".$_SESSION['hospcode']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	
	/// เทข้อมูลจาก temp ไป receive
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_receive_c (receive_no,hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost) select '".$_POST['pr']."',hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_receive_c set exp_date =NULL where exp_date='000-00-00'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	

	//ค้นหาข้อมูลจาก temp
	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp = "select * from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
	$rs_temp = mysql_query($query_rs_temp, $vmi) or die(mysql_error());
	$row_rs_temp = mysql_fetch_assoc($rs_temp);
	$totalRows_rs_temp = mysql_num_rows($rs_temp);
	
	do{
	//1 ค้นหาว่ามีข้อมูลยาที่รับใน drugs ไหม
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drugs = "select * from stock_drugs where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."'";
	$rs_drugs = mysql_query($query_rs_drugs, $vmi) or die(mysql_error());
	$row_rs_drugs = mysql_fetch_assoc($rs_drugs);
	$totalRows_rs_drugs = mysql_num_rows($rs_drugs);
	
	if($totalRows_rs_drugs<>0){
	///1 ถ้ามี
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs set remain_qty=remain_qty+'".$row_rs_temp['qty']."',pack_ratio='".$row_rs_temp['pack_ratio']."',total_cost=total_cost+'".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					
		
	}
	else {
	///1 ถ้าไม่มี
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_drugs (hospcode,working_code,remain_qty,pack_ratio,total_cost) value ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."','".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());					
	}
	mysql_free_result($rs_drugs);
	///// จบ (1) //////////

	if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";	
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	}

	//2 ค้นหาว่ามีข้อมูลยาที่รับใน drugs_c ไหม
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drugs = "select * from stock_drugs_c where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and lot".$lot." and exp_date ".$exp_date;
	$rs_drugs = mysql_query($query_rs_drugs, $vmi) or die(mysql_error());
	$row_rs_drugs = mysql_fetch_assoc($rs_drugs);
	$totalRows_rs_drugs = mysql_num_rows($rs_drugs);
	
	if($totalRows_rs_drugs<>0){

	///#2 ถ้ามี
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set remain_qty=remain_qty+'".$row_rs_temp['qty']."',pack_ratio='".$row_rs_temp['pack_ratio']."',total_cost=total_cost+'".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."' where working_code='".$row_rs_temp['working_code']."' and hospcode='".$_SESSION['hospcode']."' and lot".$lot." and exp_date".$exp_date;
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					
		
	}
	else {
	///#2 ถ้าไม่มี
	if($row_rs_temp['exp_date']==""){
	$exp_date="NULL";
	$exp_date2=" is NULL";
	}
	else{
	$exp_date="'".$row_rs_temp['exp_date']."'";	
	$exp_date2="='".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot="NULL";	
	$lot2=" is NULL";	

	}
	else{
	$lot="'".$row_rs_temp['lot']."'";	
	$lot2="='".$row_rs_temp['lot']."'";	

	}
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_drugs_c (hospcode,working_code,lot,exp_date,remain_qty,pack_ratio,buy_unit_cost,total_cost) value ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."',".$lot.",".$exp_date.",'".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','".$row_rs_temp['buy_unit_cost']."','".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());					
	}

	mysql_free_result($rs_drugs);
	///// จบ (2) //////////

	/// บันทึกลงตาราง card
	//#3
		if($row_rs_temp['exp_date']==""){
	$exp_date="NULL";
	$exp_date2=" is NULL";
	}
	else{
	$exp_date="'".$row_rs_temp['exp_date']."'";	
	$exp_date2="='".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot="NULL";	
	$lot2=" is NULL";	

	}
	else{
	$lot="'".$row_rs_temp['lot']."'";	
	$lot2="='".$row_rs_temp['lot']."'";	

	}

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_card (hospcode,working_code,operation_date,r_s_status,r_s_number,dept_id,cost,qty,pack_ratio,remain_qty,lot,exp_date) values ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."','".$edate2."','R','".$_POST['pr']."','".$_POST['dept']."','".($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost']."','".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','',".$lot.",".$exp_date.")";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number ='".$_POST['pr']."' and lot".$lot2." and exp_date".$exp_date2." limit 1";
	$rs_card = mysql_query($query_rs_card, $vmi) or die(mysql_error());
	$row_rs_card = mysql_fetch_assoc($rs_card);
	$totalRows_rs_card = mysql_num_rows($rs_card);	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card2 = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number !='".$_POST['pr']."' and operation_date <='".$edate2."' and id<='".$row_rs_card['id']."' order by id DESC limit 1";
	$rs_card2 = mysql_query($query_rs_card2, $vmi) or die(mysql_error());
	$row_rs_card2 = mysql_fetch_assoc($rs_card2);
	$totalRows_rs_card2 = mysql_num_rows($rs_card2);
	// update remain stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set remain_qty='".($row_rs_temp['qty']+$row_rs_card2['remain_qty'])."' where id='".$row_rs_card['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					

mysql_free_result($rs_card2);

mysql_free_result($rs_card);
			
}while($row_rs_temp = mysql_fetch_assoc($rs_temp));
	
	mysql_free_result($rs_temp);


	/*
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "update stock_card s set remain_qty=(select sum(remain_qty) from stock_receive_c c where hospcode='".$_SESSION['hospcode']."' and c.working_code=s.working_code) where hospcode='".$_SESSION['hospcode']."' and remain_qty=''";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
*/
		}

	}
if($_POST['do']=="edit"){
	$date11=explode("/",$_POST['date1']);	
	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp = "select * from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
	$rs_temp = mysql_query($query_rs_temp, $vmi) or die(mysql_error());
	$row_rs_temp = mysql_fetch_assoc($rs_temp);
	$totalRows_rs_temp = mysql_num_rows($rs_temp);
		
		do{
	//1 ค้นหาว่ามีข้อมูลยาที่รับใน drugs ไหม
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drugs = "select * from stock_drugs where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."'";
	$rs_drugs = mysql_query($query_rs_drugs, $vmi) or die(mysql_error());
	$row_rs_drugs = mysql_fetch_assoc($rs_drugs);
	$totalRows_rs_drugs = mysql_num_rows($rs_drugs);
	
	if($totalRows_rs_drugs<>0){
	///1 ถ้ามี
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs set remain_qty=remain_qty+'".$row_rs_temp['qty']."',pack_ratio='".$row_rs_temp['pack_ratio']."',total_cost=total_cost+'".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					
		
	}
	else {
	///1 ถ้าไม่มี
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_drugs (hospcode,working_code,remain_qty,pack_ratio,total_cost) value ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."','".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());					
	}
	mysql_free_result($rs_drugs);
	///// จบ (1) //////////

	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp2 = "select * from stock_receive_c where id='".$row_rs_temp['c_id']."'";
	$rs_temp2 = mysql_query($query_rs_temp2, $vmi) or die(mysql_error());
	$row_rs_temp2 = mysql_fetch_assoc($rs_temp2);
	$totalRows_rs_temp2 = mysql_num_rows($rs_temp2);

	if($totalRows_rs_temp2<>0){
		
	if($row_rs_temp2['exp_date']==""){
	$exp_date=" is NULL";	
	}
	if($row_rs_temp2['exp_date']!=""){
	$exp_date="='".$row_rs_temp2['exp_date']."'";
	}
	if($row_rs_temp2['lot']==""){
	$lot=" is NULL";	
	}
	if($row_rs_temp2['lot']!=""){
	$lot="='".$row_rs_temp2['lot']."'";
	}
	
	if($row_rs_temp['exp_date']==""){
	$exp_date2=" = NULL";	
	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date2="='".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot2=" = NULL";	
	}
	if($row_rs_temp['lot']!=""){
	$lot2="='".$row_rs_temp['lot']."'";
	}
	
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set operation_date='".$edate2."',dept_id='".$_POST['dept']."',qty='".$row_rs_temp['qty']."',pack_ratio='".$row_rs_temp['pack_ratio']."',lot".$lot2.",exp_date".$exp_date2.",cost='".($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost']."' where hospcode='".$_SESSION['hospcode']."' and r_s_number='".$_POST['pr']."' and lot".$lot." and exp_date".$exp_date." and working_code='".$row_rs_temp2['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_receive_c set qty='".$row_rs_temp['qty']."',pack_ratio='".$row_rs_temp['pack_ratio']."',lot".$lot2.",exp_date".$exp_date2.",buy_unit_cost='".$row_rs_temp['buy_unit_cost']."' where id='".$row_rs_temp['c_id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_receive_c set exp_date =NULL where exp_date='000-00-00'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());	

	/// update remain  ใน drugs_c
	//ถ้ามี lot เดิม

	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set remain_qty=remain_qty-'".$row_rs_temp2['qty']."'+'".$row_rs_temp['qty']."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and lot".$lot." and exp_date".$exp_date;
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	// update total cost
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set total_cost=(remain_qty/pack_ratio)*buy_unit_cost where hospcode='".$_SESSION['hospcode']."' and lot".$lot." and exp_date".$exp_date;
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	/// update stock card
	
	if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2="NULL";
	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";	
	$lot2="NULL";	
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";
	}
	mysql_select_db($database_vmi, $vmi);
	$query_rs_card = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number ='".$_POST['pr']."' and lot".$lot." and exp_date".$exp_date." limit 1";
	$rs_card = mysql_query($query_rs_card, $vmi) or die(mysql_error());
	$row_rs_card = mysql_fetch_assoc($rs_card);
	$totalRows_rs_card = mysql_num_rows($rs_card);
	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card2 = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and operation_date <='".$edate2."' and id<'".$row_rs_card['id']."' order by id DESC limit 1";
	$ss=$query_rs_card2;
	$rs_card2 = mysql_query($query_rs_card2, $vmi) or die(mysql_error());
	$row_rs_card2 = mysql_fetch_assoc($rs_card2);
	$totalRows_rs_card2 = mysql_num_rows($rs_card2);

	// update remain stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set remain_qty='".($row_rs_temp['qty']+$row_rs_card2['remain_qty'])."' where id='".$row_rs_card['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					

mysql_free_result($rs_card2);

mysql_free_result($rs_card);

	}
		else{
	
	if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2="NULL";
	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";
	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";	
	$lot2="NULL";	
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";
	}

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_receive_c (receive_no,hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost) select '".$_POST['pr']."',hospcode,working_code,qty,pack_ratio,lot,exp_date,buy_unit_cost from temp_stock_receive_c where id='".$row_rs_temp['id']."'";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());				

	/// บันทึกลงตาราง card
	//#3
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_card (hospcode,working_code,operation_date,r_s_status,r_s_number,dept_id,cost,qty,pack_ratio,remain_qty,lot,exp_date) values ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."','".$edate2."','R','".$_POST['pr']."','".$_POST['dept']."','".($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost']."','".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','',".$lot2.",".$exp_date2.")";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number ='".$_POST['pr']."' order by id DESC limit 1";
	$rs_card = mysql_query($query_rs_card, $vmi) or die(mysql_error());
	$row_rs_card = mysql_fetch_assoc($rs_card);
	$totalRows_rs_card = mysql_num_rows($rs_card);
	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card2 = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number !='".$_POST['pr']."' and operation_date <='".$edate2."' and id<='".$row_rs_card['id']."' order by id DESC limit 1";
	$rs_card2 = mysql_query($query_rs_card2, $vmi) or die(mysql_error());
	$row_rs_card2 = mysql_fetch_assoc($rs_card2);
	$totalRows_rs_card2 = mysql_num_rows($rs_card2);

	// update remain stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set remain_qty='".($row_rs_temp['qty']+$row_rs_card2['remain_qty'])."' where id='".$row_rs_card['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					

mysql_free_result($rs_card2);

mysql_free_result($rs_card);

	/// update remain  ใน drugs_c
	//ถ้ามี lot เดิม
	if($row_rs_temp['exp_date']==""){
	$exp_date="NULL";	
	}
	else{
	$exp_date="'".$row_rs_temp['exp_date']."'";	
	}
	if($row_rs_temp['lot']==""){
	$lot="NULL";	
	}
	else{
	$lot="'".$row_rs_temp['lot']."'";	
	}

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_drugs_c (hospcode,working_code,lot,exp_date,remain_qty,pack_ratio,buy_unit_cost,total_cost) value ('".$_SESSION['hospcode']."','".$row_rs_temp['working_code']."',".$lot.",".$exp_date.",'".$row_rs_temp['qty']."','".$row_rs_temp['pack_ratio']."','".$row_rs_temp['buy_unit_cost']."','".(($row_rs_temp['qty']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost'])."')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());							
		}		
	mysql_free_result($rs_temp2);	
	//update remain ใน stock_drug
	//#1
	mysql_select_db($database_vmi, $vmi);
	$query_rs_lot = "select sum(remain_qty) as sumremain,sum(total_cost) as sumcost from stock_drugs_c where working_code='".$row_rs_temp['working_code']."' and hospcode='".$_SESSION['hospcode']."' group by working_code";
	$rs_lot = mysql_query($query_rs_lot, $vmi) or die(mysql_error());
	$row_rs_lot = mysql_fetch_assoc($rs_lot);
	$totalRows_rs_lot = mysql_num_rows($rs_lot);
	//#2
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs set remain_qty='".$row_rs_lot['sumremain']."' ,total_cost='".$row_rs_lot['sumcost']."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	
		}while($row_rs_temp = mysql_fetch_assoc($rs_temp));	
		
		mysql_free_result($rs_temp);
	
	//ค้นหา c_id ที่จะลบ
	mysql_select_db($database_vmi, $vmi);
	$query_rs_search = "select * from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."' and c_id is not NULL and action='D'";
	$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
	$row_rs_search = mysql_fetch_assoc($rs_search);
	$totalRows_rs_search = mysql_num_rows($rs_search);
	
	do{
	
		if($row_rs_search['exp_date']==""){
	$exp_date=" is NULL";	
	}
	if($row_rs_search['exp_date']!=""){
	$exp_date="='".$row_rs_search['exp_date']."'";
	}
	if($row_rs_search['lot']==""){
	$lot=" is NULL";	
	}
	if($row_rs_search['lot']!=""){
	$lot="='".$row_rs_search['lot']."'";
	}

	/// ลบถ้าจำนวนไม่มี id ใน temp
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_receive_c where id ='".$row_rs_search['c_id']."'";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				
	// ลบ stockcard
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_card where r_s_number ='".$_POST['pr']."' and working_code='".$row_rs_search['working_code']."' and lot".$lot." and exp_date".$exp_date;
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				
	
	// update จำนวนที่ลบใน drug_c
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set remain_qty=remain_qty-'".$row_rs_search['qty']."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."' and lot".$lot." and exp_date".$exp_date;
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	//ลบ ข้อมูลใน drug_c หาก qty=0;
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_drugs_c where remain_qty =0";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				

	// update total cost
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set total_cost=(remain_qty/pack_ratio)*buy_unit_cost where hospcode='".$_SESSION['hospcode']."' and lot".$lot." and exp_date".$exp_date;
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	
	//update remain ใน stock_drug
	//#1
	mysql_select_db($database_vmi, $vmi);
	$query_rs_lot = "select sum(remain_qty) as sumremain,sum(total_cost) as sumcost from stock_drugs_c where working_code='".$row_rs_search['working_code']."' and hospcode='".$_SESSION['hospcode']."' group by working_code";
	$rs_lot = mysql_query($query_rs_lot, $vmi) or die(mysql_error());
	$row_rs_lot = mysql_fetch_assoc($rs_lot);
	$totalRows_rs_lot = mysql_num_rows($rs_lot);
	//#2
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs set remain_qty='".$row_rs_lot['sumremain']."',total_cost='".$row_rs_lot['sumcost']."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	mysql_free_result($rs_lot);
	
	}while($row_rs_search = mysql_fetch_assoc($rs_search));
	
	mysql_free_result($rs_search);
	
	//ค้นหาถ้าหากรายการใน stock_receive_c =0 ให้ลบใบเบิกเลย
	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp3 = "select * from stock_receive_c where receive_no ='".$_POST['pr']."'";
	$rs_temp3 = mysql_query($query_rs_temp3, $vmi) or die(mysql_error());
	$row_rs_temp3 = mysql_fetch_assoc($rs_temp3);
	$totalRows_rs_temp3 = mysql_num_rows($rs_temp3);
	
	if($totalRows_rs_temp3==0){
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_receive where receive_no ='".$_POST['pr']."'";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				

	}
	mysql_free_result($rs_temp3);
}

				
	//exit();
	//////////// สิ้นสุดการบันทึก //////////
	echo "<script>window.location.href='stock_receive.php'</script>";
	/// จับ $msg ///
	}
	
}
	
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from temp_stock_receive_c where hospcode='".$_SESSION['hospcode']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select l.dept_code,d.dept_group_name from dept_list l left outer join hospcode h on h.hospcode=l.dept_code  left outer join dept_group d on d.dept_group_id=l.dept_group where h.chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' group by d.dept_group_id order by d.dept_group_name ASC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

mysql_select_db($database_vmi, $vmi);
$query_rs_serial = "select * from stock_serial where hospcode='".$_SESSION['hospcode']."'";
$rs_serial = mysql_query($query_rs_serial, $vmi) or die(mysql_error());
$row_rs_serial = mysql_fetch_assoc($rs_serial);
$totalRows_rs_serial = mysql_num_rows($rs_serial);


if($totalRows_rs_serial==0){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_serial (hospcode,receive_no,withdraw_no) value ('".$_SESSION['hospcode']."','0','0')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_sereial2 = "select * from stock_serial where hospcode='".$_SESSION['hospcode']."'";
$rs_sereial2 = mysql_query($query_rs_sereial2, $vmi) or die(mysql_error());
$row_rs_sereial2 = mysql_fetch_assoc($rs_sereial2);
$totalRows_rs_sereial2 = mysql_num_rows($rs_sereial2);


$serial="PR".$_SESSION['hospcode'].sprintf("%05d",($row_rs_sereial2['receive_no']+1));

mysql_free_result($rs_sereial2);



	}
else{
$serial="PR".$_SESSION['hospcode'].sprintf("%05d",($row_rs_serial['receive_no']+1));	
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
div.divhead{
	
	position:fixed;
	top:0;
	width:100%;
	font-size:23px;
	padding:10px;
	border-bottom:solid 1px #CCCCCC;
	background-color:#ededed;
	}
div.divcontent{
	margin-top:40px;
	padding:10px;
	width:98%;	
	}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
</head>

<body onLoad="formSubmit('save','form1','stock_receive_list.php','divload','indicator');">
<div class="thsan-light divhead "><img src="images/receive.png" width="34" height="31" align="absmiddle"> &nbsp;&nbsp;รับยาเข้าคลัง ( Drug receive)</div>
<div class="divcontent thsan-light font18" >
  <form name="form1" method="post" action="stock_receive.php">
    <table width="500" border="0" cellspacing="0" cellpadding="5" style="margin-top:10px;">
      <tr>
        <td width="29%">เลขที่รับ</td>
        <td width="71%"><input name="pr" type="text" class="inputcss1 thsan-light font18" id="pr" style="width:100px; background-color:transparent; border:0px" readonly  value="<?php echo $serial; ?>" >
        <input type="hidden" name="do" id="do">
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="pr2" id="pr2">
        <a href="#" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; text-align:center; padding-left:10px; padding-right:10px; background-color: #999; border:1px solid #CCC; text-decoration:none; color:#FFF; height:30px" onClick="OpenPopup('stock_receive_search.php','600','400','');">
        <img src="images/search-icon-hi.png" width="20" height="20" border="0" align="absmiddle" style=" padding-bottom:3px">&nbsp;ค้นหา</a></td>
      </tr>
      <tr>
        <td>วันที่รับ</td>
        <td><span class="input-group" style="width:200px">
          <input type="text" id="date1" name="date1"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date1'])){ echo $date1; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
        </span></td>
      </tr>
      <tr>
        <td>รับจาก</td>
        <td><label for="dept"></label>
          <select name="dept" id="dept" class="inputcss1 thsan-light font18">
            <?php
do {  
?>
            <option value="<?php echo $row_rs_dept['dept_code']?>"<?php if (!(strcmp($row_rs_dept['dept_code'], $_POST['dept']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_dept['dept_group_name']?></option>
            <?php
} while ($row_rs_dept = mysql_fetch_assoc($rs_dept));
  $rows = mysql_num_rows($rs_dept);
  if($rows > 0) {
      mysql_data_seek($rs_dept, 0);
	  $row_rs_dept = mysql_fetch_assoc($rs_dept);
  }
?>
        </select>
        <a href="#" class="thsan-semibold font19 gray" style=" padding-bottom:5px; text-align:center; padding-left:10px; padding-right:10px; background-color: #CCC; border:1px solid #CCC; text-decoration:none; color:#666; height:15px" onClick="OpenPopup('stock_import.php','600','400','');">import</a>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="javascript:valid(0);" onClick="OpenPopup('stock_receive_add.php','600','400','');" id="add" name="add" class="button orange " style="padding:3px; padding-left:10px; padding-right:10px; height:25px; padding-button:5px; "><img src="images/add-drug2.png" width="20" height="20" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a> </td>
      </tr>
    </table>
   <div  id="divload" style="margin-left:0px; margin-top:10px;"></div>
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div> 
      </form>
</div>

</body>
</html>
<?php

mysql_free_result($rs_dept);

mysql_free_result($rs_serial);

?>
