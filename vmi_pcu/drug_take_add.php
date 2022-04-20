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
if(isset($_POST['save'])&&$_POST['save']=="แก้ไข"){
	$maxlevel=$_POST['among']*$_POST['pack_ratio'];
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update drug_take set among='".$maxlevel."' where id='".$_GET['id']."'";
	$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());
	
echo "<script>parent.$.fn.colorbox.close();parent.dosubmit('','show','drug_take_list2.php','drug_take_list','');</script>";
exit();	

}
if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
	$maxlevel=$_POST['among']*$_POST['pack_ratio'];
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into drug_take (drug_take_date,hospcode,working_code,among) value (NOW(),'".$_SESSION['hospcode']."','".$_POST['item']."','".$maxlevel."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	
echo "<script>parent.$.fn.colorbox.close();parent.dosubmit('','show','drug_take_list2.php','drug_take_list','');</script>";
exit();	

}
if(isset($_GET['do'])&&$_GET['do']=="edit"){
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "SELECT d.*,dd.drug_name,dd.pack_ratio,dd.buy_unit_cost,dd.sale_unit,format((g.max_level/dd.pack_ratio),0) as maxlevel,format((d.among/dd.pack_ratio),0) as among FROM drug_take d left outer join dept_list l on l.dept_code=d.hospcode left outer join drug_dept_group g on g.dept_group_id=l.dept_group and g.working_code=d.working_code left outer join drugs dd on dd.working_code=d.working_code and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."' WHERE d.hospcode='".$_SESSION['hospcode']."' and d.id='".$_GET['id']."' group by d.working_code";
$rs_item = mysql_query($query_rs_item, $vmi) or die(mysql_error());
$row_rs_item = mysql_fetch_assoc($rs_item);
$totalRows_rs_item = mysql_num_rows($rs_item);
}
else {
mysql_select_db($database_vmi, $vmi);
$query_rs_item = "SELECT d.*,dd.drug_name,dd.pack_ratio,dd.buy_unit_cost,dd.sale_unit FROM drug_dept_group d left outer join drugs dd on dd.working_code=d.working_code and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."' and dd.chwpart='".$_SESSION['chwpart']."' and dd.amppart='".$_SESSION['amppart']."'  left OUTER join dept_group g on g.dept_group_id=d.dept_group_id  LEFT OUTER JOIN dept_list l on l.dept_group=g.dept_group_id WHERE l.dept_code='".$_SESSION['hospcode']."' and d.working_code not in (SELECT working_code from drug_take where SUBSTR(drug_take_date,1,7)=SUBSTR(CURDATE(),1,7) and hospcode='".$_SESSION['hospcode']."') and dd.drug_name !='' and d.on_demand='Y' and dd.item_type='".$_GET['item_type']."' group by d.working_code order by dd.drug_name ASC";
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
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #F0F0F0;
	
}
html, body
{
  height: 100%;
}
</style>
<?php include('java_function.php'); ?>
<script src="include/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	var getdata="<?php echo $_GET['do']; ?>";
	if(getdata==""){
	$('.among1').hide();
	}
	else{$('.among1').show();
	$('#save').val("แก้ไข");}
    /// จำนวนเบิกสูงสุด depent on รายการยา
		$(".item").change(function()
		{
	if($('#item').val()!=""){
		$(".among1").show();
		var id=$(this).val();
		var dataString = 'id='+ id;
		$.ajax
		({
		type: "POST",
		url: "drug_take_select.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
		$(".among").html(html);
		} 
		});
	}
	else {
		$('.among1').hide();	
	}
		});
	
});
function test1(a){
	alert($('#'+a).val());
	}

</script>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="3" >
  <tr>
    <td class="orange" style="padding-left:10px"><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" />&nbsp; <?php if($_GET['do']=="edit"){ echo "แก้ไข"; } else{ echo "เพิ่ม"; } ?>รายการยา</td>
  </tr>
  <tr style="height:100%;">    
  <td style="height:100%;" ><form id="form1" name="form1" method="post" action="">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
      <tr>
        <td width="26%" height="36" align="right" valign="bottom" class="table_head_small_bord">เลือกรายการยา</td>
        <td width="74%" valign="bottom"><select name="item" id="item" class="item table_head_small" style="width:90%" onchange="resutName(this.value,'pack','pack_ratio','unit_price');">
          <?php if(!isset($_GET['do'])||($_GET['do']!="edit")){ ?><option value="">-- เลือกรายการยา --</option>
          <?php }
do {  
?>
          <option value="<?php echo $row_rs_item['working_code']?>"<?php if ($_GET['do']=="edit"&&!(strcmp($row_rs_item['working_code'], $row_rs_item['working_code']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item['drug_name']?></option>
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
      <tr class="among1">
        <td align="right" class="table_head_small_bord" >จำนวนเบิก</td>
        <td><select name="among" class="among table_head_small" id="among">
        <? if($_GET['do']=="edit"){
			for($i=1;$i<=$row_rs_item['maxlevel'];$i++){?>
            <option value="<?php echo $i; ?>"<?php if (!(strcmp($i, $row_rs_item['among']))) { echo "selected=\"selected\""; } ?>><?php echo $i; ?></option>
				<?php 
			}
		}?>
        </select></td>
      </tr>
      <tr class="among1">
        <td align="right" class="table_head_small_bord">ขนาดบรรจุ</td>
        <td><input name="pack" type="text" id="pack" style=" background-color:transparent; border:0px" value="<?php echo "x ".$row_rs_item['pack_ratio']." (".$row_rs_item['sale_unit'].")"; ?>" readonly="readonly" />
          <input name="pack_ratio" type="hidden" id="pack_ratio" value="<?php echo $row_rs_item['pack_ratio']; ?>" /></td>
      </tr>
      <tr class="among1">
        <td align="right" class="table_head_small_bord">ราคา/หน่วย</td>
        <td><input name="unit_price" type="text" id="unit_price" style=" background-color:transparent; border:0px;width:50px" value="<?php echo $row_rs_item['buy_unit_cost']; ?>" readonly="readonly" /> 
          บาท</td>
      </tr>
      <tr class="among1">
        <td align="right">&nbsp;</td>
        <td align="left"><input name="item_type" type="hidden" id="item_type" value="<?php echo $_GET['']; ?>" /></td>
      </tr>
      <tr class="among1">
        <td align="right">&nbsp;</td>
        <td align="left"><input type="submit" name="save" id="save" value="บันทึก" class="button_black_bar"  style="width:150px"/></td>
      </tr>
    </table>
  </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_item);
?>
