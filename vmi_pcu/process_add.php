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
        mysql_select_db($database_vmi, $vmi);
        $query_rs_old = "select * from vmi_order where stdcode='".$_GET['didstd']."' and order_month='".$_GET['order_month']."' and hospcode='".$_GET['hospcode']."'";
        $rs_old = mysql_query($query_rs_old, $vmi) or die(mysql_error());
        $row_rs_old = mysql_fetch_assoc($rs_old);
        $totalRows_rs_old = mysql_num_rows($rs_old);        
        
            if($totalRows_rs_old==0){
            mysql_select_db($database_vmi, $vmi);
            $query_update = "insert into vmi_order (hospcode,stdcode,qty,pack_ratio,order_month) values ('".$_GET['hospcode']."','".$_GET['didstd']."','".$_GET['brings']."','".$_GET['pack_ratio']."','".$_GET['order_month']."')";
            $update = mysql_query($query_update, $vmi) or die(mysql_error());
            }
            else{
            mysql_select_db($database_vmi, $vmi);
            $query_update = "update vmi_order set qty=".($row_rs_old['qty']+$_GET['brings'])." where id='".$row_rs_old['id']."' ";
            $update = mysql_query($query_update, $vmi) or die(mysql_error());
                
            }

        mysql_free_result($rs_old);
        if($update){
            mysql_select_db($database_vmi, $vmi);
            $query_rs_remain = "delete from drug_remain where stdcode='".$_GET['didstd']."' and vmi_date='".$_GET['order_month']."' and hospcode='".$_GET['hospcode']."'";
            $rs_remain = mysql_query($query_rs_remain, $vmi) or die(mysql_error());

            if($_GET['remain']!=0){
            mysql_select_db($database_vmi, $vmi);
            $query_update = "insert into drug_remain (hospcode,stdcode,qty,date_remain,vmi_date) values ('".$_GET['hospcode']."','".$_GET['didstd']."','".$_GET['remain']."',NOW(),'".$_GET['order_month']."')";
            $update = mysql_query($query_update, $vmi) or die(mysql_error());
            }
            
            mysql_select_db($database_vmi, $vmi);
            $query_update = "update month_rate set data_flag='Y' where hospcode='".$_GET['hospcode']."' and didstd='".$_GET['didstd']."' and month_rate='".substr($_GET['order_month'],5,2)."' and year_rate='".substr($_GET['order_month'],0,4)."'";
            $update = mysql_query($query_update, $vmi) or die(mysql_error());
            
            echo "<script>parent.$.fn.colorbox.close();parent.formSubmit('search','form1','analyzing.php','displayDiv','indicator')</script>";     
        }
        exit();

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	
	if($_POST['order']!=0){ 
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update vmi_order set qty='".$_POST['order']."',remark='".$_POST['remark']."' where stdcode='".$_POST['didstd']."' and order_month='".$_POST['month_rate']."' and hospcode='".$_POST['hospcode']."' ";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
	}
	else {
		mysql_select_db($database_vmi, $vmi);
		$query_delete = "delete from vmi_order where stdcode='".$_POST['didstd']."' and order_month='".$_POST['month_rate']."' and hospcode='".$_POST['hospcode']."'  ";
		$delete = mysql_query($query_delete, $vmi) or die(mysql_error());	

		$query_update = "update month_rate set data_flag = null where didstd='".$_POST['didstd']."' and concat(year_rate,'-',month_rate)='".$_POST['month_rate']."' and hospcode='".$_POST['hospcode']."' ";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
	}
	
		if($_POST['remain']!=0||$_POST['remain']==0){
	
mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select * from drug_remain where stdcode='".$_POST['didstd']."' and vmi_date='".$_POST['month_rate']."' and hospcode='".$_POST['hospcode']."' order by vmi_date DESC limit 1";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$totalRows_rs_month = mysql_num_rows($rs_month);

		if(($totalRows_rs_month<>0)&&($_POST['remain']!=0)){
		$query_update = "update drug_remain set data_flag = null,qty='".$_POST['remain']."' where id='".$row_rs_month['id']."' ";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
		}
		if(($totalRows_rs_month==0)&&($_POST['remain']!=0)){
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert drug_remain (hospcode,stdcode,qty,date_remain,vmi_date) value ('".$_POST['hospcode']."','".$_POST['didstd']."','".$_POST['remain']."',NOW(),'".$_POST['month_rate']."')";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());			
		}
		if($totalRows_rs_month<>0&&$_POST['remain']==0){
			$query_delete = "delete from drug_remain where id='".$row_rs_month['id']."' ";
			$delete = mysql_query($query_delete, $vmi) or die(mysql_error());
		}
		mysql_free_result($rs_month);
	}
		
			
		

