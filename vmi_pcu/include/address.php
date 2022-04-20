<? 
mysql_select_db($database_borndb, $borndb);
$query_rs_chw = "select * from born_chw order by provid ASC";
$rs_chw = mysql_query($query_rs_chw, $borndb) or die(mysql_error());
$row_rs_chw = mysql_fetch_assoc($rs_chw);
$totalRows_rs_chw = mysql_num_rows($rs_chw);
?>
<script type="text/javascript">
		//**** List Province (Start) ***//
		function ListProvince(SelectValue)
		{
			form1.amp.length = 0
			form1.tmb.length = 0
			
			//*** Insert null Default Value ***//
			var myOption = new Option('== กรุณาเลือกอำเภอ ==','')  
			form1.amp.options[form1.amp.length]= myOption
			<?
			$intRows = 0;
			mysql_select_db($database_borndb, $borndb);
$query_rs_amp = "select * from born_amp";
$rs_amp = mysql_query($query_rs_amp, $borndb) or die(mysql_error());
//$row_rs_chw = mysql_fetch_assoc($rs_chw);
			$intRows = 0;
			while($objResult = mysql_fetch_array($rs_amp))
			{
			$intRows++;
			?>			
				x = <?=$intRows;?>;
				mySubList = new Array();
				
				strGroup = <?=$objResult["provid"];?>;
				strValue = "<?=$objResult["distid"];?>";
				strItem = "<?=$objResult["distname"]; ?>";
				mySubList[x,0] = strItem;
				mySubList[x,1] = strGroup;
				mySubList[x,2] = strValue;
				if (mySubList[x,1] == SelectValue){
					var myOption = new Option(mySubList[x,0], mySubList[x,2])  
					form1.amp.options[form1.amp.length]= myOption					
				}
			<?
			}
			?>																	
		}
		//**** List Province (End) ***//

		
		//**** List Amphur (Start) ***//
		function ListAmphur(SelectValue)
		{
			form1.tmb.length = 0

			//*** Insert null Default Value ***//
			var myOption = new Option('== กรุณาเลือกตำบล ==','')  
			form1.tmb.options[form1.tmb.length]= myOption
			<?
			$intRows = 0;
mysql_select_db($database_borndb, $borndb);
$query_rs_tmb = "select * from born_tmb";
$rs_tmb = mysql_query($query_rs_tmb, $borndb) or die(mysql_error());
			$intRows = 0;
			while($objResult = mysql_fetch_array($rs_tmb))
			{
			$intRows++;
			?>
				x = <?=$intRows;?>;
				mySubList = new Array();
				
				strGroup = <?=$objResult["distid"];?>;
				strValue = "<?=$objResult["subdistid"];?>";
				strItem = "<?=$objResult["subdistname"];?>";
				mySubList[x,0] = strItem;
				mySubList[x,1] = strGroup;
				mySubList[x,2] = strValue;
							
				if (mySubList[x,1] == SelectValue){
					var myOption = new Option(mySubList[x,0], mySubList[x,2])  
					form1.tmb.options[form1.tmb.length]= myOption					
				}
			<?
			}
			?>																	
		}
		//**** List Amphur (End) ***//
</script>