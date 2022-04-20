<?
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php include('include/function.php'); ?>
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
$query_rs_setting = "select * from setting where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."'";
$rs_setting = mysql_query($query_rs_setting, $vmi) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_vmi, $vmi);
$query_rs_month = "select substr(DATE_ADD('".$_GET['month']."-01',interval 1 MONTH),1,7) as nextmonth,substr('".$_GET['month']."-01',1,7) as thismonth,substr(DATE_ADD('".$_GET['month']."-01',interval -1 MONTH),1,7) as lastmonth";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$nextmonth=$row_rs_month['nextmonth'];
$thismonth=$row_rs_month['thismonth'];
$lastmonth=$row_rs_month['lastmonth'];
mysql_free_result($rs_month);

if($row_rs_setting['vaccine_method']==1){
	$condition="concat(t.year,'-',t.month)='".$nextmonth."'";
	$thismonth2=$nextmonth;
	$lastmonth2=$lastmonth;
	}
else{	
	$condition="concat(t.year,'-',t.month)='".date('Y-m')."'";	
	$thismonth2=$thismonth;
	$lastmonth2=$thismonth;
	}
mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select p.vaccine_project,p.id from vaccine_list l left outer join vaccine_project p on p.id=l.vaccine_project where l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' group by l.vaccine_project order by p.id ASC";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);

mysql_select_db($database_vmi, $vmi);
$query_first_date = "select * from vaccine_take where concat(year,'-',month)='".$_GET['month']."' and hospcode='".$_GET['deptcode']."' order by vaccine_take_date ASC limit 1";
$first_date = mysql_query($query_first_date, $vmi) or die(mysql_error());
$row_first_date = mysql_fetch_assoc($first_date);
$totalRows_first_date = mysql_num_rows($first_date);

mysql_select_db($database_vmi, $vmi);
$query_rs_dept = "select concat(hosptype,name) as hospname from hospcode where hospcode='".$_GET['deptcode']."'";
$rs_dept = mysql_query($query_rs_dept, $vmi) or die(mysql_error());
$row_rs_dept = mysql_fetch_assoc($rs_dept);
$totalRows_rs_dept = mysql_num_rows($rs_dept);

