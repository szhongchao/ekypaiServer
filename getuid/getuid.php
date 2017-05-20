<?php
require_once("API/qqConnectAPI.php");
$qc = new QC();
$qc->qq_callback();
$qc->get_openid();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!-- Bootstrap -->
<link href="images/bootstrap.min.css" rel="stylesheet">
<link href="images/main.css" rel="stylesheet">
<link href="images/enter.css" rel="stylesheet">
<link rel="stylesheet" href="images/loading.css">
<script src="images/jquery.min.js"></script>
<script src="images/bootstrap.min.js"></script>
<script src="images/jquery.particleground.min.js"></script>
<script src="images/loading.js"></script>
</head>
<body>
<div id="particles">
  <canvas class="pg-canvas" width="1920" height="911" style="display: block;"></canvas>
  <div class="intro" style="margin-top: -256.5px;">
    <div class="container">
      <div class="row" style="padding:30px 0;">
        <div class="col-md-3 col-centered tac"> <img src="images/logo.png" alt="logo"> </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div id="showuid" class="col-md-3 col-sm-8 col-centered">
           <!-- <div class="form-group">
             <input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="请输入购课QQ号码" value="2113424951" autocomplete="off" aria-required="true" data-tip="英文字母数字或下划线">
           </div>
           <div class="form-group">
             <input type="text" class="form-control" id="uid" name="uid" placeholder="此处自动显示课程机器码"  autocomplete="off" aria-required="true" data-tip="英文字母数字或下划线">
           </div>
           <div id="validator-tips" class="validator-tips"></div>
           <div class="checkbox">
             <label><input type="checkbox" value="checkbox" checked="checked" name="ag">同意 </label>
             <a href="javascript:void(0)" id="userAgreement" style="text-decoration:none">用户协议</a> </div>
           <div class="form-center-button">
             <button type="submit" id="submit_button" onclick="getuidbyQQ();" class="btn btn-primary btn-current">获取课程激活码</button>
           </div> -->
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function () {
        var intro = $('.intro');
        $('#particles').particleground({
            dotColor: 'rgba(52, 152, 219, 0.36)',
            lineColor: 'rgba(52, 152, 219, 0.86)',
            density: 10000,
            proximity: 200,
            lineWidth: 0.2
        });
        intro.css({
            'margin-top': -(intro.height() / 2 + 100)
        });
       getuidbyAT();
    });
var showuid = '<div id="qqnum" class="form-group"></div>'+
			  '<div id="uid" class="form-group"></div>'+
			  '<div id="validator-tips" class="validator-tips"></div>'+
			  '<div class="form-group"> 如有误请及时联系QQ客服211342495</div>';
var getuid = '<div class="form-group">'+
                '<input type="text" class="form-control" id="qqnum" name="qqnum" value="" placeholder="请输入购课QQ号码"  autocomplete="off" aria-required="true" data-tip="英文字母数字或下划线">'+
			'</div>'+
			'<div id="validator-tips" class="validator-tips"></div>'+
			'<div class="checkbox">'+
				'<label><input type="checkbox" value="checkbox" checked="checked" name="ag">同意 </label>'+
				'<a href="javascript:void(0)" id="userAgreement" style="text-decoration:none">用户协议</a> </div>'+
			'<div class="form-center-button">'+
				'<button type="submit" id="submit_button" onclick="getuidbyQQ();" class="btn btn-primary btn-current">获取课程激活码</button>'+
			'</div>';
var excep = '<div id="qqnum" class="form-group"></div>'+
			'<div id="uid" class="form-group"></div>';
var getuidbyQQ = function(){
      var load = new Loading();
      load.init();
      load.start();
      var qqnum = $("#qqnum").val();
   	  $.ajax({url:"getuidbyQQ.php?qqnum="+qqnum,
   		 success:function(result){
   			 var result=JSON.parse(result);
   			 if(result.cid == "1"){
   				 $("#showuid").html(showuid);
   				 $("#qqnum").html("您的购课QQ号是:"+result.qqnum); 
   				 $("#uid").html("您的课程激活码是:"+result.uid);
   			 }else if(result.cid == "0"){
   				 $("#showuid").html(excep);
   				 $("#qqnum").html("未查到您的购课记录,如果您已购课");
   				 $("#uid").html("请联系QQ客服211342495处理");
   			 }else if(result.cid == "2"){
   				$("#showuid").html(excep);
  				$("#qqnum").html("自助绑定失败,请用您的购课QQ:"+result.qqnum+"联系QQ客服211342495");
  				$("#uid").html("并把您的自助编码:"+result.openid+"发给QQ客服进行绑定");
   			 }else{
   				$("#showuid").html(excep);
 			    $("#qqnum").html("未知错误");
 			  	$("#uid").html("请联系QQ客服211342495处理");
   			 }
   			load.stop();
   	    	}
   		 });
   }
  
 var getuidbyAT = function(){
  	var load = new Loading();
	load.init();
	load.start();
  	 $.ajax({url:"getuidbyAT.php",
  		 success:function(result){
  			 var result=JSON.parse(result);
  			 if(result.cid == "1"){
  				 $("#showuid").html(showuid);
  				 $("#qqnum").html("您的购课QQ号是:"+result.qqnum); 
  				 $("#uid").html("您的课程激活码是:"+result.uid);
  			 }else{
  				 $("#showuid").html(getuid);
  			 }
  			 load.stop();
  	    	}
  		 });
    }
</script>
</body>
</html>