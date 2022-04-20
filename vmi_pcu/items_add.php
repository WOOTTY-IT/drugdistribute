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

if(isset($_POST['delete'])&&($_POST['delete']=="ลบข้อมูล")){
	mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select * from drugs_logic where working_code='".$_POST['working_code_edit']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);
	if($totalRows_rs_search==0){
		mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from drugs where working_code='".$_POST['working_code_edit']."' and hospcode='".$_SESSION['hospcode']."'";
	$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	echo "<script>parent.$.fn.colorbox.close();parent.page_load('itemdiv','items.php?item_type=".$_POST['items']."');</script>";

	}
	else{
	echo "<script>alert('ไม่สามารถลบได้เนื่องจากรายการนี้ถูกกำหนดในประเภทรายการแล้ว');</script>";	
		}
mysql_free_result($rs_search);

}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	
	if($_POST['action']=="add"){	
	mysql_select_db($database_vmi, $vmi);
$query_rs_working_code = "select * from drugs where working_code='".$_POST['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_working_code = mysql_query($query_rs_working_code, $vmi) or die(mysql_error());
$row_rs_working_code = mysql_fetch_assoc($rs_working_code);
$totalRows_rs_working_code = mysql_num_rows($rs_working_code);
	
if($totalRows_rs_working_code<>0){
	echo "<script>alert('working_code นี้มีแล้ว กรุณาแก้ไขใหม่อีกครั้ง');</script>";
	}
else{
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into drugs (working_code,drug_name,pack_ratio,sale_unit,buy_unit_cost,supply_type,item_type,istatus,hospcode,chwpart,amppart) values ('".$_POST['working_code']."','".$_POST['item_name']."','".$_POST['pack_ratio']."','".$_POST['sale_unit']."','".$_POST['buy_unit_cost']."','".$_POST['supply_type']."','".$_POST['items']."','".$_POST['istatus']."','".$_SESSION['hospcode']."','".$_SESSION['chwpart']."','".$_SESSION['amppart']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());	
	}
	
	echo "<script>parent.$.fn.colorbox.close();parent.page_load('itemdiv','items.php?item_type=".$_POST['items']."');</script>";

	}
	if($_POST['action']=="edit"){
	mysql_select_db($database_vmi, $vmi);
	$query_update = "update drugs set drug_name='".$_POST['item_name']."',pack_ratio='".$_POST['pack_ratio']."',sale_unit='".$_POST['sale_unit']."',buy_unit_cost='".$_POST['buy_unit_cost']."',supply_type='".$_POST['supply_type']."',item_type='".$_POST['items']."',istatus='".$_POST['istatus']."' where working_code='".$_POST['working_code_edit']."' and hospcode='".$_SESSION['hospcode']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
	$rs_udpate = mysql_query($query_update, $vmi) or die(mysql_error());

	echo "<script>parent.$.fn.colorbox.close();parent.page_load('itemdiv','items.php?item_type=".$_POST['items']."');</script>";
	
	}
	
	if($_POST['action']=="add"){	
	mysql_free_result($rs_working_code);
	}
	
	}

if($_GET['action']=="edit"){
mysql_select_db($database_vmi, $vmi);
$query_rs_items = "select * from drugs where working_code='".$_GET['working_code']."' and hospcode='".$_SESSION['hospcode']."'";
$rs_items = mysql_query($query_rs_items, $vmi) or die(mysql_error());
$row_rs_items = mysql_fetch_assoc($rs_items);
$totalRows_rs_items = mysql_num_rows($rs_items);
}
mysql_select_db($database_vmi, $vmi);
$query_rs_item_type = "select * from item_type order by id DESC";
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class=" thfont font16 font_bord">
<?php echo $row_rs_items['drug_name']; ?>
</div>
<form name="form1" method="post" action="">
  <table width="600" border="0" cellspacing="0" cellpadding="3" style="margin-top:10px" class="thfont font13">
    <tr>
      <td width="222">ชื่อเวชภัณฑ์</td>
      <td width="366"><label for="item_name"></label>
      <input name="item_name" type="text" id="item_name" value="<?php if(isset($_POST['item_name'])){ echo $_POST['item_name']; } else { echo $row_rs_items['drug_name']; } ?>"></td>
    </tr>
    <tr>
      <td>Working Code</td>
      <td><input name="working_code" type="text" id="working_code" value="<?php echo $row_rs_items['working_code']; ?>" <?php if($_GET['action']=="edit"){ echo "disabled"; } ?>></td>
    </tr>
    <tr>
      <td>ขนาดบรรจุ</td>
      <td><input name="pack_ratio" type="text" id="pack_ratio" value="<?php if(isset($_POST['pack_ratio'])){ echo $_POST['pack_ratio']; } else {echo $row_rs_items['pack_ratio'];} ?>"></td>
    </tr>
    <tr>
      <td>หน่วยบรรจุ</td>
      <td><input name="sale_unit" type="text" id="sale_unit" value="<?php if(isset($_POST['sale_unit'])){ echo $_POST['sale_unit']; } else { echo $row_rs_items['sale_unit'];} ?>"></td>
    </tr>
    <tr>
      <td>ราคา/หน่วยบรรจุ</td>
      <td><input name="buy_unit_cost" type="text" id="buy_unit_cost" value="<?php if(isset($_POST['buy_unit_cost'])){ echo $_POST['buy_unit_cost']; } else { echo $row_rs_items['buy_unit_cost']; } ?>"></td>
    </tr>
    <tr>
      <td>ED/NED</td>
      <td><label for="supply_type"></label>
        <select name="supply_type" id="supply_type">
          <option value="1" <?php if (!(strcmp(1, $row_rs_items['supply_type']))) {echo "selected=\"selected\"";} ?>>ED</option>
          <option value="2" <?php if (!(strcmp(2, $row_rs_items['supply_type']))) {echo "selected=\"selected\"";} ?>>NED</option>
      </select></td>
    </tr>
    <tr>
      <td>ประเภท</td>
      <td><span class="thfont font12">
        <select name="items" id="items" class="thfont font12 inputcss1" onchange="page_load('itemdiv','items.php?item_type='+this.value)">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_item_type['id']?>"<?php if (!(strcmp($row_rs_item_type['id'], $row_rs_items['item_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item_type['item_type_name']?></option>
          <?php
} while ($row_rs_item_type = mysql_fetch_assoc($rs_item_type));
  $rows = mysql_num_rows($rs_item_type);
  if($rows > 0) {
      mysql_data_seek($rs_item_type, 0);
	  $row_rs_item_type = mysql_fetch_assoc($rs_item_type);
  }
?>
        </select>
      </span></td>
    </tr>
    <tr>
      <td>สถานะ</td>
      <td><input <?php if (!(strcmp($row_rs_items['istatus'],"Y"))) {echo "checked=\"checked\"";} ?> name="istatus" type="checkbox" id="istatus" value="Y">
      <label for="istatus">ใช้งาน</label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="save" id="save" value="บันทึก">
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>">
      <input name="working_code_edit" type="hidden" id="working_code_edit" value="<?php echo $_GET['working_code']; ?>">
      <input type="submit" name="delete" id="delete" value="ลบข้อมูล" onClick="return confirm('ต้องการลบข้อมูลจริงหรือไม่?')"></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
if($_GET['action']=="edit"){

mysql_free_result($rs_items);

}

mysql_free_result($rs_item_type);

?>
