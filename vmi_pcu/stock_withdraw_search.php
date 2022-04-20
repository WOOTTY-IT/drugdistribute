<?
ob_start();
session_start();
?>
<?php require_once('Connections/vmi.php'); ?>
<?php
// ฟังก์ชั่นสำหรับการแบ่งหน้า NEW MODIFY
function page_navi($before_p,$plus_p,$total,$total_p,$chk_page){      
    global $urlquery_str;   
    $pPrev=$chk_page-1;   
    $pPrev=($pPrev>=0)?$pPrev:0;   
    $pNext=$chk_page+1;   
    $pNext=($pNext>=$total_p)?$total_p-1:$pNext;        
    $lt_page=$total_p-4;   
    if($chk_page>0){     
        echo "<a  href='$urlquery_str"."pages=".intval($pPrev+1)."' class='naviPN'>Prev</a>";   
    }   
    if($total_p>=11){   
        if($chk_page>=4){   
            echo "<a $nClass href='$urlquery_str"."pages=1'>1</a><a class='SpaceC'>. . .</a>";      
        }   
        if($chk_page<4){   
            for($i=0;$i<$total_p;$i++){     
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                if($i<=4){   
                echo "<a $nClass href='$urlquery_str"."pages=".intval($i+1)."'>".intval($i+1)."</a> ";      
                }   
                if($i==$total_p-1 ){    
                echo "<a class='SpaceC'>. . .</a><a $nClass href='$urlquery_str"."pages=".intval($i+1)."'>".intval($i+1)."</a> ";      
                }          
            }   
        }   
        if($chk_page>=4 && $chk_page<$lt_page){   
            $st_page=$chk_page-3;   
            for($i=1;$i<=5;$i++){   
                $nClass=($chk_page==($st_page+$i))?"class='selectPage'":"";   
                echo "<a $nClass href='$urlquery_str"."pages=".intval($st_page+$i+1)."'>".intval($st_page+$i+1)."</a> ";         
            }   
            for($i=0;$i<$total_p;$i++){     
                if($i==$total_p-1 ){    
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                echo "<a class='SpaceC'>. . .</a><a $nClass href='$urlquery_str"."pages=".intval($i+1)."'>".intval($i+1)."</a> ";      
                }          
            }                                      
        }      
        if($chk_page>=$lt_page){   
            for($i=0;$i<=4;$i++){   
                $nClass=($chk_page==($lt_page+$i-1))?"class='selectPage'":"";   
                echo "<a $nClass href='$urlquery_str"."pages=".intval($lt_page+$i)."'>".intval($lt_page+$i)."</a> ";      
            }   
        }           
    }else{   
        for($i=0;$i<$total_p;$i++){     
            $nClass=($chk_page==$i)?"class='selectPage'":"";   
            echo "<a href='$urlquery_str"."pages=".intval($i+1)."' $nClass  >".intval($i+1)."</a> ";      
        }          
    }      
    if($chk_page<$total_p-1){   
        echo "<a href='$urlquery_str"."pages=".intval($pNext+1)."'  class='naviPN'>Next</a>";   
    }   
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style type="text/css">
/* css แบ่งหน้า */
.browse_page{   
    clear:both;   
    margin-left:12px;   
    height:25px;   
    margin-top:5px;   
    display:block;   
}   
.browse_page a,.browse_page a:hover{   
    display:block;   
	width: 2%;
    font-size:14px;   
    float:left;   
    margin:0px 5px;
    border:1px solid #CCCCCC;   
    background-color:#F4F4F4;   
    color:#333333;   
    text-align:center;   
    line-height:22px;   
    font-weight:bold;   
    text-decoration:none;   
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;	
}   
.browse_page a:hover{   
	border:1px solid #CCCCCC;
	background-color:#999999;
    color:#FFFFFF;   
}   
.browse_page a.selectPage{   
    display:block;   
    width:45px;   
    font-size:14px;   
    float:left;   
    margin-right:2px;   
	border:1px solid #CCCCCC;
	background-color:#999999;

    color:#FFFFFF;   
    text-align:center;   
    line-height:22px;    
    font-weight:bold;   
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;	
}   
.browse_page a.SpaceC{   
    display:block;   
    width:45px;   
    font-size:14px;   
    float:left;   
    margin-right:2px;   
    border:0px dotted #0A85CB;   
    background-color:#FFFFFF;   
    color:#333333;   
    text-align:center;   
    line-height:22px;   
    font-weight:bold;   
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;	
}   
.browse_page a.naviPN{   
    width:50px;   
    font-size:12px;   
    display:block;   
/*    width:25px;   */
    float:left;   
	border:1px solid #CCCCCC;
	background-color:#999999;
    color:#FFFFFF;   
    text-align:center;   
    line-height:22px;   
    font-weight:bold;      
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;	
}  
/* จบ css แบ่งหน้า */
</style>
<style>
div.divhead{
	
	position:fixed;
	top:0;
	width:100%;
	font-size:23px;
	padding:10px;
	border-bottom:solid 1px #CCCCCC;
	background-color:#ededed;
	}
div.divcontent{
	margin-top:40px;
	padding:10px;
	width:98%;	
	}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script src="include/jquery.js"></script>
<?php include('java_function.php'); ?>
<script>
function selpr(pr,pr_date,dept){
	window.parent.$("#ps2").val(pr);
	window.parent.$("#ps").val(pr);
	window.parent.$("#date1").val(pr_date);
	window.parent.$("#dept").val(dept);
	
	parent.formSubmit('edit','form1','stock_withdraw_list.php','divload','indicator','load');
	parent.$.fn.colorbox.close();
	}
</script>
</head>

<body>
<div class="thsan-light divhead "><img src="images/withdraw.png" width="34" height="31" align="absmiddle"> &nbsp;&nbsp;รายการเบิกยาจากคลัง ( Drug Withdrawal List)</div>

<div style="margin-top:60px;text-align:left;width:100%;">
<!--ส่วนสร้างฟอร์ม สำหรับค้นหา -->
<form id="form_search" name="form_search" method="get" action="">
  <input type="text" name="keyword" id="keyword" class="inputcss1"  style="height:28px" />
  <input type="submit" name="search" id="search" value="ค้นหา" class="gray thsan-light font19" style="height:28px; width:80px; border:solid 1px #CACACA" />
</form>


<table width="100%" border="0" align="center" cellpadding="0" cellspacing="00" style="margin-top:10px">
<tbody>
  <tr>
    <td align="left" >


    <table width="100%" border="1" cellspacing="0" cellpadding="2" style="border-collapse:collapse;" class="thsan-light">
    	<tr>
        <td width="60" height="20" align="center" bgcolor="#F2F2F2">#</td>
        <td width="202" height="20" bgcolor="#F2F2F2">&nbsp; PS number</td>
        <td width="139" align="center" bgcolor="#F2F2F2">รายการ</td>
        <td width="185" align="center" bgcolor="#F2F2F2">วันที่</td>
        <td width="157" align="center" bgcolor="#F2F2F2">มูลค่า</td>
        </tr>
<?php
        $i=1;
		mysql_select_db($database_vmi, $vmi);
        $q="select c.withdraw_no,COUNT(*) as item,sum((c.qty_order/c.pack_ratio)*r.buy_unit_cost) as sumprice,concat(DATE_FORMAT(s.date_withdraw,'%d/%m/'),(DATE_FORMAT(s.date_withdraw,'%Y')+543)) as withdraw_date,s.date_withdraw,sum(c.qty_order) as sumqty,c.working_code,s.dept_id  
from stock_withdraw s left outer join stock_withdraw_c c on c.withdraw_no=s.withdraw_no left outer join stock_receive_c r on r.id=c.r_id  WHERE 1 and c.hospcode='".$_SESSION['hospcode']."' ";
		// เงื่อนไขการค้นหา ถ้ามีการส่งค่า ตัวแปร $_GET['keyword'] 
		if(isset($_GET['keyword']) && $_GET['keyword']!=""){
			// ต่อคำสั่ง sql 
			$q.=" AND c.receive_no LIKE '%".trim($_GET['keyword'])."%' ";	
		}
        $qr = mysql_query($q, $vmi) or die(mysql_error());
		//$qr=@mysql_query($q);	
		
		$total=mysql_num_rows($qr);
		$e_page=8; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
		if(!isset($_GET['pages'])){   
			$_GET['pages']=0;   
		}else{   
			$_GET['pages']=$_GET['pages']-1;
			if($_GET['pages']<0){
				$_GET['pages']=0;	
			}
			$chk_page=$_GET['pages'];     
			$_GET['pages']=$_GET['pages']*$e_page;   
		}   
		$q.=" group by c.withdraw_no ORDER BY withdraw_no DESC  LIMIT ".$_GET['pages'].",$e_page";
			
        $qr = mysql_query($q, $vmi) or die(mysql_error());
		if($total>=1){   
			$plus_p=($chk_page*$e_page)+$total;   
		}else{   
			$plus_p=($chk_page*$e_page);       
		}   
		$total_p=ceil($total/$e_page);   
		$before_p=($chk_page*$e_page)+1;  
		/// END PAGE NAVI ZONE			
		
        while($rs=mysql_fetch_array($qr)){
	$query_rs_lot = "select * from stock_card where working_code='".$rs['working_code']."' and hospcode='".$_SESSION['hospcode']."' and operation_date>='".$rs['date_withdraw']."' and r_s_number!='".$rs['withdraw_no']."' and id > (select id from stock_card where r_s_number='".$rs['withdraw_no']."' group by r_s_number) order by id DESC limit 1";
	$rs_lot = mysql_query($query_rs_lot, $vmi) or die(mysql_error());
	$row_rs_lot = mysql_fetch_assoc($rs_lot);
	$totalRows_rs_lot = mysql_num_rows($rs_lot);

?>  
  <tr <?php if($totalRows_rs_lot==0){ ?>onClick="selpr('<?=$rs['withdraw_no']?>','<?=$rs['withdraw_date']?>','<?=$rs['dept_id'];?>');"<?php } ?> style="cursor:pointer; <?php if($totalRows_rs_lot!=0){ echo "color: #D2D2D2"; } ?>" class="grid4">
    <td width="60" height="20" align="center"><?=(($e_page*$chk_page)+$i)?></td>
    <td height="20">&nbsp;<?=$rs['withdraw_no']?></td>
    <td align="center"><?=$rs['item']?></td>
    <td align="center"><?=$rs['withdraw_date']?></td>
    <td align="center"><?=number_format($rs['sumprice'],2);?></td>
  </tr>
<?php $i++; 
	mysql_free_result($rs_lot);
	} ?>     
    </table>
    
    <br />

    </td>
  </tr>
</tbody>


 

<tfoot>
  <tr>
    <td align="left">

 <div style="margin:auto;width:100%;">
     <?php if($total>10){ ?>                  
    <div class="browse_page">   
        <?php      
    if(count($_GET)<=1){
        $urlquery_str="?";
    }else{
		$para_get="";
		foreach($_GET as $key=>$value){
			if($key!="pages"){
				$para_get.=$key."=".$value."&";
			}
		}
        $urlquery_str="?$para_get";
    }
    
    // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า      
    page_navi($before_p,$plus_p,$total,$total_p,$chk_page);       
    ?>
    </div>   
    <?php } ?>     
</div>  
    
    </td>
  </tr>
</tfoot>    
</table>

</div>


</body>
</html>
