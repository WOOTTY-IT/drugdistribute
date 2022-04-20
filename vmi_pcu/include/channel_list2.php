<?php require_once('../Connections/hos.php'); 
if(isset($channel)&&($channel!="")){
mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from kohrx_television_channel where id='".$_GET['channel']."'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_update = "update kohrx_dispensing_setting set value='".$row_rs_channel['channel_url']."' where name='television'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());
echo "<script type='text/javascript'>parent.jQuery.fn.colorbox.close();</script>";
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
<table width="600" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
  <tr>
    <td colspan="7" class="table_head_small_bord">Free tv original</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=1"><img src="../images/channel/ch3.png" width="65" height="66" border="0" /></a><br />
      ช่อง 3</td>
    <td align="center"><a href="channel_list2.php?channel=47"><img src="../images/channel/ch3.png" width="65" height="66" border="0" /></a><br />
ช่อง 3 (สำรอง1)</td>
    <td align="center"><a href="channel_list2.php?channel=42"><img src="../images/channel/ch3.png" width="65" height="66" border="0" /></a><br />
    ช่อง 3 (สำรอง2)</td>
    <td align="center"><p><a href="channel_list2.php?channel=2"><img src="../images/channel/ch5.png" width="65" height="66" border="0" /></a><br />
      ช่อง 5 </p></td>
    <td align="center"><a href="channel_list2.php?channel=3"><img src="../images/channel/ch7.png" width="65" height="66" border="0" /></a><br />
      ช่อง 7</td>
    <td align="center"><a href="channel_list2.php?channel=4"><img src="../images/channel/ch9.png" width="65" height="66" border="0" /></a><br />
      ช่อง 9</td>
    <td align="center"><img src="../images/channel/NBT.png" width="65" height="66" /><br />
      nbt</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=5"><img src="../images/channel/thaiPBS.png" width="65" height="66" border="0" /></a><br />
      ThaiPBS</td>
    <td align="center"><a href="channel_list2.php?channel=6"><img src="../images/channel/springnews.png" width="65" height="66" border="0" /></a><br />
      SpringNews</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7" class="table_head_small_bord">Digital tv HD</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=7"><img src="../images/channel/3-HD.png" alt="" width="65" height="66" border="0" /></a><br />
    ช่อง 3 HD</td>
    <td align="center"><a href="channel_list2.php?channel=8"><img src="../images/channel/3-Family.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 3 family</td>
    <td align="center"><a href="channel_list2.php?channel=9"><img src="../images/channel/3-SD.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 3 SD</td>
    <td align="center"><a href="channel_list2.php?channel=10"><img src="../images/channel/ch5.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 5 HD</td>
    <td align="center"><a href="channel_list2.php?channel=12"><img src="../images/channel/BBTV-CH7.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 7 HD</td>
    <td align="center"><a href="channel_list2.php?channel=13"><img src="../images/channel/ch9.png" alt="" width="65" height="66" border="0" /></a><br />
ช่อง 9 HD</td>
    <td align="center"><a href="channel_list2.php?channel=14"><img src="../images/channel/mcotkids&amp;family.jpg" alt="" width="64" height="60" border="0" /></a><br />
    MCOT</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=18"><img src="../images/channel/LOCA.png" alt="" width="65" height="66" border="0" /></a><br />LOCA</td>
    <td align="center"><a href="channel_list2.php?channel=19"><img src="../images/channel/TNT-24.png" alt="" width="65" height="66" border="0" /></a><br />
    TNN24</td>
    <td align="center"><a href="channel_list2.php?channel=20"><img src="../images/channel/Bright-TV.png" alt="" width="65" height="66" border="0" /></a><br />
    Bright TV</td>
    <td align="center"><a href="channel_list2.php?channel=21"><img src="../images/channel/nationchannel.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Nation ch.
</td>
    <td align="center"><a href="channel_list2.php?channel=22"><img src="../images/channel/TRUE-4-U.png" alt="" width="65" height="66" border="0" /></a><br /> 
      True4U
</td>
    <td align="center"><a href="channel_list2.php?channel=23"><img src="../images/channel/now.png" alt="" width="65" height="66" border="0" /></a><br />
    NOW</td>
    <td align="center"><a href="channel_list2.php?channel=24"><img src="../images/channel/thaich8.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Ch.8
</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=15"><img src="../images/channel/workpoint.png" alt="" width="65" height="66" border="0" /></a><br />
      Workpoint</td>
    <td align="center"><a href="channel_list2.php?channel=16"><img src="../images/channel/GMMChannel.png" alt="" width="65" height="66" border="0" /></a><br />
      GMM channel </td>
    <td align="center"><a href="channel_list2.php?channel=17"><img src="../images/channel/one-HD.png" alt="" width="65" height="66" border="0" /></a><br />
      One HD</td>
    <td align="center"><a href="channel_list2.php?channel=25"><img src="../images/channel/Mono-TV.png" alt="" width="65" height="66" border="0" /></a><br />
      MONO29</td>
    <td align="center"><a href="channel_list2.php?channel=26"><img src="../images/channel/new)tv.png" alt="" width="65" height="66" border="0" /></a><br />
      new)tv </td>
    <td align="center"><a href="channel_list2.php?channel=27"><img src="../images/channel/Thairath-TV.png" alt="" width="65" height="66" border="0" /></a><br />
      new)tv </td>
    <td align="center"><a href="channel_list2.php?channel=28"><img src="../images/channel/AMARIN-TV-HD.png" alt="" width="65" height="66" border="0" /></a><br />
    Amarin HD</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=29"><img src="../images/channel/PPTV-HD.png" alt="" width="65" height="66" border="0" /></a><br />
      PPTV HD</td>
    <td align="center"><a href="channel_list2.php?channel=40"><img src="../images/channel/THV_DigitalTV.jpeg" alt="" width="59" height="61" border="0" /></a><br />
    THV </td>
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
  </tr>
  <tr>
    <td colspan="7" align="left" class="table_head_small_bord">Varieties</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=30"><img src="../images/channel/MoneyChannel.png" alt="" width="65" height="66" border="0" /></a><br /> 
      Money ch.
