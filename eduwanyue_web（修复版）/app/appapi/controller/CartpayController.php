<?php
/**
 * 购物车支付回调
 */

namespace app\appapi\controller;
use cmf\controller\HomeBaseController;
use think\Db;

class CartpayController extends HomebaseController {
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

                $where['orderno']=$out_trade_no;
                $where['type']=1;

                $data=[
                    'trade_no'=>$trade_no
                ];

				$this->logali("where:".json_encode($where));
                $res=handelPay($where,$data);
				if($res==0){
                    $this->logali("orderno:".$out_trade_no.' 订单信息不存在');
                    echo "fail";
                    exit;
				}

                $this->logali("成功");
                echo "success";		//请不要修改或删除
                exit;
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
    //支付宝 回调
	public function notify_pc_ali() {
        $configpri=getConfigPri();


        //读取后台配置信息
		$getConfigPri=getConfigPri();
		$getConfigPub=getConfigPub();
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']=$configpri['aliapp_partner'];
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $configpri['aliapp_check'];
		//支付宝账号
		$alipay_config['seller_email'] =$configpri['aliapp_seller_id'];
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = CMF_ROOT.'sdk/alipay/cacert.pem';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		require_once(CMF_ROOT."sdk/alipay/lib/alipay_notify.class.php");
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

                $where['orderno']=$out_trade_no;
                $where['type']=1;

                $data=[
                    'trade_no'=>$trade_no
                ];

				$this->logali("where:".json_encode($where));
                $res=handelPay($where,$data);
				if($res==0){
                    $this->logali("orderno:".$out_trade_no.' 订单信息不存在');
                    echo "fail";
                    exit;
				}

                $this->logali("成功");
                echo "success";		//请不要修改或删除
                exit;
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
				$this -> logwx("签名失败:");//log打印保存
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


    /* UNIAPP端小程序支付 */
    private $smallDate = null;
    public function notify_small(){
        $configpri=getConfigPri();
        //$xmlInfo = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xmlInfo=file_get_contents("php://input");

        //解析xml
        $arrayInfo = $this -> xmlToArray($xmlInfo);
        $this -> smallDate = $arrayInfo;
        $this -> logsmall("small_data:".json_encode($arrayInfo));//log打印保存
        if($arrayInfo['return_code'] == "SUCCESS"){
            if(isset($arrayInfo['return_msg']) && $arrayInfo['return_msg'] != null){
                echo $this -> returnInfo("FAIL","签名失败");
                $this -> logsmall("签名失败:");//log打印保存
                exit;
            }else{
                $smallSign = $arrayInfo['sign'];
                unset($arrayInfo['sign']);
                $arrayInfo['appid']  =  $configpri['small_appid'];
                $arrayInfo['mch_id'] =  $configpri['small_mchid'];
                $key =  $configpri['small_key'];
                ksort($arrayInfo);//按照字典排序参数数组
                $sign = $this -> sign($arrayInfo,$key);//生成签名
                $this -> logsmall("数据打印测试签名signmy:".$sign.":::小程序sign:".$smallSign);//log打印保存
                if($this -> checkSign($smallSign,$sign)){
                    echo $this -> returnInfo("SUCCESS","OK");
                    $this -> logsmall("签名验证结果成功:".$sign);//log打印保存
                    $this -> orderServer('small');//订单处理业务逻辑
                    exit;
                }else{
                    echo $this -> returnInfo("FAIL","签名失败");
                    $this -> logsmall("签名验证结果失败:本地加密：".$sign.'：：：：：三方加密'.$smallSign);//log打印保存
                    exit;
                }
            }
        }else{
            echo $this -> returnInfo("FAIL","签名失败");
            $this -> logsmall($arrayInfo['return_code']);//log打印保存
            exit;
        }
    }



    /* UNIAPP端h5支付 */
    private $h5Date = null;
    public function notify_hfive(){
        $configpri=getConfigPri();
        //$xmlInfo = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xmlInfo=file_get_contents("php://input");

        //解析xml
        $arrayInfo = $this -> xmlToArray($xmlInfo);
        $this -> $h5Date = $arrayInfo;
        $this -> loghfive("h5_data:".json_encode($arrayInfo));//log打印保存
        if($arrayInfo['return_code'] == "SUCCESS"){
            if(isset($arrayInfo['return_msg']) && $arrayInfo['return_msg'] != null){
                echo $this -> returnInfo("FAIL","签名失败");
                $this -> loghfive("签名失败:");//log打印保存
                exit;
            }else{
                $hfiveSign = $arrayInfo['sign'];
                unset($arrayInfo['sign']);

                $arrayInfo['appid']  =  $configpri['pc_wx_appid'];
                $arrayInfo['mch_id'] =  $configpri['pc_wx_mchid'];
                $key =  $configpri['pc_wx_key'];
                ksort($arrayInfo);//按照字典排序参数数组
                $sign = $this -> sign($arrayInfo,$key);//生成签名
                $this -> loghfive("数据打印测试签名signmy:".$sign.":::h5sign:".$hfiveSign);//log打印保存
                if($this -> checkSign($hfiveSign,$sign)){
                    echo $this -> returnInfo("SUCCESS","OK");
                    $this -> loghfive("签名验证结果成功:".$sign);//log打印保存
                    $this -> orderServer('h5');//订单处理业务逻辑
                    exit;
                }else{
                    echo $this -> returnInfo("FAIL","签名失败");
                    $this -> loghfive("签名验证结果失败:本地加密：".$sign.'：：：：：三方加密'.$hfiveSign);//log打印保存
                    exit;
                }
            }
        }else{
            echo $this -> returnInfo("FAIL","签名失败");
            $this -> loghfive($arrayInfo['return_code']);//log打印保存
            exit;
        }
    }






    /***微信PC支付 */
    public function notify_pc_wx(){
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
				$this -> logwx("签名失败:");//log打印保存
				exit;
			}else{
				$wxSign = $arrayInfo['sign'];
				unset($arrayInfo['sign']);
				$arrayInfo['appid']  =  $configpri['pc_wx_appid'];
				$arrayInfo['mch_id'] =  $configpri['pc_wx_mchid'];
				$key =  $configpri['pc_wx_key'];
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
	private function orderServer($paytype = ''){

		$info = $this -> wxDate;
        $this->logwx("info:".json_encode($info));
        $where['type']=2;

        if ($paytype == 'small') {
            $info = $this->smallDate;
            $this->logsmall("info:".json_encode($info));
            $where['type'] = 3;
        } elseif ($paytype == 'h5') {
            $info = $this->h5Date;
        }

        $where['orderno']=$info['out_trade_no'];

        $trade_no=$info['transaction_id'];

        $data=[
            'trade_no'=>$trade_no
        ];

        $this->logwx("where:".json_encode($where));
        $res=handelPay($where,$data);
        if($res==0){
            $this->logwx("orderno:".' 订单信息不存在');
            return false;
        }


        $this->logwx("成功");
        return true;

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
    /* public function test(){
        $where=[
            'orderno'=>'48_200530104114164',
            'type'=>'1',
        ];

        $data=['trade_no'=>'ceshi123'];

        handelPay($where,$data);
    } */


    /* 打印log */
	protected function logali($msg){
		file_put_contents(CMF_ROOT.'data/log/package_logali_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
	}

	/* 打印log */
	protected function logwx($msg){
		file_put_contents(CMF_ROOT.'data/log/package_logwx_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
	}

    /* 打印小程序支付log */
    protected function logsmall($msg){
        file_put_contents(CMF_ROOT.'data/log/package_logsmall_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
    }

    /* 打印h5支付log */
    protected function loghfive($msg){
        file_put_contents(CMF_ROOT.'data/log/package_loghfive_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
    }


}


