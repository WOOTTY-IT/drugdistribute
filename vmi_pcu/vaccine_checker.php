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
?>
<?php include('include/function.php'); ?>
<?php

mysql_select_db($database_vmi, $vmi);
$query_rs_setting = "select * from setting where chwpart='".$_GET['chwpart']."' and amppart='".$_GET['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

if($row_rs_setting['vaccine_method']==1){
	$monthyear=date('Y')."-".sprintf("%02d", (ltrim(date('m'),0)+1));	
	}
if($row_rs_setting['vaccine_method']==2){
	$monthyear=date('Y')."-".date('m');		
	}

mysql_select_db($database_vmi, $vmi);
$query_rs_notsend = "select concat(hosptype,' ',name) as hospname from user u left outer join hospcode h on h.hospcode=u.hospcode where u.hospcode not in (select v.hospcode from vaccine_take v where concat(year,'-',month)='".$monthyear."' group by v.hospcode) and h.chwpart='".$_GET['chwpart']."' and h.amppart='".$_GET['amppart']."' group by u.hospcode ";
$rs_notsend = mysql_query($query_rs_notsend, $vmi) or die(mysql_error());
$row_rs_notsend = mysql_fetch_assoc($rs_notsend);
$totalRows_rs_notsend = mysql_num_rows($rs_notsend);


$mms =  "รพ.สต.ที่ยังไม่ส่งเบิกวัคซีน ประจำเดือน ".monthThai($monthyear)." คือ ";

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php if ($totalRows_rs_notsend > 0) { // Show if recordset not empty ?>
<table width="600" border="0" cellspacing="0" cellpadding="5" class="thsan-light font16">
  <tr>
    <td colspan="2" class="font_bord"><span style="border-bottom:dotted 1px #000000">หน่วยงานที่ยังไม่ส่ง</span></td>
  </tr>
  <?php $i=0; do{ $i++; ?>
  <?php 
  $mms.=$i.". ".$row_rs_notsend['hospname'];
  if($i<>$totalRows_rs_notsend){ $mms.=", "; }
  ?>
  <tr>
    <td width="5%"><?php echo $i; ?></td>
    <td width="95%"><?php echo $row_rs_notsend['hospname']; ?></td>
  </tr>
  <? 
	 } while ($row_rs_notsend = mysql_fetch_assoc($rs_notsend)); ?>
</table>
<?php } // Show if recordset not empty ?>
<?php 
if($totalRows_rs_notsend<>0){
	if((date('d')>"15")&&(date('d')<"19")){
		if(date('H:s')>"09:00"){
mysql_select_db($database_vmi, $vmi);
$query_rs_line_notify = "select * from line_notify where notify_header='vaccine' and notify_date=CURDATE() and amppart='".$_GET['amppart']."' and chwpart='".$_GET['chwpart']."'";
$rs_line_notify = mysql_query($query_rs_line_notify, $vmi) or die(mysql_error());
$row_rs_line_notify = mysql_fetch_assoc($rs_line_notify);
$totalRows_rs_line_notify = mysql_num_rows($rs_line_notify);

$mms.="กรุณาส่งไม่เกินวันที่ 18 นะครับของคุณครับ";	

	if($totalRows_rs_line_notify==0){	
		$lineapi = $row_rs_setting['token_vaccine'];


//line Send
//ส่งไปยัง line1 ====================================
$chOne = curl_init(); 
curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
// SSL USE 
curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
//POST 
curl_setopt( $chOne, CURLOPT_POST, 1); 
// Message 
curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$mms"); 
//ถ้าต้องการใส่รุป ให้ใส่ 2 parameter imageThumbnail และimageFullsize
//curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$mms&imageThumbnail=http://plusquotes.com/images/quotes-img/surprise-happy-birthday-gifts-5.jpg&imageFullsize=http://plusquotes.com/images/quotes-img/surprise-happy-birthday-gifts-5.jpg&stickerPackageId=1&stickerId=100"); 
// follow redirects 
curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1); 
//ADD header array 
$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$lineapi.'', ); 
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
//RETURN 
curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec( $chOne ); 
//Close connect 
curl_close( $chOne );      


//insert line notify
mysql_select_db($database_vmi, $vmi);
$query_insert = "insert into line_notify (notify_header,notify_date,amppart,chwpart) values ('vaccine',CURDATE(),'".$_GET['amppart']."','".$_GET['chwpart']."')";
$rs_insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	
	}
	
	mysql_free_result($rs_line_notify);

 		}
	}
}
?>
</body>
</html>
<?php 
mysql_free_result($rs_setting);

mysql_free_result($rs_notsend);



?>