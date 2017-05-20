<?php
require_once '../configs/configs.php';
require_once '../lib/mysql.func.php';
require_once("API/qqConnectAPI.php");
$tp = connect();
$qqnum = $_GET['qqnum'];
//$qqnum = '2113424951';
set_time_limit(0);//设置超时时间
$sql="select * from zp_userinfo where qqnum='{$qqnum}'";
//根据登录名和密码查询数据表
$result=mysqli_query($tp,$sql);
$affrows = mysqli_affected_rows($tp);
if($result->num_rows == 1){
	$oauth = new Oauth();
	$openid= $oauth ->get_openid();
	$row = $result->fetch_assoc();
	if($openid == $row['openid']){
		$result= array(
				'cid' => $affrows,
				'qqnum' => $qqnum,
				'uid'=>$row['uid'],
		);
	}
	elseif($row['openid']==null or $row['openid']==""){
		$qc = new QC();
		$arr = $qc->get_user_info();
		$gurl= $arr['figureurl'];
		//$gender =$arr["gender"];//用户性别
		$nickname = $arr["nickname"];//用户昵称
		$gfile=file_get_contents($gurl);
		$qurl= $row['figureurl'];
		$qfile=file_get_contents($qurl);
		if(base64_encode($qfile) == base64_encode($gfile) and base64_encode($nickname) == $row['qqname']){
			$sqlu="update zp_userinfo set openid = '{$openid}' where qqnum='{$qqnum}'";
			mysqli_query($tp,$sqlu);
			$result= array(
					'cid' => $affrows,
					'qqnum' => $qqnum,
					'uid'=>$row['uid'],
			);
		}else{
			$result= array(
					'cid' => '2',//自助绑定失败,请联系QQ211342495客服绑定
					'qqnum' => $qqnum,
					'uid'=>'',
					'openid'=> $openid,
			);
		}
	}else{
		$result= array(
				'cid' => '2',//自助绑定失败,请联系QQ211342495客服绑定
				'qqnum' => $qqnum,
				'uid'=>'',
				'openid'=> $openid,
		);
	}
}else{
	$result= array(
			'cid' => $affrows, //未查到您的购课记录,请联系QQ211342495咨询 返回0
			'qqnum' => '',
			'uid'=>'',
	);
}
echo json_encode($result);
?>