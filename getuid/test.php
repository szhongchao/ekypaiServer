<?php

set_time_limit(0);//设置超时时间
$gurl= 'http://qzapp.qlogo.cn/qzapp/101403674/BCE6AAFE34F0BFCF486E1124DD085C77/30';
$gfile=file_get_contents($gurl);
$qurl= 'http://qlogo3.store.qq.com/qzone/572233090/572233090/30';
$qfile=file_get_contents($qurl);
if(sha1($qfile) == sha1($gfile) and md5($qfile) == md5($gfile)){
	echo "成功";
}