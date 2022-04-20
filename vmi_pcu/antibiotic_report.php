<?php
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>

<?php
mysql_select_db($database_vmi, $vmi);
$query_atb = "select d.drug_name,s.stdcode from drugs d left outer join drug_stdcode s on s.working_code=d.working_code where substr(stdcode,1,19) in (select stdcode from antibiotic ) group by substr(s.stdcode,1,19) order by d.drug_name";
$atb = mysql_query($query_atb, $vmi) or die(mysql_error());
$row_atb = mysql_fetch_assoc($atb);
$totalRows_atb = mysql_num_rows($atb);

mysql_select_db($database_vmi, $vmi);
$query_dep = "select l.dept_code,g.dept_group_name from dept_list l left outer join dept_group g on g.dept_group_id=l.dept_group left outer join hospcode h on h.hospcode=g.hospmain where h.chwpart='".$_SESSION['chwpart']."' and h.amppart='".$_SESSION['amppart']."'";
//echo $query_dep;
$dep = mysql_query($query_dep, $vmi) or die(mysql_error());
$row_dep = mysql_fetch_assoc($dep);
$totalRows_dep = mysql_num_rows($dep);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Antibiotic  Report</title>
<?php include('java_css_online.php'); ?>
<script>
$(document).ready(function(){
        $('#indicator').hide();
    
    $('#search').click(function(){
        $('#indicator').show();
                $("#result").load('antibiotic_report_list.php?datestart='+$('#datestart').val()+'&dateend='+$('#dateend').val()+'&hospcode='+$('#hospcode').val()+'&atb='+$('#atb_drug').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });
});
    </script>
<style>
    html,body{overflow:hidden}
    .spinner {
  border: 1px solid;
  position: fixed;
  z-index: 1;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 50 50'%3E%3Cpath d='M28.43 6.378C18.27 4.586 8.58 11.37 6.788 21.533c-1.791 10.161 4.994 19.851 15.155 21.643l.707-4.006C14.7 37.768 9.392 30.189 10.794 22.24c1.401-7.95 8.981-13.258 16.93-11.856l.707-4.006z'%3E%3CanimateTransform attributeType='xml' attributeName='transform' type='rotate' from='0 25 25' to='360 25 25' dur='0.6s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E") center / 50px no-repeat;
}
</style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;รายงานการใช้ยาปฏิชีวนะใน รพ.สต.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">

    </div>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh; padding: 10px;">
<div class="card m-2" id="search-tool">
    <div class="card-body">
        <div class="row">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกช่วงวันที่</label>
            <div class="col-sm-4">
                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	

            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div>
        </div>
        <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">พื้นที่รับผิดชอบ</label>
                <div class="col-sm-4" >
                    <select id="hospcode" name="hospcode" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <?php do{ ?>
                        <option value="<?php echo $row_dep['dept_code']; ?>" ><?php echo $row_dep['dept_group_name']; ?></option>
                        <?php }while($row_dep = mysql_fetch_assoc($dep)); ?>                        
                    </select>
                </div>
        </div>
        
        <div class="row mt-2">
                <label class="col-form-label col-form-label-sm col-sm-2">รายการยา</label>
                <div class="col-sm-4" >
                    <select id="atb_drug" name="atb_drug" class="form-control">
                        <option value="" >ทั้งหมด</option>
                        <?php do{ ?>
                        <option value="<?php echo $row_atb['stdcode']; ?>" ><?php echo $row_atb['drug_name']; ?></option>
                        <?php }while($row_atb = mysql_fetch_assoc($atb)); ?>
                    </select>
                </div>                
                <div class="col-sm-2" ><button class="btn btn-primary" id="search">ค้นหา</button></div>

        </div> 


                    
</div>
</div>
    <div id="result" class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:65vh;"></div>

<div class="spinner" id="indicator"></div>
<div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<script type="text/javascript" src="include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="include/datepicker/css/daterangepicker.css" />
<script type="text/javascript">
$(function() {

    var start = moment().subtract(0, 'days');
    var end = moment().subtract(0, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		$('#datestart').val(start.format('Y-MM-DD'));
		$('#dateend').val(end.format('Y-MM-DD'));

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
		lang:'th',
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'ย้อนหลัง 7 วัน': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
           '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'ปีงบประมาณนี้':[moment([new Date().getFullYear(), 9, 01]).subtract(1,'year'),moment([new Date().getFullYear(), 8, 30])],
		   'ปีงบประมาณก่อน':[moment([new Date().getFullYear(), 9, 01]).subtract(2,'year'),moment([new Date().getFullYear(), 8, 30]).subtract(1,'year')]
        }
    }, cb);
	
    cb(start, end);
	


});
</script>
    </div>

</body>
</html>
<?php mysql_free_result($atb);?>
<?php mysql_free_result($dep); ?>
