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
if($_POST['do']=="search"){
if($process_type=="1"){
	$condition=" and d.data_flag is null";
	}
else {$condition=" and d.data_flag ='Y'";
}



$my=$_POST['year']."-".$_POST['month'];
mysql_select_db($database_vmi, $vmi);
$query_sel_drug = "select i.drug_name,i.working_code,d.hospcode,d.didstd,d.qty as sumqty,i.pack_ratio,l.percent_cutpoint,concat(d.year_rate,'-',d.month_rate) as ym_rate from month_rate d left outer join drug_stdcode s on s.stdcode=d.didstd and s.hospcode='".$_SESSION['hospcode']."' left outer join drugs i on i.working_code=s.working_code and i.hospcode='".$_SESSION['hospcode']."' left outer join drugs_logic l on l.working_code=i.working_code and l.hospcode='".$_SESSION['hospcode']."' where concat(d.year_rate,'-',d.month_rate)= '$my' and drug_name !='' ".$condition." and d.hospcode='".$_POST['dept']."' and i.working_code in (select d.working_code from drug_dept_group d left outer join dept_list l on l.dept_group=d.dept_group_id  where l.dept_code='".$_POST['dept']."' and (d.on_demand !='Y' or d.on_demand is NULL)) order by drug_name ASC";
$sel_drug = mysql_query($query_sel_drug, $vmi) or die(mysql_error());
$row_sel_drug = mysql_fetch_assoc($sel_drug);
$totalRows_sel_drug = mysql_num_rows($sel_drug);
//ค้นหา
/*
mysql_select_db($database_vmi, $vmi);
$query_sel_drug1 = "select id from vmi_order where order_month='".$my."' and hospcode='".$_POST['dept']."' limit 1";
$sel_drug1 = mysql_query($query_sel_drug1, $vmi) or die(mysql_error());
$row_sel_drug1 = mysql_fetch_assoc($sel_drug1);
$totalRows_sel_drug1 = mysql_num_rows($sel_drug1);
*/
//ค้นหา
mysql_select_db($database_vmi, $vmi);
$query_sel_drug2 = "select id from vmi_order where order_month>'".$my."' and hospcode='".$_POST['dept']."' limit 1";
$sel_drug2 = mysql_query($query_sel_drug2, $vmi) or die(mysql_error());
$row_sel_drug2 = mysql_fetch_assoc($sel_drug2);
$totalRows_sel_drug2 = mysql_num_rows($sel_drug2);

mysql_select_db($database_vmi, $vmi);
$query_sel_drug3 = "select count(*) as count_analyze from vmi_order where order_month='".$my."' and hospcode='".$_POST['dept']."' limit 1";
$sel_drug3 = mysql_query($query_sel_drug3, $vmi) or die(mysql_error());
$row_sel_drug3 = mysql_fetch_assoc($sel_drug3);
$totalRows_sel_drug3 = mysql_num_rows($sel_drug3);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_sel_drug > 0) { 

// Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="1" class=" thsan-light font19 table" >
    <tr class="gray font18 thsan-semibold">
      <td width="3%" align="center">ลำดับ</td>
      <td width="24%" align="center">ชื่อยา</td>
      <td width="10%" align="center">1.จำนวนที่ใช้จริง</td>
      <td width="10%" align="center">2.เหลือครั้งที่แล้ว</td>
      <td width="9%" align="center">1+2</td>
      <td width="9%" align="center">ขนาดบรรจุ</td>
      <td width="8%" align="center">จำนวนเบิก</td>
      <td width="8%" align="left">1.เหลือ</td>
      <?php if(($process_type=="2")&&($totalRows_sel_drug2==0)){ ?><td width="9%" align="center">คงคลัง</td>
      <td width="10%" align="center">max</td><?php } ?>
    </tr>
    <?php $i=0;$sum_order_bring=0;
 do { $i++; $brings=0;
 	// หา max stock
	mysql_select_db($database_vmi, $vmi);
	$query_rs_dept = "select d.*,s.stdcode from drug_dept_group d left outer join dept_list l on l.dept_group=d.dept_group_id left outer join drug_stdcode s on s.working_code=d.working_code and s.hospcode='".$_SESSION['hospcode']."' where l.dept_code='".$dept."' and d.working_code='".$row_sel_drug['working_code']."'";
	$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
	$row_rs_dept = mysql_fetch_assoc($rs_dept);
	$totalRows_rs_dept = mysql_num_rows($rs_dept);	
	
	mysql_select_db($database_vmi, $vmi);
	$query_sel_remain = "select d.* from drug_remain d where hospcode='".$dept."' and stdcode='".$row_sel_drug['didstd']."' and vmi_date < '".$my."' ".$condition." order by vmi_date DESC";
	$sel_remain = mysql_query($query_sel_remain, $vmi) or die(mysql_error());
	$row_sel_remain = mysql_fetch_assoc($sel_remain);
	$totalRows_sel_remain = mysql_num_rows($sel_remain);

	mysql_select_db($database_vmi, $vmi);
	$query_sel_remain2 = "select d.* from drug_remain d where hospcode='$dept' and stdcode='".$row_sel_drug['didstd']."' and vmi_date = '".$my."' ";
	$sel_remain2 = mysql_query($query_sel_remain2, $vmi) or die(mysql_error());
	$row_sel_remain2 = mysql_fetch_assoc($sel_remain2);
	$totalRows_sel_remain2 = mysql_num_rows($sel_remain2);
	
if($totalRows_sel_drug<>0){
if($_POST['process_type']==2){
	mysql_select_db($database_vmi, $vmi);
	$query_rs_order = "select * from vmi_order where hospcode='".$_POST['dept']."' and stdcode ='".$row_sel_drug['didstd']."' and order_month='".$row_sel_drug['ym_rate']."'";
	$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
	$row_rs_order = mysql_fetch_assoc($rs_order);
	$totalRows_rs_order = mysql_num_rows($rs_order);

 if(($process_type=="2")&&($totalRows_sel_drug2==0)){
	//คงคลัง
  	mysql_select_db($database_vmi, $vmi);
	$query_rs_order3 = "select remain_qty,pack_ratio from stock_drugs  where working_code='".$row_sel_drug['working_code']."' and hospcode='".$row_sel_drug['hospcode']."'";
	$rs_order3 = mysql_query($query_rs_order3, $vmi) or die(mysql_error());
	$row_rs_order3 = mysql_fetch_assoc($rs_order3);
	$totalRows_rs_order3 = mysql_num_rows($rs_order3);

	//max
  	mysql_select_db($database_vmi, $vmi);
	$query_rs_order4 = "select g.max_level from drug_dept_group g left outer join dept_list l on l.dept_group =g.dept_group_id  where g.working_code='".$row_sel_drug['working_code']."' and l.dept_code='".$row_sel_drug['hospcode']."'";
	$rs_order4 = mysql_query($query_rs_order4, $vmi) or die(mysql_error());
	$row_rs_order4 = mysql_fetch_assoc($rs_order4);
	$totalRows_rs_order4 = mysql_num_rows($rs_order4);
 }
	}
/// หาจำนวนผลรวมจำนวนเบิก  เพื่อเก็บในตัวแปร $sum_order_bring
if($totalRows_rs_order<>0){ $sum_order_bring+=$row_rs_order['qty'];} else { if($order3!=0){ if($order3<=$percent_cutpoint){ $sum_order_bring+=0+intval($order); } if($order3>$percent_cutpoint) { $sum_order_bring+= $order4;}} else { $sum_order_bring+= $order4; } } 


if($row_sel_drug['sumqty']!=""){
  $order=number_format((($row_sel_drug['sumqty']+$row_sel_remain['qty'])/$row_sel_drug['pack_ratio']),2);


  $percent_cutpoint=number_format($row_sel_drug['percent_cutpoint']/100,2);

  //ปริมาณปกติ จำนวนรวม + จำนวนเหลือ /ขนาดบรรจุ
  $order1=number_format(($row_sel_drug['sumqty']+$row_sel_remain['qty'])/$row_sel_drug['pack_ratio']);
}
  $order2=explode('.',$order); //แยกหาตัวเลขก่อนและหลังจุดทศนิยม
  $order3=$order-intval($order);//จุดทศนิยม  intval($order)=ตัวเต็มด้านหน้า  ลบเพื่อหาจุดทศนิยมที่เหลือจริงๆ 0.xx
  $order4=ceil($order); //ปัดเป็นจำนวนเต็ม
  $remain2= $row_sel_drug['pack_ratio']*$order3;  //หาจำนวนเม็ดที่เหลือจากจุดทศนิยมด้านหลัง
  ?>
    <tr class=" grid4 font16">
      
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><?php echo "$row_sel_drug[drug_name]"; ?>&nbsp;</td>
      <td align="center"><?php echo "$row_sel_drug[sumqty]"; ?></td>
      <td align="center"><?php echo "$row_sel_remain[qty]"; ?></td>
      <td align="center"><label for="sum_use[]"></label>
      <input type="text" name="sum_use[]" id="sum_use[]" value="<?php echo "$row_sel_drug[sumqty]"+"$row_sel_remain[qty]"; ?>" style="width:95%; background-color:transparent; border:0px; text-align:center" /></td>
      <td align="center"><input name="pack_ratio[]" type="text" id="pack_ratio[]" style="width:95%; background-color:transparent; border:0px; text-align:center" value="<?php echo $row_sel_drug['pack_ratio']; ?>" /></td>
      <td align="center"><input name="order_bring[]" type="text" class="border" id="order_bring[]" onkeyup="bring();" <?php if(($process_type=="2")&&($totalRows_sel_drug2==0)){ echo "readonly=\"readonly\""; } ?> onkeypress="return isNumber(event);"  style="background-color:#F00; color:#FFFFFF" value="<?php if($totalRows_rs_order<>0){ $brings= $row_rs_order['qty']; echo $brings;} else {if($order3!=0){if($order3<$percent_cutpoint){  if(0+intval($order)>($row_rs_dept['max_level']/$row_sel_drug['pack_ratio'])){$brings= $row_rs_dept['max_level']/$row_sel_drug['pack_ratio']; echo $brings;} else { $brings= 0+intval($order); echo $brings; } } if($order3>=$percent_cutpoint) { if($order4>($row_rs_dept['max_level']/$row_sel_drug['pack_ratio'])){ $brings= $row_rs_dept['max_level']/$row_sel_drug['pack_ratio']; echo $brings;} else { $brings=$order4; echo $brings;} }} else { if($order4>($row_rs_dept['max_level']/$row_sel_drug['pack_ratio'])){ $brings= $row_rs_dept['max_level']/$row_sel_drug['pack_ratio']; echo $brings;} else { $brings= $order4; echo $brings;} } } ?>" size="3" <?php if($_POST['disable_button']=="Y"){ echo "readonly=\"readonly\""; }; ?> />
        <input name="stdcode[]" type="hidden" id="stdcode[]" value="<?php echo $row_sel_drug['didstd']; ?>" /></td>
      <td align="left"><input name="remain[]" type="text" class="border" id="remain[]" style="background-color: #FF0;" value="<?php if($_POST['process_type']==1){ if($row_sel_drug['sumqty']<=$row_rs_dept['max_level']){if($order3!=0){ if($order3<$percent_cutpoint){ echo ($remain3=$row_sel_drug['sumqty']+$row_sel_remain['qty'])-($brings*$row_sel_drug['pack_ratio']); } if($order3>=$percent_cutpoint){ echo $remain3= 0; }   }if($order3==0) { echo $remain3=0;}} else{ echo $remain3= ($row_sel_drug['sumqty']+$row_sel_remain['qty'])-($brings*$row_sel_drug['pack_ratio']); } } else{ if($row_sel_remain2['qty']!=0){ echo $remain3= $row_sel_remain2['qty']; } else { echo $remain3= 0;} } ?>" size="3" readonly="readonly"  />
        <?php if(($process_type=="2")&&($totalRows_sel_drug2==0)){ ?>
        <a href="javascript:alertload1('process_edit.php?working_code=<?php echo $row_sel_drug['working_code']; ?>&hospcode=<?php echo $dept; ?>&use=<?php echo $row_sel_drug['sumqty']+$row_sel_remain['qty']; ?>&order_id=<?php echo $row_rs_order['id']; ?>','500','400');"><img src="images/edit-icon.png" width="30" height="30" border="0" align="absmiddle" /></a> <?php } else {?>
        <img src="images/delete.png" width="21" height="20" onclick="if(confirm('ต้องการลบจริงๆหรือไม่?')==true){alertload1('clear_remain.php?didstd=<?php echo $row_sel_drug['didstd']; ?>&hospcode=<?php echo $dept; ?>','0','0');}" style="cursor:pointer" align="absmiddle" /><?php } if($row_rs_order['remark']=="มีการลบจำนวนคงเหลือ"){ echo "<span style=\"color:red;font-size:30px;\">*</span>";} ?>&ensp;
        <?php if($brings!=0&&($_POST['process_type']==1)){ ?>
        <a onclick="javascript:if(confirm('ต้องการเบิกรายการนี้จริงหรือไม่')==true){alertload1('process_add.php?didstd=<?php echo $row_sel_drug['didstd']; ?>&amp;working_code=<?php echo $row_sel_drug['working_code']; ?>&amp;hospcode=<?php echo $dept; ?>&amp;use=<?php echo $row_sel_drug['sumqty']+$row_sel_remain['qty']; ?>&amp;brings=<?php echo $brings; ?>&amp;pack_ratio=<?php echo $row_sel_drug['pack_ratio']; ?>&amp;order_month=<?php echo $my; ?>&amp;remain=<?php echo $remain3; ?>','400','400');}" style="cursor: pointer"><img src="images/dc7epb8gi.gif" width="24" height="22" alt=""/></a>
        <?php } ?></td>
      <?php if(($process_type=="2")&&($totalRows_sel_drug2==0)){ ?><td align="center"><?php if($row_rs_order3['remain_qty']!=""){ print $row_rs_order3['remain_qty']/$row_sel_drug['pack_ratio']." X ".number_format($row_sel_drug['pack_ratio']); } ?></td>
      <td align="center"><?php if($row_rs_order4['max_level']!=""){ print $row_rs_order4['max_level']/$row_sel_drug['pack_ratio']." X ".number_format($row_sel_drug['pack_ratio']); } ?></td><?php } ?>
    </tr>
    <?php } 
	mysql_free_result($sel_remain);
	mysql_free_result($sel_remain2);
	mysql_free_result($rs_dept);
	
	if(($process_type=="2")&&($totalRows_sel_drug2==0)){
	mysql_free_result($rs_order3);
	mysql_free_result($rs_order4);
	}
	} while ($row_sel_drug = mysql_fetch_assoc($sel_drug)); ?>
 </table>
  <?php } // Show if recordset not empty ?>
  <hr class="style-two"/>
