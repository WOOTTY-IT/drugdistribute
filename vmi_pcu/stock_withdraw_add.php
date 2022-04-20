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

	if(isset($_POST['save2'])&&($_POST['save2']=="ยกเลิกลบ")){
mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_withdraw_c set action=NULL where working_code='".$_POST['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());		
	$state="success";	
	}

	if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
		for($z=0;$z<count($id);$z++){
			if($_POST['qty_order'][$z]!=0){
			
			if($_POST['exp_date'][$z]==""){
			$exp="NULL";	
			}
			else{
			$exp="'".$_POST['exp_date'][$z]."'";
			}
			if($_POST['lot'][$z]==""){
			$lot="NULL";	
			}
			else{
			$lot="'".$_POST['lot'][$z]."'";
			}
			mysql_select_db($database_vmi, $vmi);
			$query_insert = "insert into temp_stock_withdraw_c (r_id,qty_order,pack_ratio,working_code,hospcode,lot,exp_date,buy_unit_cost) value ('".$_POST['id'][$z]."','".($_POST['qty_order'][$z]*$_POST['pack_ratio'][$z])."','".$_POST['pack_ratio3']."','".$_POST['working_code']."','".$_SESSION['hospcode']."',".$lot.",".$exp.",'".$_POST['buy_unit_cost'][$z]."')";
			$insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
			}
		}
	$state="success";

		
	}
if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
		for($z=0;$z<count($id);$z++){
			/// ถ้า qty เป็น 0
			if($_POST['qty_order'][$z]==0){
			mysql_select_db($database_vmi, $vmi);
			$query_update = "delete from temp_stock_withdraw_c where r_id='".$_POST['id'][$z]."' and hospcode='".$_SESSION['hospcode']."'";
			$update = mysql_query($query_update, $vmi) or die(mysql_error());	
			}
			// ถ้า qty ไม่เป็น 0
			if($_POST['qty_order'][$z]!=0){
				//ค้นหาว่าเคยบันทึกแล้วรึยัง
				mysql_select_db($database_vmi, $vmi);
				$query_rs_search = "select * from temp_stock_withdraw_c where r_id='".$_POST['id'][$z]."' and hospcode='".$_SESSION['hospcode']."'";
				$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
				$row_rs_search = mysql_fetch_assoc($rs_search);
				$totalRows_rs_search = mysql_num_rows($rs_search);
			if($totalRows_rs_search<>0){
			mysql_select_db($database_vmi, $vmi);
			$query_update = "update temp_stock_withdraw_c set qty_order='".($_POST['qty_order'][$z]*$_POST['pack_ratio'][$z])."',pack_ratio='".$_POST['pack_ratio3']."' where r_id='".$_POST['id'][$z]."' and hospcode='".$_SESSION['hospcode']."'";
			$update = mysql_query($query_update, $vmi) or die(mysql_error());	
			}
			else{
						
			if($_POST['exp_date'][$z]==""){
			$exp="NULL";	
			}
			else{
			$exp="'".$_POST['exp_date'][$z]."'";
			}
			if($_POST['lot'][$z]==""){
			$lot="NULL";	
			}
			else{
			$lot="'".$_POST['lot'][$z]."'";
			}

			mysql_select_db($database_vmi, $vmi);
			$query_update = "insert into temp_stock_withdraw_c (r_id,qty_order,pack_ratio,working_code,hospcode,lot,exp_date,buy_unit_cost) value ('".$_POST['id'][$z]."','".($_POST['qty_order'][$z]*$_POST['pack_ratio'][$z])."','".$_POST['pack_ratio3']."','".$_POST['working_code']."','".$_SESSION['hospcode']."',".$lot.",".$exp.",'".$_POST['buy_unit_cost'][$z]."')";
			$update = mysql_query($query_update, $vmi) or die(mysql_error());	

			}
			}
		}
	$state="success";
		
	}

