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
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into drugs_logic (working_code,drug_type,percent_cutpoint,hospcode) value ('".$_POST['drugs']."','".$_POST['drug_type']."','".$_POST['percent']."','".$_SESSION['hospcode']."')";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();
parent.page_load('item_type','drug_type.php?item_type=".$_POST['item_type']."');
</script>";
exit();	

	}
if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
mysql_select_db($database_vmi, $vmi);
$query_update = "update drugs_logic set drug_type='".$_POST['drug_type']."',percent_cutpoint='".$_POST['percent']."' where id='".$_POST['id']."'";
$rs_update = mysql_query($query_update, $vmi) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();
parent.page_load('item_type','drug_type.php?item_type=".$_POST['item_type']."');
</script>";
exit();		
}
if(isset($_POST['delete'])&&($_POST['delete']=="ลบ")){
	mysql_select_db($database_vmi, $vmi);
$query_rs_search = "select d.working_code from drug_dept_group d left outer join dept_group g on g.dept_group_id=d.dept_group_id where d.working_code='".$_POST['drugs']."' and hospmain='".$_SESSION['hospcode']."'";
$rs_search = mysql_query($query_rs_search, $vmi) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);
if($totalRows_rs_search==0){
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from drugs_logic where id='".$_POST['id']."'";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

}else{
	echo "<script>alert('ไม่สามารถลบได้เนื่องจากรายการนี้ถูกกำหนดเป็นรายการของหน่วยงานแล้ว');</script>";	

	}
echo "<script>parent.$.fn.colorbox.close();
parent.page_load('item_type','drug_type.php?item_type=".$_POST['item_type']."');
</script>";

mysql_free_result($rs_search);

exit();		
}
if(isset($_GET['eid'])&&($_GET['eid']!="")){
	if(isset($_GET['working_code'])&&($_GET['working_code']!="")){
	$condition=" working_code='".$_GET['working_code']."'";	
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_edit = "SELECT * FROM drugs_logic where id='".$_GET['eid']."'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

}
else{
	$condition=" working_code not in (select d.working_code from drugs_logic d left outer join hospcode h on h.hospcode=d.hospcode where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."') ";
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_drug = "SELECT * FROM drugs where ".$condition." and chwpart='".$_SESSION['chwpart']."' and item_type='".$_GET['item_type']."' and amppart='".$_SESSION['amppart']."' ORDER BY drug_name ASC";
$rs_drug = mysql_query($query_rs_drug, $vmi) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);


mysql_select_db($database_vmi, $vmi);
$query_rs_type = "select * from drug_type";
$rs_type = mysql_query($query_rs_type, $vmi) or die(mysql_error());
$row_rs_type = mysql_fetch_assoc($rs_type);
$totalRows_rs_type = mysql_num_rows($rs_type);
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
function resutName(type,percent)
	{
		if(type!=""){
		switch(type)
		{
			<?
	mysql_select_db($database_vmi, $vmi);

	$strSQL = "SELECT * from drug_type";
	$objQuery = mysql_query($strSQL);
	while($objResult = mysql_fetch_array($objQuery))
	{?>
	case "<?=$objResult["id"];?>":
	document.getElementById(percent).value = "<?php print $objResult["percent_cutpoint"];?>";
		break;
			<?
			}
			?>
			default:
		document.getElementById(percent).value = "";
		}
		}
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr>
      <td width="116">เลือกประเภทรายการยา</td>
      <td width="372"><select name="drugs" class="table_head_small" id="drugs">
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
      <input name="id" type="hidden" id="id" value="<?php echo $row_rs_edit['id']; ?>" /></td>
    </tr>
    <tr>
      <td>ประเภทยา</td>
      <td><select name="drug_type" class="table_head_small" id="drug_type" onchange="resutName(this.value,'percent');">
        <option value="" <?php if (!(strcmp("", $row_rs_edit['drug_type']))) {echo "selected=\"selected\"";} ?>>เลือกประเภทยา</option>
        <?php
do {  
?>
<option value="<?php echo $row_rs_type['id']?>"<?php if (!(strcmp($row_rs_type['id'], $row_rs_edit['drug_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_type['drug_type']?></option>
        <?php
} while ($row_rs_type = mysql_fetch_assoc($rs_type));
  $rows = mysql_num_rows($rs_type);
  if($rows > 0) {
      mysql_data_seek($rs_type, 0);
	  $row_rs_type = mysql_fetch_assoc($rs_type);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>% การตัดเบิก</td>
      <td><input name="percent" type="text" class="table_head_small" id="percent" style="width:50px" value="<?php echo $row_rs_edit['percent_cutpoint']; ?>" onkeypress="is_number(event);" /> 
      % (สามารถปรับเปลี่ยนได้)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="save" id="save" value="<?php if(isset($_GET['eid'])&&($_GET['eid']!="")){
echo "แก้ไข"; } else { echo "บันทึก"; } ?>"  />
<?php if(isset($_GET['eid'])&&($_GET['eid']!="")){?>
<input type="submit" name="delete" id="delete" value="ลบ" onclick="return confirm('ต้องการลบจริงหรือไม่');"  /><?php } ?>
<input name="item_type" type="hidden" id="item_type" value="<?php echo $_GET['item_type']; ?>" /></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_type);

if(isset($_GET['eid'])&&($_GET['eid']!="")){
mysql_free_result($rs_edit);

}
?>
