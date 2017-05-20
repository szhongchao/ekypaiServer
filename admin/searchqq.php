<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
$arrysub=[];
$arryPUser = getPhoneInfo();
if($arryPUser['adminId'] == "" || $arryPUser['adminId'] == null ){
	$result= array(
			'cid' => '登录失败',
	);
	$arrysub[] = $result;
	die(json_encode($arrysub));
} 
$act=$_REQUEST['act'];
if($act==="getUserInfo"){
	$uid=$_POST['uid'];
	$sqls= "select  * from zp_userinfo where uid = {$uid}";
	$result= mysqli_query($tp,$sqls);
	while($row = mysqli_fetch_assoc($result)){
		$arrysub[] = $row;
	}
}elseif($act==="editUserInfo"){
	$uid=$_POST['uid'];
	$qqnum=$_POST['qqnum'];
	$fileidstr=$_POST['fileidstr'];
	$openid = $_POST['openid'];
	$sqls= "update zp_userinfo set fileidstr='{$fileidstr}',qqnum='{$qqnum}',openid='{$openid}' where uid='{$uid}'"; //修改qq和权限
	$result= mysqli_query($tp,$sqls);
	$affrows = mysqli_affected_rows($tp);
	$result= array(
			'cid' => '更新成功',
			'affrows'=>$affrows,
	);
	$arrysub[] = $result;
	
}elseif($act==="delUserInfo"){
	$uid=$_POST['uid'];
	$sqls= "delete from zp_userinfo  where uid='{$uid}'"; //删除用户
	$result= mysqli_query($tp,$sqls);
	$affrows = mysqli_affected_rows($tp);
	$result= array(
			'cid' => '删除成功',
			'affrows'=>$affrows,
	);
	$arrysub[] = $result;
}elseif($act==="getUserlist"){
	$qqnum=$_POST['qqnum'];
	//$qqnum='2';
	$sqls= "select  * from zp_userinfo where qqnum like '%".$qqnum."%'";
	$result= mysqli_query($tp,$sqls);
	while($row = mysqli_fetch_assoc($result)){
		$arrysub[] = $row;
	}
}
echo json_encode($arrysub);