if(isset($_POST['save2'])&&($_POST['save2']=="ลบ")){
	mysql_select_db($database_vmi, $vmi);
	$query_rs_drug = "select * from temp_stock_withdraw_c where working_code='".$_POST['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
	$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
	$row_rs_drug = mysql_fetch_assoc($rs_drug);
	$totalRows_rs_drug = mysql_num_rows($rs_drug);
	if(($totalRows_rs_drug!=0)&&($row_rs_drug['c_id']==NULL)){
		echo "delete";
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from temp_stock_withdraw_c  where id='".$row_rs_drug['id']."'";
	$delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	$state="success";
	}
	else if(($totalRows_rs_drug!=0)&&($row_rs_drug['c_id']!=NULL)){
	echo "update";
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update temp_stock_withdraw_c set action='D' where id='".$row_rs_drug['id']."'";
	$update = mysql_query($query_update, $vmi) or die(mysql_error());		
	$state="success";

	}
	mysql_free_result($rs_drug);
	echo $state;

}
	
	if(isset($_GET['do'])&&($_GET['do']=="edit")){
	//// 	หาจำนวนทั้งหมดที่เคยเบิกไว้
	mysql_select_db($database_vmi, $vmi);
	$query_sum_qty = "select sum(qty_order) as sum_qty,pack_ratio from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."' and working_code='".$_GET['did']."' group by working_code";
	$sum_qty = mysql_query($query_sum_qty, $vmi) or die(mysql_error());
	$row_sum_qty = mysql_fetch_assoc($sum_qty);
	$totalRows_sum_qty = mysql_num_rows($sum_qty);
	//////
	$condition=" and c.working_code='".$_GET['did']."'";
	$condition2=" ";
	}
	else{
	$condition=" and c.working_code not in (select working_code from temp_stock_withdraw_c where hospcode='".$_SESSION['hospcode']."' group by c.working_code)";	
	$condition2=" and c.remain_qty >0";
	}

	if($state=="success"){
	echo "<script>parent.$.fn.colorbox.close();parent.formSubmit('".$_POST['get_do']."','form1','stock_withdraw_list.php','divload','indicator','edit');</script>";
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select d.working_code,d.drug_name from stock_drugs_c c left outer join drugs d on d.working_code=c.working_code and d.chwpart='".$_SESSION['chwpart']."' and d.amppart='".$_SESSION['amppart']."' where c.hospcode='".$_SESSION['hospcode']."' ".$condition2.$condition."  group by c.working_code order by d.drug_name ASC ";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);

if(isset($_GET['did'])&&($_GET['did']!="")){
mysql_select_db($database_vmi, $vmi);
$query_rs_selected = "select *,(total_cost/(remain_qty/pack_ratio)) as buy_unit_cost,sum(remain_qty) as remain_qty from stock_drugs_c where working_code='".$_GET['did']."' and hospcode='".$_SESSION['hospcode']."' group by lot order by exp_date ASC";
$rs_selected = mysql_query($query_rs_selected, $vmi) or die(mysql_error());
$row_rs_selected = mysql_fetch_assoc($rs_selected);
$totalRows_rs_selected = mysql_num_rows($rs_selected);

mysql_select_db($database_vmi, $vmi);
$query_rs_selected2 = "select * from temp_stock_withdraw_c where working_code='".$_GET['did']."' and hospcode='".$_SESSION['hospcode']."' group by working_code ";
$rs_selected2 = mysql_query($query_rs_selected2, $vmi) or die(mysql_error());
$row_rs_selected2 = mysql_fetch_assoc($rs_selected2);
$totalRows_rs_selected2 = mysql_num_rows($rs_selected2);

mysql_select_db($database_vmi, $vmi);
$query_rs_pack = "select pack_ratio from stock_receive_c where working_code='".$_GET['did']."' and hospcode='".$_SESSION['hospcode']."' group by pack_ratio";
$rs_pack = mysql_query($query_rs_pack, $vmi) or die(mysql_error());
$row_rs_pack = mysql_fetch_assoc($rs_pack);
$totalRows_rs_pack = mysql_num_rows($rs_pack);
}
include('include/function.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
<script>
$(document).ready(function() {
	<?php 	if(isset($_GET['do'])&&($_GET['do']=="edit")){
?>
	$('#save').show();	
	$('#save2').show();	
	
<?php } else {?>
	$('#save').hide();
	$('#save2').hide();	
	
<?php } ?>		
});

function sum_order2(){
	var total = 0;//
    var list = document.getElementsByClassName("sellist");
    var pack = document.getElementsByClassName("selpack");

    var values = [];
    for(var i = 0; i < list.length; ++i) {
        values.push(parseFloat((list[i].value*pack[i].value)));

    }
    total = values.reduce(function(previousValue, currentValue, index, array){
        return previousValue + currentValue;
    });
    document.getElementById("qtysum").value = total/$('#pack_ratio3').val();
	$('#qty1').val(total);
	
		if(parseInt($('#qtysum').val())==0){
		<?php 	if(!isset($_GET['do'])&&($_GET['do']!="edit")){ ?>
		$('#save').hide();	
		<?php } ?>
		}    
		else if(parseInt($('#qtysum').val())!=0){
		$('#save').show();	
		}


	}
	
function sum_order3(){
	$("select[name='pack_ratio3']").each(function(){
	if($(this).val()!='')
		{
		if($('#qty1').val()!=""){
			$('#qtysum').val(parseInt($('#qty1').val())/parseInt($(this).val()));
		}
		}
	});
	
}
function sum_order(){
var qtysum=0;
$("select[name='qty_order[]']").each(function(){
	if($(this).val()!='')
    {
		qtysum=qtysum+parseInt(($(this).val()));		

	}
});

$('#qtysum').val(qtysum);
}

</script>

</head>

<body>
<div style="padding:10px" class=" thsan-semibold font20">
ทำรายการเบิกยาจากคลัง
</div>
<div style="padding:10px" class=" thsan-light font16">
  <form name="form1" method="post" action="">
    <table width="600" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="99">เลือกรายการยา</td>
        <td width="481"><label for="item"></label>
          <select name="item" id="item" onChange="window.location.href='stock_withdraw_add.php?did='+this.value" class="thsan-light font20 inputcss1">
            <?php 	if(!isset($_GET['do'])&&($_GET['do']!="edit")){
?><option value="" <?php if (!(strcmp("", $_GET['did']))) {echo "selected=\"selected\"";} ?>>เลือกรายการยา</option><?php } ?>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_item['working_code']?>"<?php if (!(strcmp($row_rs_item['working_code'], $_GET['did']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item['drug_name']?></option>
            <?php
} while ($row_rs_item = mysql_fetch_assoc($rs_item));
  $rows = mysql_num_rows($rs_item);
  if($rows > 0) {
      mysql_data_seek($rs_item, 0);
	  $row_rs_item = mysql_fetch_assoc($rs_item);
  }
?>
        </select>
        <input name="working_code" type="hidden" id="working_code" value="<?php echo $_GET['did']; ?>">
        <input name="get_do" type="hidden" id="get_do" value="<?php echo $_GET['do']; ?>"></td>
      </tr>
      <tr>
        <td colspan="2"><?php if(isset($_GET['did'])&&($_GET['did']!="")){ ?><table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td width="9%" align="center"  style="border-top:#666 solid 1px; border-bottom:#666 solid 1px">ลำดับ</td>
            <td width="19%" align="center"  style="border-top:#666 solid 1px; border-bottom:#666 solid 1px">เลขที่ผลิต</td>
            <td width="31%" align="center"  style="border-top:#666 solid 1px; border-bottom:#666 solid 1px">วันหมดอายุ</td>
            <td width="19%" align="center"  style="border-top:#666 solid 1px; border-bottom:#666 solid 1px">คงเหลือ</td>
            <td width="22%" align="center"  style="border-top:#666 solid 1px; border-bottom:#666 solid 1px">จำนวนเบิก</td>
          </tr>
          <?php $i=0; do { $i++; 
		  	if($bg == "#FFFFFF") { //ส่วนของการ สลับสี 
			$bg = "#ededed";} else {$bg = "#FFFFFF";}
		if(isset($_GET['do'])&&($_GET['do']=="edit")){
			if($row_rs_selected['exp_date']==""){
			$exp_date=" is NULL";	
			}
			if($row_rs_selected['exp_date']!=""){
			$exp_date="='".$row_rs_selected['exp_date']."'";
			}
			if($row_rs_temp['lot']==""){
			$lot=" is NULL";	
			}
			if($row_rs_temp['lot']!=""){
			$lot="='".$row_rs_selected['lot']."'";
			}
		mysql_select_db($database_vmi, $vmi);
		$query_sel_item = "select * from temp_stock_withdraw_c where working_code='".$row_rs_selected['working_code']."' and lot".$lot." and exp_date".$exp_date." and hospcode='".$_SESSION['hospcode']."'";
		$sel_item = mysql_query($query_sel_item, $vmi) or die(mysql_error());
		$row_sel_item = mysql_fetch_assoc($sel_item);
		$totalRows_sel_item = mysql_num_rows($sel_item);

		}
		if($totalRows_rs_selected2<>0&&$row_rs_selected2['c_id']!=NULL){
		$remain=(($row_rs_selected['remain_qty']+$row_rs_selected2['qty_order'])/$row_rs_selected2['pack_ratio']);
			}
		else if($totalRows_rs_selected2<>0&&$row_rs_selected2['c_id']==NULL){
		$remain=(($row_rs_selected['remain_qty'])/$row_rs_selected2['pack_ratio']);
			}
		else 	if($totalRows_rs_selected2==0){
		$remain=(($row_rs_selected['remain_qty'])/$row_rs_selected['pack_ratio']);
		}
		  ?>
  <tr bgcolor="<?php echo $bg; ?>">
    <td align="center" ><?php echo $i; ?></td>
    <td align="center" ><?php echo $row_rs_selected['lot']; ?>
      <input name="lot[]" type="hidden" id="lot[]" value="<?php echo $row_rs_selected['lot']; ?>"></td>
    <td align="center" ><?php if($row_rs_selected['exp_date']==NULL){ echo "";}else{ print dateThai($row_rs_selected['exp_date']); } ?>
      <input name="exp_date[]" type="hidden" id="exp_date[]" value="<?php echo $row_rs_selected['exp_date']; ?>"></td>
    <td align="center" ><?php print ($row_rs_selected['remain_qty']/$row_rs_selected['pack_ratio'])." X ".$row_rs_selected['pack_ratio'];  ?></td>
    <td align="center" ><label for="qty_order[]"></label>
      <select name="qty_order[]" id="qty_order[]" onChange="sum_order2();" class="sellist">
        <?php $k==0; for($k=0;$k<=$remain;$k++){ ?>
        <option value="<?php echo $k; ?>" <?php if (!(strcmp($k,($row_sel_item['qty_order']/$row_rs_selected['pack_ratio']) ))) {echo "selected=\"selected\"";} ?>><?php echo $k; ?></option>
        <?php } ?>
      </select>
      <input name="id[]" type="hidden" id="id[]" value="<?php echo $row_rs_selected['id']; ?>">
      <input name="pack_ratio[]" type="hidden" id="pack_ratio[]" value="<?php echo $row_rs_selected['pack_ratio']; ?>" class="selpack">
      <input name="buy_unit_cost[]" type="hidden" id="buy_unit_cost[]" value="<?php echo $row_rs_selected['buy_unit_cost']; ?>"></td>
  </tr>
  <?php } while ($row_rs_selected = mysql_fetch_assoc($rs_selected)); ?>
              <tr>
            <td width="9%" align="center" style="border-top:solid 2px #666666"  >&nbsp;</td>
            <td width="19%" align="center" style="border-top:solid 2px #666666" >&nbsp;</td>
            <td width="31%" align="center" style="border-top:solid 2px #666666" ><input name="pack_ratio1" type="hidden" id="pack_ratio1" value="<?php echo $row_rs_pack['pack_ratio']; ?>">
              <input name="qty1" type="hidden" id="qty1" value="<?php echo $row_sum_qty['sum_qty']; ?>"></td>
            <td width="19%" align="center" style="border-top:solid 2px #666666" class="font20" >รวม</td>
            <td width="22%" align="center"  style="border-top:solid 2px #666666" class="thsan-bold font20"><label for="qtysum"></label>
              <input name="qtysum" type="text" id="qtysum" value="<?php if(isset($_GET['do'])&&($_GET['do']=="edit")){
echo ($row_sum_qty['sum_qty']/$row_sum_qty['pack_ratio']); } else { echo 0; } ?>" class="thsan-bold font20" style="width:30px; text-align:right; border: solid 0px" readonly> 
              X 
              <label for="pack_ratio3"></label>
              <select name="pack_ratio3" id="pack_ratio3" onChange="sum_order3();">
                <?php
do {  
?>
                <option value="<?php echo $row_rs_pack['pack_ratio']?>"<?php if (!(strcmp($row_rs_pack['pack_ratio'], $row_sum_qty['pack_ratio']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_pack['pack_ratio']?></option>
                <?php
} while ($row_rs_pack = mysql_fetch_assoc($rs_pack));
  $rows = mysql_num_rows($rs_pack);
  if($rows > 0) {
      mysql_data_seek($rs_pack, 0);
	  $row_rs_pack = mysql_fetch_assoc($rs_pack);
  }
?>
              </select></td>
          </tr>
  
        </table><?php } ?></td>
      </tr>
      <tr>
        <td height="46" colspan="2" align="center"><?php if($row_rs_selected2['action']==NULL){  ?><input style="height:45px ; width:80px; text-align:center; padding:10px; cursor:pointer" class="thsan-light font18" type="submit" name="save" id="save" value="<?php 	if(isset($_GET['do'])&&($_GET['do']=="edit")){
 echo "แก้ไข"; } else { echo "บันทึก"; } ?>"><?php } ?>
        <input style="height:45px ; width:80px; text-align:center; padding:10px; cursor:pointer" class="thsan-light font18" type="submit" name="save2" id="save2" value="<?php if($row_rs_selected2['action']=="D"){ echo "ยกเลิกลบ"; } else { echo "ลบ"; } ?>" onClick="return confirm('ต้องการ<?php if($row_rs_selected2['action']=="D"){ echo "ยกเลิกลบ"; } else { echo "ลบ"; } ?>ข้อมูลนี้จริงหรือไม่');"></td>
      </tr>
    </table>
  </form>
</div>

</body>
</html>
<?php
mysql_free_result($rs_item);

if(isset($_GET['did'])&&($_GET['did']!="")){
mysql_free_result($rs_selected);

mysql_free_result($rs_pack);


}
	if(isset($_GET['do'])&&($_GET['do']=="edit")){
		mysql_free_result($sel_item);

	mysql_free_result($sum_qty);
	}
?>
