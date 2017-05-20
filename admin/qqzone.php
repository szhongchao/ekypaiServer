<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
 $arrysub =  array();
$arryPUser = getPhoneInfo();
 if($arryPUser['adminId'] == "" || $arryPUser['adminId'] == null ){
 	$post_data = array(
 			'cid' => '登录失败',
 	);
 	$arrysub[] = $post_data;
 	die(json_encode($arrysub));
 } 
$qqnum=$_POST['qqnum'];
$p_skey=$_POST['p_skey'];
$gpname = $_POST['gpname'];
 //$g_tk = $_POST['g_tk']; 
 $maxcode = 6;
 set_time_limit(0);//设置超时时间
 /* $qqnum='211342495';
$p_skey='pKtdJxQchuryjFjFENoooqqleyTTbxxCknziBqu7GTE_';
$gpname ='18-TfpgSchool';
$g_tk ='867583187';   */

$hash= 5381;
 for($i=0;$i<strlen($p_skey);++$i){
 $hash =gmp_add($hash , gmp_add(($hash << 5),ord($p_skey[$i])));
 }
 $g_tk = $hash & 0x7fffffff;

$Cookie= 'uin=o'.$qqnum.';  p_uin=o'.$qqnum.'; p_skey='.$p_skey;
$url = 'https://h5.qzone.qq.com/proxy/domain/r.qzone.qq.com/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin='.$qqnum.'&follow_flag=1&groupface_flag=0&fupdate=1&g_tk='.$g_tk;
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_COOKIE, $Cookie);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
$data = curl_exec($curl);
curl_close($curl);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);
$dataJson=json_decode($data);
if($dataJson->code =='0') {
	$itemsArray = $dataJson->data->items;
	$gpnamesArray = $dataJson->data->gpnames;
	$TfpgId ="-1";
	for ($i= 0;$i< count($gpnamesArray); $i++){
		$gpnames=json_encode($gpnamesArray[$i]);
		$gpnameArray= json_decode($gpnames,true);
		if($gpnameArray["gpname"]==$gpname){
			$TfpgId= $gpnameArray["gpid"];
		}
	}
	//从数据库选出机器码
	$sqls= 'select  * from zp_userinfo';
	$result= mysqli_query($tp,$sqls);
	$itemsrows =  array();
	while($itemsrow = mysqli_fetch_array($result)){
		$itemsrows[]= $itemsrow;
	}
	for ($i= 0;$i< count($itemsArray); $i++){
		$items=json_encode($itemsArray[$i]);
		$itemArray= json_decode($items,true);
		if($itemArray["groupid"] == $TfpgId){
			$pass = true;
			$fileidstr= conFS($itemArray["remark"],$maxcode);
			$qqname = base64_encode($itemArray["name"]);
			for($j=0;$j<count($itemsrows);$j++){
				$row=$itemsrows[$j];
				if($itemArray["uin"] == $row['qqnum']){
					$model= array();
					if($itemArray["img"] != $row['figureurl']){ //添加QQzone头像
						$model['figureurl'] = $itemArray["img"];
					}
					if($qqname != $row['qqname']){ //添加QQ姓名
						
						$model['qqname'] = $qqname;
					}
					if($fileidstr != $row['fileidstr']){
						$model['fileidstr'] = $fileidstr;
					}
					if(count($model) >0){
						$columns="";
						foreach($model as $key=>$value){
							$columns=empty($columns)?
							sprintf("%s='%s'",$key,$value):
							sprintf("%s,%s='%s'",$columns,$key,$value);
						}
						$sql = sprintf("update zp_userinfo set %s where qqnum={$row['qqnum']}",$columns);
						$result = mysqli_query($tp,$sql);
						$post_data = array(
								'cid' => '1',
								'pwd'=> '',
								'drmsts'=> '1',
								'qqnum' => $itemArray["uin"],
								'fileidstr'=> $fileidstr,
								'pcnum'=>'2',
								'licnumbers'=> '0'
						);
						$arrysub[] = $post_data;
					}
					$pass = false;
					break;
				}
			}
			if($pass){
				$uid=getCode(15);
				$sql="insert into zp_userinfo(uid,drmsts,qqnum,fileidstr,pcnum,licnumbers,figureurl,qqname) values({$uid},1,{$itemArray["uin"]},{$fileidstr},2,0,{$itemArray["img"]},{$qqname})";
				$result = mysqli_query($tp,$sql);
				$post_data = array(
						'cid' => '1',
						'pwd'=> '',
						'drmsts'=> '1',
						'qqnum' => $itemArray["uin"],
						'fileidstr'=> $fileidstr,
						'pcnum'=>'2',
						'licnumbers'=> '0'
				);
				$arrysub[] = $post_data;
			}
		}
	}
	
}else{
	$post_data = array(
			'cid' => 'QQ空间登录失败',
	);
	$arrysub[] = $post_data;
}
//返回操作的用户
echo json_encode($arrysub);

function conFS($remark,$maxcode){
	$fileidstr ='';
	preg_match_all ( '/([a-z]+)([0-9]+)/i', $remark, $fileidstrAarr );
	for($j = 0; $j < count ( $fileidstrAarr [1] ); $j ++) {
		for($k=0;$k<strlen($fileidstrAarr[2][$j]);$k++){
			if($fileidstrAarr [2] [$j] [$k] !='0'){
				$fileidstr = $fileidstr.$fileidstrAarr[1][$j].'0'.$fileidstrAarr [2] [$j] [$k].'|';
			}else{
				for($z=1;$z<$maxcode;$z++){
					$fileidstr = $fileidstr.$fileidstrAarr[1][$j].'0'.$z.'|';
				}
			}
		}
	}
	return substr($fileidstr,0,strlen($fileidstr)-1);
}

function getCode($iCount){
	$arrChar = strtoupper("0123456789123456789123456789123456789123456789");
	$k=strlen($arrChar);
	$strCode = '';
	For ($i=0; $i<$iCount;$i++)
	{
		$j = mt_rand(0,45);
		$strCode .= substr($arrChar,$j,1);
	}
	return $strCode;
}

