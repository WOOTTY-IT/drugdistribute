﻿<?php require_once('Connections/vmi.php'); ?>
<?php
mysql_select_db($database_vmi, $vmi);
$query_rs_con = "select * from setting";
$rs_con = mysql_query($query_rs_con, $vmi) or die(mysql_error());
$row_rs_con = mysql_fetch_assoc($rs_con);
$totalRows_rs_con = mysql_num_rows($rs_con);
 
/*do{
$li = file('http://vmi.mhhos.go.th/tube.txt');
foreach($li as $value){
	$value1=explode('|',$value);
    if($value1[0]==md5('kohrx'.$row_rs_con['chwpart'].$row_rs_con['amppart'].'whatdoyoudo').md5(md5('kohrx'.$row_rs_con['chwpart'].$row_rs_con['amppart'].'whatdoyoudo'))&&($value1[1]>=date('Y-m-d'))){$lio=1;}
	}
if($lio!=1){
	echo "<div align=\"center\" style=\"padding:20px; font-size:20px; color:red;\"><img src=\"https://www.araratrsl.com.au/wp-content/uploads/2014/09/icon-members-only.jpg\" width=\"40%\" height=\"auto\"\></br>คุณไม่มีสิทธิใช้โปรแกรม  กรุณาติดต่อเจ้าของโปรแกรม</div>";
	exit();
	}
}*/
while($row_rs_con = mysql_fetch_assoc($rs_con));
mysql_free_result($rs_con);
?>

<link rel="icon" sizes="30x40" href="images/drug_icon.png" />
<script  src="include/ajax_framework.js"></script>
<script src="include/jquery.maskedinput.js" type="text/javascript"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="include/jquery-ui-1.8.10.offset.datepicker.min.js"></script>
<script type="text/javascript" src="include/ui.datepicker-th.js">
</script>
<script type="text/javascript" src="include/jquery.form.js"></script>
 
<!--Jquery Menu-->
<link rel="stylesheet" href="include/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="include/scripts/gettheme.js"></script>
<!--
<script type="text/javascript" src="include/scripts/jquery-1.10.1.min.js"></script>
-->
    <script type="text/javascript" src="include/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="include/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="include/jqwidgets/jqxcheckbox.js"></script>

<!-- Click tr and Keep color -->
<script type="text/javascript">
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

	
function changbgcolor(id)
{
	var tr = document.getElementsByClassName('grid2')
	for(var i=0;i<tr.length;i++)
	{
		if(tr[i].id == id)
		{
			document.getElementById(tr[i].id).style.background= '#FBAA6F';
		}
		else
		{
		document.getElementById(tr[i].id).style.background='';
		}
	}
}

function resutName(drug,detail,pack,price)
	{
		if(drug!=""){
		switch(drug)
		{
			<?
			mysql_select_db($database_vmi, $vmi);
			$strSQL = "SELECT * from drugs where chwpart='".$_SESSION['chwpart']."' and amppart='".$_SESSION['amppart']."' ";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["working_code"];?>":
		document.getElementById(detail).value = "<?php print "x ".$objResult["pack_ratio"]." (".$objResult["sale_unit"].")";?>";
		
		document.getElementById(pack).value='<?php print $objResult["pack_ratio"];?>';
		document.getElementById(price).value="<?php print $objResult["buy_unit_cost"];?>";
		break;
			<?
			}
			?>
		}
		}
	}
</script>	
<!--//////////////////////////////////////// -->
<!-- fancybox -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="include/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="include/jquery.fancybox.js?v=2.1.3"></script>
	<link rel="stylesheet" type="text/css" href="include/jquery.fancybox.css?v=2.1.2" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="include/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="include/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="include/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="include/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="include/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

	<script type="text/javascript">
