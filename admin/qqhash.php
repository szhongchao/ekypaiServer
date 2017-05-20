<?php
/**
 * 申请http://connect.opensns.qq.com/apply
 * 列表http://connect.opensns.qq.com/my
 */
session_start();
$qq_oauth_config = array(
	'oauth_consumer_key'=>'*******',//APP ID
	'oauth_consumer_secret'=>'******************',//APP KEY
	'oauth_callback'=>"http://192.168.2.11:81/admin/qq.php?action=reg",//这里修改为当前脚本
	'oauth_request_token_url'=>"http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token",
	'oauth_authorize_url'=>'http://openapi.qzone.qq.com/oauth/qzoneoauth_authorize',
	'oauth_request_access_token_url'=>'http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token',
	'user_info_url' => 'http://openapi.qzone.qq.com/user/get_user_info',
);
$action = isset($_GET['action']) ? $_GET['action'] : '';


$qq = new qq_oauth($qq_oauth_config);
switch($action){
	//用户登录 Step1：请求临时token
	case 'login':
		$token = $qq->oauth_request_token();
		$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
		$qq->authorize($token['oauth_token']);
	break;
	//Step4：Qzone引导用户跳转到第三方应用
	case 'reg':
		$qq->register_user();
		$access_token = $qq->request_access_token();
		if($token = $qq->save_access_token($access_token)){
			//保存,一般发给用户cookie,以及用户入库
			//var_dump($token);
			$_SESSION['oauth_token'] = $token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
			$_SESSION['openid'] = $token['openid'];
			header('Content-Type: text/html; charset=utf-8');
			$user_info = json_decode($qq->get_user_info());
			if($user_info->ret!=0){
				exit("获取头像昵称时发生错误".$user_info->msg);
			} else {
				echo 'QQ昵称：',$user_info->nickname,
				'<img src="',$user_info->figureurl,'" />',
				'<img src="',$user_info->figureurl_1,'" />',
				'<img src="',$user_info->figureurl_2,'" />';
			}
			
		}
	break;
	default :
}


class qq_oauth{
	private $config;
	function __construct($config){
		$this->config = $config;
	}
	/**
	 * 返回配置
	 * @param string $name
	 * 
	 */
	function C($name){
		return isset($this->config[$name]) ?  $this->config[$name] : FALSE;
	}
	/**
	 * 构建请求URL
	 * @param string $url
	 * @param array $params
	 * @param string $oauth_token_secret
	 * 
	 */
	function build_request_uri($url,$params=array(),$oauth_token_secret=''){
		$oauth_consumer_key = $this->C('oauth_consumer_key');
		$oauth_consumer_secret = $this->C('oauth_consumer_secret');
		
		$params = array_merge(array(
			'oauth_version'=>'1.0',
			'oauth_signature_method'=>'HMAC-SHA1',
			'oauth_timestamp'=>time(),
			'oauth_nonce'=>rand(1000,99999999),
			'oauth_consumer_key'=>$oauth_consumer_key,
		),$params);
		$encode_params = $params;
		ksort($encode_params);
		$oauth_signature = 'GET&'.urlencode($url).'&'.urlencode(http_build_query($encode_params));
		$oauth_signature = base64_encode(hash_hmac('sha1',$oauth_signature,$oauth_consumer_secret.'&'.$oauth_token_secret,true));
		$params['oauth_signature'] = $oauth_signature;
		return $url.'?'.http_build_query($params);
	}
	/**
	 * 校验回调是否返回约定的参数 
	 */
	function check_callback(){
		if(isset($_GET['oauth_token']))
			if(isset($_GET['openid']))
				if(isset($_GET['oauth_signature']))
					if(isset($_GET['timestamp']))
						if(isset($_GET['oauth_vericode']))
							return true;
		return false;
	}
	
