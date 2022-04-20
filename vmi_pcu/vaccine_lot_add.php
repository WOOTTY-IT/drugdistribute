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

if(isset($_POST['button2'])&&($_POST['button2']=="ลบข้อมูล")){
	$date11=explode("/",$_POST['exp']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);
mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from vaccine_lot where date_take='".$_POST['date']."' and vaccine_lot='".$_POST['lot']."' and vaccine_id='".$_POST['vaccine']."' and amppart='".$_SESSION['amppart']."' and chwpart='".$_SESSION['chwpart']."' ";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
echo "<script>parent.$.fn.colorbox.close();parent.vaccine_list();</script>";
}

if(isset($_POST['button'])&&($_POST['button']=="บันทึก")){
	$date11=explode("/",$_POST['exp']);
	$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from vaccine_lot where date_take='".$_POST['date']."' and vaccine_lot='".$_POST['lot']."' and vaccine_id='".$_POST['vaccine']."' and amppart='".$_SESSION['amppart']."' and chwpart='".$_SESSION['chwpart']."' ";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());
	
	for($i=0;$i<count($_POST['hospcode_check']);$i++){
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert  into vaccine_lot (date_take,vaccine_id,vaccine_lot,vaccine_exp,hospcode,amppart,chwpart) value ('".$_POST['date']."','".$_POST['vaccine']."','".$_POST['lot']."','".$edate2."','".$_POST['hospcode_check'][$i]."','".$_SESSION['amppart']."','".$_SESSION['chwpart']."') ";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	
	}
echo "<script>parent.$.fn.colorbox.close();parent.vaccine_list();</script>";
}

if(isset($_GET['action'])){
mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine_edit = "select * from vaccine_lot where vaccine_lot='".$_GET['vaccine_lot']."' and vaccine_id='".$_GET['vaccine_id']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_vaccine_edit = mysql_query($query_rs_vaccine_edit, $vmi) or die(mysql_error());
$row_rs_vaccine_edit = mysql_fetch_assoc($rs_vaccine_edit);
$totalRows_rs_vaccine_edit = mysql_num_rows($rs_vaccine_edit);

$exp_edit=substr($row_rs_vaccine_edit['vaccine_exp'],8,2)."/".substr($row_rs_vaccine_edit['vaccine_exp'],5,2)."/".(substr($row_rs_vaccine_edit['vaccine_exp'],0,4)+543);

$condition=" where id='".$_GET['vaccine_id']."'";
}

mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine = "select * from vaccines ".$condition." order by vaccine_name ASC";
$rs_vaccine = mysql_query($query_rs_vaccine, $vmi) or die(mysql_error());
$row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
$totalRows_rs_vaccine = mysql_num_rows($rs_vaccine);

mysql_select_db($database_vmi, $vmi);
$query_rs_reciever = "select h.hospcode,h.name,h.hosptype from dept_list d left outer join hospcode h on h.hospcode=d.dept_code where  h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";
$rs_reciever = mysql_query($query_rs_reciever, $vmi) or die(mysql_error());
$row_rs_reciever = mysql_fetch_assoc($rs_reciever);
$totalRows_rs_reciever = mysql_num_rows($rs_reciever);

$date=$_GET['date1']; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>
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

</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="25%">เลือกวัคซีน</td>
    <td width="75%"><label for="vaccine"></label>
      <select name="vaccine" id="vaccine">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_vaccine['id']?>"><?php echo $row_rs_vaccine['vaccine_name']?></option>
        <?php
} while ($row_rs_vaccine = mysql_fetch_assoc($rs_vaccine));
  $rows = mysql_num_rows($rs_vaccine);
  if($rows > 0) {
      mysql_data_seek($rs_vaccine, 0);
	  $row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
  }
?>
      </select>
      <input name="date" type="hidden" id="date" value="<?php echo $date; ?>" /></td>
  </tr>
  <tr>
    <td>lot</td>
    <td><label for="lot"></label>
      <input name="lot" type="text" id="lot" value="<?php echo $_GET['vaccine_lot']; ?>" /></td>
  </tr>
  <tr>
    <td>expire</td>
    <td><span class="input-group" style="width:200px">
      <input type="text" id="exp" name="exp"  class=" inputcss1 thsan-light font18" style="width:100px" value="<?php if($exp_edit<>""){ echo $exp_edit; } else { echo date('d/m/').(date('Y')+543); } ?>"  />
    </span></td>
  </tr>
  <tr>
    <td valign="top">สถานบริการที่ได้รับ</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <?php $intRows = 0; 
	  do{
		  $intRows++;
		  
mysql_select_db($database_vmi, $vmi);
$query_rs_dept_edit = "select * from vaccine_lot where vaccine_id='".$_GET['vaccine_id']."' and vaccine_lot='".$_GET['vaccine_lot']."' and chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' and hospcode='".$row_rs_reciever['hospcode']."' and date_take='".$_GET['date_current']."'";
$rs_dept_edit = mysql_query($query_rs_dept_edit, $vmi) or die(mysql_error());
$row_rs_dept_edit = mysql_fetch_assoc($rs_dept_edit);
$totalRows_rs_dept_edit = mysql_num_rows($rs_dept_edit);

		  
	  ?>
              <td><table width="100%" border="0" cellspacing="0" >
                <tr>
                  <td class="table_head_small"><input name="hospcode_check[]" type="checkbox" id="hospcode_check[]"  value="<?php echo $row_rs_reciever['hospcode']; ?>" <?php if(!isset($_GET['action'])&&$_GET['action']!="edit"){ ?>checked="checked"<?php } ?> <?php if(isset($_GET['action'])&&$_GET['action']=="edit"){ if (!(strcmp($row_rs_reciever['hospcode'],$row_rs_dept_edit['hospcode']))) {echo "checked=\"checked\"";} } ?> />
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
    <td valign="top">&nbsp;</td>
    <td><input type="button" name="button11" id="button11" value="Select All" onclick="check();" />
      <input type="button" name="button21" id="button21" value="Unselect" onclick="uncheck();" /></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td><input type="submit" name="button" id="button" value="บันทึก" />
      &nbsp;<input type="submit" name="button2" id="button2" value="ลบข้อมูล" /></td>
  </tr>
  </table>

</form>
</body>
</html>
<?php
mysql_free_result($rs_vaccine);

mysql_free_result($rs_reciever);
?>
