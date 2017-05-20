<?php
$qqnum = "3402935632";
$qurl= "http://q2.qlogo.cn/headimg_dl?dst_uin=".$qqnum."&spec=100";
$qfile=file_get_contents("$qurl");

$gurl = "https://q3.qlogo.cn/g?b=qq&k=c5H7lm19DwvibGe25MGZ5ng&s=100&t=1481941656";
$gfile=file_get_contents($gurl);

if(base64_encode($qfile) == base64_encode($gfile)){
	echo "base64相同 </br>";
}else{
	echo "base64不相同 </br>";
}