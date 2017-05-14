<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
$arrysub=[];
if(@$_SESSION['adminId']==""){
	
	$result= array(
			'cid' => '000000',
	);
	$arrysub[] = $result;
	die(json_encode($arrysub));
}
$qqnum=$_POST['qqnum'];
//$qqnum='2';
  

$sqls= "select  * from zp_userinfo where qqnum like '%".$qqnum."%'";
$result= mysqli_query($tp,$sqls);
while($row = mysqli_fetch_assoc($result)){
	$arrysub[] = $row;
}
echo json_encode($arrysub);