<div align="center">
<?php 
//ถ้าไม่มียอดเบิกเลยซักช่อง ให้ซ่อนปุ่มพวกนี้
if($sum_order_bring!=0){
 if($_POST['disable_button']!="Y"){ if($totalRows_sel_drug<>0&&$process_type=="1"&&$row_sel_drug3['count_analyze']==0){ ?>
  <a href="javascript:valid(0);" onclick="formSubmit('process','form1','process_vmi.php','displayDiv','indicator');" class="btn btn-success thsan-light font19"><img src="images/dc7epb8gi.gif" width="25" height="24" align="absmiddle" /> ดำเนินการ</a>
  <?php } else if ($totalRows_sel_drug<>0&&$process_type=="2"){ ?>
  <a href="javascript:valid(0);" onclick="formSubmit('delete','form1','process_vmi.php','displayDiv','indicator');" class="btn btn-danger thsan-light font19" ><img src="images/black-white-metro-delete-icon.png" width="20" height="20" align="absmiddle" /> ลบใบเบิก</a>
  <?php 
  	} 
  } 
}
 if($process_type=="2"){
  ?>
 <a href="javascript:valid(0);" onclick="window.open('order_print_admin.php?order_date=<?php echo $my; ?>&dept=<?php echo $dept; ?>','_blank');" class=" btn btn-default thsan-light font19"><img src="images/Printer and Fax.png" width="24" height="24" align="absmiddle" /> พิมพ์ใบเบิก</a><?php } ?>
</p>
</body>
</html>
<?php
mysql_free_result($sel_drug);
mysql_free_result($sel_drug2);
mysql_free_result($sel_drug3);

if($totalRows_sel_drug<>0&&$_POST['process_type']==2){
mysql_free_result($rs_order);

}
?>
