<?
ob_start();
session_start();
?>
<?php require_once('Connections/datacenter.php'); ?>
<?php require_once('Connections/vmi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

include('include/function.php');

if($_GET['atb']!=""){
    $condition=" and a.did='".$_GET['atb']."'";
}
if($_GET['hospcode']!=""){
    $condition.=" and a.hospcode='".$_GET['hospcode']."'";
}

mysql_select_db($database_datacenter, $datacenter);
$query_rs_list = "select a.hospcode,a.hn,a.vstdate,a.drug_name,a.qty,h.name,concat(p.pname,p.fname,' ',p.lname) as pt_name from datacenter_antibiotic_drug a left outer join hospcode h on h.hospcode=a.hospcode left outer join datacenter_patient p on p.hospcode=a.hospcode and p.hn=a.hn where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."'".$condition." order by vstdate";
//echo $query_rs_list;
$rs_list = mysql_query($query_rs_list, $datacenter) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
$totalRows_rs_list = mysql_num_rows($rs_list);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php //include('java_css_online.php'); ?>

<script>
$(document).ready(function() {
    $('#tables').DataTable({
        "paging": false,
        "searching": true
    });
} );
</script>
</head>

<body>
<table id="tables" style="font-size: 14px;" class="table-bordered table-striped table-hover table-sm" width="100%">
    <thead>
        <tr>
            <td class="text-center">ลำดับ</td>
            <td class="text-center">สถานบริการ</td>
            <td class="text-center">hn</td>
            <td class="text-center">ชื่อ</td>
            <td class="text-center">วันที่รับบริการ</td>
            <td class="text-center">รายการยา</td>
            <td class="text-center">จำนวน</td>
        </tr>
    </thead>
    <tbody>
        <?php $i=0; do { $i++; ?>
        <tr>
            <td class="text-center"><?php echo $i; ?></td>
            <td><?php echo $row_rs_list['name']; ?></td>
            <td class="text-center"><?php echo $row_rs_list['hn']; ?></td>
            <td class="text-center"><?php echo $row_rs_list['pt_name']; ?></td>
            <td class="text-center"><?php echo dateThai($row_rs_list['vstdate']); ?></td>
            <td><?php echo $row_rs_list['drug_name']; ?></td>
            <td class="text-center"><?php echo $row_rs_list['qty']; ?></td>
        </tr>
        <?php } while($row_rs_list = mysql_fetch_assoc($rs_list)); ?>
    </tbody>    
</table>
</body>
</html>