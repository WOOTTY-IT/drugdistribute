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
	if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
		//บนทึกลงตาราง stock_withdraw
		$date11=explode("/",$_POST['date1']);
		$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);
if($_POST['do']=="save"){
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into stock_withdraw_c (r_id,qty_order,pack_ratio,working_code,hospcode,withdraw_no,lot,exp_date,buy_unit_cost) select r_id,qty_order,pack_ratio,working_code,hospcode,'".$_POST['ps']."',lot,exp_date,buy_unit_cost from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
		//นับจำนวนรายการทั้งหมด
		mysql_select_db($database_vmi, $vmi);
$query_item_count = "select sum((w.qty_order/r.pack_ratio)*r.buy_unit_cost) as sumprice ,count(distinct(w.working_code)) as sumitem from stock_withdraw_c w left outer join stock_receive_c r on r.id=w.r_id where w.hospcode='".$_SESSION['hospcode']."' and w.withdraw_no='".$_POST['ps']."'";
$item_count = mysql_query($query_item_count, $vmi) or die(mysql_error());
$row_item_count = mysql_fetch_assoc($item_count);
$totalRows_item_count = mysql_num_rows($item_count);


		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into stock_withdraw (hospcode,date_withdraw,withdraw_no,total_item,total_cost,dept_id) value ('".$_SESSION['hospcode']."','".$edate2."','".$_POST['ps']."','".$row_item_count['sumitem']."','".$row_item_count['sumprice']."','".$_POST['dept']."')";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
		
		/// ตัดยอดใน stock_receive_c
	mysql_select_db($database_vmi, $vmi);
	$query_rs_search = "select * from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
	$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
	$row_rs_search = mysql_fetch_assoc($rs_search);
	$totalRows_rs_search = mysql_num_rows($rs_search);
	
//	$z=0;
	do{
		
	if($row_rs_search['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2=" NULL";	

	}
	if($row_rs_search['exp_date']!=""){
	$exp_date="='".$row_rs_search['exp_date']."'";
	$exp_date2="'".$row_rs_search['exp_date']."'";

	}
	if($row_rs_search['lot']==""){
	$lot=" is NULL";
	$lot2=" NULL";
		
	}
	if($row_rs_search['lot']!=""){
	$lot="='".$row_rs_search['lot']."'";
	$lot2="'".$row_rs_search['lot']."'";

	}
	
	//ค้นหารายการยาแบบเรียง lot
	mysql_select_db($database_vmi, $vmi);
	$query_rs_search2 = "select * from stock_drugs_c where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."' and lot".$lot." and exp_date ".$exp_date." and remain_qty !=0 ";
	$rs_search2 = mysql_query($query_rs_search2, $vmi) or die(mysql_error());
	$row_rs_search2 = mysql_fetch_assoc($rs_search2);
	$totalRows_rs_search2 = mysql_num_rows($rs_search2);
	
/*		$ii=0;
		$qtys=0;
		//$qtys=$row_rs_search['qty_order'];
*/
		do{
/*
		if($ii==0){
		$qty_dif=$row_rs_search2['remain_qty']-$row_rs_search['qty_order'];
		}
		if($ii>0){
		$qty_dif=$row_rs_search2['remain_qty']-$qtys;
		}
		if($qty_dif<=0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_receive_c set remain_qty='0' where hospcode='".$_SESSION['hospcode']."' and id='".$row_rs_search2['id']."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
			// จำนวนที่เหลือ
			$qtys=$row_rs_search['qty_order']-$row_rs_search2['remain_qty'];
		}
		
		if($qty_dif>0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_receive_c set remain_qty='".$qty_dif."' where hospcode='".$_SESSION['hospcode']."' and id='".$row_rs_search2['id']."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
		}
		
		$ii++;*/

		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_drugs_c set remain_qty=remain_qty-'".$row_rs_search['qty_order']."',total_cost=((remain_qty/pack_ratio)*'".($row_rs_search2['total_cost']/($row_rs_search2['remain_qty']/$row_rs_search2['pack_ratio']))."') where id='".$row_rs_search2['id']."' ";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());	
		}while($row_rs_search2 = mysql_fetch_assoc($rs_search2));
	mysql_free_result($rs_search2);	

	//update remain ใน stock_drug
	//#1
	mysql_select_db($database_vmi, $vmi);
	$query_rs_lot = "select sum(remain_qty) as sumremain,sum(total_cost) as sumcost from stock_drugs_c where working_code='".$row_rs_search['working_code']."' and hospcode='".$_SESSION['hospcode']."' group by working_code";
	$rs_lot = mysql_query($query_rs_lot, $vmi) or die(mysql_error());
	$row_rs_lot = mysql_fetch_assoc($rs_lot);
	$totalRows_rs_lot = mysql_num_rows($rs_lot);
	//#2
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs set remain_qty='".$row_rs_lot['sumremain']."' ,total_cost='".$row_rs_lot['sumcost']."' where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	
	mysql_free_result($rs_lot);

	/// บันทึกลงตาราง card
	//#3
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_card (hospcode,working_code,operation_date,r_s_status,r_s_number,dept_id,cost,qty,pack_ratio,remain_qty,lot,exp_date) values ('".$_SESSION['hospcode']."','".$row_rs_search['working_code']."','".$edate2."','S','".$_POST['ps']."','".$_SESSION['hospcode']."','".($row_rs_search['qty_order']/$row_rs_search['pack_ratio'])*$row_rs_search['buy_unit_cost']."','".$row_rs_search['qty_order']."','".$row_rs_search['pack_ratio']."','',".$lot2.",".$exp_date2.")";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."' and r_s_number ='".$_POST['ps']."' and lot".$lot." and exp_date".$exp_date." limit 1";
	$rs_card = mysql_query($query_rs_card, $vmi) or die(mysql_error());
	$row_rs_card = mysql_fetch_assoc($rs_card);
	$totalRows_rs_card = mysql_num_rows($rs_card);	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card2 = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_search['working_code']."' and r_s_number !='".$_POST['ps']."' and operation_date <='".$edate2."' and id<='".$row_rs_card['id']."' order by id DESC limit 1";
	$rs_card2 = mysql_query($query_rs_card2, $vmi) or die(mysql_error());
	$row_rs_card2 = mysql_fetch_assoc($rs_card2);
	$totalRows_rs_card2 = mysql_num_rows($rs_card2);
	// update remain stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set remain_qty='".($row_rs_card2['remain_qty']-$row_rs_search['qty_order'])."' where id='".$row_rs_card['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					

mysql_free_result($rs_card2);

mysql_free_result($rs_card);
		
	}while(	$row_rs_search = mysql_fetch_assoc($rs_search));	
	mysql_free_result($rs_search);	
	

	if($insert){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_serial set withdraw_no=withdraw_no+1 where hospcode='".$_SESSION['hospcode']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());


	mysql_free_result($item_count);

	}

		}
if($_POST['do']=="edit"){


	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp = "select * from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
	$rs_temp = mysql_query($query_rs_temp, $vmi) or die(mysql_error());
	$row_rs_temp = mysql_fetch_assoc($rs_temp);
	$totalRows_rs_temp = mysql_num_rows($rs_temp);
		
		do{
	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp2 = "select * from stock_withdraw_c where id='".$row_rs_temp['c_id']."'";
	$rs_temp2 = mysql_query($query_rs_temp2, $vmi) or die(mysql_error());
	$row_rs_temp2 = mysql_fetch_assoc($rs_temp2);
	$totalRows_rs_temp2 = mysql_num_rows($rs_temp2);


	if($totalRows_rs_temp2<>0){
	
	if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2=" NULL";	

	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";

	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";
	$lot2=" NULL";
		
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";

	}

	if($row_rs_temp['action']!='D'){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_withdraw_c set qty_order='".$row_rs_temp['qty_order']."',pack_ratio='".$row_rs_temp['pack_ratio']."',lot=".$lot2.",exp_date=".$exp_date2." where id='".$row_rs_temp['c_id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
		/// บันทึกลงตาราง card
	// ถ้าเคยบันทึก stock_card แล้ว	
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set operation_date='".$edate2."',dept_id='".$_SESSION['hospcode']."',qty='".$row_rs_temp['qty_order']."',pack_ratio='".$row_rs_temp['pack_ratio']."',lot=".$lot2.",exp_date=".$exp_date2.",cost='".($row_rs_temp['qty_order']/$row_rs_temp['pack_ratio'])*$row_rs_temp['buy_unit_cost']."' where hospcode='".$_SESSION['hospcode']."' and r_s_number='".$_POST['ps']."' and lot".$lot." and exp_date".$exp_date." and working_code='".$row_rs_temp['working_code']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	$process="1";		
	}
	
		// ถ้า action=D
	if($row_rs_temp['action']=='D'){
	
	if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2=" NULL";	

	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";

	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";
	$lot2=" NULL";
		
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";

	}
	// update 
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_drugs_c set remain_qty=remain_qty+'".$row_rs_temp2['qty_order']."',total_cost=((remain_qty/pack_ratio)*'".$row_rs_temp2['buy_unit_cost']."') where working_code='".$row_rs_temp2['working_code']."' and lot".$lot." and exp_date".$exp_date." and hospcode='".$_SESSION['hospcode']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());

	//ลบ stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_card where working_code='".$row_rs_temp2['working_code']."' and lot".$lot." and exp_date".$exp_date." and hospcode='".$_SESSION['hospcode']."' and r_s_number='".$row_rs_temp2['withdraw_no']."'";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				
	$process="2";		

	}


		}
		else{
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_withdraw_c (withdraw_no,hospcode,r_id,working_code,qty_order,pack_ratio,lot,exp_date) select '".$_POST['ps']."',hospcode,r_id,working_code,qty_order,pack_ratio,lot,exp_date from temp_stock_withdraw_c where id='".$row_rs_temp['id']."'";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());				

	//ถ้ายังไม่เคยบันทึกใน stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_card (hospcode,working_code,operation_date,r_s_status,r_s_number,dept_id,cost,qty,pack_ratio,remain_qty,lot,exp_date) select c.hospcode,c.working_code,'".$edate2."','S','".$_POST['ps']."','".$_SESSION['hospcode']."',(c.qty_order/c.pack_ratio)*c2.buy_unit_cost,c.qty_order,c.pack_ratio,'',c.lot,c.exp_date  from temp_stock_withdraw_c c left outer join stock_receive_c c2 on c2.id=c.r_id where c.hospcode='".$_SESSION['hospcode']."'";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	

	$process="1";
		}
		
	if($process=="1"){
/// ทำรายการตัดยอดเพื่อหา remain_qty
	//ค้นหารายการยาแบบเรียง lot
		if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2=" NULL";	

	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";

	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";
	$lot2=" NULL";
		
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";

	}
	mysql_select_db($database_vmi, $vmi);
	$query_rs_search2 = "select * from stock_drugs_c where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and lot".$lot." and exp_date ".$exp_date." and remain_qty !=0 ";
	$rs_search2 = mysql_query($query_rs_search2, $vmi) or die(mysql_error());
	$row_rs_search2 = mysql_fetch_assoc($rs_search2);
	$totalRows_rs_search2 = mysql_num_rows($rs_search2);

//		$ii=0;
//		$qtys=0;
		//$qtys=$row_rs_search['qty_order'];
		do{
/*		if($ii==0){
		$qty_dif=$row_rs_search2['remain_qty']-$row_rs_temp['qty_order'];
		}
		if($ii>0){
		$qty_dif=$row_rs_search2['remain_qty']-$qtys;
		}
		if($qty_dif<=0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_receive_c set remain_qty='0' where hospcode='".$_SESSION['hospcode']."' and id='".$row_rs_search2['id']."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
			// จำนวนที่เหลือ
			$qtys=$row_rs_search['qty_order']-$row_rs_search2['remain_qty'];
		}
		
		if($qty_dif>0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_receive_c set remain_qty='".$qty_dif."' where hospcode='".$_SESSION['hospcode']."' and id='".$row_rs_search2['id']."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
		}
		
		$ii++;
*/		
		if($totalRows_rs_temp2<>0){
		$msg=1;
		//ถ้า update stock_withdraw_c
		$qty_order=0+$row_rs_temp2['qty_order']-$row_rs_temp['qty_order'];
		}
		else{
		$msg=2;
		// ถ้า insert stock_withdraw_c
		$qty_order=0-$row_rs_temp['qty_order'];
		}
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update stock_drugs_c set remain_qty=remain_qty+'".$qty_order."',total_cost=((remain_qty/pack_ratio)*'".$row_rs_temp2['buy_unit_cost']."') where id='".$row_rs_search2['id']."' ";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());	
		$msg.=$query_update;
		}while($row_rs_search2 = mysql_fetch_assoc($rs_search2));
		
	}
	
		
	mysql_free_result($rs_temp2);	

	/// บันทึกลงตาราง card
		if($row_rs_temp['exp_date']==""){
	$exp_date=" is NULL";	
	$exp_date2=" NULL";	

	}
	if($row_rs_temp['exp_date']!=""){
	$exp_date="='".$row_rs_temp['exp_date']."'";
	$exp_date2="'".$row_rs_temp['exp_date']."'";

	}
	if($row_rs_temp['lot']==""){
	$lot=" is NULL";
	$lot2=" NULL";
		
	}
	if($row_rs_temp['lot']!=""){
	$lot="='".$row_rs_temp['lot']."'";
	$lot2="'".$row_rs_temp['lot']."'";

	}
	mysql_select_db($database_vmi, $vmi);
	$query_rs_card = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number ='".$_POST['ps']."' and lot".$lot." and exp_date".$exp_date." limit 1";
	$rs_card = mysql_query($query_rs_card, $vmi) or die(mysql_error());
	$row_rs_card = mysql_fetch_assoc($rs_card);
	$totalRows_rs_card = mysql_num_rows($rs_card);	

	mysql_select_db($database_vmi, $vmi);
	$query_rs_card2 = "select * from stock_card where hospcode='".$_SESSION['hospcode']."' and working_code='".$row_rs_temp['working_code']."' and r_s_number !='".$_POST['ps']."' and operation_date <='".$edate2."' and id<='".$row_rs_card['id']."' order by id DESC limit 1";
	$rs_card2 = mysql_query($query_rs_card2, $vmi) or die(mysql_error());
	$row_rs_card2 = mysql_fetch_assoc($rs_card2);
	$totalRows_rs_card2 = mysql_num_rows($rs_card2);
	// update remain stock_card
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_card set remain_qty='".($row_rs_card2['remain_qty']-$row_rs_temp['qty_order'])."' where id='".$row_rs_card['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());					