	function get_contents($url){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl,CURLOPT_URL,$url);
		return curl_exec($curl);
	}
	/**
	 * Step1：请求临时token、Step2：生成未授权的临时token
	 */
	function oauth_request_token(){
		$url = $this->build_request_uri($this->C('oauth_request_token_url'));
		$tmp_oauth_token = $this->get_contents($url);
		parse_str($tmp_oauth_token);
		/*
		oauth_token	未授权的临时token
		oauth_token_secret	token的密钥，该密钥仅限于临时token
		error_code	错误码
		*/
		if(isset($error_code)) exit($error_code);
		return array(
			'oauth_token'=>$oauth_token,
			'oauth_token_secret'=>$oauth_token_secret
		);
	}
	/**
	 * Step3：引导用户到Qzone的登录页
	 * @param string $oauth_token 未授权的临时token
	 */
	function authorize($oauth_token){
		$str = "HTTP/1.1 302 Found";
		header($str);
		$url = $this->C('oauth_authorize_url');
		$query_strings = http_build_query(array(
			'oauth_consumer_key'=>$this->C('oauth_consumer_key'),
			'oauth_token'=>$oauth_token,
			'oauth_callback'=>$this->C('oauth_callback'),
		));
		header('Location: '.$url.'?'.$query_strings);
	}
	/**
	 * Step4：Qzone引导用户跳转到第三方应用
	 * @return bool 验证是否有效 
	 */
	function register_user(){
		/*
		 * oauth_token	已授权的临时token
		 * openid	腾讯用户对外的统一ID，该OpenID与用户QQ号码一一对应
		 * oauth_signature	签名值，方便第三方来验证openid以及来源的可靠性。
		 * 		使用HMAC-SHA1算法：
		 * 		源串：openid+timestamp（串中间不要添加'+'符号）
		 * 		密钥：oauth_consumer_secret
		 * timestamp	openid的时间戳
		 * oauth_vericode	授权验证码。
		 */
		if($this->check_callback()){
			//校验签名
			$signature = base64_encode(hash_hmac('sha1',$_GET['openid'].$_GET['timestamp'],$this->C('oauth_consumer_secret'),true));
			if(!empty($_GET['oauth_signature']) && $signature==$_GET['oauth_signature']){
				$_SESSION['oauth_token'] = $_GET['oauth_token'];
				$_SESSION['oauth_vericode'] = $_GET['oauth_vericode'];
				return;
			}
		}
		//校验未通过
		exit('UNKNOW REQUEST');
	}
	/**
	 * Step5：请求access token 
	 */
	function request_access_token(){
		$url = $this->build_request_uri($this->C('oauth_request_access_token_url'),array(
			'oauth_token'=>$_SESSION['oauth_token'],
			'oauth_vericode'=>$_SESSION['oauth_vericode']
		),$_SESSION['oauth_token_secret']);
		return $this->get_contents($url);
	}
	/**
	 * Step6：生成access token （保存access token）
	 * 
	 * 关于access_token
	 * 目前access_token(及其secret)是长期有效的，和某一个openid对应，目前可以支持线下获取该openid的信息。 
	 * 当然，用户有权限在Qzone这边删除对第三方的授权，此时该access_token会失效，需要重新走整个流程让用户授权。
	 * 以后会逐步丰富access_token的有效性，长期有效、短期有效、用户登录时才有效等。
	 */
	function save_access_token($access_token_str){
		parse_str($access_token_str,$access_token_arr);
		if(isset($access_token_arr['error_code'])){
			return FALSE;
		} else {
			return $access_token_arr;
		}
	}
	/**
	 * 目前腾讯仅开放该API
	 * 获取登录用户信息，目前可获取用户昵称及头像信息。
	 * http://openapi.qzone.qq.com/user/get_user_info
	 */
	function get_user_info(){
		$url = $this->build_request_uri($this->C('user_info_url'),array(
			'oauth_token'=>$_SESSION['oauth_token'],
			'openid'=>$_SESSION['openid'],
		),$_SESSION['oauth_token_secret']);
		return $this->get_contents($url);
	}
}
