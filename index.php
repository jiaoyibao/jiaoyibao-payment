<?php
$URL='http://payment.jiaoyibao.me/payment/';
$PID='';
$KEY='';

$orderid=date('YmdHis',time()).rand(100,999);//生成订单号
$parameter = array(
	"merchantId" => $PID,
	"outTradeNo"	=> $orderid,
	"subject"	=> '名称',//商品名称(非空)
	"totalAmount"	=> '0.5',//商品金额大于等于0.5
	"body"	=> '描述',
	"noUrl"	=> "http://你的域名/demo/noUrl.php",//回调地址
	"reUrl"	=> "http://你的域名/demo/",//支付成功跳转地址（不会传递任何参数，一般为订单详情url）
	"payType"	=> 'alipaymq'//请在交易宝控制台中获取支付类型参数，不定时新增
);
ksort($parameter);//按照第一个字符的键值 ASCII 码递增排序（字母升序排序），如果遇到相同字符则按照第二个字符的键值 ASCII 码递增排序，以此类推。
reset($parameter);
foreach ($parameter as $pars) {
	$myparameter.=$pars;//将排序后的值，组合起来，此时生成的字符串为待签名字符串。
}
$signs=md5($myparameter.$KEY);//将Key 作为密钥使用 MD5 加密方式对待签名字符串进行签名得到sign值。
$parameter['sign']=$signs;//将sign加入到数据中

$backcont=curlphp($URL,$parameter,1);//POST提交，获取返回值
$backcontarray=json_decode($backcont,true);
if(empty($backcontarray)){
	echo '系统繁忙，请稍后再试';
}else{
	if($backcontarray['code']=='1'){//返回值 1成功，-1失败
		//返回的url地址，可用header()进行跳转
		header("Location: ".$backcontarray['msg']); 
	}else{
		echo $backcontarray['msg'];//输出失败的原因
	}
}


function curlphp($url,$params=false,$ispost=0){//0为get 1为post 
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
	curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
	curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);//禁用后cURL将终止从服务端进行验证
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);//不验证HTTPS证书
	if( $ispost )
	{
		curl_setopt( $ch , CURLOPT_POST , true );
		curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
		curl_setopt( $ch , CURLOPT_URL , $url );
	}
	else
	{
		if($params){
			curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
		}else{
			curl_setopt( $ch , CURLOPT_URL , $url);
		}
	}
	$response = curl_exec( $ch );
	if ($response === FALSE) {
		echo "cURL Error: " . curl_error($ch);
		return false;
	}
	curl_close( $ch );
	return $response;
}
		