mysql_free_result($rs_card2);

mysql_free_result($rs_card);

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
	
	mysql_free_result($rs_lot);
		}while($row_rs_temp = mysql_fetch_assoc($rs_temp));			
		mysql_free_result($rs_temp);


	
	/// ลบถ้าจำนวนไม่มี id ใน temp
		mysql_select_db($database_vmi, $vmi);
	$query_rs_temp4 = "	select r_id,c_id,qty_order from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."' and c_id is not NULL and action='D'
";
	$rs_temp4 = mysql_query($query_rs_temp4, $vmi) or die(mysql_error());
	$row_rs_temp4= mysql_fetch_assoc($rs_temp4);
	$totalRows_rs_temp4 = mysql_num_rows($rs_temp4);

	do{
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_withdraw_c where id ='".$row_rs_temp4['c_id']."' and withdraw_no ='".$_POST['ps']."'";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				
	
	} while ($row_rs_temp4= mysql_fetch_assoc($rs_temp4));
	
	mysql_free_result($rs_temp4);
	
	//ค้นหาถ้าหากรายการใน stock_receive_c =0 ให้ลบใบเบิกเลย
	mysql_select_db($database_vmi, $vmi);
	$query_rs_temp3 = "select * from stock_withdraw_c where withdraw_no ='".$_POST['ps']."'";
	$rs_temp3 = mysql_query($query_rs_temp3, $vmi) or die(mysql_error());
	$row_rs_temp3 = mysql_fetch_assoc($rs_temp3);
	$totalRows_rs_temp3 = mysql_num_rows($rs_temp3);
	
	if($totalRows_rs_temp3==0){
	mysql_select_db($database_vmi, $vmi);
	$query_delete1 = "delete from stock_withdraw where withdraw_no ='".$_POST['ps']."'";
	$delete = mysql_query($query_delete1, $vmi) or die(mysql_error());				

	}
	mysql_free_result($rs_temp3);
	
				//นับจำนวนรายการทั้งหมด
		mysql_select_db($database_vmi, $vmi);
