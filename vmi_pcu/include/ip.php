<? 
$ip=@$REMOTE_ADDR; 
?>
<?php
mysql_select_db($database_hos, $hos);
$query_onlineuser = "SELECT o.name as full_name ,h.name as depname,o.hospital_department_id,h.name as departmentname,h.dep,u.kskloginname,d2.name as position_name,o.doctorcode,d.position_id,kd.depcode FROM onlineuser u left outer join opduser o on o.loginname=u.kskloginname 
left outer join kskdepartment kd on u.department=kd.department
left outer join hospital_department_2 h on h.id=o.hospital_department_id left outer join doctor d on d.code=o.doctorcode left outer join doctor_position d2 on d2.id=d.position_id WHERE u.computername='$ip'";
$onlineuser = mysql_query($query_onlineuser, $hos) or die(mysql_error());
$row_onlineuser = mysql_fetch_assoc($onlineuser);
$totalRows_onlineuser = mysql_num_rows($onlineuser);
?>
<?
// config
$department=$row_onlineuser['hospital_department_id']; /// ˹��§ҹ
$user=$row_onlineuser['kskloginname']; //���ӡ�����
$dep=$row_onlineuser['dep']; //˹�ǹ�ҹ���
$full_name=$row_onlineuser['full_name']; /// ���ͼ����
$depname=$row_onlineuser['depname']; /// ����˹��§ҹ
$doctor_code=$row_onlineuser['doctorcode'];
$position_id=$row_onlineuser['position_id'];
$depcode=$row_onlineuser['depcode'];
?>