$(document).ready(function() {
	$('#fancybox').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		scrolling : 'no',
		    beforeShow  : function() {
                $('body, html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('body, html').css('overflowY','auto');
            },
		beforeClose :function(){	
		inputform();
		}
	});
	$('#fancybox2').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		scrolling : 'no',
		    beforeShow  : function() {
                $('body, html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('body, html').css('overflowY','auto');
            }
	});
	$('#fancybox3').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		scrolling : 'no',
				    beforeShow  : function() {
                $('body, html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('body, html').css('overflowY','auto');
            },
		beforeClose :function(){formSubmit('AN','displayIndiv','indicator');}
	});

		$('#fancybox4').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		scrolling : 'no',
				    beforeShow  : function() {
                $('body, html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('body, html').css('overflowY','auto');
            }
	});

		$('#fancybox5').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		scrolling : 'no',
				    beforeShow  : function() {
                $('body, html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('body, html').css('overflowY','auto');
            }
	});

});	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
    
<SCRIPT language=Javascript>
//เรียก popup string
function OpenPopup(URL,w,h,str)
	{
		data=$('#id').val();
		data2=$('#do').val();
	$.colorbox({
	href:URL+'?data='+data+'&data2='+data2+str,
				iframe:true,
				width:w, 
				height:h,
				scrolling:true,
				onOpen : function () {
		$('html').css('overflowY','hidden');},
				onCleanup :function(){
				$('html').css('overflowY','auto');}		 
	});
	}
	
//เปรียบเทียบวันที่
function addLeadingZeros (n, length)
{
    var str = (n > 0 ? n : -n) + "";
    var zeros = "";
    for (var i = length - str.length; i > 0; i--)
        zeros += "0";
    zeros += str;
    return n >= 0 ? zeros : "-" + zeros;
}
var now = new Date(); 
var dateString = addLeadingZeros(now.getDate(),2) + "/" + addLeadingZeros((now.getMonth()+1),2) + "/" + (now.getFullYear()+543); 
       <!-- ให้ key ได้เฉพาะตัวเลข
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </SCRIPT>
<script language="javascript">

function visitdate(vstdate)
	{	
	window.location.href="index.php?vstdate="+vstdate;
	}

$(document).ready(function($){ 
  	$("#date1").mask("99/99/9999");   
  	$("#date2").mask("99/99/9999");   

	});


