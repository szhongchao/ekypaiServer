<?php
require_once '../include.php';
$qqnum = $_POST['account'];
//$qqnum = '2113424951';
$access_token = "11111111";

$gurl='https://q4.qlogo.cn/g?b=qq&k=0ywa6hMxq4nSDCJ3qYhZKA&s=100&t=0';

$sql="select * from zp_userinfo where qqnum='{$qqnum}'";
//根据登录名和密码查询数据表
$result=mysqli_query($tp,$sql);
if($result->num_rows == 1){
	$row = $result->fetch_assoc();
	if($row['access_token']==null or $row['access_token']==""){
		//$gurl= $_POST['gurl'];
		$gfile=file_get_contents($gurl);
		
		$qurl= $row['figureurl'];
		$qfile=file_get_contents($qurl);
		
		if(sha1($qfile) == sha1($gfile) and md5($qfile) == md5($gfile)){
			echo sha1($qfile)."++++".sha1($gfile);
			echo "qq号码是:".$qqnum."的课程激活密码是:".$row['uid'];
		}else{
			echo "请把您的账号ID:".$access_token."用您的购课QQ:".$qqnum."发给客服QQ211342495进行绑定!";
		}
		
	}elseif($access_token  ==$row['access_token']){
		echo $access_token."+++++".$row['access_token'];
		echo "qq号码是:".$qqnum."的课程激活密码是:".$row['uid'];
	}else{
		echo "自助获取密码失败,如果您已购课程请把您的账号ID:".$access_token."发给客服QQ211342495进行绑定,完成绑定之后您便可以自助获取密码";
	}
}else{
	echo "未查到您的购课记录,请联系QQ211342495咨询";
}
?>