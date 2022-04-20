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
if(isset($_POST['button5'])&&($_POST['button5']=="แก้ไข")){
	$detail= str_replace("\n", "<br>\n", "$detail");

mysql_select_db($database_vmi, $vmi);
$query_update = "update message_board set  sender='$sender',detail='$detail',head='$header' where id='$id'";
$update = mysql_query($query_update, $vmi) or die(mysql_error());

	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete from message_reciever_hospcode where board_id='$id' and hospcode not in (''";
	
for($i=0;$i<count($hospcode_check);$i++){
$query_delete.=",'$hospcode_check[$i]'";
//ค้นหาว่้าเคยบันทึกใน messege receive หรือไม่
mysql_select_db($database_vmi, $vmi);
$query_msid2 = "select * from message_reciever_hospcode where board_id='$id' and hospcode='$hospcode_check[$i]'";
$rs_msid2 = mysql_query($query_msid2, $vmi) or die(mysql_error());
$row_rs_msid2 = mysql_fetch_assoc($rs_msid2);
$totalRows_rs_msid2 = mysql_num_rows($rs_msid2);

///ถ้าไม่
	if($totalRows_rs_msid2==""){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into message_reciever_hospcode (board_id,hospcode) value ('$id','$hospcode_check[$i]')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
}
	// คำสั่ง delete
	$query_delete.=")";
	$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();</script>";
exit();	

}

if(isset($_POST['button5'])&&($_POST['button5']=="ส่ง")){
	if(trim($_FILES["fileUpload"]["tmp_name"]) != "")
{
	$uploadOk = 1;

$length = 10;

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		$images = $_FILES["fileUpload"]["tmp_name"];
		$ext = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_EXTENSION);
		$filename=$randomString.".".$ext;
	
			// chcek if file limit
	if ($_FILES["fileUpload"]["size"] > 2000000) {
		echo "ไฟล์ใหญ่เกินไป<br>";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($ext != "jpg" && $ext != "png" && $ext != "jpeg"
	&& $ext != "gif" && $ext != "pdf" && $ext != "doc" && $ext != "docx" && $ext != "xls" && $ext != "xlsx" && $ext != "ppt" && $ext != "pptx"  && $ext != "zip"  ) {
		echo "อนุญาตให้อัพโหลดได้เฉพาะ JPG, JPEG, PNG , GIF , pdf ,zip หรือไฟล์งานเท่านั้น<br>";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else {
		
		if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"],"upload/board/".$filename)) {
			//chmod("uploads/datacenter/".$target_file_new, 0777);
			echo "คุณอัพโหลดไฟล์เรียบร้อย";	
	
	chmod("upload/board/".$filename, 0755);

	$detail= str_replace("\n", "<br>\n", "$detail");

mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into message_board (date_send,hospcode,sender,detail,head,file_upload) value (NOW(),'$hospcode','$sender','$detail','$header','$filename')";
$insert = mysql_query($query_insert, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_msid = "select id from message_board order by id DESC";
$rs_msid = mysql_query($query_msid, $vmi) or die(mysql_error());
$row_rs_msid = mysql_fetch_assoc($rs_msid);
$board_id=$row_rs_msid['id'];

for($i=0;$i<count($hospcode_check);$i++){
//ค้นหาว่้าเคยบันทึกใน messege receive หรือไม่
mysql_select_db($database_vmi, $vmi);
$query_msid2 = "select * from message_reciever_hospcode where board_id='$board_id' and hospcode='$hospcode_check[$i]'";
$rs_msid2 = mysql_query($query_msid2, $vmi) or die(mysql_error());
$row_rs_msid2 = mysql_fetch_assoc($rs_msid2);
$totalRows_rs_msid2 = mysql_num_rows($rs_msid2);

///ถ้าไม่
	if($totalRows_rs_msid2==""){
	mysql_select_db($database_vmi, $vmi);
	$query_insert = "insert into message_reciever_hospcode (board_id,hospcode) value ('$board_id','$hospcode_check[$i]')";
	$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
}

echo "<script>parent.$.fn.colorbox.close();</script>";
exit();	
	}
	else{
        echo "ไม่สามารถอัพโหลดไฟล์ได้";		
		}
	}
}
}
if(isset($_POST['button5'])){
if($_POST['button5']=="บันทึก"){
mysql_free_result($rs_msid);
}
}
	


if($_GET['do']=="edit"){
mysql_select_db($database_vmi, $vmi);
$query_rs_edit = "select * from message_board where id='$_GET[id]'";
$rs_edit = mysql_query($query_rs_edit, $vmi) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);



}

mysql_select_db($database_vmi, $vmi);
$query_rs_reciever = "select h.hospcode,h.name,h.hosptype from dept_list d left outer join hospcode h on h.hospcode=d.dept_code where h.hospcode !='".$_SESSION['hospcode']."' and h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";
$rs_reciever = mysql_query($query_rs_reciever, $vmi) or die(mysql_error());
$row_rs_reciever = mysql_fetch_assoc($rs_reciever);
$totalRows_rs_reciever = mysql_num_rows($rs_reciever);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function check() {

    var checkboxes = document.getElementsByName('hospcode_check[]');

    for (var i = 0, length = checkboxes.length; i < length; i++) {
        checkboxes[i].checked = true;
    }
}
function uncheck() {

    var checkboxes = document.getElementsByName('hospcode_check[]');

    for (var i = 0, length = checkboxes.length; i < length; i++) {
        checkboxes[i].checked = false;
    }
}

</script>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	margin-left: 20px;
	margin-top: 20px;
	margin-right: 20px;
	margin-bottom: 20px;
}
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
  <tr>
    <td><form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
      <p class="big_red16">ส่งข้อความ</p>
      <table width="550" border="0" cellpadding="2" cellspacing="0" class="head_small_gray">
        <tr>
          <td valign="top">หัวข้อ</td>
          <td><input name="header" type="text" class="inputcss1" id="header" value="<?php echo $row_rs_edit['head']; ?>" style="width:100%" /></td>
        </tr>
        <tr>
          <td width="122" valign="top">ข้อความ</td>
          <td width="470"><textarea name="detail" cols="45" rows="5" id="detail" style="width:100%"><?php echo str_replace("<br>\n","\n", $row_rs_edit['detail']); ?></textarea></td>
        </tr>
        <tr>
          <td valign="top">หน่วยงานปลายทาง</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <?php $intRows = 0; 
	  do{
		  $intRows++;
		  
mysql_select_db($database_vmi, $vmi);
$query_rs_dept_edit = "select * from message_reciever_hospcode where board_id='$id' and hospcode='$row_rs_reciever[hospcode]'";
$rs_dept_edit = mysql_query($query_rs_dept_edit, $vmi) or die(mysql_error());
$row_rs_dept_edit = mysql_fetch_assoc($rs_dept_edit);
$totalRows_rs_dept_edit = mysql_num_rows($rs_dept_edit);

		  
	  ?>
              <td><table width="100%" border="0" cellspacing="0" >
                <tr>
                  <td class="table_head_small"><input name="hospcode_check[]" type="checkbox" id="hospcode_check[]"  value="<?php echo $row_rs_reciever['hospcode']; ?>" <?php if(!isset($_GET['do'])&&$_POST['do']!="edit"){ ?>checked="checked"<?php } ?> <?php if (!(strcmp($row_rs_reciever['hospcode'],$row_rs_dept_edit['hospcode']))) {echo "checked=\"checked\"";} ?> />
                    <?php echo $row_rs_reciever['hosptype'].".".$row_rs_reciever['name']; ?>
                    <input name="hospcode_id[]" type="hidden" id="hospcode_id[]" value="<?php echo $row_rs_reciever['hospcode']; ?>" />
                    <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" /></td>
                </tr>
              </table></td>
              <?
		if(($intRows)%2==0)
		{
		echo"</tr>";
		}
		else
		{
		echo "<td>";
		}	
	}while ($row_rs_reciever = mysql_fetch_assoc($rs_reciever)); ?>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="button" name="button11" id="button11" value="Select All" onclick="check();" />
            <input type="button" name="button21" id="button21" value="Unselect" onclick="uncheck();" /></td>
        </tr>
        <tr>
          <td>แนบไฟล์</td>
          <td>
            <input name="fileUpload" type="file" class="table_head_small" id="fileUpload" />
         </td>
        </tr>
        <tr>
          <td>ผู้ส่ง</td>
          <td><input name="sender" type="text" class="inputcss1" id="sender" value="<?php echo $row_rs_edit['sender']; ?>" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="button5" id="button5" value="<?php if($_GET['do']=="edit"){ echo "แก้ไข"; }else { echo "ส่ง"; } ?>" class="button red"  /></td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_reciever);


if($_GET['do']=="edit"){
mysql_free_result($rs_edit);
mysql_free_result($rs_dept_edit);
}
?>