function formSubmit(sID,form,URL,displayDiv,indicator,eID) {
	if(sID!=""){
	document.getElementById('do').value=sID;		
	}
	if(eID!=""){
	document.getElementById('id').value=eID;
		}		
	var data = getFormData(form);
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
	
	
function formSubmit2(sID,displayDiv,indicator,eID) {
	if(sID!=""){
	document.getElementById('do2').value=sID;
	}
	var URL = "opitem.php";			
	if(eID!=""){
	document.getElementById('id2').value=eID;
		}		
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}

function formSubmit3(sID,displayDiv,indicator,eID) {
	if(sID!=""){
	document.getElementById('do4').value=sID;
	}
	if(sID=="save"){
	var URL = "dispen_save.php";}
	if(sID=="save_ipd"){
	var URL = "dispen_save_ipd.php";}
			
	if(eID!=""){
	document.getElementById('id4').value=eID;
		}		
	var data = getFormData("form3");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}

function formSubmit4(sID,displayDiv,indicator,eID,edate) {
	if(sID!=""){
	document.getElementById('do4').value=sID;		
	if(sID=="HN"){
	var URL = "detail.php"+eID;		
	}
		
	if(sID=="AN"){
	var URL = "profile_list.php";		
		}

	if(sID=="Q"){
	var URL = "detail.php";		
		}
	if(sID=="first"){
	var URL = "opitem.php";
		}
	else if(sID=="insert"){
	var URL = "drug_save.php";		
		}
	}
	if(eID!=""){
	document.getElementById('id4').value=eID;
		}
	document.getElementById('date1').value=	edate;	
	var data = getFormData("form4");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}

function formSubmit5(sID,displayDiv,indicator,url,eID) {
	if(sID!=""){
	document.getElementById('do').value=sID;		
	}
	if(eID!=""){
	document.getElementById('id').value=eID;
	}
	var URL=url;		
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
	
	
document.onkeydown = chkEvent 
	function chkEvent(e) {
		var keycode;
		if (window.event) keycode = window.event.keyCode; //*** for IE ***//
		else if (e) keycode = e.which; //*** for Firefox ***//
		if(keycode==13)
		{
			return false;
		}
	}

    function setNextFocus(objId){
        if (event.keyCode == 13){
        if(objId!="Submit"){
	        var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
            }
        }
		if(objId=="Submit"){
			formSubmit3('save','displayIndiv','indicator','1');
			}
		}
	}



function drugcheck(a){
	
	var arr1 = window.document.getElementsByName("qty[]");
	var arr2 = window.document.getElementsByName("icode[]");
	var arr3 = window.document.getElementsByName("qty_rcv[]");

var arr_length = arr1.length;
for(i = 0; i < arr_length; i++){
		if(arr2[i].value==a){
			if(parseFloat(arr1[i].value)>parseFloat(arr3[i].value)|| parseFloat(arr1[i].value)<0){
			alert("จำนวนที่คุณกำหนดมีค่ามากกว่าจำนวนที่แพทย์สั่ง หรือเท่ากับว่าง หรือมีค่าน้อยกว่า 0");	
			arr1[i].value=arr3[i].value;
			}
		}
	}
}

</script>
<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){      
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;   
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;     
    properties = "width="+windowWidth+",height="+windowHeight;  
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;     
    window.open(url,name,properties);  
}  
</script>
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />

<script>

$(document).ready(function(){	


			$("div#displayDiv").click(function(){

				$(".colorbox5").colorbox({
				iframe:true,
				title:'แสดงรายละเอียด',
				width:"90%", 
				height:"90%",
				scrolling:true,
				onOpen : function () {
		$('body, html').css('overflowY','hidden');},
				onCleanup :function(){
				$('body, html').css('overflowY','auto');}
				 });

			$(".iframe").colorbox({iframe:true,width:"100%", height:"90%",scrolling:true,				onOpen : function () {$('body, html').css('overflowY','hidden');},onCleanup :function(){
				$('body, html').css('overflowY','auto');}
			 	});
			});
			
			
			$("#button7").click(function(){			
			$(".iframe2").colorbox({iframe:true,href:"drug.php",width:"80%", height:"100%",scrolling:true,				onOpen : function () {
		$('body, html').css('overflowY','hidden');},
				onCleanup :function(){
				$('body, html').css('overflowY','auto');}
,onClosed:function(){document.location.reload(true);}});
		
			});

			
			});

function alertload(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url+"?"+str+"="+queue,onOpen : function () {
$('body, html').css('overflowY','hidden');},
onCleanup :function(){$('body, html').css('overflowY','auto');}
		});

	}			

function alertload1(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,	onOpen : function () {$('body, html').css('overflowY','hidden');},onCleanup :function(){
$('body, html').css('overflowY','auto');}
});

	}			
//alertload with close
function alertload2(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('body, html').css('overflowY','hidden');},onCleanup :function(){
$('body, html').css('overflowY','auto');}
,onClosed:function(){formSubmit('Q','displayIndiv','indicator','');}});

	}			

function alertload3(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,	onOpen : function () {$('body, html').css('overflowY','hidden');},onCleanup :function(){
$('body, html').css('overflowY','auto');}
,onClosed:function(){alertload1('','60%','30%');}});

	}			



function doctorcode(icode,doctor){
	document.getElementById(doctor).value=icode;
	}

