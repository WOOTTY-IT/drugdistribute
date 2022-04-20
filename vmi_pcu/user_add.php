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
		mysql_select_db($database_vmi, $vmi);
	$query_update = "update user set username='$username',password='".$_POST['password']."',member_status='".$_POST['usertype']."',hospcode_type_id='".$_POST['hosptype']."',item_type='".$_POST['item_type']."' where id='".$_POST['id']."'";
	$rs_udpate = mysql_query($query_update, $vmi) or die(mysql_error());
	echo "<script>parent.$.fn.colorbox.close();</script>";
	exit();	

}

if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
	if($username==""&&$password==""){
	$msg="กรุณากรอกชื่อและรหัสผ่านให้ครบถ้วน";	
	}
	else {
	mysql_select_db($database_vmi, $vmi);
$query_rs_user = "select * from user where username='".$_POST['username']."'";
$rs_user = mysql_query($query_rs_user, $vmi) or die(mysql_error());
$row_rs_user = mysql_fetch_assoc($rs_user);
$totalRows_rs_user = mysql_num_rows($rs_user);
	if($totalRows_rs_user<>0){
		$msg="ชื่อที่คุณกำหนดพบว่ามีอยุ่ในระบบแล้ว กรุณาใช้ชื่อใหม่";
		}
	else if($totalRows_rs_user==0) {
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into user (hospcode,username,password,member_status,hospcode_type_id,item_type) value ('".$_POST['dept']."','".$_POST['username']."','".$_POST['password']."','".$_POST['usertype']."','".$_POST['hosptype']."','".$_POST['item_type']."')";
	$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	echo "<script>parent.$.fn.colorbox.close();</script>";
	exit();	

	}
	mysql_free_result($rs_user);
	}
}

mysql_select_db($database_vmi, $vmi);
$query_rs_group = "select * from dept_group where hospmain='".$_SESSION['hospcode']."'";
$rs_group = mysql_query($query_rs_group, $vmi) or die(mysql_error());
$row_rs_group = mysql_fetch_assoc($rs_group);
$totalRows_rs_group = mysql_num_rows($rs_group);


if($_GET['do']=="edit"){
mysql_select_db($database_vmi, $vmi);
$query_rs_edit = "SELECT * FROM user WHERE id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);


mysql_select_db($database_vmi, $vmi);
$query_rs_hospcode = "SELECT h.hospcode,concat(hosptype,h.name) as hospname,g.dept_group_id FROM hospcode h left outer join dept_list d on d.dept_code=h.hospcode left outer join dept_group g on g.dept_group_id=d.dept_group WHERE hospcode='".$row_rs_edit['hospcode']."'";
$rs_hospcode = mysql_query($query_rs_hospcode, $vmi) or die(mysql_error());
$row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_rs_hospcode = mysql_num_rows($rs_hospcode);

}
mysql_select_db($database_vmi, $vmi);
$query_rs_hosptype = "select * from hospcode_type";
$rs_hosptype = mysql_query($query_rs_hosptype, $vmi) or die(mysql_error());
$row_rs_hosptype = mysql_fetch_assoc($rs_hosptype);
$totalRows_rs_hosptype = mysql_num_rows($rs_hosptype);

mysql_select_db($database_vmi, $vmi);
$query_rs_item_type = "select * from item_type";
$rs_item_type = mysql_query($query_rs_item_type, $vmi) or die(mysql_error());
$row_rs_item_type = mysql_fetch_assoc($rs_item_type);
$totalRows_rs_item_type = mysql_num_rows($rs_item_type);

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
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
<script type="text/javascript">
/// เลือกกลุ่มหน่วยงาน


$(document).ready(function() {
 $('.user').hide(); 
 $('#delete').hide();



 $('#dept_group').change(function(){
	 if($('#dept_group').val()!=""){
 	$('#dept').change(function(){
	 if($('#dept').val()!=""){
		 $('.user').show();  
		 }
	 else { $('.user').hide(); 
}
	 });  

		 }
	 else { $('.user').hide(); 
}
 });
 
   var user_edit="<?php echo $_GET['do']; ?>";
	if(user_edit=="edit"){ $('.user').show(); $('#delete').show(); 
	
	}
  
});
</script>
</head>

<body>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table_head_small">
  <tr>
    <td class=" rounded_top gray"><img src="images/add-user.png" width="30" height="30" align="absmiddle" />&nbsp; <span class="table_head_small_bord">เพิ่มผู้ใช้งาน</span></td>
  </tr>
  <tr>
    <td  class=" border-bottm-gray border-left-gray border-right-gray rounded_bottom"><form id="form1" name="form1" method="post" action="">
      <?php if(isset($msg)){ echo "<br /><span class=\"small_red_bord\">$msg</span>"; } ?>
<table width="400" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td width="126">1. เลือกกลุ่มหน่วยงาน</td>
          <td width="262"><select name="dept_group" class="table_head_small dept_group" id="dept_group" style="width:261px">
            <?php if(!isset($_GET['do'])){ ?><option value="" <?php if (!(strcmp("", $row_rs_hospcode['dept_group_id']))) {echo "selected=\"selected\"";} ?>>เลือกกลุ่มหน่วยงาน</option><?php } ?>
            <?php
