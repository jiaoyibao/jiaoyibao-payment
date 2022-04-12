<?php
$PID='';
$KEY='';

$json_param=$_REQUEST;
if($json_param['merchantId']==$PID and $_REQUEST['tradeStatus']=='success'){
	$requestsign = $json_param['sign'];
	unset($json_param['sign']);//从数组中剔除sign，因为他不参与签名
	
	ksort($json_param);////按照第一个字符的键值 ASCII 码递增排序（字母升序排序），如果遇到相同字符则按照第二个字符的键值 ASCII 码递增排序，以此类推。
	reset($json_param);
	foreach ($json_param as $pars) {
		$md5json_param.=$pars;////将排序后的值，组合起来，此时生成的字符串为待签名字符串。
	}
	$signmd5=md5($md5json_param.$KEY);//将Key 作为密钥使用 MD5 加密方式对待签名字符串进行签名得到sign值。
	if($signmd5==$requestsign){//对比生成的sign与传回的sign是否想同
		//验证成功，接下来需要做金额对比（判断金额是否一至），订单状态确认（防止二次处理）
		
		
		echo 'success';//务必返回success
		
	}else{
		echo 'fail';
	}
}else{
	echo 'fail';
}

?>
