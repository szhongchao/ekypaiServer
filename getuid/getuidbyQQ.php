<?php
require_once '../configs/configs.php';
require_once '../lib/mysql.func.php';
$tp = connect();
$qqnum = $_GET['qqnum'];
//$qqnum = '2113424951';
set_time_limit(0);//设置超时时间
$sql="select * from zp_userinfo where qqnum='{$qqnum}'";
//根据登录名和密码查询数据表
$result=mysqli_query($tp,$sql);
$affrows = mysqli_affected_rows($tp);
if($result->num_rows == 1){
	
	$openidmd5=md5($_COOKIE['oid']);
	$row = $result->fetch_assoc();
	if($openidmd5 == $row['openidmd5']){
		$result= array(
				'cid' => $affrows,
				'qqnum' => $qqnum,
				'uid'=>$row['uid'],
		);
	}
	elseif($row['openidmd5']==null or $row['openidmd5']==""){
		$gurl= $_COOKIE['figureurl'];
		//$gender =$arr["gender"];//用户性别
		$nickname = $_COOKIE['nickname'];//用户昵称
		$gfile=file_get_contents($gurl);
		$qurl= $row['figureurl'];
		$qfile=file_get_contents($qurl);
		if(base64_encode($qfile) == base64_encode($gfile) and base64_encode($nickname) == $row['qqname']){
			$sqlu="update zp_userinfo set openidmd5 = '{$openidmd5}' where qqnum='{$qqnum}'";
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
					'openidmd5'=> $openidmd5,
			);
		}
	}else{
		$result= array(
				'cid' => '2',//自助绑定失败,请联系QQ211342495客服绑定
				'qqnum' => $qqnum,
				'uid'=>'',
				'openidmd5'=> $openidmd5,
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