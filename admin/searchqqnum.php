<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
checkAdminLogined();
$qqnum=$_POST['qqnum'];
//$qqnum='2';
  

$sqls= "select  * from zp_userinfo where qqnum like '%".$qqnum."%'";
$result= mysqli_query($tp,$sqls);
$array =[];
while($row = mysqli_fetch_assoc($result)){
	$array[] = $row;
}
echo json_encode($array);