<?php
header('Content-type:text/json;charset=utf-8');

$qqnum='211342495';
$p_skey='8KJRsSJHuRBmul-3Bdarivx0auw*nz1PFT9NE5paj7c_';
$gpname ='19-TfpgSchool';
$g_tk = '1603508514';

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

$data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);
$dataJson=json_decode($data);
$code= $dataJson->code;


echo $data;