</script>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-8922143-3']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
<script type="text/javascript">
            // OUR FUNCTION WHICH IS EXECUTED ON LOAD OF THE PAGE.
            function digitized() {
                var dt = new Date();    // DATE() CONSTRUCTOR FOR CURRENT SYSTEM DATE AND TIME.
                var hrs = dt.getHours();
                var min = dt.getMinutes();
                var sec = dt.getSeconds();

                min = Ticking(min);
                sec = Ticking(sec);

                document.getElementById('dc').innerHTML = hrs + ":" + min;
                document.getElementById('dc_second').innerHTML = sec;
                if (hrs > 12) { document.getElementById('dc_hour').innerHTML = 'PM'; }
                else { document.getElementById('dc_hour').innerHTML = 'AM'; }

                var time
                
                // THE ALL IMPORTANT PRE DEFINED JAVASCRIPT METHOD.
                time = setTimeout('digitized()', 1000);      
            }

            function Ticking(ticVal) {
                if (ticVal < 10) {
                    ticVal = "0" + ticVal;
                }
                return ticVal;
            }
			
	//
/*-------INSERT,EDIT,DEL----------*/
function dosubmit(id,do_it,url,div,form){
//alert(edit_id);
if(do_it=="delete"){
if (!confirm("ต้องการลบข้อมูลจริงหรือไม่")){
      return false;
 }
}
if(form!=""){
var form_data=getFormData(form);
}
if(form==""){
var form_data="1=1";	
}
var str=Math.random();
var datastring=form_data+'&str='+str+'&action_id='+id+'&action_do='+do_it;
$.ajax({
type:'POST',
url:url,
data:datastring,
beforeSend: function(){
$('#'+div).html('<img src="images/loader.gif">');
},
success:function(){
$('#'+div).load(url,datastring);
if(do_it=="post"){
	$('#div_post').hide();
	$('#poster').val('');
	$('#comment').val('');
	}
}
});
}
///////////////
///========dependent========//////
$(document).ready(function()
{
//เลือกจังหวัด
$(".chw").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
$("#department").val("");
$.ajax
({
type: "POST",
url: "amphur.php",
data: dataString,
cache: false,
success: function(html)
{
$(".amp").html(html);
} 
});

});
//// เลือกอำเภอ
$(".amp").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
$.ajax
({
type: "POST",
url: "department.php",
data: dataString,
cache: false,
success: function(html)
{
$(".department").html(html);
} 
});

});

/// เลือกกลุ่มหน่วยงาน
$(".dept_group").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
$.ajax
({
type: "POST",
url: "dept_select.php",
data: dataString,
cache: false,
success: function(html)
{
$(".dept").html(html);
} 
});

});

});

//user management
function useradd(url,w,h){
	///
	$.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){dosubmit('','show','user_list.php','user_list','');}
	});
	////
	}	

//drug add
function poppage(url,w,h,id,do_it,url2,div,form){
	///
	$.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){dosubmit(id,do_it,url2,div,form);}
	});
	////
	}
function divhide(a){
	$('.tbdiv').hide();
	$('#'+a+'1').show();
	}	
function poppage2(url,w,h){
	///
	$.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
	});
	////
}
/// clear form
function clearForm(){
for (var i = 0, j = arguments.length; i < j; i++){
	$('#'+arguments[i]).val('');
    }	}
	
        </script>
<script type="text/javascript" >
//// form UPload ภาพ
/* $(document).ready(function() { 
            $('#photoimg').live('change', function(){
			$("#preview").html('');
			$("#preview").html('<img src="images/loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({target: '#preview'}).submit();
 		});
        });
*/

function is_number(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

</script>        
<style type="text/css">
            .clock
            {
                vertical-align: middle;
                font-family: Arial, Sans-Serif;
                font-size: 40px;
                font-weight: normal;
                color: #000;
            }
            .clocklg
            {
                vertical-align: middle;
                font-family: Arial, Sans-Serif;
                font-size: 14px;
                font-weight: normal;
                color: #555;
            }
 </style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<title>ระบบเบิกยา VMI</title>
