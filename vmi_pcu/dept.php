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

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select * from dept_group where hospmain='".$_SESSION['hospcode']."' order by dept_group_name ASC";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
//$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

mysql_select_db($database_vmi, $vmi);
$query_rs_chw = "select * from province order by province_name ASC";
$rs_chw = mysql_query($query_rs_chw, $vmi) or die(mysql_error());
$row_rs_chw = mysql_fetch_assoc($rs_chw);
$totalRows_rs_chw = mysql_num_rows($rs_chw);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="include/jquery.js"></script>
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function(e) {
    $('#dept_add').hide();
});
function deptshow(id,name){
	$('#dept_group_name').text("กลุ่มหน่วยงาน: "+name);
	$('#id').val(id);
	$('#dept_add').show();
	}

function clearDept(){
$(".department").html("");
$('.department').append($('<option/>', { 
        value: "",
        text : "--กรุณาเลือก--" 
    }));	}
</script>
</head>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td height="32" class="gray" style="padding-left:10px"><img src="images/solutions-departments.png" width="45" height="40" align="absmiddle" />&nbsp; <span class=" thsan-semibold" style="font-size:25px">กำหนดหน่วยงาน&nbsp;(Departments)</span></td>
  </tr>
  <tr style="height:25px">
    <td align="left" valign="middle" class=" bargreen" style=" text-align:left;padding-left:10px; height:30px" >เลือกกลุ่มหน่วยงาน</td>
  </tr>
  <tr style="height:25px">
    <td  >
        <table  width="800px" border="0" cellspacing="5"  style="border-collapse:collapse;" class="table_collapse">
        <?php echo "<tr>"; $intRows = 0;
		while ($row_rs_dept = mysql_fetch_assoc($rs_dept)){
			$intRows++;
			//echo "<td>";
	   ?>
            <td align="center" class="gray" height="30px" width="200px" style="border:0px; border-right:1px solid #CCC; padding:5px; cursor:pointer;"  onclick="changbgcolor(this.id,'blue');deptshow('<?php echo $row_rs_dept['dept_group_id']; ?>','<?php echo $row_rs_dept['dept_group_name']; ?>');dosubmit('<?php echo $row_rs_dept['dept_group_id']; ?>','show','dept_list.php','dept_list','form1');" id="<?php echo $row_rs_dept['dept_group_id']; ?>"><a href="javascript:valid(0);" class=" thfont font12 link_red" style="text-decoration:none; color:#000000"><?php echo $row_rs_dept['dept_group_name']; ?></a><?php
		echo"</td>";
		if(($intRows)%4==0)
		{
		echo"</tr>";
		}
		else
		{
		echo "<td>";
		}	
	} ?>
          </tr>
    </table>

    </td>
  </tr>
</table>
<form id="form1" name="form1" method="post" action="">
  <div id="dept_add"><table width="800" border="0" cellpadding="5" cellspacing="0" class=" thsan-light font16">
    <tr>
      <td colspan="2" style="padding-left:10px"></td>
    </tr>
    <tr>
      <td colspan="2" align="left" style="padding-left:20px"><div id="dept_group_name" class=" big_red16 thsan-light" style="font-size:25px"></div></td>
      </tr>
    <tr>
      <td width="12%" align="right" style="padding-left:10px">เลือกจังหวัด      </td>
      <td width="88%" style="padding-left:10px"><select name="chw" id="chw" class=" thsan-light inputcss1 chw" style="width:200px">
        <option selected="selected">--เลือกจังหวัด--</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_chw['province_id']?>"><?php echo $row_rs_chw['province_name']?></option>
        <?php
} while ($row_rs_chw = mysql_fetch_assoc($rs_chw));
  $rows = mysql_num_rows($rs_chw);
  if($rows > 0) {
      mysql_data_seek($rs_chw, 0);
	  $row_rs_chw = mysql_fetch_assoc($rs_chw);
  }
?>
      </select>
      <input type="hidden" name="id" id="id" /></td>
    </tr>
    <tr>
      <td align="right" style="padding-left:10px">เลือกอำเภอ      </td>
      <td style="padding-left:10px"><select name="amp" id="amp" class="thsan-light inputcss1 amp" style="width:200px">
        <option selected="selected">-- เลือกอำเภอ --</option>
      </select></td>
    </tr>
    <tr>
      <td align="right" class="small_red_bord" style="padding-left:10px">หน่วยงาน      </td>
      <td style="padding-left:10px"><select name="department" id="department" class=" grid_font_box_red thsan-light inputcss1_white department" style="width:200px">
        <option selected="selected">-- เลือกหน่วยงาน --</option>
      </select></td>
    </tr>
    <tr>
      <td style="padding-left:10px">&nbsp;</td>
      <td style="padding-left:10px"><input type="button" name="add" id="add" value="เพิ่ม" class="button red" onclick="dosubmit('','add','dept_list.php','dept_list','form1');clearForm('chw','amp','department');clearDept();" /></td>
    </tr>
    <tr>
      <td colspan="2" style="padding-left:10px"></td>
    </tr>
    <tr>
      <td colspan="2" style="padding-left:10px">  <div id="dept_list" ></div>
</td>
    </tr>
  </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_dept);

mysql_free_result($rs_chw);
?>
