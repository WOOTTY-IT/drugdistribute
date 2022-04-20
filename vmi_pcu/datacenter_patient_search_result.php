<?php require_once('Connections/datacenter.php'); ?>
<?php 
	$sexplode=explode(' ',$_GET['keyword']);
	$fname=$sexplode[0];
	$lname=$sexplode[1];
	if($sexplode[0]!=""){
	$condition.="where fname like '%$fname%'";
	}
	if($sexplode[1]!=""){
	$condition.=" and lname like '%$lname%'";
	}

if($_GET['keyword']!=""){
mysql_select_db($database_datacenter, $datacenter);
$query_rs_patient = "select p.hn,p.pname,p.fname,p.lname,p.fathername,p.mathername,p.cid from datacenter_patient p  where hn like '%".$_GET['keyword']."' or cid like '".$_GET['keyword']."%' or ( fname like '%".$fname."%' and lname like '%".$lname."%' ) group by cid limit 50";
//echo $query_rs_patient;
$rs_patient = mysql_query($query_rs_patient, $datacenter) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);
}
 ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php if($totalRows_rs_patient<>0){ ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="t1 table-striped  dt-responsive nowrap" style="width:100%">
  <thead>
  <tr >
    <th align="center" bgcolor="#9DBAD5" style="padding-left: 10px;" >HN</th>
    <th align="center" bgcolor="#9DBAD5"  >ชื่อ</th>
    <th align="center" bgcolor="#9DBAD5"  >นามสกุล</th>
    <th align="center" bgcolor="#9DBAD5"  >cid</th>
    <th align="center" bgcolor="#9DBAD5"  >บิดา</th>
    <th align="center" bgcolor="#9DBAD5"  >มารดา</th>
  </tr>
  </thead>
  <tbody>
  <?php do{ ?>
  <tr class="cursor" onClick="detail_load('<?php echo $row_rs_patient['cid']; ?>');" data-dismiss="modal">
    <td align="left" style="padding-left: 10px;"><?php echo $row_rs_patient['hn']; ?></td>
    <td align="left"><?php echo $row_rs_patient['pname'].$row_rs_patient['fname']; ?></td>
    <td align="left"><?php echo $row_rs_patient['lname']; ?></td>
    <td align="left"><?php echo $row_rs_patient['cid']; ?></td>
    <td align="left"><?php echo $row_rs_patient['fathername']; ?></td>
    <td align="left"><?php echo $row_rs_patient['mathername']; ?></td>
  </tr><? }while($row_rs_patient = mysql_fetch_assoc($rs_patient)); ?>
  </tbody>
</table>
<?php } ?>
</body>
</html>
<?php
if($_GET['keyword']!=""){

mysql_free_result($rs_patient);
}
 ?>
