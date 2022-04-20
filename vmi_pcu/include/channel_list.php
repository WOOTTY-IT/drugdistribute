<?php require_once('../Connections/hos.php'); 
if(isset($channel)&&($channel!="")){
mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from kohrx_television_channel where channel='".$_GET['channel']."'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_update = "update kohrx_dispensing_setting set value='".$row_rs_channel['channel_url']."' where name='television'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="230" align="center" valign="middle" style="background-image:url(../images/Devices-video-television-icon.png); background-repeat:no-repeat; background-position:center">ขณะนี้ท่านกำลังชมช่อง<br />
       <span style="font-size:20px; color:#FFFFFF"><?php echo $channel; ?> </span></td>
  </tr>
</table>
<table width="800" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td colspan="10" class="table_head_small_bord">Free tv original</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=ch3"><img src="../images/channel/ch3.png" width="65" height="66" border="0" /></a><br />
      ช่อง 3</td>
    <td align="center"><p><a href="channel_list.php?channel=ch5"><img src="../images/channel/ch5.png" width="65" height="66" border="0" /></a><br />
      ช่อง 5
    </p></td>
    <td align="center"><a href="channel_list.php?channel=ch7"><img src="../images/channel/ch7.png" width="65" height="66" border="0" /></a><br />
    ช่อง 7</td>
    <td align="center"><a href="channel_list.php?channel=ch9"><img src="../images/channel/ch9.png" width="65" height="66" border="0" /></a><br />
    ช่อง 9</td>
    <td align="center"><img src="../images/channel/NBT.png" width="65" height="66" /><br />
    nbt</td>
    <td align="center"><a href="channel_list.php?channel=thaipbs"><img src="../images/channel/thaiPBS.png" width="65" height="66" border="0" /></a><br />
    ThaiPBS</td>
    <td align="center"><a href="channel_list.php?channel=springnews"><img src="../images/channel/springnews.png" width="65" height="66" border="0" /></a><br />
      SpringNews</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" class="table_head_small_bord">Digital tv HD</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=ch3hd"><img src="../images/channel/3-HD.png" alt="" width="65" height="66" border="0" /></a><br />
    ช่อง 3 HD</td>
    <td align="center"><a href="channel_list.php?channel=ch3fam"><img src="../images/channel/3-Family.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 3 family</td>
    <td align="center"><a href="channel_list.php?channel=ch3sd"><img src="../images/channel/3-SD.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 3 SD</td>
    <td align="center"><a href="channel_list.php?channel=ch5hd"><img src="../images/channel/ch5.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 5 HD</td>
    <td align="center"><a href="channel_list.php?channel=ch7hd"><img src="../images/channel/BBTV-CH7.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 7 HD</td>
    <td align="center"><a href="channel_list.php?channel=ch9hd"><img src="../images/channel/ch9.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 9 HD</td>
    <td align="center"><a href="channel_list.php?channel=mcotkidfam"><img src="../images/channel/mcotkids&amp;family.jpg" alt="" width="64" height="60" border="0" /></a><br />
    MCOT</td>
    <td align="center"><a href="channel_list.php?channel=workpoint"><img src="../images/channel/workpoint.png" alt="" width="65" height="66" border="0" /></a><br />
    Workpoint</td>
    <td align="center"><a href="channel_list.php?channel=gmmch"><img src="../images/channel/GMMChannel.png" alt="" width="65" height="66" border="0" /></a><br /> 
      GMM channel
</td>
    <td align="center"><a href="channel_list.php?channel=onehd"><img src="../images/channel/one-HD.png" alt="" width="65" height="66" border="0" /></a><br />
One HD</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=loca"><img src="../images/channel/LOCA.png" alt="" width="65" height="66" border="0" /></a><br />LOCA</td>
    <td align="center"><a href="channel_list.php?channel=tnn24"><img src="../images/channel/TNT-24.png" alt="" width="65" height="66" border="0" /></a><br />
    TNN24</td>
    <td align="center"><a href="channel_list.php?channel=bright"><img src="../images/channel/Bright-TV.png" alt="" width="65" height="66" border="0" /></a><br />
    Bright TV</td>
    <td align="center"><a href="channel_list.php?channel=nation"><img src="../images/channel/nationchannel.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Nation ch.
</td>
    <td align="center"><a href="channel_list.php?channel=tru4u"><img src="../images/channel/TRUE-4-U.png" alt="" width="65" height="66" border="0" /></a><br /> 
      True4U
</td>
    <td align="center"><a href="channel_list.php?channel=now"><img src="../images/channel/now.png" alt="" width="65" height="66" border="0" /></a><br />
    NOW</td>
    <td align="center"><a href="channel_list.php?channel=ch8"><img src="../images/channel/thaich8.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Ch.8
</td>
    <td align="center"><a href="channel_list.php?channel=mono"><img src="../images/channel/Mono-TV.png" alt="" width="65" height="66" border="0" /></a><br />
    MONO29</td>
    <td align="center"><a href="channel_list.php?channel=new)tv"><img src="../images/channel/new)tv.png" alt="" width="65" height="66" border="0" /></a><br /> 
      new)tv
