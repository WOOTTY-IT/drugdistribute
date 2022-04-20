<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>
<script>
$(document).ready(function() {
	$('#indicator1').hide();

//+++++++++++ hn press spacebar ++++++++++++//
        $('#cid').bind('keyup', function(e) {
            // ลบข้อมูลช่อง Q
            
            
            if(e.which == '13'){ //enter
                
                $('#indicator1').show();
                
                $("#detail").load('med_reconcile_result.php?cid='+$('#cid').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator1').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });
            }
            if (e.which == 32){//space bar
             // alert();
                $('#cid').val("");
//++++++++++++ modal ++++++++++++++//
            
			$('#modal-body-lg').load('datacenter_patient_search.php',function(responseTxt, statusTxt, xhr){
                $('.modal-title').html('<i class="fas fa-fingerprint"></i>&nbsp;ค้นหาผู้ป่วย');
                $('#myModal').modal({show:true});                
			});           
			
//++++++++++++ modal ++++++++++++++//

            }

        
    });    
//datepicker Thai
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
     
    // กรณีใช้แบบ inline
  /*  $("#testdate4").datetimepicker({
        timepicker:false,
        format:'d-m-Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        inline:true  
    });    */   
     
     
    // กรณีใช้แบบ input
    $("#vstdate").datetimepicker({
        timepicker:false,
        format:'d/m/Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear();  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $input.val(fulldateTH);
        },
    });       
    // กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
    $("#vstdate").on("mouseenter mouseleave",function(e){
        var dateValue=$(this).val();
        if(dateValue!=""){
                var arr_date=dateValue.split("/"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
                // ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
                //  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
                if(e.type=="mouseenter"){
                    var yearT=arr_date[2]-543;
                }       
                if(e.type=="mouseleave"){
                    var yearT=parseInt(arr_date[2])+543;
                }   
                dateValue=dateValue.replace(arr_date[2],yearT);
                $(this).val(dateValue);                                                 
        }       
    });
	
});

//ใส่ 0 ข้างหน้าตัวเลข
function leftPad(value, length) { 
    return ('0'.repeat(length) + value).slice(-length); 
}
// alert load
function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}

function med_reconcile_load(){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?hn='+$('#hn').val()+'&vstdate='+encodeURIComponent($('#vstdate').val()), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });	
	}

function drug_del(id,action){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?id='+id+'&action='+action+'&vstdate='+encodeURIComponent($('#vstdate').val())+'&hn='+$('#hn').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });			
	}

function detail_load(cid){
	$('#indicator1').show();
	$("#detail").load('med_reconcile_result.php?cid='+cid, function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")
		$('#indicator1').hide();
		  //alert("External content loaded successfully!");
		if(statusTxt == "error")
		  alert("Error: " + xhr.status + ": " + xhr.statusText);
  	});
}	

</script>

<style>
html,body{overflow:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
	
.modal-header-success {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #5cb85c;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
     border-top-left-radius: 4px;
     border-top-right-radius: 4px;
}
.modal-header-gray {
    color: #666;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #CCC;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
     border-top-left-radius: 4px;
     border-top-right-radius: 4px;
}

.modal-header-warning {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #f0ad4e;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-danger {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #d9534f;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-info {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #5bc0de;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-primary {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #428bca;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;

}
body.modal-open, .modal-open .navbar-fixed-top, .modal-open .navbar-fixed-bottom {
    padding-right: 0px !important;
    overflow-y: auto;
}

.overlay{
    position: absolute;
    top:40%;
    left:0px;
    width: 100%;
    height: 100%;
    opacity: .5;
}
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white navbar-fixed-top"  >
  <!-- Navbar content -->
  <div class="form-row">
      <div class="col-sm-auto card-title font20 font_bord"><i class="fas fa-laptop-medical" style="font-size: 25px;"></i>&ensp;HOSxp&ensp;Medication Reconciliation</div>
      &emsp;&emsp;
      <div class="col-sm-auto"><i class="far fa-calendar-alt" style="font-size: 28px;"></i></div>
      
      <div class="col-sm-auto"><input type="text" name="vstdate" id="vstdate" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control form-control-plaintext thfont font16 font-weight-bolder" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px; background-color:#E6E6FA">
      </div>
      
      <div class="col-sm-auto"><input name="cid" placeholder="cid" type="search" id="cid" class="form-control" style=" width: 200px; height: 30px;" maxlength="13" /></div>
  </div>
</nav>
<div id="indicator1" class="p-3 overlay text-center">
<button class="btn btn-primary" id="loader">
  <span class="spinner-border spinner-border-sm"></span>
  กรุณารอสักครู่..
</button>
</div>

<div id="detail"></div>
    
<!-- large MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-gray">
<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fingerprint"></i>&ensp;ค้นหาผู้ป่วย</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>            </div>
            <div class="modal-body" id="modal-body-lg" style=" padding:0px;">

            </div>
        </div>
    </div>
</div>
<!-- large MODAL --></body>
</html>