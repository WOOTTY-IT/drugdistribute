<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<script src="include/jquery.js"></script>

</head>

<body style="margin:0px;padding:0px;overflow:hidden;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="gray thsan-bold" style="font-size:30px; padding:10px" ><img src="images/warehouse_illu_01.png" width="83" height="61" align="absmiddle" />&nbsp; ระบบบริหารจัดการคลัง</td>
  </tr>
  <tr align="center">
    <td align="left">
    <nav class="shift thsan-semibold font18">
        <ul>
			<a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_receive'"><li <?php if($_GET['sub_page2']=="stock_receive"){ echo "class=\"button_stock\""; } ?>>รับยาเข้า</li></a>        
			<a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_withdraw'"><li <?php if($_GET['sub_page2']=="stock_withdraw"){ echo "class=\"button_stock\""; } ?>>เบิกยา</li></a>        
			<a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_card'"><li <?php if($_GET['sub_page2']=="stock_card"){ echo "class=\"button_stock\""; } ?>>สต๊อกการ์ด</li></a>          
			<a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_remain'"><li <?php if($_GET['sub_page2']=="stock_remain"){ echo "class=\"button_stock\""; } ?>>คงคลัง</li></a>
            
            <a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_report'"><li <?php if($_GET['sub_page2']=="stock_report"){ echo "class=\"button_stock\""; } ?>>รายงาน</li></a>            
            
            <a href="javascript:valide(0);" onClick="window.location.href='main.php?sub_page=stock&sub_page2=stock_setting'"><li <?php if($_GET['sub_page2']=="stock_setting"){ echo "class=\"button_stock\""; } ?>>ตั้งค่า</li></a>            

        </ul>
    </nav>
    </td>
  </tr>
  <tr align="center">
    <td><iframe src="<?php if(isset($_GET['sub_page2'])){ echo $_GET['sub_page2'].".php"; } ?>" frameborder="0" style="overflow:hidden;" class="full" scrolling="auto" ></iframe></td>
  </tr>
</table>
</body>
</html>