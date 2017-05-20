<?php
/*
 *调用接口代码
 *
 **/
require_once("API/qqConnectAPI.php");
$qc = new QC();
$oauth = new Oauth();
$arr = $qc->get_user_info();
echo '<meta charset="UTF-8">';
echo "<p>";
echo "openid:". $oauth ->get_openid();
echo "<p>";
echo "access_token:".$qc->get_access_token();
echo "<p>";
echo "Gender:".$arr["gender"];
echo "</p>";
echo "<p>";
echo "NickName:".$arr["nickname"];
echo "</p>";
echo "<p>";
echo "30头像:".$arr['figureurl'];
echo "<p>";
echo "<p>";
echo "50头像:".$arr['figureurl_1'];
echo "<p>";
echo "<p>";
echo "100头像:".$arr['figureurl_2'];
echo "</p>";

?>
