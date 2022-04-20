<?php require_once('Connections/datacenter.php'); ?>
<?php
include('include/function.php');
function dateThai3($date){
	if($date!=""){
	$_month_name = array("01"=>"1","02"=>"2","03"=>"3","04"=>"4","05"=>"5","06"=>"6","07"=>"7","08"=>"8","09"=>"9","10"=>"10","11"=>"11","12"=>"12");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)."/".$_month_name[$mm]."/".substr($yy,2,2)." ".$time;
	return $dateT;
		}
	 }


mysql_select_db($database_datacenter, $datacenter);
$query_rs_patient = "select DATE_FORMAT(CURDATE(), '%Y') - DATE_FORMAT(p.birthday, '%Y') - (DATE_FORMAT(CURDATE(), '00-%m-%d') < DATE_FORMAT(p.birthday, '00-%m-%d')) AS years,p.hn,p.pname,p.fname,p.lname,p.fathername,p.mathername,p.cid,p.addrpart,p.moopart,t.full_name from datacenter_patient p left outer join thaiaddress t on t.chwpart=p.chwpart and t.amppart=p.amppart and t.tmbpart=p.tmbpart where cid ='".$_GET['cid']."' group by cid";
//echo $query_rs_patient;
$rs_patient = mysql_query($query_rs_patient, $datacenter) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

mysql_select_db($database_datacenter, $datacenter);
$query_rs_visit = "select v.vstdate,v.vn,v.hospcode,h.name as hospname from datacenter_vn_stat v left outer join hospcode h on h.hospcode=v.hospcode left outer join datacenter_drug_opd d on d.vn=v.vn and d.hospcode=v.hospcode where v.cid ='".$_GET['cid']."' and d.hos_guid !='' group by v.vn order by v.vn DESC";
//echo $query_rs_patient;
$rs_visit = mysql_query($query_rs_visit, $datacenter) or die(mysql_error());
$row_rs_visit = mysql_fetch_assoc($rs_visit);
$totalRows_rs_visit = mysql_num_rows($rs_visit);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<?php include('java_css_online.php'); ?>
<script>
function drug_load(vn,hospcode){
	$('#indicator1').show();
	$("#second").load('med_reconcile_drug.php?vn='+vn+'&hospcode='+hospcode, function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")
		$('#indicator1').hide();
		  //alert("External content loaded successfully!");
		if(statusTxt == "error")
		  alert("Error: " + xhr.status + ": " + xhr.statusText);
  	});
}	
</script>
<style>
body{overflow: hidden}

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
.splitter {
    width: 100%;
    height: calc(100vh - 100px);
    display: flex;
}

#separator {
    cursor: col-resize;
    background-color: #aaa;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='30'><path d='M2 0 v30 M5 0 v30 M8 0 v30' fill='none' stroke='black'/></svg>");
    background-repeat: no-repeat;
    background-position: center;
    width: 5px;
    height: 100%;

/* prevent browser's built-in drag from interfering */
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#first {
    /*background-color: #dde;*/
    width: 20%;
    height: 100%;
    min-width: 200px;
}

#second {
    /*background-color: #eee;*/
    width: 80%;
    height: 100%;
    min-width: 10px;
}

</style>
</head>

<body>

<div id="profile" class="p-3 bg-light" style="height: 100px;border-bottom: solid 1px #A1A1A1">
	<div><strong>ชื่อ-นามสกุล :</strong>&nbsp;<?php echo $row_rs_patient['pname'].$row_rs_patient['fname']." ".$row_rs_patient['lname']; ?>&ensp;<strong>อายุ :</strong>&nbsp;<?php echo $row_rs_patient['years']; ?>&nbsp;<strong>ปี</strong></strong></div>
	<div><strong>CID :</strong>&nbsp;<?php echo $row_rs_patient['cid']; ?></div>
	<div><strong>ที่อยู่ :</strong>&nbsp;<?php echo $row_rs_patient['addrpart']." หมู่ ".$row_rs_patient['moopart']." ".$row_rs_patient['full_name']; ?></div>
</div>
<div class="splitter">
    <div id="first" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:cal(100vh-250px);">
		<div class="text-white text-center" style=" padding:10px; background-color: #939DA0">วันที่รับยา</div>

		<div class="list-group list-group-flush border-bottom" >
					
		  <?php do { ?>
			<button type="button" class="list-group-item list-group-item-action" onClick="drug_load('<?php echo $row_rs_visit['vn']; ?>','<?php echo $row_rs_visit['hospcode']; ?>')">
				<?php echo date_db2th($row_rs_visit['vstdate'])."</br>"."<span style=\"font-size:12px\">".$row_rs_visit['hospname']."</span>"; ?>
			</button>
			<?php } while($row_rs_visit = mysql_fetch_assoc($rs_visit)); ?>
		</div>	
	</div>
    <div id="separator" ></div>
    <div id="second" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:cal(100vh-250px);"></div>
</div>

<script>

// function is used for dragging and moving
function dragElement( element, direction)
{
    var   md; // remember mouse down info
    const first  = document.getElementById("first");
    const second = document.getElementById("second");
    
    element.onmousedown = onMouseDown;
    
    function onMouseDown( e )
    {
    //console.log("mouse down: " + e.clientX);
    md = {e,
          offsetLeft:  element.offsetLeft,
          offsetTop:   element.offsetTop,
          firstWidth:  first.offsetWidth,
          secondWidth: second.offsetWidth};
    document.onmousemove = onMouseMove;
    document.onmouseup = () => { 
        //console.log("mouse up");
        document.onmousemove = document.onmouseup = null;
    }
    }
    
    function onMouseMove( e )
    {
    //console.log("mouse move: " + e.clientX);
    var delta = {x: e.clientX - md.e.clientX,
                 y: e.clientY - md.e.clientY};
    
    if (direction === "H" ) // Horizontal
    {
        // prevent negative-sized elements
        delta.x = Math.min(Math.max(delta.x, -md.firstWidth),
                   md.secondWidth);
        
        element.style.left = md.offsetLeft + delta.x + "px";
        first.style.width = (md.firstWidth + delta.x) + "px";
        second.style.width = (md.secondWidth - delta.x) + "px";
    }
    }
}


dragElement( document.getElementById("separator"), "H" );

</script>
</body>
</html>
<?php
mysql_free_result($rs_patient);
mysql_free_result($rs_visit);
?>