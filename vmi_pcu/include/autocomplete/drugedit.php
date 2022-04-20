<?php require_once('../../Connections/hos.php'); ?>
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

if(isset($_POST['button'])&&($_POST['button']=="แก้ไข")){
	mysql_select_db($database_hos, $hos);
	//update dep_time
	$update="update ovst set cur_dep_time = now() where vn = '$vn'
";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "1. ".$update;

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ovst set cur_dep_time = now() where vn = \'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "2. ".$update;
	
	//update last_send_time
		mysql_select_db($database_hos, $hos);
	$update="UPDATE service_time SET last_send_time=now() WHERE vn='$vn'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "3. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE service_time SET last_send_time=now() WHERE vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "4. ".$update;
	
	
	if($code!='0201'){
	//ถ้าไม่ใช่ตามแพทย์สั่ง
	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="UPDATE opitemrece SET qty='$qty',drugusage='$code',discount=NULL,sum_price=unitprice*'$qty',sp_use=NULL WHERE hos_guid='$hos_guid'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE opitemrece SET qty=\'".$qty."\',drugusage=\'".$code."\',discount=NULL,sum_price=unitprice*\'".$qty."\' WHERE hos_guid=\'".$hos_guid."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "6. ".$update;
	}
	if($code=='0201'){
	//ถ้าใช่ตามแพทย์สั่ง
	//get serial
	mysql_select_db($database_hos, $hos);
	$getserial="select get_serialnumber('sp_use') as cc";
	$sgetserial= mysql_query($getserial, $hos) or die(mysql_error());
	$row_getserial = mysql_fetch_assoc($sgetserial);	
	echo "1. ".$getserial."</br>";
	//replicate_log serial number
	mysql_select_db($database_hos, $hos);
	$replicate="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no = \'".$row_getserial['cc']."\' where name =\'sp_use\'')";
	$sreplicate= mysql_query($replicate, $hos) or die(mysql_error());
	echo "2. ".$replicate."</br>";
	
	//insert sp_use
	mysql_select_db($database_hos, $hos);
	$insert="INSERT INTO sp_use (sp_use,sp_name,name1,name2,name3,`user`,sp_use_guid,oldcode,hos_guid,hos_guid_ext) VALUES ('".$row_getserial['cc']."','".$code2."','$sp_use1','$sp_use2','$sp_use3','kohrx',NULL,NULL,NULL,NULL)";
	$sinsert= mysql_query($insert, $hos) or die(mysql_error());
	echo "3. ".$insert."</br>";

	//replicate_log serial number
	mysql_select_db($database_hos, $hos);
	$replicate="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO sp_use (sp_use,sp_name,name1,name2,name3,`user`,sp_use_guid,oldcode,hos_guid,hos_guid_ext) VALUES (\'".$row_getserial['cc']."\',\'".$code2."\',\'$sp_use1\',\'$sp_use2\',\'$sp_use3\',\'kohrx\',NULL,NULL,NULL,NULL)')";
	$sreplicate= mysql_query($replicate, $hos) or die(mysql_error());
	echo "4. ".$replicate."</br>";

	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="UPDATE opitemrece SET qty='$qty',drugusage='$code',sp_use='".$row_getserial['cc']."',discount=NULL,sum_price=unitprice*'$qty' WHERE hos_guid='$hos_guid'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;
	echo "5. ".$update."</br>";


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE opitemrece SET qty=\'".$qty."\',drugusage=\'".$code."\',sp_use=\'".$row_getserial['cc']."\',discount=NULL,sum_price=unitprice*\'".$qty."\' WHERE hos_guid=\'".$hos_guid."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	echo "6. ".$update."</br>";

	}
	
	//update service_time
		mysql_select_db($database_hos, $hos);
	$update="update service_time set rx_time_type = '' where vn='$vn'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update service_time set rx_time_type = \'\' where vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
echo "<script>parent.$.fancybox.close()</script>";
exit();
	}

if(isset($_POST['button2'])&&($_POST['button2']=="ลบ")){
	mysql_select_db($database_hos, $hos);
	//update dep_time
	$update="update ovst set cur_dep_time = now() where vn = '$vn'
";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "1. ".$update;

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ovst set cur_dep_time = now() where vn = \'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "2. ".$update;
	
	//update last_send_time
		mysql_select_db($database_hos, $hos);
	$update="UPDATE service_time SET last_send_time=now() WHERE vn='$vn'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "3. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE service_time SET last_send_time=now() WHERE vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "4. ".$update;

	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="DELETE FROM opitemrece WHERE hos_guid='$hos_guid'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','DELETE FROM opitemrece WHERE hos_guid=\'".$hos_guid."\'')
";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "6. ".$update;
	
	
	//update service_time
		mysql_select_db($database_hos, $hos);
	$update="update service_time set rx_time_type = '' where vn='$vn'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update service_time set rx_time_type = \'\' where vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
echo "<script>parent.$.fancybox.close()</script>";
exit();
	}

