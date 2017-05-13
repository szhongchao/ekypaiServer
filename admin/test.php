<?php
header('Content-type:text/json;charset=utf-8');

$fileidstr = 'po0en0ma0';
preg_match_all ( '/([a-z]+)([0-9]+)/i', $fileidstr, $fileidstrAarr );
$fileidstr= '';
for($i = 0; $i < count ( $fileidstrAarr [1] ); $i ++) {
	for($j=0;$j<strlen($fileidstrAarr[2][$i]);$j++){
		if($fileidstrAarr [2] [$i] [$j] !='0'){
			$fileidstr = $fileidstr.$fileidstrAarr[1][$i].'0'.$fileidstrAarr [2] [$i] [$j].'|';
		}else{
			for($z=1;$z<9;$z++){
				$fileidstr = $fileidstr.$fileidstrAarr[1][$i].'0'.$z.'|';
			}
		}
		
	}
}
$fileidstr= substr($fileidstr,0,strlen($fileidstr)-1);
echo $fileidstr;


if($fileidstrAarr [2] [$j] [$k]!='0'){
	$fileidstr = $fileidstr.$fileidstrAarr[1][$j].'0'.$fileidstrAarr [2] [$j] [$k].'|';
}else{
	for($z=1;$z<$maxcode;$z++){
		$fileidstr = $fileidstr.$fileidstrAarr[1][$i].'0'.$z.'|';
	}
}