$query_item_count = "select sum((w.qty_order/r.pack_ratio)*r.buy_unit_cost) as sumprice ,count(distinct(w.working_code)) as sumitem from stock_withdraw_c w left outer join stock_receive_c r on r.id=w.r_id where w.hospcode='".$_SESSION['hospcode']."' and w.withdraw_no='".$_POST['ps']."'";
$item_count = mysql_query($query_item_count, $vmi) or die(mysql_error());
$row_item_count = mysql_fetch_assoc($item_count);
$totalRows_item_count = mysql_num_rows($item_count);

	mysql_select_db($database_vmi, $vmi);
	$query_update = "update stock_withdraw set dept_id='".$_POST['dept']."',date_withdraw='".$edate2."',total_item='".$row_item_count['sumitem']."', total_cost='".$row_item_count['sumprice']."' where withdraw_no='".$_POST['ps']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());
	mysql_free_result($item_count);

}

		////////////////////////
		echo "<script>window.location.href='stock_withdraw.php'</script>";

	}
	
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."'";
$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_serial = "select * from stock_serial where hospcode='".$_SESSION['hospcode']."'";
$rs_serial = mysql_query($query_rs_serial, $vmi) or die(mysql_error());
$row_rs_serial = mysql_fetch_assoc($rs_serial);
$totalRows_rs_serial = mysql_num_rows($rs_serial);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select * from stock_withdraw_dept where hospcode='".$_SESSION['hospcode']."' order by dept_name ASC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