echo "<script>parent.formSubmit('search','form1','analyzing.php','displayDiv','indicator');parent.$.fn.colorbox.close();</script>";
exit();	

}
	
mysql_select_db($database_vmi, $vmi);
$query_rs_items = "select * from drugs where working_code='".$_GET['working_code']."'";
$rs_items = mysql_query($query_rs_items, $vmi) or die(mysql_error());
$row_rs_items = mysql_fetch_assoc($rs_items);
$totalRows_rs_items = mysql_num_rows($rs_items);

mysql_select_db($database_vmi, $vmi);
$query_rs_order = "SELECT v.*,r.qty as remain FROM vmi_order v left outer join drug_remain r on r.stdcode=v.stdcode and v.order_month=r.vmi_date and v.hospcode=r.hospcode WHERE v.id='".$_GET['order_id']."'";
$rs_order = mysql_query($query_rs_order, $vmi) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

	mysql_select_db($database_vmi, $vmi);
	$query_rs_dept = "select d.*,s.stdcode from drug_dept_group d left outer join dept_list l on l.dept_group=d.dept_group_id left outer join drug_stdcode s on s.working_code=d.working_code and s.hospcode='".$_SESSION['hospcode']."' where l.dept_code='".$row_rs_order['hospcode']."' and d.working_code='".$_GET['working_code']."'";
	$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
	$row_rs_dept = mysql_fetch_assoc($rs_dept);
	$totalRows_rs_dept = mysql_num_rows($rs_dept);	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>
<script src="include/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() { 
$('#clear_remain').click(function(){
	$('#remain').val(0);
	$('#remark').val('มีการลบจำนวนคงเหลือ');
	});   
$( "#order" ).keyup(function(){
	var order
	if($('#order').val()==0){order=0;}
	else{order=$('#order').val()*<?php echo $row_rs_items['pack_ratio']; ?>;}
	var cal=($('#use').val()-order);

if(cal>0){$('#remain').val(cal);}
else{$('#remain').val("0");}
	});
});

function maxcheck(){
	if($('#order').val()><?php echo $row_rs_dept['max_level']/$row_rs_order['pack_ratio']; ?>)
		{
		$('#order').val("<?php echo $row_rs_dept['max_level']/$row_rs_order['pack_ratio']; ?>");
		}
	}
</script>
<body class="big_black16">
<p><?php echo $row_rs_items['drug_name']; ?>
</p>
<form id="form1" name="form1" method="post" action="">
  <p class="table_head_small">
    จำนวนใช้จริง 
    <input name="use" type="text" class="table_head_small_bord" id="use" style="width:50px; border:0px" value="<?php echo $use; ?>" />
    <span class="table_head_small_bord"><?php echo $row_rs_items['sale_unit']; ?></span>    
    <input name="didstd" type="hidden" id="didstd" value="<?php echo $row_rs_order['stdcode']; ?>" />
    <input name="month_rate" type="hidden" id="month_rate" value="<?php echo $row_rs_order['order_month']; ?>" />
    <input name="hospcode" type="hidden" id="hospcode" value="<?php echo $_GET['hospcode']; ?>" />
    <input type="hidden" name="remark" id="remark" />
    <br />
    จำนวนเบิก 
    <input name="order" type="text" class="table_head_small" id="order" style="width:50px; background-color:#F00; color:#FFF; border:1px solid #000" value="<?php echo $row_rs_order['qty']; ?>" onkeyup="maxcheck();" />
    <span class="small_red_bord">    x <?php echo $row_rs_items['pack_ratio']; ?></span>&nbsp; จำนวนเหลือ 
    <input name="remain" type="text" class="table_head_small" id="remain" style="width:50px; background-color:#FF0; border: 1px solid #000" value="<?php if(empty($row_rs_order['remain'])){ echo 0; } else { echo $row_rs_order['remain']; } ?>" readonly="readonly" />
  <?php echo $row_rs_items['sale_unit']; ?><?php if(!empty($row_rs_order['remain'])){ ?>&nbsp;<img src="images/delete.png" width="19" height="18" align="absmiddle" id="clear_remain" style="cursor:pointer;" /><?php } ?><br />
  <br />
  <input type="submit" name="save" id="save" value="บันทึก" class="button blue" />
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_items);

mysql_free_result($rs_order);

mysql_free_result($rs_dept);
?>
