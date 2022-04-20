<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?
	function mthai($month,$year){
	switch($month) {
	case "01":
	$thaidate= "ม.ค. ".substr($year+543,2,2);
	break;
	case "02":
	$thaidate="ก.พ.".substr($year+543,2,2);
	break;
	case "03":
	$thaidate="มี.ค. ".substr($year+543,2,2);
	break;
	case "04":
	$thaidate="เม.ย. ".substr($year+543,2,2);
	break;
	case "05":
	$thaidate="พ.ค. ".substr($year+543,2,2);
	break;
	case "06":
	$thaidate="มิ.ย. ".substr($year+543,2,2);
	break;
	case "07":
	$thaidate="ก.ค. ".substr($year+543,2,2);
	break;
	case "08":
	$thaidate= "ส.ค. ".substr($year+543,2,2);
	break;
	case "09":
	$thaidate= "ก.ย. ".substr($year+543,2,2);
	break;
	case "10":
	$thaidate= "ต.ค. ".substr($year+543,2,2);
	break;
	case "11":
	$thaidate= "พ.ย. ".substr($year+543,2,2);
	break;
	case "12":
	$thaidate= "ธ.ค. ".substr($year+543,2,2);
	break;
	}
	return $thaidate;
	}
$num_w=array("1","2","3","4","5","6","7");
$thai_w=array("จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์","อาทิตย์");
$thai_n=array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
$w=$thai_w[date("w")];
$d=date("d");
$n=$thai_n[date("n") -1];
$y=date("Y") +543;
$t=date("àÇÅÒ H ¹ÒÌÔ¡Ò i ¹Ò·Õ s ÇÔ¹Ò·Õ");

	function DateDiff($strDate1,$strDate2)
	 {
				return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	 }
	 
	 function dateThai($date){
	$_month_name = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)." ".$_month_name[$mm]." ".$yy." ".$time;
	return $dateT;
	}
	
	 function datethai2($iorder)
	 {
	   $y1=substr($iorder,-8,4);$m1=substr($iorder,-4,2);$d1=substr($iorder,-2,2);
	   return $y1."-".$m1."-".$d1;	
	 }
	 function monthThai($date){
	$_month_name = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=$_month_name[$mm]." ".$yy." ".$time;
	return $dateT;
	}

function date_th2db($date){

	$date1=explode('/',$date);
	$edate=($date1[2]-543)."-".$date1[1]."-".$date1[0];
	return $edate;

}
function date_db2th($date){
	if($date!=""){
	$date1=explode('-',$date);
	$edate=$date1[2]."/".$date1[1]."/".($date1[0]+543);
	return $edate;
	}
}	
?>