function msg($tg,$vc,$ds,$l_vc,$l_order,$remain){
	if(($tg!=0)&&($vc!=0)){
		if($vc<>ceil($tg/$ds)){
		return "- ขวดเปิดใช้เดือนที่แล้วไม่สัมพันธ์กับจำนวนผู้รับบริการ";
		}
		
		if($vc!=0){
			if($l_order>$vc){	//ถ้าจำนวนเบิกมากกว่าจำนวนเปิด			
				if($l_order-$vc!=$remain){
				$msg="- จำนวนคงเหลือไม่สัมพันธ์กับวัคซีนที่ใช้ในเดือนที่แล้ว1";
				return $msg;
				}
			}
			if($l_order<$vc){ // ถ้าจำนวนเบิกน้อยกว่าจำนวนเปิด
				if($remain!=0){
				$msg="- จำนวนคงเหลือไม่สัมพันธ์กับวัคซีนที่ใช้ในเดือนที่แล้ว2";					
				return $msg;
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:56%;
	top:126px;
	width:380px;
	height:27px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left:50%;
	top:152px;
	width:309px;
	height:29px;
	z-index:2;
}
#apDiv3 {
	position:absolute;
	left:10px;
	top:126px;
	width:171px;
	height:25px;
	z-index:3;
}
#apDiv4 {
	position:absolute;
	left:10px;
	top:182px;
	width:559px;
	height:31px;
	z-index:4;
}
#apDiv5 {
	position:absolute;
	left:10px;
	top:212px;
	width:559px;
	height:31px;
	z-index:4;
}
#apDiv6 {
	position:absolute;
	left:114px;
	top:244px;
	width:445px;
	height:31px;
	z-index:4;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body onload="print();">
<div align="right" style="position:absolute; text-align:right; right: 0px;" class="thfont font16">แบบ ว.3/1</div>
<div id="apDiv1" class="thfont font16"><?php echo $row_rs_dept['hospname']; ?></div>
<div id="apDiv2" class="thfont font16">วันที่ <?php echo dateThai($row_first_date['vaccine_take_date']); ?></div>
<div id="apDiv3" class="thfont font16">ที่&nbsp;.............................</div>
<div id="apDiv4" class="thfont font16">เรื่อง  ขอเบิกวัคซีนสร้างเสริมภูมิคุ้มกันโรค</div>
<div id="apDiv5" class="thfont font16">เรียน  ผู้อำนวยการโรงพยาบาลพระยุพราชเลิงนกทา</div>
<div id="apDiv6" class="thfont font16"><?php echo $row_rs_dept['hospname']; ?> ขอเบิกวัคซีน&nbsp;ดังนี้</div>
<div align="center" style="margin-top:0px" ><img src="images/garuda3.jpg" width="93" height="101" /></div>
<div ><br /></div>
<div style="position:absolute; top:280px; width:100%;" align="center">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="thfont font12 table_collapse">
    <tr>
      <td rowspan="3" align="center" class="border font_bord">กลุ่มเป้าหมาย</td>
      <td rowspan="3" align="center" class="border font_bord">วัคซีน</td>
      <td colspan="4" align="center" class="border font_bord">ข้อมูลการเบิกวัคซีน</td>
      <td colspan="3" align="center" class="border font_bord">ผลการให้ว้คซีนในเดือนที่ผ่านมา</td>
      <td rowspan="3" class="border font_bord" >หมายเหตุ</td>
    </tr>
    <tr>
      <td rowspan="2" align="center" class="border font_bord">เป้าหมาย</td>
      <td colspan="3" align="center" class="border font_bord">จำนวนวัคซีน(ขวด)</td>
      <td rowspan="2" align="center" class="border font_bord">จำนวนผู้รับบริการ</td>
      <td rowspan="2" align="center" class="border font_bord">จำนวนวัคซีนที่เปิดใช้</td>
      <td rowspan="2" align="center" class="border font_bord">อัตราสูญเสีย</td>
    </tr>
    <tr>
      <td align="center" class="border font_bord">ที่ต้องการ</td>
      <td align="center" class="border font_bord">ยอดคงเหลือ</td>
      <td align="center" class="border font_bord">ที่ขอเบิก</td>
    </tr>
    <?php  do {  $i=0;
mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine = 
"select t.*,v.vaccine_name,l.vaccine_factor,v.doses,t2.last_use as last_use2,t2.remain as remain2,t2.target as target2 from vaccine_take t left outer join vaccines v on v.id=t.vaccine_id left outer join vaccine_list l on l.vaccine_id=v.id and l.vaccine_project='".$row_rs_project['id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' left outer join vaccine_take t2 on t2.vaccine_id=t.vaccine_id and concat(t2.year,'-',t2.month)='".$lastmonth2."' and t2.hospcode='".$_GET['deptcode']."' where t.vaccine_project='".$row_rs_project['id']."' and t.hospcode='".$_GET['deptcode']."' and concat(t.year,'-',t.month)='".$_GET['month']."' group by t.vaccine_id";
$rs_vaccine = mysql_query($query_rs_vaccine, $vmi) or die(mysql_error());
$row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
$totalRows_rs_vaccine = mysql_num_rows($rs_vaccine);

if($totalRows_rs_vaccine<>0){
$request2= ceil(($row_rs_vaccine['target2']*$row_rs_vaccine['vaccine_factor'])/$row_rs_vaccine['doses']);
if($request2<=$row_rs_vaccine['remain']){
$dispen=0;
}
if($request2>$row_rs_vaccine['remain']){
$dispen=$request2-$row_rs_vaccine['remain'];	
}

}

if($totalRows_rs_vaccine>1){
mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine2 =
"select t.*,v.vaccine_name,l.vaccine_factor,v.doses,t2.last_use as last_use2,t2.remain as remain2,t2.target as target2,ceil((t2.target*l.vaccine_factor)/v.doses) as last_request from vaccine_take t left outer join vaccines v on v.id=t.vaccine_id left outer join vaccine_list l on l.vaccine_id=v.id and l.vaccine_project='".$row_rs_project['id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' left outer join vaccine_take t2 on t2.vaccine_id=t.vaccine_id and concat(t2.year,'-',t2.month)='".$lastmonth2."' and t2.hospcode='".$_GET['deptcode']."'  where t.vaccine_project='".$row_rs_project['id']."' and t.hospcode='".$_GET['deptcode']."' and concat(t.year,'-',t.month)='".$_GET['month']."' group by t.vaccine_id limit 1,".($totalRows_rs_vaccine-1);

$rs_vaccine2 = mysql_query($query_rs_vaccine2, $vmi) or die(mysql_error());
$row_rs_vaccine2 = mysql_fetch_assoc($rs_vaccine2);
$totalRows_rs_vaccine2 = mysql_num_rows($rs_vaccine2);
}
$i++;
if($totalRows_rs_vaccine<>0){

 ?>
    <tr>
      <td rowspan="<?php echo $totalRows_rs_vaccine; ?>" align="center" bgcolor="#FFFFFF" class="border font13"><?php print $row_rs_project['vaccine_project']; ?></td>
      <td align="left" bgcolor="#FFFFFF" class="border" ><?php echo $i.". ".$row_rs_vaccine['vaccine_name']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['target']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php $request= ceil(($row_rs_vaccine['target']*$row_rs_vaccine['vaccine_factor'])/$row_rs_vaccine['doses']); echo $request; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['remain']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border font_bord" style="color:#FF0000"><?php if($row_rs_vaccine['remain']>=$request){ echo 0;} else { echo $request-$row_rs_vaccine['remain'];}?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['last_visit']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['last_use']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php if($row_rs_vaccine['last_use']==0){ echo "0";}else{ echo str_replace('.00','',number_format((((($row_rs_vaccine['last_use']*$row_rs_vaccine['doses'])-$row_rs_vaccine['last_visit'])/($row_rs_vaccine['last_use']*$row_rs_vaccine['doses']))*100),2))."%"; } ?></td>
      <td class="border"><?php echo msg($row_rs_vaccine['last_visit'],$row_rs_vaccine['last_use'],$row_rs_vaccine['doses'],$row_rs_vaccine['last_use2'],ceil(($row_rs_vaccine['target2']*$row_rs_vaccine['vaccine_factor'])/$row_rs_vaccine['doses']),$row_rs_vaccine['remain']);if($row_rs_vaccine['remark']!=""){ echo "<br>- ".$row_rs_vaccine['remark']; }
	   /*echo "<br>last_visit=".$row_rs_vaccine['last_visit'].",last_use=".$row_rs_vaccine['last_use'].",doses=".$row_rs_vaccine['doses'].",last_use2=".$row_rs_vaccine['last_use2'].",request2=".$request2.",remain=".$row_rs_vaccine['remain'];*/ ?></td>
    </tr>
    <?php if($totalRows_rs_vaccine>1){ 
	$j=$i;
	do{
	
			$request3= ceil(($row_rs_vaccine2['target2']*$row_rs_vaccine2['vaccine_factor'])/$row_rs_vaccine2['doses']);
if($request3<=$row_rs_vaccine2['remain']){
$dispen2=0;
}
if($request3>$row_rs_vaccine2['remain']){
$dispen2=$request3-$row_rs_vaccine2['remain'];	
}

		$j++;
?>
    <tr>
      <td bgcolor="#FFFFFF" class="border"><?php echo $j.". ".$row_rs_vaccine2['vaccine_name']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine2['target']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php $request= ceil(($row_rs_vaccine2['target']*$row_rs_vaccine2['vaccine_factor'])/$row_rs_vaccine2['doses']); echo $request; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine2['remain']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border font_bord" style="color:#FF0000"><?php if($row_rs_vaccine2['remain']>=$request){ echo 0;} else { echo $request-$row_rs_vaccine2['remain'];}?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine2['last_visit']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine2['last_use']; ?></td>
      <td align="center" bgcolor="#FFFFFF" class="border"><?php if($row_rs_vaccine2['last_use']==0){ echo "0";}else{ echo str_replace('.00','',number_format((((($row_rs_vaccine2['last_use']*$row_rs_vaccine2['doses'])-$row_rs_vaccine2['last_visit'])/($row_rs_vaccine2['last_use']*$row_rs_vaccine2['doses']))*100),2))."%"; } ?></td>
      <td class="border"><?php echo msg($row_rs_vaccine2['last_visit'],$row_rs_vaccine2['last_use'],$row_rs_vaccine2['doses'],$row_rs_vaccine2['last_use2'],ceil(($row_rs_vaccine2['target2']*$row_rs_vaccine2['vaccine_factor'])/$row_rs_vaccine2['doses']),$row_rs_vaccine2['remain']); if($row_rs_vaccine2['remark']!=""){echo "<br>- ".$row_rs_vaccine2['remark']; } /*echo "<br>last_visit=".$row_rs_vaccine2['last_visit'].",last_use=".$row_rs_vaccine2['last_use'].",doses=".$row_rs_vaccine2['doses'].",last_use2=".$row_rs_vaccine2['last_use2'].",request2=".$request3.",remain2=".$row_rs_vaccine2['remain'];*/ 

	  ?></td>
    </tr>
    <?php }while($row_rs_vaccine2 = mysql_fetch_assoc($rs_vaccine2));
  } ?>
    <?php
  mysql_free_result($rs_vaccine);

  if($totalRows_rs_vaccine>1){
  mysql_free_result($rs_vaccine2);
  		}
	}
} while ($row_rs_project = mysql_fetch_assoc($rs_project)); ?>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thfont font16" style="margin-top:20px">
  <tr>
    <td align="center">ขอแสดงความนับถือ</td>
  </tr>
  <tr>
    <td height="84" align="center" valign="bottom">(.............................................................)<br />
      ตำแหน่ง .........................................</td>
  </tr>
  <tr>
    <td align="center" height="40px">ผู้เบิก</td>
  </tr>
</table>

</div>

</body>
</html>
<?php

mysql_free_result($rs_project);
mysql_free_result($first_date);
mysql_free_result($rs_dept);
mysql_free_result($rs_setting);

?>
