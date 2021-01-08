<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/12 17:08
// +----------------------------------------------------------------------
namespace app\appapi\controller;
use cmf\controller\HomeBaseController;
use think\Db;

/**
 * 课程支付回调
 */
class CoursepayController extends HomebaseController {
	//支付宝 回调
	public function notify_ali() {
        $configpri=getConfigPri();
		require_once(CMF_ROOT."sdk/alipay_app/alipay.config.php");
        //合作身份者id，以2088开头的16位纯数字
        $alipay_config['partner']		= $configpri['aliapp_partner'];
        
		require_once(CMF_ROOT."sdk/alipay_app/lib/alipay_core.function.php");
		require_once(CMF_ROOT."sdk/alipay_app/lib/alipay_rsa.function.php");
		require_once(CMF_ROOT."sdk/alipay_app/lib/alipay_notify.class.php");

		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		$this->logali("ali_data:".json_encode($_POST));
		if($verify_result) {//验证成功
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			
			//交易金额
			$total_fee = $_POST['total_fee'];
			
			if($_POST['trade_status'] == 'TRADE_FINISHED') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		
			}else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	
				$orderinfo=Db::name('course_users')->where("orderno='{$out_trade_no}' and type='1'")->find();
				$this->logali("orderinfo:".json_encode($orderinfo));	
				if($orderinfo){
                    if($orderinfo['status']==0){
                        /* 更新 订单状态 */
                        Db::name('course_users')->where("id='{$orderinfo['id']}'")->update(array( "status"=>1,"trade_no"=>$trade_no,"paytime"=>time() ));

                        $this->logali("orderno:".$out_trade_no."成功");
                    }else{
                        $this->logali("orderno:".$out_trade_no."已确认支付成功");
                    }

					echo "success";		//请不要修改或删除
					exit;
				}else{
					$this->logali("orderno:".$out_trade_no.' 订单信息不存在');		
				}											
			}
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

			echo "fail";		//请不要修改或删除			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else {
			$this->logali("验证失败");		
			//验证失败
			echo "fail";
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}			
		
	}
	/* 支付宝支付 */
	
	/* 微信支付 */
    private $wxDate = null;
	public function notify_wx(){
		$configpri=getConfigPri();
		//$xmlInfo = $GLOBALS['HTTP_RAW_POST_DATA'];
		$xmlInfo=file_get_contents("php://input"); 

		//解析xml
		$arrayInfo = $this -> xmlToArray($xmlInfo);
		$this -> wxDate = $arrayInfo;
		$this -> logwx("wx_data:".json_encode($arrayInfo));//log打印保存
		if($arrayInfo['return_code'] == "SUCCESS"){
			if(isset($arrayInfo['return_msg']) && $arrayInfo['return_msg'] != null){
				echo $this -> returnInfo("FAIL","签名失败");
				$this -> logwx("签名失败:".$sign);//log打印保存
				exit;
			}else{
				$wxSign = $arrayInfo['sign'];
				unset($arrayInfo['sign']);
				$arrayInfo['appid']  =  $configpri['wx_appid'];
				$arrayInfo['mch_id'] =  $configpri['wx_mchid'];
				$key =  $configpri['wx_key'];
				ksort($arrayInfo);//按照字典排序参数数组
				$sign = $this -> sign($arrayInfo,$key);//生成签名
				$this -> logwx("数据打印测试签名signmy:".$sign.":::微信sign:".$wxSign);//log打印保存
				if($this -> checkSign($wxSign,$sign)){
					echo $this -> returnInfo("SUCCESS","OK");
					$this -> logwx("签名验证结果成功:".$sign);//log打印保存
					$this -> orderServer();//订单处理业务逻辑
					exit;
				}else{
					echo $this -> returnInfo("FAIL","签名失败");
					$this -> logwx("签名验证结果失败:本地加密：".$sign.'：：：：：三方加密'.$wxSign);//log打印保存
					exit;
				}
			}
		}else{
			echo $this -> returnInfo("FAIL","签名失败");
			$this -> logwx($arrayInfo['return_code']);//log打印保存
			exit;
		}			
	}
	
	private function returnInfo($type,$msg){
		if($type == "SUCCESS"){
			return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code></xml>";
		}else{
			return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code><return_msg><![CDATA[{$msg}]]></return_msg></xml>";
		}
	}		
	
	//签名验证
	private function checkSign($sign1,$sign2){
		return trim($sign1) == trim($sign2);
	}
	/* 订单查询加值业务处理
	 * @param orderNum 订单号	   
	 */
	private function orderServer(){
		$info = $this -> wxDate;
		$this->logwx("info:".json_encode($info));
		$orderinfo=Db::name('course_users')->where("orderno='{$info['out_trade_no']}' and type='2'")->find();
		//$this->logwx("sql:".M()->getLastSql());
		$this->logwx("orderinfo:".json_encode($orderinfo));
		if($orderinfo){
            if($orderinfo['status']==0){
                /* 更新 订单状态 */
                Db::name('course_users')->where("id='{$orderinfo['id']}'")->update(array("status"=>1,"trade_no"=>$info['transaction_id'],"paytime"=>time()));
                
                $this->logwx("orderno:".$out_trade_no.' 支付成功');
            }else{
                $this->logwx("orderno:".$out_trade_no.' 已确认支付成功');
            }
		}else{
			$this->logwx("orderno:".$out_trade_no.' 订单信息不存在');		
			return false;
		}		

	}		
	/**
	* sign拼装获取
	*/
	private function sign($param,$key){
		
		$sign = "";
		foreach($param as $k => $v){
			$sign .= $k."=".$v."&";
		}
	
		$sign .= "key=".$key;
		$sign = strtoupper(md5($sign));
		return $sign;
	
	}
	/**
	* xml转为数组
	*/
	private function xmlToArray($xmlStr){
		$msg = array(); 
		$postStr = $xmlStr; 
		$msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
		return $msg;
	}
	
	/* 微信支付 */

    
    /* 打印log */
	protected function logali($msg){
		file_put_contents(CMF_ROOT.'data/log/course_logali_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
	}
    
	/* 打印log */
	protected function logwx($msg){
		file_put_contents(CMF_ROOT.'data/log/course_logwx_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
	}
    
}


