<?php
/*
 *调用接口代码
 *
 **/
require_once("API/qqConnectAPI.php");
$acs = $_COOKIE['acs'];
$oid = $_COOKIE['oid'];
$qc = new QC($acs,$oid);
$arr = $qc->get_user_info();

echo '<meta charset="UTF-8">';
echo "<p>";
echo "openid:". $oid;
echo "<p>";
echo "access_token:".$acs;
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
