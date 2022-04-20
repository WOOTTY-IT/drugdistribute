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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_function.php'); ?>

<script type="text/javascript">
$(document).ready(function(e) {
    $('#item_add').hide();
	$('#add').hide();
	$('#countmonth').change(function(){
	dosubmit($('#id').val(),'countmonth','item_add_list','item_list','form1');
		});

});
function deptshow(id,name,countmonth){
	$('#dept_group_name').text("กลุ่มหน่วยงาน: "+name);
	$('#countmonth').val(countmonth);
	$('#id').val(id);
	$('#item_add').show();
	$('#add').show();

	}
</script>
</head>

<body>

<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td height="32" class="gray" style="padding-left:10px" colspan="2"><img src="images/add-drug.png" width="32" height="32" align="absmiddle" />&nbsp; <span class="table_head_small_bord">กำหนดรายการยาของแต่ละกลุ่มหน่วยงาน</span></td>
  </tr>
  <tr style="height:25px">
    <td align="left" valign="middle" class=" bargreen" style=" text-align:left;padding-left:10px; height:30px" >เลือกกลุ่มหน่วยงาน</td>
  </tr>
  <tr style="height:25px">
    <td  >            <table  width="800px" border="0" cellspacing="5"  style="border-collapse:collapse;" class="table_collapse">
        <?php echo "<tr>"; $intRows = 0;
		while ($row_rs_dept = mysql_fetch_assoc($rs_dept)){
			$intRows++;
			//echo "<td>";
	   ?>

            <td align="center" class="gray" height="30px" width="200px" style="border:0px; border-right:1px solid #CCC; padding:5px; cursor:pointer;"  onclick="changbgcolor(this.id,'gray');deptshow('<?php echo $row_rs_dept['dept_group_id']; ?>','<?php echo $row_rs_dept['dept_group_name']; ?>','<?php echo $row_rs_dept['stock_month']; ?>');dosubmit('<?php echo $row_rs_dept['dept_group_id']; ?>','show','item_add_list.php','item_list','form1');" id="<?php echo $row_rs_dept['dept_group_id']; ?>"><a href="javascript:valid(0);" class="table_head_small_bord link_red"><?php echo $row_rs_dept['dept_group_name']; ?></a>
<?php
		echo"</td>";
		if(($intRows)%6==0)
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
  </tr>
</table>
<form id="form1" name="form1" method="post" action="">
  <div id="item_add"><table width="600" border="0" cellpadding="5" cellspacing="0" class="table_head_small">
    <tr>
      <td style="padding-left:10px"></td>
    </tr>
    <tr>
      <td align="left" style="padding-left:10px"><div id="dept_group_name" class=" big_red16" style=" border-bottom:dashed 1px #FF0000"></div></td>
      </tr>
    <tr>
      <td style="padding-left:10px">        <a href="javascript:OpenPopup('item_add2.php','500','500','');" id="add" name="add" class="button orange " style="padding:5px; padding-left:10px; padding-right:10px; height:30px; padding-button:5px; "><img src="images/add-drug2.png" width="30" height="30" align="absmiddle" / border="0">&nbsp; เพิ่มรายการยา</a>        
        <input type="hidden" name="id" id="id" />
       &nbsp;&nbsp;<span class="small_red_bord"> รายการเบิก 
        </span>       <select name="countmonth" class="inputcss1" id="countmonth" style="background-color:#FF0000; color:#FFFFFF" >
          <option value="1">1 เดือน</option>
          <option value="2">2 เดือน</option>
          <option value="3">3 เดือน</option>
          <option value="4">4 เดือน</option>
          <option value="5">5 เดือน</option>
          <option value="6">6 เดือน</option>
          <option value="7">7 เดือน</option>
          <option value="8">8 เดือน</option>
          <option value="9">9 เดือน</option>
          <option value="10">10 เดือน</option>
          <option value="11">11 เดือน</option>
          <option value="12">12 เดือน</option>
        </select></td>
      </tr>
    <tr>
      <td style="padding-left:10px"></td>
    </tr>
    <tr>
      <td style="padding-left:10px">  <div id="item_list" ></div>
</td>
    </tr>
  </table>
  </div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_dept);
?>
