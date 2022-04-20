<?php require_once('Connections/vmi.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ThaiCreate.Com PHP & Text To MySQL</title>
<style type="text/css">
body {
	background-color: #ededed;
}
</style>
</head>
<body>
<p>อัพโหลดข้อมูลเสร็จเรียบร้อยแล้ว </p>
<?
	if(move_uploaded_file($_FILES["fileCVS"]["tmp_name"],"upload/DRUG_OPD.txt")){
$strFileName = "DRUG_OPD.txt";
$objFopen = fopen("upload/".$strFileName, 'r');
if ($objFopen) {
    while (!feof($objFopen)) {
        $file = fgets($objFopen, 4096);
		$aa=explode("|",$file);
		if($aa[0]!=""){ $hospcode=$aa[0]; }
	//ค้นหา record ว่าเคยมีไหม
	mysql_select_db($database_vmi, $vmi);
	$query_search = "SELECT * FROM drug_import where hospcode='$aa[0]' and pid='$aa[1]' and seq='$aa[2]' and date_serv='".substr($aa[3],0,4)."-".substr($aa[3],4,2)."-".substr($aa[3],6,2)."' and didstd = '".$aa[5]."' "  ;
	$search = mysql_query($query_search, $vmi) or die(mysql_error());
	$row_search = mysql_fetch_assoc($search);
	$totalRows_search = mysql_num_rows($search);
	
	//ถ้าไม่พบรายการ
	if($totalRows_search==0){
		if($aa[3]!=""){
		$date_serv=	substr($aa[3],0,4)."-".substr($aa[3],4,2)."-".substr($aa[3],6,2);
		}else {
		$date_serv="0000-00-00";	
		}
		
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into drug_import (hospcode,pid,seq,date_serv,clinic,didstd,dname,amount,unit,unit_packing,drugprice,drugcost,provider,d_update,date_upload) value ('".$aa[0]."','".$aa[1]."','".$aa[2]."','".$date_serv."','".$aa[4]."','".$aa[5]."','".$aa[6]."','".$aa[7]."','".$aa[8]."','".$aa[9]."','".$aa[10]."','".$aa[11]."','".$aa[12]."','".substr($aa[13],0,4)."-".substr($aa[13],4,2)."-".substr($aa[13],6,2)." ".substr($aa[13],8,2).":".substr($aa[13],10,2).":".substr($aa[13],12 ,2)."',NOW())";
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
	}
	/////////////////////////////////
	//ถ้าพบรายการ
		if($totalRows_search<>0){
		mysql_select_db($database_vmi, $vmi);
		$query_update = "update drug_import set dname='$aa[6]',amount='$aa[7]',unit='$aa[8]',unit_packing='$aa[9]',drugprice='$aa[10]',drugcost='$aa[11]',d_update='".substr($aa[13],0,4)."-".substr($aa[13],4,2)."-".substr($aa[13],6,2)." ".substr($aa[13],8,2).":".substr($aa[13],10,2).":".substr($aa[13],12 ,2)."',date_upload=NOW() where hospcode='$aa[0]' and pid='$aa[1]' and seq='$aa[2]' and  didstd='".$aa[5]."'";
		$update = mysql_query($query_update, $vmi) or die(mysql_error());
		}
	}
	
	mysql_select_db($database_vmi, $vmi);
	$query_delete = "delete FROM drug_import where amount=0";
	$delete = mysql_query($query_delete, $vmi) or die(mysql_error());

    fclose($objFopen);
}


//คำนวณ rate
	$today=date('Y-m-d');
	mysql_select_db($database_vmi, $vmi);
	$query_search2 = "SELECT * FROM drug_import where hospcode='".$hospcode."' and date_upload='$today' group by didstd"  ;
	$search2 = mysql_query($query_search2, $vmi) or die(mysql_error());
	$row_search2 = mysql_fetch_assoc($search2);
	$totalRows_search2 = mysql_num_rows($search2);
do{	
//ค้นหาการ group เดือนและ ปี
	mysql_select_db($database_vmi, $vmi);
	$query_search3 = "select SUBSTR(date_serv,1,4) as year_rate,SUBSTR(date_serv,6,2) as month_rate,sum(amount) as sum_qty from drug_import where didstd='$row_search2[didstd]' and hospcode='$hospcode' group by SUBSTR(date_serv,1,7)"  ;
	$search3 = mysql_query($query_search3, $vmi) or die(mysql_error());
	$row_search3 = mysql_fetch_assoc($search3);
	$totalRows_search3 = mysql_num_rows($search3);
		
		do{
	mysql_select_db($database_vmi, $vmi);
	$query_search4 = "select * from month_rate where hospcode='$hospcode' and month_rate='".$row_search3['month_rate']."' and year_rate='".$row_search3['year_rate']."' and didstd='".$row_search2['didstd']."' "  ;
	$search4 = mysql_query($query_search4, $vmi) or die(mysql_error());
	$row_search4 = mysql_fetch_assoc($search4);
	$totalRows_search4 = mysql_num_rows($search4);
		if($totalRows_search4==0){
		mysql_select_db($database_vmi, $vmi);
		$query_insert = "insert into month_rate (year_rate,month_rate,hospcode,didstd,qty) select SUBSTR(date_serv,1,4),SUBSTR(date_serv,6,2),'$hospcode','$row_search2[didstd]',sum(amount) from drug_import where didstd='$row_search2[didstd]' and hospcode='$hospcode' group by SUBSTR(date_serv,1,7) "  ;
		$insert = mysql_query($query_insert, $vmi) or die(mysql_error());
		}
		else{
		$query_update = "update month_rate set qty=(select sum(amount) as sum_qty from drug_import where didstd='$row_search2[didstd]' and hospcode='$hospcode' and SUBSTR(date_serv,1,4)='$row_search3[year_rate]' and SUBSTR(date_serv,6,2)='$row_search3[month_rate]') where year_rate='$row_search3[year_rate]' and month_rate='$row_search3[month_rate]' and hospcode='$hospcode' and didstd='$row_search2[didstd]' "  ;
		$update = mysql_query($query_update, $vmi) or die(mysql_error());		
		}
		mysql_free_result($search4);
		}while(	$row_search3 = mysql_fetch_assoc($search3));

mysql_free_result($search3);
}while(	$row_search2 = mysql_fetch_assoc($search2));
/*if($delete){
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=analyze.php\" />";

	}
*/
	}
?>
</table>
</body>
</html>
<?php
    mysql_free_result($search);

    mysql_free_result($search2);
?>