do {  
?>
            <option value="<?php echo $row_rs_group['dept_group_id']?>"<?php if (!(strcmp($row_rs_group['dept_group_id'], $row_rs_hospcode['dept_group_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_group['dept_group_name']?></option>
            <?php
} while ($row_rs_group = mysql_fetch_assoc($rs_group));
  $rows = mysql_num_rows($rs_group);
  if($rows > 0) {
      mysql_data_seek($rs_group, 0);
	  $row_rs_group = mysql_fetch_assoc($rs_group);
  }
?>
            </select>
            </td>
        </tr>
        <tr>
          <td>2. เลือกหน่วยงาน</td>
          <td><select name="dept" id="dept" class="table_head_small dept" style="width:261px">
         <?php if(!isset($_GET['do'])){ echo "<option value=\"\">เลือกหน่วยงาน</option>"; } ?>
            <?php if($_GET['do']=="edit"){
do {  
?>
            <option value="<?php echo $row_rs_hospcode['hospcode']?>"><?php echo $row_rs_hospcode['hospname']?></option>
            <?php
} while ($row_rs_hospcode = mysql_fetch_assoc($rs_hospcode));
  $rows = mysql_num_rows($rs_hospcode);
  if($rows > 0) {
      mysql_data_seek($rs_hospcode, 0);
	  $row_rs_hospcode = mysql_fetch_assoc($rs_hospcode);
  }
			}
?>
          </select></td>
        </tr>
        <tr class="user">
          <td valign="top">3. กำหนดรหัสผ่าน</td>
          <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small" style="border:1px #CCCCCC solid">
            <tr>
              <td colspan="2" class="rounded_top">&nbsp;</td>
              </tr>
            <tr>
              <td width="31%" align="right">username</td>
              <td width="69%"><input name="username" type="text" class="inputcss1" id="username" value="<?php echo $row_rs_edit['username']; ?>" /></td>
            </tr>
            <tr>
              <td align="right">password</td>
              <td><input name="password" type="text" class="inputcss1" id="password" value="<?php echo $row_rs_edit['password']; ?>" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr class="user">
          <td>4. ประเภทหน่วยงาน</td>
          <td><label for="hosptype"></label>
            <select name="hosptype" id="hosptype">
              <?php
do {  
?>
              <option value="<?php echo $row_rs_hosptype['id']?>"<?php if (!(strcmp($row_rs_hosptype['id'], $row_rs_edit['hospcode_type_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_hosptype['hospcode_type_name']?></option>
              <?php
} while ($row_rs_hosptype = mysql_fetch_assoc($rs_hosptype));
  $rows = mysql_num_rows($rs_hosptype);
  if($rows > 0) {
      mysql_data_seek($rs_hosptype, 0);
	  $row_rs_hosptype = mysql_fetch_assoc($rs_hosptype);
  }
?>
            </select></td>
        </tr>
        <tr class="user">
          <td>5. ประเภทสิทธิ์</td>
          <td><select name="usertype" class="table_head_small" id="usertype" title="<?php echo $row_rs_user['member_status']; ?>">
            <option value="user" selected="selected" <?php if (!(strcmp("user", $row_rs_edit['member_status']))) {echo "selected=\"selected\"";} ?>>user</option>
            <option value="super_user" <?php if (!(strcmp("super_user", $row_rs_edit['member_status']))) {echo "selected=\"selected\"";} ?>>super_user</option>
<option value="admin" <?php if (!(strcmp("admin", $row_rs_edit['member_status']))) {echo "selected=\"selected\"";} ?>>admin</option>
          </select>
            <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" /></td>
        </tr>
        <tr class="user">
          <td>ประเภทเวชภัณฑ์</td>
          <td><label for="item_type"></label>
            <select name="item_type" class="head_small_gray" id="item_type">
              <option value="" <?php if (!(strcmp("", $row_rs_edit['item_type']))) {echo "selected=\"selected\"";} ?>>ทั้งหมด</option>
              <?php
do {  
?>
<option value="<?php echo $row_rs_item_type['id']?>"<?php if (!(strcmp($row_rs_item_type['id'], $row_rs_edit['item_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_item_type['item_type_name']?></option>
              <?php
} while ($row_rs_item_type = mysql_fetch_assoc($rs_item_type));
  $rows = mysql_num_rows($rs_item_type);
  if($rows > 0) {
      mysql_data_seek($rs_item_type, 0);
	  $row_rs_item_type = mysql_fetch_assoc($rs_item_type);
  }
?>
            </select></td>
        </tr>
        <tr class="user">
          <td>&nbsp;</td>
          <td><input type="submit" name="save" id="save" value="<?php if($_GET['do']=="edit"){ echo "แก้ไข"; } else if(!isset($_GET['do'])){ echo "บันทึก"; } ?>" class="button_black_bar" style="width:100px" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_group);

if($_GET['do']=="edit"){
mysql_free_result($rs_hospcode);


mysql_free_result($rs_edit);
}
mysql_free_result($rs_hosptype);

mysql_free_result($rs_item_type);

?>