mysql_select_db($database_hos, $hos);
$query_drugitems = "SELECT concat(name,' ',strength) as drugname,qty,d.shortlist,o.drugusage,o.icode,sp.sp_use,sp.sp_name,sp.name1 as sp_name1,sp.name2 as sp_name2,sp.name3 as sp_name3,d.name1,d.name2,d.name3 FROM opitemrece o left outer join  s_drugitems s on s.icode=o.icode left outer join drugusage d on d.drugusage=o.drugusage left outer join sp_use sp on sp.sp_use=o.sp_use WHERE o.icode ='$icode' and vn='$vn'";
//echo $query_drugitems;
$drugitems = mysql_query($query_drugitems, $hos) or die(mysql_error());
$row_drugitems = mysql_fetch_assoc($drugitems);
$totalRows_drugitems = mysql_num_rows($drugitems);


require_once "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="jquery.js"></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />

<script type="text/javascript">
$().ready(function() {
	//$("#showusage").hide();
	//$("#sp_use").hide();

	$("#drugusage").autocomplete("get_course_list.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#drugusage").result(function(event, data, formatted) {
		$("#code").val(data[1]); $("#code2").val(data[5]); 
		
			if(data[1]!="0201"){$("#name1").val(data[2]); $("#name2").val(data[3]); $("#name3").val(data[4]); $("#showusage").show();}	
			if(data[1]=="0201"){ $("#sp_use1").val(data[2]); $("#sp_use2").val(data[3]); $("#sp_use3").val(data[4]); $("#sp_use").show();}
	});

});
</script>
<link href="../../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="big_black16">แก้ไขรายการยา
</p>
<div id="content">
<form autocomplete="off" method="post"  action="drugedit.php">
  <table width="600" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td colspan="2" class="big_red16"><?php echo $row_drugitems['drugname']; ?>
        <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
        <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
        <input name="hos_guid" type="hidden" id="hos_guid" value="<?php echo $hos_guid; ?>" /></td>
    </tr>
    <tr>
      <td width="11%" valign="top">วิธีใช้</td>
      <td><input name="drugusage" type="text" id="drugusage" value="<?php if($row_drugitems['drugusage']!="0201"||$row_drugitems['drugusage']!=""){ echo $row_drugitems['shortlist']; } if(($row_drugitems['drugusage']=="0201")||($row_drugitems['drugusage'] =="")){ echo $row_drugitems['sp_name1'].$row_drugitems['sp_name2'].$row_drugitems['sp_name3']; } ?>" />
        <input name="code" type="hidden"  id="code" size="5" value="<?php echo $row_drugitems['drugusage']; ?>" />
        <input name="code2" type="hidden"  id="code2" size="5" value="<?php echo $row_drugitems['code']; ?>" />
        # 
        <input name="qty" type="text" id="qty" size="3"  value="<?php echo $row_drugitems['qty']; ?>" /> <input type="submit" name="button" id="button" value="แก้ไข" />
        <input type="submit" name="button2" id="button2" value="ลบ" />
        <br />
        <span class="small_red"><?php echo $msg; ?>&nbsp;</span><div id="longlist"></div>
       <?php if($row_drugitems['drugusage']!=""&&$row_drugitems['drugusage']!="0201"){ ?>
        <table width="500" border="0" cellpadding="2" cellspacing="0" class="table_head_small" id="showusage">
          <tr>
            <td align="center">บรรทัด 1</td>
            <td align="center"><input name="name1" type="text" disabled="disabled" id="name1" value="<?php echo $row_drugitems['name1']; ?>" size="50" /></td>
            </tr>
          <tr>
            <td align="center">บรรทัด 2</td>
            <td align="center"><input name="name2" type="text" disabled="disabled" id="name2" value="<?php echo $row_drugitems['name2']; ?>" size="50" /></td>
            </tr>
          <tr>
            <td align="center">บรรทัด 3</td>
            <td align="center"><input name="name3" type="text" disabled="disabled" id="name3" value="<?php echo $row_drugitems['name3']; ?>" size="50" /></td>
            </tr>
        </table> <?php } ?>       
<?php if(!empty($row_drugitems['drugusage'])&&($row_drugitems['drugusage']=="0201")){ ?>
        <table width="500" border="0" cellpadding="2" cellspacing="0" class="table_head_small" id="sp_use" >         
          <tr >
            <td colspan="2" align="center">กำหนดวิธีใช้เอง</td>
            </tr>
          <tr>
            <td align="center">บรรทัด 1</td>
            <td align="center"><input name="sp_use1" type="text" id="sp_use1" value="<?php echo $row_drugitems['sp_name1']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td align="center">บรรทัด 2</td>
            <td align="center"><input name="sp_use2" type="text" id="sp_use2" value="<?php echo $row_drugitems['sp_name2']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td align="center">บรรทัด 3</td>
            <td align="center"><input name="sp_use3" type="text" id="name6" value="<?php echo $row_drugitems['sp_name3']; ?>" size="50" /></td>
          </tr>
        </table> <? } ?>       <br />
</td>
      </tr>
  </table>
</form>
</div>
</body>
</html>
<?php
mysql_free_result($drugitems);

?>
