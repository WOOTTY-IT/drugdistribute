<?php require_once('Connections/datacenter.php'); ?>
<?php
mysql_select_db($database_datacenter, $datacenter);
$query_rs_drug = "select d.drug_name,u.shortlist,d.qty,concat(s.name1,s.name2,s.name3) as sp_name from datacenter_drug_opd d left outer join datacenter_drugusage u on u.drugusage=d.drugusage and u.hospcode=d.hospcode left outer join datacenter_sp_use s on s.sp_use=d.sp_use where vn ='".$_GET['vn']."' and d.hospcode='".$_GET['hospcode']."' order by drug_name ASC";
//echo $query_rs_drug;
$rs_drug = mysql_query($query_rs_drug, $datacenter) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div class="p-3">
<div class="card">
	<div class="card-header">
		<h6>รายการยาที่ผู้ป่วยได้รับ</h5>
	</div>
	<div class="card-body">
		<table class="table" width="100%">
			<thead>
				<tr>
					<td class="text-center">#</td>
					<td class="text-center">รายการยา</td>
					<td class="text-center">วิธีใช้</td>
					<td class="text-center">จำนวน</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=0; do{ $i++; ?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td class="text-center"><?php echo $row_rs_drug['drug_name']; ?></td>
					<td class="text-center"><?php echo if($row_rs_drug['shortlist']!=""){ echo $row_rs_drug['shortlist'];}; ?></td>
					<td class="text-center"><?php echo $row_rs_drug['qty']; ?></td>
				</tr>	
				<?php }while($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
			</tbody>
		</table>
	</div>

</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>