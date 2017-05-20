<?php
require_once '../configs/configs.php';
require_once '../lib/mysql.func.php';
require_once("API/qqConnectAPI.php");
$tp = connect();
$oauth = new Oauth();
$openid= $oauth ->get_openid();
$sqls="select * from zp_userinfo where openid='{$openid}'";
$resu=mysqli_query($tp,$sqls);
$affrows = mysqli_affected_rows($tp);
if($resu->num_rows == 1){
	$row = $resu->fetch_assoc();
	$result= array(
			'cid' => $affrows,
			'qqnum' => $row['qqnum'],
			'uid'=>$row['uid'],
	);
}else{
	$result= array(
			'cid' => $affrows,
	);
}
echo json_encode($result);
?>