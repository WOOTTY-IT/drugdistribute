<?
ob_start();
session_start();
?><?php require_once('Connections/vmi.php'); ?>
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
$query_rs_month = "select substr(DATE_ADD(CURDATE(),interval 1 MONTH),1,7) as nextmonth,substr(CURDATE(),1,7) as thismonth,substr(DATE_ADD(CURDATE(),interval -1 MONTH),1,7) as lastmonth";
$rs_month = mysql_query($query_rs_month, $vmi) or die(mysql_error());
$row_rs_month = mysql_fetch_assoc($rs_month);
$nextmonth=$row_rs_month['nextmonth'];
$thismonth=$row_rs_month['thismonth'];
$lastmonth=$row_rs_month['lastmonth'];
mysql_free_result($rs_month);

if($row_rs_setting['vaccine_method']==1){
	$condition="concat(t.year,'-',t.month)='".$nextmonth."'";
	$thismonth2=$nextmonth;
	$lastmonth2=$thismonth;
	}
else{	
	$condition="concat(t.year,'-',t.month)='".date('Y-m')."'";	
	$thismonth2=$thismonth;
	$lastmonth2=$lastmonth;
	}

mysql_select_db($database_vmi, $vmi);
$query_delete = "delete from vaccine_take where hospcode=''";
$rs_delete = mysql_query($query_delete, $vmi) or die(mysql_error());

mysql_select_db($database_vmi, $vmi);
$query_rs_project = "select p.vaccine_project,p.id from vaccine_list l left outer join vaccine_project p on p.id=l.vaccine_project where l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' group by l.vaccine_project order by p.id ASC";
$rs_project = mysql_query($query_rs_project, $vmi) or die(mysql_error());
$row_rs_project = mysql_fetch_assoc($rs_project);
$totalRows_rs_project = mysql_num_rows($rs_project);


mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine1 = "select t.* from vaccine_take t  where  t.hospcode='".$_SESSION['hospcode']."' and ".$condition." and t.confirm='Y'";
$rs_vaccine1 = mysql_query($query_rs_vaccine1, $vmi) or die(mysql_error());
$row_rs_vaccine1 = mysql_fetch_assoc($rs_vaccine1);
$totalRows_rs_vaccine1 = mysql_num_rows($rs_vaccine1);

