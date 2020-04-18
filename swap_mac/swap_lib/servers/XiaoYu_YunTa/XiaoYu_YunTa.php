<?php

//SWAP对接云塔IDC系统
//作责：地狱筱雨
//邮箱：2119566807@qq.com

function XiaoYu_YunTa_POSTDATA($params){
	$return = file_get_contents($params['url']."?".http_build_query($params['data']));
	return json_decode($return,true);
}

function XiaoYu_YunTa_ConfigOptions()
{
    $configarray = array("产品ID" => array("Type" => "text", "Description" => "产品id"), "开通优惠码" => array("Type" => "text", "Description" => "可用于代理开通的优惠码，咨询上级"));
    return $configarray;
}
function XiaoYu_YunTa_CreateAccount($params){
	$cp = get_query_vals('服务', '*', array('id' => $params['serviceid']));
	$year = json_decode($cp['周期'], true);
	$year = $year['介绍'];
    $XiaoYu_YunTa_POSTDATA = array(
		'url' => $params['serverhostname'] . "api/User/CreateService.php",
		'data' => array(
			'ytidc_user' => $params['serverusername'],
            'ytidc_pass' => $params['serverpassword'],
            'username' => $params['username'],
            'password' => $params['password'],
			'time' => $year,
			'product' => $params['configoption1'],
			'promo_code' => $params['configoption2'],
		),
	);
    $content = XiaoYu_YunTa_POSTDATA($XiaoYu_YunTa_POSTDATA);
	if($content['msg'] == "开通成功"){
		if(!empty($content['username']) || !empty($content['password'])){
			if(empty($content['username'])){
				$content['username'] = $params['username'];
			}
			if(empty($content['password'])){
				$content['password'] = $params['password'];
			}
			update_query("服务", array("密码" => encrypt($content['password']), "用户名"=>$content['username']),array("id" => $params['serviceid']));
			return '成功';
		}else{
			return '成功';
		}
	}else{
		return $content['msg'];
	}
}

function XiaoYu_YunTa_ServerRenewalAccount($params){
	$cp = get_query_vals('服务', '*', array('id' => $params['serviceid']));
	$product = get_query_vals('产品', '*', array('id' => $cp['产品id']));
	$cycleid = $_POST['cycleid'];
	$year = json_decode($product['周期'], true);
	$year = $year[$cycleid]['介绍'];
    $XiaoYu_YunTa_POSTDATA = array(
		'url' => $params['serverhostname'] . "api/User/RenewService.php",
		'data' => array(
			'ytidc_user' => $params['serverusername'],
			'ytidc_pass' => $params['serverpassword'],
			'username' => $params['username'],
			'time' => $year,
		),
	);
    $content = XiaoYu_YunTa_POSTDATA($XiaoYu_YunTa_POSTDATA);
	if($content['status'] == 'success'){
        return '成功';
    }else{
        return $content['msg'];
    }
}

function XiaoYu_YunTa_SuspendAccount($params){
	return '成功';
}

function XiaoYu_YunTa_TerminateAccount($params){
	return '成功';
}

function XiaoYu_YunTa_ClientArea($params ){	
	$OSWAP_5fbd4faf3630168a85b97b131d2fb6ea = array('<form name="frm" action="'.$params["serverhostname"].'api/User/LoginService.php" method="get" target="_blank"><input type="hidden" value="login" name="module"><input name="username" type="hidden" value="'.$params["username"].'"><input name="password" type="hidden" value="'.$params["password"].'"></form><a href="javascript:document.frm.submit();
">登入控制面板</a>');
 return $OSWAP_5fbd4faf3630168a85b97b131d2fb6ea;
}

function XiaoYu_YunTa_ResetPassword($params){
	return '不支持修改密码';
}

function XiaoYu_YunTa_ChangePackage($params){
	return '不支持修改密码';
}

?>