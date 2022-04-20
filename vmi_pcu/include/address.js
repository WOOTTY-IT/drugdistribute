		//**** List Province (Start) ***//
		function ListProvince(SelectValue)
		{
			frmMain.amp.length = 0
			frmMain.tmb.length = 0
			
			//*** Insert null Default Value ***//
			var myOption = new Option('== กรุณาเลือกอำเภอ ==','')  
			frmMain.amp.options[frmMain.amp.length]= myOption
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
					frmMain.amp.options[frmMain.amp.length]= myOption					
				}
			<?
			}
			?>																	
		}
		//**** List Province (End) ***//

		
		//**** List Amphur (Start) ***//
		function ListAmphur(SelectValue)
		{
			frmMain.tmb.length = 0

			//*** Insert null Default Value ***//
			var myOption = new Option('== กรุณาเลือกตำบล ==','')  
			frmMain.tmb.options[frmMain.tmb.length]= myOption
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
					frmMain.tmb.options[frmMain.tmb.length]= myOption					
				}
			<?
			}
			?>																	
		}
		//**** List Amphur (End) ***//