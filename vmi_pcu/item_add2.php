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

if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
	$maxlevel=$_POST['max']*$_POST['pack_ratio'];
	if($maxlevel!=0){
		if($_POST['od']=="Y"){
		$on_demand="'Y'";	
		}
		else if($_POST['od']!="Y"){
		$on_demand="NULL";	
		}

	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into drug_dept_group (dept_group_id,working_code,max_level,on_demand) value ('".$_POST['data']."','".$_POST['item']."','$maxlevel',".$on_demand.")";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
echo "<script>parent.$.fn.colorbox.close();
parent.page_load('item_list','item_add.php?action_id=".$_POST['data']."&item_type=".$_POST['item_type']."');
</script>";
exit();	


}
if(isset($_POST['save'])&&$_POST['save']=="แก้ไข"){
		if($_POST['od']=="Y"){
		$on_demand="'Y'";	
		}
		else if($_POST['od']!="Y"){
		$on_demand="NULL";	
		}
	$maxlevel=$_POST['max']*$_POST['pack_ratio'];
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update drug_dept_group set  max_level='$maxlevel' , on_demand=".$on_demand." where id='".$_POST['edit_id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();
parent.page_load('item_list','item_add.php?action_id=".$_POST['data']."&item_type=".$_POST['item_type']."');
</script>";
exit();	
}

mysql_select_db($database_vmi, $vmi);
$query_rs_edit = "SELECT g.working_code,g.on_demand,d.pack_ratio,d.sale_unit,(g.max_level/d.pack_ratio) as max_pack  FROM drug_dept_group g left outer join drugs d on d.working_code=g.working_code WHERE g.id='".$_GET['edit_id']."'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
if($totalRows_rs_edit<>0)
{
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select working_code,drug_name from drugs where working_code ='".$row_rs_edit['working_code']."'";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);	
	}
else{
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "select s.working_code,s.drug_name from drugs_logic l left outer join drugs s on s.working_code=l.working_code and s.hospcode=l.hospcode where l.hospcode='".$_SESSION['hospcode']."'  and s.working_code not in (select working_code from drug_dept_group d left outer join dept_group g on g.dept_group_id=d.dept_group_id where d.dept_group_id='".$_GET['dept_group_id']."' and g.hospmain='".$_SESSION['hospcode']."') and s.drug_name !='' and s.item_type=".$_GET['item_type']." order by drug_name ASC";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_function.php'); ?>
<script src="include/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	var getdata="<?php echo $_GET['edit_id']; ?>";
	if(getdata!=""){
		$('#save').val("แก้ไข");
		$('#save').show();
		}
	else {
	$('#save').hide();}
    $('#item').change(function(){
		if($('#item').val()!=""){
			$('#save').show();
		}
		else {$('#save').hide();
		$('#max').val(""); $('#pack').val("");$('#pack_ratio').val("");}
		});
});

function resutName(drug,detail,pack,price)
	{
		if(drug!=""){
		switch(drug)
		{
			<?
			mysql_select_db($database_vmi, $vmi);

			$strSQL = "SELECT * from drugs";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["working_code"];?>":
		document.getElementById(detail).value = "<?php print "x ".$objResult["pack_ratio"]." (".$objResult["sale_unit"].")";?>";
		
		document.getElementById(pack).value='<?php print $objResult["pack_ratio"];?>';
		document.getElementById(price).value="<?php print $objResult["buy_unit_cost"];?>";
		break;
			<?
			}
			?>
		}
		}
	}

</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="item_add2.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td class="table_head_small"><select name="item" class="table_head_small" id="item" style=" width:300px; border-top:0px; border-left:0px; border-right:0px; border-bottom: dashed 1px #000000" onchange="resutName(this.value,'pack','pack_ratio');" <?php if($edit_id!=""){ echo "disabled=\"disabled\""; } ?>>
          <option value="" <?php if (!(strcmp("", $row_rs_edit['working_code']))) {echo "selected=\"selected\"";} ?>>เลือกรายการยา</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rs_item['working_code']?>"<?php if (!(strcmp($row_rs_item['working_code'], $row_rs_edit['working_code']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item['drug_name']?></option>
          <?php
} while ($row_rs_item = mysql_fetch_assoc($rs_item));
  $rows = mysql_num_rows($rs_item);
  if($rows > 0) {
      mysql_data_seek($rs_item, 0);
	  $row_rs_item = mysql_fetch_assoc($rs_item);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td class="table_head_small">จำนวนเบิกได้สูงสุด
          <select name="max" class="table_head_small" id="max" style="border-top:0px; border-left:0px; border-right:0px; border-bottom: dashed 1px #000000">
            <?php for($i=0;$i<=500;$i++){
        ?><option value="<?php echo $i; ?>"<?php if (!(strcmp($i, number_format($row_rs_edit['max_pack'])))) {echo "selected=\"selected\"";} ?>><?php echo $i; ?></option> <? } ?>
          </select>
          &nbsp;<input name="pack" type="text" class="small_red" id="pack"  style="width:100px; border:0px;" value="<?php if($edit_id!=""){ echo "X".$row_rs_edit['pack_ratio']."(".$row_rs_edit['sale_unit'].")"; } ?>" readonly="readonly"/>
          <br />
          <input <?php if (!(strcmp($row_rs_edit['on_demand'],"Y"))) {echo "checked=\"checked\"";} ?> name="od" type="checkbox" id="od" value="Y" />
          <label for="od"></label>
<label for="pack_ratio"></label>
          on demand
          <input name="pack_ratio" type="hidden" id="pack_ratio" value="<?php echo $row_rs_edit['pack_ratio']; ?>" />
          <input name="edit_id" type="hidden" id="edit_id" value="<?php echo $_GET['edit_id']; ?>" />
          <input name="data" type="hidden" id="data" value="<?php echo $_GET['dept_group_id']; ?>" />
          <input name="item_type" type="hidden" id="item_type" value="<?php echo $_GET['item_type']; ?>" />
        <br /><input name="save" type="submit" class="bar_gray" id="save" value="บันทึก"  /></td>
      </tr>
    </table>
</form>
</body>
</html>
<?php
mysql_free_result($rs_item);

mysql_free_result($rs_edit);
?>