</td>
    <td align="center"><a href="channel_list.php?channel=thairat"><img src="../images/channel/Thairath-TV.png" alt="" width="65" height="66" border="0" /></a><br />
new)tv </td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=amarin"><img src="../images/channel/AMARIN-TV-HD.png" alt="" width="65" height="66" border="0" /></a><br />
    Amarin HD</td>
    <td align="center"><a href="channel_list.php?channel=pptv"><img src="../images/channel/PPTV-HD.png" alt="" width="65" height="66" border="0" /></a><br />
PPTV HD</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="left" class="table_head_small_bord">Varieties</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=money"><img src="../images/channel/MoneyChannel.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Money ch.
</td>
    <td align="center"><a href="channel_list.php?channel=superbunteng"><img src="../images/channel/superbunteung.png" alt="" width="65" height="66" border="0" /></a><br />
    Super บันเทิง</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td 1align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="left" class="table_head_small_bord">Movies</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=moviehit"><img src="../images/channel/2002061Hb.jpg" alt="" width="75" height="55" border="0" /></a><br /> 
      moviehit
</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="left" class="table_head_small_bord">กีฬา</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list.php?channel=tsp7"><img src="../images/channel/true-sport-7.png" alt="" width="76" height="57" border="0" /></a><br />
    true sport 7</td>
    <td align="center"><a href="channel_list.php?channel=cth1"><img src="../images/channel/football-hd.com-ch_52614d7ca71d86_64502776.png" alt="" width="65" height="66" border="0" /></a><br /> 
      EPL1
</td>
    <td align="center"><a href="channel_list.php?channel=cth2"><img src="../images/channel/football-hd.com-ch_52614cb648fcc7_42600034.png" alt="" width="65" height="66" border="0" /></a><br />
EPL2</td>
    <td align="center"><a href="channel_list.php?channel=cth3"><img src="../images/channel/football-hd.com-ch_52614ce912cf55_58026454.png" alt="" width="65" height="66" border="0" /></a><br />
EPL3</td>
    <td align="center"><a href="channel_list.php?channel=cth4"><img src="../images/channel/football-hd.com-ch_53720a88b25585_64050529.png" alt="" width="65" height="66" border="0" /></a><br />
EPL4</td>
    <td align="center"><a href="channel_list.php?channel=cth5"><img src="../images/channel/football-hd.com-ch_537231c6b17756_69709092.png" alt="" width="65" height="66" border="0" /></a><br />
EPL5</td>
    <td align="center"><a href="channel_list.php?channel=cth6"><img src="../images/channel/football-hd.com-ch_53d00c6788d227_34377173.png" alt="" width="65" height="66" border="0" /></a><br />
EPL6</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>