function msg($tg,$vc,$ds,$l_vc,$l_order,$remain){
	if(($tg!=0)&&($vc!=0)){
		if($vc<>ceil($tg/$ds)){
		$msg="- ขวดเปิดใช้เดือนที่แล้วไม่สัมพันธ์กับจำนวนผู้รับบริการ";
		return $msg;
		}
		
		if($vc!=0){
			if($l_order>$vc){	//ถ้าจำนวนเบิกมากกว่าจำนวนเปิด			
				if($l_order-$vc!=$remain){
				$msg="- จำนวนคงเหลือไม่สัมพันธ์กับวัคซีนที่ใช้ในเดือนที่แล้ว";
				return $msg;
				}
			}
			if($l_order<$vc){ // ถ้าจำนวนเบิกน้อยกว่าจำนวนเปิด
				if($remain!=0){
				if($l_order-$vc!=$remain){
				$msg="- จำนวนคงเหลือไม่สัมพันธ์กับวัคซีนที่ใช้ในเดือนที่แล้ว";					
				return $msg;
				}
				}
			}
		}
	}
}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="1000" border="0" cellspacing="0" cellpadding="3" class="thsan-light font14 table_collapse">
  <tr>
    <td rowspan="3" align="center" bgcolor="#E6E6E6" class="border font_bord">กลุ่มเป้าหมาย</td>
    <td rowspan="3" align="center" bgcolor="#E6E6E6" class="border font_bord">วัคซีน</td>
    <td colspan="5" align="center" bgcolor="#E6E6E6" class="border font_bord">ข้อมูลการเบิกวัคซีน</td>
    <td colspan=" <?php if((ltrim(date('d'),'0')<=$row_rs_setting['end_date'])&&($totalRows_rs_vaccine1==0)){ echo "5"; } else {echo "4"; } ?>" align="center" bgcolor="#E6E6E6" class="border font_bord">ผลการให้ว้คซีนในเดือนที่ผ่านมา</td>
  </tr>
  <tr>
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">เป้าหมาย</td>
    <td colspan="3" align="center" bgcolor="#E6E6E6" class="border font_bord">จำนวนวัคซีน(ขวด)</td>
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">จำนวนผู้รับบริการ</td>
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">จำนวนวัคซีนที่เปิดใช้</td>
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">อัตราสูญเสีย</td>
   
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">&nbsp;</td>
    <td rowspan="2" align="center" bgcolor="#E6E6E6" class="border font_bord">หมายเหตุ</td>

  </tr>
  <tr>
    <td align="center" bgcolor="#E6E6E6" class="border font_bord">ที่ต้องการ</td>
    <td align="center" bgcolor="#E6E6E6" class="border font_bord">ยอดคงเหลือ</td>
    <td align="center" bgcolor="#E6E6E6" class="border font_bord">ที่ขอเบิก</td>
  </tr>
 <?php  do {  $i=0;

mysql_select_db($database_vmi, $vmi);
$query_rs_vaccine = "select t.*,v.vaccine_name,l.vaccine_factor,v.doses,t2.last_use as last_use2,t2.remain as remain2,t2.target as target2 from vaccine_take t left outer join vaccines v on v.id=t.vaccine_id left outer join vaccine_list l on l.vaccine_id=v.id and l.vaccine_project='".$row_rs_project['id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' left outer join vaccine_take t2 on t2.vaccine_id=t.vaccine_id and concat(t2.year,'-',t2.month)='".$lastmonth2."' and t2.hospcode='".$_SESSION['hospcode']."' where t.vaccine_project='".$row_rs_project['id']."' and t.hospcode='".$_SESSION['hospcode']."' and ".$condition." group by t.vaccine_id";
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
$query_rs_vaccine2 = "select t.*,v.vaccine_name,l.vaccine_factor,v.doses,t2.last_use as last_use2,t2.remain as remain2,t2.target as target2 from vaccine_take t left outer join vaccines v on v.id=t.vaccine_id left outer join vaccine_list l on l.vaccine_id=v.id and l.vaccine_project='".$row_rs_project['id']."' and l.chwpart='".$_SESSION['chwpart']."' and l.amppart='".$_SESSION['amppart']."' left outer join vaccine_take t2 on t2.vaccine_id=t.vaccine_id and concat(t2.year,'-',t2.month)='".$lastmonth2."' and t2.hospcode='".$_SESSION['hospcode']."'  where t.vaccine_project='".$row_rs_project['id']."' and t.hospcode='".$_SESSION['hospcode']."' and ".$condition." group by t.vaccine_id limit 1,".($totalRows_rs_vaccine-1);
$rs_vaccine2 = mysql_query($query_rs_vaccine2, $vmi) or die(mysql_error());
$row_rs_vaccine2 = mysql_fetch_assoc($rs_vaccine2);
$totalRows_rs_vaccine2 = mysql_num_rows($rs_vaccine2);

}
$i++;
if($totalRows_rs_vaccine<>0){

 ?> <tr>
    
    <td rowspan="<?php echo $totalRows_rs_vaccine; ?>" align="center" bgcolor="#FFFFFF" class="border font_bord font16"><?php echo $row_rs_project['vaccine_project']; ?></td>
    <td align="left" bgcolor="#FFFFFF" class="border" ><?php echo $i.". ".$row_rs_vaccine['vaccine_name']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['target']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php $request= ceil(($row_rs_vaccine['target']*$row_rs_vaccine['vaccine_factor'])/$row_rs_vaccine['doses']); echo $request; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['remain']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border font_bord" style="color:#FF0000"><?php if($row_rs_vaccine['remain']>=$request){ echo 0;} else { echo $request-$row_rs_vaccine['remain'];}?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['last_visit']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php echo $row_rs_vaccine['last_use']; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="border"><?php if($row_rs_vaccine['last_use']==0){ echo "0";}else{ echo str_replace('.00','',number_format((((($row_rs_vaccine['last_use']*$row_rs_vaccine['doses'])-$row_rs_vaccine['last_visit'])/($row_rs_vaccine['last_use']*$row_rs_vaccine['doses']))*100),2))."%"; } ?></td>
       <?php  if((($row_rs_setting['vaccine_method']==1)&&(ltrim(date('d'),'0')<=($row_rs_setting['vaccine_end_date']+10)))||(($row_rs_setting['vaccine_method']==2)&&(ltrim(date('d'),'0')<=$row_rs_setting['end_date']))){ if($row_rs_drug_confirm['confirm']==NULL){ ?>
        <td align="center" bgcolor="#FFFFFF" class="border" style="cursor:pointer" onClick="poppage('vaccine_take_add.php?id=<?php echo $row_rs_vaccine['id']; ?>&do=edit','800','500','','show','vaccine_take_list.php','drug_take_list','');"><img src="images/mail_new.png" width="25" height="25"></td>
                <?php }} ?>

        <td align="left" bgcolor="#FFFFFF" class="border"><?php echo msg($row_rs_vaccine['last_visit'],$row_rs_vaccine['last_use'],$row_rs_vaccine['doses'],$row_rs_vaccine['last_use2'],$dispen,$row_rs_vaccine['remain']); echo $msg; if($row_rs_vaccine['remark']!=""){echo "<br>- ".$row_rs_vaccine['remark'];} /*echo "<br>last_visit=".$row_rs_vaccine['last_visit'].",last_use=".$row_rs_vaccine['last_use'].",doses=".$row_rs_vaccine['doses'].",last_use2=".$row_rs_vaccine['last_use2'].",request2=".$request2.",remain=".$row_rs_vaccine['remain']; */  ?></td>
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
       <?php  if((($row_rs_setting['vaccine_method']==1)&&(ltrim(date('d'),'0')<=($row_rs_setting['vaccine_end_date']+10)))||(($row_rs_setting['vaccine_method']==2)&&(ltrim(date('d'),'0')<=$row_rs_setting['end_date']))){ if($row_rs_drug_confirm['confirm']==NULL){ ?>
    <td align="center" bgcolor="#FFFFFF" class="border" style="cursor:pointer" onClick="poppage('vaccine_take_add.php?id=<?php echo $row_rs_vaccine2['id']; ?>&do=edit','800','500','','show','vaccine_take_list.php','drug_take_list','');"><img src="images/mail_new.png" width="25" height="25"></td>
    	<?php }} ?>

    <td align="center" bgcolor="#FFFFFF" class="border"><?php echo msg($row_rs_vaccine2['last_visit'],$row_rs_vaccine2['last_use'],$row_rs_vaccine2['doses'],$row_rs_vaccine2['last_use2'],$dispen2,$row_rs_vaccine2['remain']);  echo $msg; if($row_rs_vaccine2['remark']!=""){echo "<br>- ".$row_rs_vaccine2['remark']; } /*echo "<br>last_visit=".$row_rs_vaccine2['last_visit'].",last_use=".$row_rs_vaccine2['last_use'].",doses=".$row_rs_vaccine2['doses'].",last_use2=".$row_rs_vaccine2['last_use2'].",request2=".$request3.",remain=".$row_rs_vaccine2['remain']; */ ?></td>
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
<div style="background-color: #E1E1E1; border:solid 1px #999999; margin-top:10px; width:150px; padding:5px; cursor:pointer" onClick="poppage2('vaccine_take_roll_print.php?deptcode=<?php echo $_SESSION['hospcode']; ?>&month=<?php echo $thismonth2; ?>','90%','90%');" align="center" class="thsan-light font15"><img src="images/Printer and Fax.png" width="27" height="27" border="0" align="absmiddle">พิมพ์แบบ ว3/1</div>
</body>
</html>
<?php
mysql_free_result($rs_project);

mysql_free_result($rs_setting);

mysql_free_result($rs_vaccine1);

?>