</td>
    <td align="center"><a href="channel_list2.php?channel=31"><img src="../images/channel/superbunteung.png" alt="" width="65" height="66" border="0" /></a><br />
    Super บันเทิง</td>
    <td align="center"><a href="channel_list2.php?channel=42"><img src="../images/channel/very.png" alt="" width="70" height="52" border="0" /></a><br />
    Very</td>
    <td align="center"><a href="channel_list2.php?channel=43"><img src="../images/channel/HAYHA.jpg" alt="" width="65" height="50" border="0" /></a><br />
    HAYHA</td>
    <td align="center"><a href="channel_list2.php?channel=44"><img src="../images/channel/ch_18.gif" alt="" width="73" height="36" border="0" /></a><br /> 
      X-Zyte
</td>
    <td align="center" 1align="center"><a href="channel_list2.php?channel=45"><img src="../images/channel/mtv-logo.jpg" alt="" width="70" height="57" border="0" /></a><br />
    MTV</td>
    <td align="center"><a href="channel_list2.php?channel=46"><img src="../images/channel/truemusic.png" alt="" width="65" height="66" border="0" /></a><br />
    Tue music</td>
  </tr>
  <tr>
    <td colspan="7" align="left" class="table_head_small_bord">Movies</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=32"><img src="../images/channel/2002061Hb.jpg" alt="" width="75" height="55" border="0" /></a><br /> 
      moviehit
</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7" align="left" class="table_head_small_bord">กีฬา</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=33"><img src="../images/channel/true-sport-7.png" alt="" width="76" height="57" border="0" /></a><br />
    true sport 7</td>
    <td align="center"><a href="channel_list2.php?channel=34"><img src="../images/channel/football-hd.com-ch_52614d7ca71d86_64502776.png" alt="" width="65" height="66" border="0" /></a><br /> 
      EPL1
</td>
    <td align="center"><a href="channel_list2.php?channel=35"><img src="../images/channel/football-hd.com-ch_52614cb648fcc7_42600034.png" alt="" width="65" height="66" border="0" /></a><br />
EPL2</td>
    <td align="center"><a href="channel_list2.php?channel=36"><img src="../images/channel/football-hd.com-ch_52614ce912cf55_58026454.png" alt="" width="65" height="66" border="0" /></a><br />
EPL3</td>
    <td align="center"><a href="channel_list2.php?channel=37"><img src="../images/channel/football-hd.com-ch_53720a88b25585_64050529.png" alt="" width="65" height="66" border="0" /></a><br />
EPL4</td>
    <td align="center"><a href="channel_list2.php?channel=38"><img src="../images/channel/football-hd.com-ch_537231c6b17756_69709092.png" alt="" width="65" height="66" border="0" /></a><br />
EPL5</td>
    <td align="center"><a href="channel_list2.php?channel=39"><img src="../images/channel/football-hd.com-ch_53d00c6788d227_34377173.png" alt="" width="65" height="66" border="0" /></a><br />
    EPL6</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7" align="left">KIDs</td>
  </tr>
  <tr>
    <td align="center"><a href="channel_list2.php?channel=11"><img src="../images/channel/100px-TrueVisions-030-Toon_Channel.jpg" alt="" width="81" height="52" border="0" /></a><br />
toon</td>
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