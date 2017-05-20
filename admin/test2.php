<?php
//修改数据
echo '<br/>修改<br/>';



//echo modify('zp_userinfo',array('figureurl'=>'11111','openid'=>'22222','qqanme'=>'33333'),'572233090');  

$model = array('figureurl'=>'11111','openid'=>'22222','qqanme'=>'33333');
$table_name = 'zp_userinfo';
foreach($model as $key=>$value){
	$columns=empty($columns)?
	sprintf("%s='%s'",$key,$value):
	sprintf("%s,%s='%s'",$columns,$key,$value);
}
$where = "qqnum = 572233090";
echo $sqlcmd=sprintf("update %s set %s where %s",$table_name,$columns,$where);

/* function modify($table_name,$model,$primary){
	$columns='';
	$wheres='';
	foreach($model as $key=>$value){
		$columns=empty($columns)?
		sprintf("`%s`='%s'",$key,$value):
		sprintf("%s,`%s`='%s'",$columns,$key,$value);
	}
	$wheres=sprintf("`%s`='%s'",$primary['qqnum']);
	$sqlcmd=sprintf("UPDATE `%s` SET %s WHERE %s",$table_name,$columns,$wheres);
	echo $sqlcmd;
}  
 */