if($totalRows_rs_serial==0){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into stock_serial (hospcode,receive_no,withdraw_no) value ('".$_SESSION['hospcode']."','0','0')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_sereial2 = "select * from stock_serial where hospcode='".$_SESSION['hospcode']."'";
$rs_sereial2 = mysql_query($query_rs_sereial2, $vmi) or die(mysql_error());
$row_rs_sereial2 = mysql_fetch_assoc($rs_sereial2);
$totalRows_rs_sereial2 = mysql_num_rows($rs_sereial2);


$serial="PS".$_SESSION['hospcode'].sprintf("%05d",($row_rs_sereial2['withdraw_no']+1));

mysql_free_result($rs_sereial2);




mysql_free_result($rs_serial);

	}
else{
$serial="PS".$_SESSION['hospcode'].sprintf("%05d",($row_rs_serial['withdraw_no']+1));	
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

<body onLoad="formSubmit('save','form1','stock_withdraw_list.php','divload','indicator');">
<div class="thsan-light divhead "><img src="images/withdraw.png" width="34" height="31" align="absmiddle"> &nbsp;&nbsp;เบิกยาจากคลัง ( Drug Withdrawal)</div>
<div class="divcontent thsan-light font18" >
  <form name="form1" method="post" action="stock_withdraw.php">
    <table width="500" border="0" cellspacing="0" cellpadding="5" style="margin-top:10px;">
      <tr>
        <td>เลขที่เบิก</td>
        <td><input name="ps" type="text" class="inputcss1 thsan-light font18" id="ps" style="width:100px; background-color:transparent; border:0px" readonly  value="<?php echo $serial; ?>" >
          <input type="hidden" name="do" id="do">
        <input type="hidden" name="id" id="id">        <input type="hidden" name="ps2" id="ps2"><a href="#" class="thsan-semibold font19" style="padding-top:2px; padding-bottom:3px; text-align:center; padding-left:10px; padding-right:10px; background-color: #999; border:1px solid #CCC; text-decoration:none; color:#FFF; height:30px" onClick="OpenPopup('stock_withdraw_search.php','600','400','');">
        <img src="images/search-icon-hi.png" width="20" height="20" border="0" align="absmiddle" style=" padding-bottom:3px">&nbsp;ค้นหา</a></td>
      </tr>
      <tr>
        <td width="121">วันที่เบิก</td>
        <td width="367"><span class="input-group" style="width:200px">
          <input type="text" id="date1" name="date1"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if(isset($_POST['date1'])){ echo $date1; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
        </span></td>
      </tr>
      <tr>
        <td>หน่วยงานที่เบิก</td>
        <td><label for="dept"></label>
          <select name="dept" id="dept" class="inputcss1 thsan-light font18">
            <?php
do {  
?>
            <option value="<?php echo $row_rs_dept['id']?>"<?php if (!(strcmp($row_rs_dept['id'], $_POST['dept']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_dept['dept_name']?></option>
            <?php
} while ($row_rs_dept = mysql_fetch_assoc($rs_dept));
  $rows = mysql_num_rows($rs_dept);
  if($rows > 0) {
      mysql_data_seek($rs_dept, 0);
	  $row_rs_dept = mysql_fetch_assoc($rs_dept);
  }
?>
          </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="javascript:valid(0);" onClick="OpenPopup('stock_withdraw_add.php','700','500','');" id="add" name="add" class="button orange " style="padding:3px; padding-left:10px; padding-right:10px; height:25px; padding-button:5px; "><img src="images/add-drug2.png" width="20" height="20" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a></td>
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

?>