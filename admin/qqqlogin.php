<?php
function qqlogin(){
$qqno='2014336837';
$qqpw='q12345w12345';
$cookie = dirname(__FILE__).'/cookie.txt';
$post = array( 
'login_url' => 'http://pt.3g.qq.com/s?sid=ATAll43N7ZULRQ5V8zdfojol&aid=nLogin',
'q_from' => '', 
'loginTitle' => 'login', 
'bid' => '0', 
'qq' => $qqno, 
'pwd' => $qqpw, 
'loginType' => '1', 
'loginsubmit' => 'login',
);
$url = 'http://pt.3g.qq.com/handleLogin?aid=nLoginHandle&sid=ATAll43N7ZULRQ5V8zdfojol';//请求url
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, 0); 
curl_setopt($curl, CURLOPT_URL,$url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
// ?Cookie
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
$result = curl_exec($curl);
curl_close($curl);
}
?>
<?php
qqlogin();
?>