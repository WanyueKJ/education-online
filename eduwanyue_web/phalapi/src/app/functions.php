<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/09 08:50
// +----------------------------------------------------------------------
namespace App;
    /* curl get请求 */

    use think\Console;

    function curl_get($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	} 
    /* curl POST 请求 */
	function curl_post($url,$curlPost='',$headers=''){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, false);
		curl_setopt($curl, CURLOPT_POST, true);
        if($curlPost){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        }

        if ($headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  // 从证书中检查SSL加密算法是否存在
		
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	}

    /* 去除NULL 判断空处理 主要针对字符串类型*/
	function checkNull($checkstr){
        $checkstr=trim($checkstr);
		//$checkstr=urldecode($checkstr);
		$checkstr=html_entity_decode($checkstr);

		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		return $str;	
	}
    
	/* 去除emoji表情 */
	function filterEmoji($str){
		$str = preg_replace_callback(
			'/./u',
			function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},
			$str);
		return $str;
	}
    
    /* 校验签名 */
    function checkSign($data,$sign){
		// return 1;
        if($sign==''){
            return 0;
        }
        $key=\PhalApi\DI()->config->get('app.sign_key');
        $str='';
        ksort($data);
        foreach($data as $k=>$v){
            $str.=$k.'='.$v.'&';
        }
        $str.=$key;
        $newsign=md5($str);
        
        if($sign==$newsign){
            return 1;
        }
        return 0;
    }
    
     /* 检验手机号 */
	function checkMobile($mobile){
		$ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}
        
        return 0;
		
	}
    
    /* 校验邮箱 */
    function checkEmail($email){
        $preg='/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/';
        $isok=preg_match($preg,$email);
        if($isok){
            return 1;
        }
        return 0;
    }
    
    /* 校验密码 */
    function checkPass($pass){
        /* 必须包含字母、数字 */
        $preg='/^(?=.*[A-Za-z])(?=.*[0-9])[a-zA-Z0-9~!@&%#_]{6,20}$/';
        $isok=preg_match($preg,$pass);
        if($isok){
            return 1;
        }
        return 0;
    }

    /* 检测用户是否存在 */
    function checkUser($where){
        if($where==''){
            return 0;
        }

        $isexist=\PhalApi\DI()->notorm->users->where($where)->fetchOne();
        if($isexist){
            return 1;
        }
        
        return 0;
    }
    
    /* 密码加密 */
	function setPass($pass){
		
		$authcode=\PhalApi\DI()->config->get('app.authcode');
		$pass="###".md5(md5($authcode.$pass));
		return $pass;
	}
    
    /* 随机数 */
	function random($length = 6 , $numeric = 1) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}
    
    /* 发送验证码 */
    function sendCode($account,$code){
        $rs = array('code' => 1001, 'msg' => \PhalApi\T('发送失败'));
		$config = getConfigPri();
        
        if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;
        }
        
        // $res=sendCodeByCCP($account,$code);
        
        $res=sendCodeByTx($account,$code);
        
        //$res=sendEmailCode($account,$code);
        
        return $res;
    }
    /* 公共配置 */
	function getConfigPub() {
		$key='getConfigPub';
		$config=getcaches($key);
		if(!$config){
			$config= \PhalApi\DI()->notorm->option
					->select('option_value')
					->where("option_name='site_info'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
		}
        

		return 	$config;
	}		
	
	/* 私密配置 */
	function getConfigPri() {
		$key='getConfigPri';
		$config=getcaches($key);
		if(!$config){
			$config=\PhalApi\DI()->notorm->option
					->select('option_value')
					->where("option_name='configpri'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
		}
        
		if(isset($config['login_type'])){
			if(is_array($config['login_type'])){
				
			}else if($config['login_type']){
				$config['login_type']=preg_split('/,|，/',$config['login_type']);
			}else{
				$config['login_type']=array();
			}
		}
        
		if(isset($config['share_type'])){
			if(is_array($config['share_type'])){
				
			}else if($config['share_type']){
				$config['share_type']=preg_split('/,|，/',$config['share_type']);
			}else{
				$config['share_type']=array();
			}
		}

		return 	$config;
	}


	/* 邮箱配置 */
	function getEmail() {
		$key='getEmail';
		$config=getcaches($key);
		if(!$config){
			$config=\PhalApi\DI()->notorm->option
					->select('option_value')
					->where("option_name='smtp_setting'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
		}


		return 	$config;
	}
    
    /* 邮箱模板 */
	function getEmailTemp() {
		$key='getEmailTemp';
		$config=getcaches($key);
		if(!$config){
			$config=\PhalApi\DI()->notorm->option
					->select('option_value')
					->where("option_name='email_template_verification_code'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
			
		}
        if(!$config){
            $config=[
                'subject'=>\PhalApi\T('验证码'),
                'template'=>\PhalApi\T('您的验证码是：{$code}。请不要把验证码泄露给其他人'),
            ];
        }

		return 	$config;
	}
    
 	/**
	 * 返回带协议的域名
	 */
	function get_host(){
		$config=getConfigPub();
		return $config['site_url'];
	}
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	function get_upload_path($file){
        if($file==''){
            return '';
        }
		if(strpos($file,"http")===0){
            $filepath= $file;
		}else if(strpos($file,"/")===0){
			$filepath= get_host().$file;
		}else{
            $uptype=\PhalApi\DI()->config->get('app.uptype');
            if($uptype==1){
                 /* 七牛上传 */
                $space_host= \PhalApi\DI()->config->get('app.Qiniu.space_host');
            }else{
                /* 本地 上传 */
                $space_host= get_host().'/upload';
            }
			$filepath=$space_host."/".$file;
		}

        return html_entity_decode($filepath);
	}
    
	/* 判断token */
	function checkToken($uid,$token) {

        if($uid<1 || $token==''){
            return 700;
        }

        $key="token_".$uid;
		$userinfo=hGetAll($key);
		if(!$userinfo){
			$userinfo=\PhalApi\DI()->notorm->users_token
						->select('token,expire_time')
						->where('user_id = ? ', $uid)
						->fetchOne();

            if($userinfo){
                hMSet($key,$userinfo);
            }
		}

		if(!$userinfo || $userinfo['token']!=$token || $userinfo['expire_time']<time()){
			return 700;
		}
        return 	0;
		
	}
    
    /* 用户基本信息 */
	function getUserInfo($uid,$type=0) {
		$info=hGetAll("userinfo_".$uid);
		if(!$info){
			$info=\PhalApi\DI()->notorm->users
					->select('id,user_nickname,avatar,avatar_thumb,sex,signature,birthday,type,signoryid,identity')
					->where('id=?',$uid)
					->fetchOne();	
			if($info){

			}else if($type==1){
                return 	$info;
            }else{
                $info['id']=$uid;
                $info['user_nickname']=\PhalApi\T('用户不存在');
                $info['avatar']='/default.png';
                $info['avatar_thumb']='/default_thumb.png';
                $info['sex']='0';
                $info['signature']='';
                $info['birthday']='0';
            }
            if($info){
                delcache("userinfo_".$uid);
                hMSet("userinfo_".$uid,$info);
            }
		}
        
        if($info){
            $info=handleUser($info);

        }

		return 	$info;		
	}
    
    /* 处理用户信息 */
    function handleUser($info){
        
        $info['avatar']=get_upload_path($info['avatar']);
        $info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
        if(isset($info['signoryid'])){
            $info['signory']=getSignory($info['signoryid']);
            unset($info['signoryid']);
        }
        if(isset($info['identity'])){
            $info['identitys']=getIdentity($info['identity']);
            unset($info['identity']);
        }
        
        
        
        unset($info['birthday']);
        
        return $info;
    }
    
    
    /* 年龄计算 */
    function getAge($time=0){
        if($time<=0){
            return '';
        }
        $nowtime=time();
        $y_n=date('Y',$nowtime);
        $y_b=date('Y',$time);
        
        $age=$y_n - $y_b;
        
        return (string)$age;
    }
    
    /* 统计粉丝数 */
    function getFansNum($uid){
        $nums =\PhalApi\DI()->notorm->users_attention
				->where('touid = ?', $uid)
				->count();
        return (string)$nums;
    }
    /* 统计关注数 */
    function getFollowNum($uid){
        $nums =\PhalApi\DI()->notorm->users_attention
				->where('uid = ?', $uid)
				->count();
        return (string)$nums;
    }
    
    /* 是否关注 */
    function isAttent($uid,$liveuid){
        if($uid<1 || $liveuid<1 || $uid==$liveuid){
            return '0';
        }
        
        $isok =\PhalApi\DI()->notorm->users_attention
                ->select('*')
				->where('uid = ? and touid=?', $uid,$liveuid)
				->fetchOne();
        
        if($isok){
            return '1';
        }
        return '0';
    }
    
    /* 获取用户最新余额*/
    function getUserCoin($uid){
        $info =\PhalApi\DI()->notorm->users
				->select('coin')
				->where('id = ?', $uid)
				->fetchOne();
        return $info;
    }
    
    /* 扣费 */
    function upCoin($uid,$total=0,$type=0){
        if($uid < 1 || $total<=0){
            return 0;
        }
        if($type==1){
            $ifok =\PhalApi\DI()->notorm->users
                    ->where('id = ? and coin >=?', $uid,$total)
                    ->update(array('coin' => new \NotORM_Literal("coin - {$total}") ) );
            
            return $ifok;
        }
        $ifok =\PhalApi\DI()->notorm->users
				->where('id = ? and coin >=?', $uid,$total)
				->update(array('coin' => new \NotORM_Literal("coin - {$total}"),'consumption' => new \NotORM_Literal("consumption + {$total}") ) );
        return $ifok;
    }
    
    /* 退费 */
    function addCoin($uid,$total=0,$type=0){
        if($uid < 1 || $total<=0){
            return 0;
        }
        if($type==1){
            $ifok =\PhalApi\DI()->notorm->users
                    ->where('id = ? ', $uid)
                    ->update(array('coin' => new \NotORM_Literal("coin + {$total}") ) );
            
            return $ifok;
        }
        $ifok =\PhalApi\DI()->notorm->users
				->where('id = ? ', $uid)
				->update(array('coin' => new \NotORM_Literal("coin + {$total}"),'consumption' => new \NotORM_Literal("consumption - {$total}") ) );
        return $ifok;
    }
    
    /* 增加映票 */
    function addVotes($uid,$votes=0,$votestotal=0){
        
        if($uid < 1 || $votes<=0){
            return 0;
        }
        
        if(!$votestotal){
            $ifok=\PhalApi\DI()->notorm->users
					->where('id = ?', $uid)
					->update( array('votes' => new \NotORM_Literal("votes + {$votes}") ));
            return $ifok;
        }
        
        $ifok=\PhalApi\DI()->notorm->users
					->where('id = ?', $uid)
					->update( array('votes' => new \NotORM_Literal("votes + {$votes}"),'votestotal' => new \NotORM_Literal("votestotal + {$votestotal}") ));
        return $ifok;
    }

    /* 消费记录 */
    function addCoinRecord($insert){
        $rs=0;
        if($insert){
            $rs=\PhalApi\DI()->notorm->users_coinrecord->insert($insert);
        }
        
        return $rs;
    }
    
    /* 票记录 */
    function addVotesRecord($insert){
        $rs=0;
        if($insert){
            $rs=\PhalApi\DI()->notorm->users_votesrecord->insert($insert);
        }
        
        return $rs;
    }
    
    /* 离线时间 */
	function offtime($time){
		$cha=time()-$time;
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			//return $cha.'秒之前';
			return \PhalApi\T('1分钟前');
		}else if($iz<60){
			return \PhalApi\T('{n}分钟前',['n'=>$iz]);
		}else if($hz<24){
			return \PhalApi\T('{n}小时前',['n'=>$hz]);
		}else if($dz<30){
			return \PhalApi\T('{n}天前',['n'=>$dz]);
		}else{
			return date('m-d H:i',$time);
		}
	}
    
    /* 字符串加密 */
    function encryption($code){
		$str = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp';
		$strl=strlen($str);
        
	   	$len = strlen($code);

      	$newCode = '';
	   	for($i=0;$i<$len;$i++){
         	for($j=0;$j<$strl;$j++){
            	if($str[$j]==$code[$i]){
               		if(($j+1)==$strl){
                   		$newCode.=$str[0];
	               	}else{
	                   	$newCode.=$str[$j+1];
	               	}
	            }
         	}
      	}
      	return $newCode;
	}	
    
    /* 字符串解密 */
    function decrypt($code){
		$str = '1ecxXyLRB.COdrAi:q09Z62ash-QGn8VFNIlb=fM/D74WjS_EUzYuw?HmTPvkJ3otK5gp';
		$strl=strlen($str);

	   	$len = strlen($code);

      	$newCode = '';
	   	for($i=0;$i<$len;$i++){
     		for($j=0;$j<$strl;$j++){
        		if($str[$j]==$code[$i]){
	           		if($j-1<0){
	        			$newCode.=$str[$strl-1];
	               	}else{
						$newCode.=$str[$j-1];
	               	}
            	}
         	}
      	}
      	return $newCode;
	}
    
    /* 邮箱验证码 */
    function sendEmailCode($account,$code){
        
        $emailtemp = getEmailTemp();
        
        if(!checkEmail($account)){
            return ["code" => 1000, "msg" => \PhalApi\T('邮箱格式错误')];
        }
        
        
        $subject=$emailtemp['subject'];
        $template=$emailtemp['template'];
        
        if(\PhalApi\DI()->lang=='en'){
            if($emailtemp['subject_en']!=''){
                $subject=$emailtemp['subject_en'];
            }
            
            if($emailtemp['template_en']!=''){
                $template=$emailtemp['template_en'];
            }
            
        }
        
        $message=str_replace('{$code}',$code,$template);
        
        $res=sendEmail($account,$subject,$message);
        
        return $res;
        
    }
    
    /* 发送邮件 */
    function sendEmail($address, $subject, $message){

        
        $smtpSetting = getEmail();
        
        $config=[
            'host' => $smtpSetting['host'],
            'secure' => $smtpSetting['smtp_secure'],
            'port' => $smtpSetting['port'],
            'username' => $smtpSetting['username'],
            'password' => $smtpSetting['password'],
            'from' =>  $smtpSetting['from'],
            'fromName' => $smtpSetting['from_name'],
            'sign' => '',
        ];
        
        if(!\PhalApi\DI()->mailer){
            \PhalApi\DI()->mailer= new \PhalApi\PHPMailer\Lite($config,true);
        }

        $rs=\PhalApi\DI()->mailer->send($address,$subject,$message);
        // 发送邮件。
        if (!$rs) {
            return ["code" => 1000, "msg" => \PhalApi\T('发送失败')];
        } 
        
        return ["code" => 0, "msg" => ""];

    }
    
    /* APP微信支付 
    *  orderid  订单号
    *  money    CNY（元）
    *  url      回调URL（全链接）
    *  body     提示标题
    */
	function wxPay($orderid,$money,$url,$body='充值虚拟币') {
        
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$configpri = getConfigPri(); 

		 //配置参数检测
		if($configpri['wx_appid']== "" || $configpri['wx_mchid']== "" || $configpri['wx_key']== ""){
			$rs['code'] = 1002;
			$rs['msg'] = '微信未配置';
			return $rs;					 
		}
			 
		$noceStr = md5(rand(100,1000).time());//获取随机字符串
		$time = time();
			
		$paramarr = array(
			"appid"       =>   $configpri['wx_appid'],
			"body"        =>    $body,
			"mch_id"      =>    $configpri['wx_mchid'],
			"nonce_str"   =>    $noceStr,
			"notify_url"  =>    $url,
			"out_trade_no"=>    $orderid,
			"total_fee"   =>    $money*100, 
			"trade_type"  =>    "APP"
		);
		$sign = sign($paramarr,$configpri['wx_key']);//生成签名
		$paramarr['sign'] = $sign;
		$paramXml = "<xml>";
		foreach($paramarr as $k => $v){
			$paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
		}
		$paramXml .= "</xml>";
			 
		$ch = curl_init ();
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
		@curl_setopt($ch, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_POST, 1);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $paramXml);
		@$resultXmlStr = curl_exec($ch);
		if(curl_errno($ch)){
			//print curl_error($ch);
			file_put_contents(API_ROOT.'/../data/log/appwxpay_'.date('Y-m-d').'.txt',date('y-m-d H:i:s').' 提交参数信息 ch:'.json_encode(curl_error($ch))."\r\n",FILE_APPEND);
		}
		curl_close($ch);

		$result2 = xmlToArray($resultXmlStr);
        
        if($result2['return_code']=='FAIL'){
            $rs['code']=1005;
			$rs['msg']=$result2['return_msg'];
            return $rs;	
        }
		$time2 = time();
		$prepayid = $result2['prepay_id'];
		$sign = "";
		$noceStr = md5(rand(100,1000).time());//获取随机字符串
		$paramarr2 = array(
			"appid"     =>  $configpri['wx_appid'],
			"noncestr"  =>  $noceStr,
			"package"   =>  "Sign=WXPay",
			"partnerid" =>  $configpri['wx_mchid'],
			"prepayid"  =>  $prepayid,
			"timestamp" =>  $time2
		);
		$paramarr2["sign"] = sign($paramarr2,$configpri['wx_key']);//生成签名
		
		$rs['info']=$paramarr2;
		return $rs;			
	}
	
	/**
	* sign拼装获取
	*/
	function sign($param,$key){
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
	function xmlToArray($xmlStr){
		$msg = array(); 
		$postStr = $xmlStr; 
		$msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
		return $msg;
	}
    /* APP微信支付 */
    
    /* 容联云短信验证码 */
    function sendCodeByCCP($mobile,$code){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $config = getConfigPri();
        
        require_once API_ROOT.'/../sdk/ronglianyun/CCPRestSDK.php';
        
        //主帐号
        $accountSid= $config['ccp_sid'];
        //主帐号Token
        $accountToken= $config['ccp_token'];
        //应用Id
        $appId=$config['ccp_appid'];
        //请求地址，格式如下，不需要写https://
        $serverIP='app.cloopen.com';
        //请求端口 
        $serverPort='8883';
        //REST版本号
        $softVersion='2013-12-26';
        
        $tempId=$config['ccp_tempid'];
        
        file_put_contents(API_ROOT.'/../data/log/sendCode_ccp_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 post_data: accountSid:'.$accountSid.";accountToken:{$accountToken};appId:{$appId};tempId:{$tempId}\r\n",FILE_APPEND);

        $rest = new \REST($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);
        
        $datas=[];
        $datas[]=$code;
        
        $result = $rest->sendTemplateSMS($mobile,$datas,$tempId);
        file_put_contents(API_ROOT.'/../data/log/sendCode_ccp_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
        
         if($result == NULL ) {
            $rs['code']=1002;
			$rs['msg']=\PhalApi\T("发送失败");
            return $rs;
         }
         if($result->statusCode!=0) {
            //echo "error code :" . $result->statusCode . "<br>";
            //echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑
            $rs['code']=1002;
			//$rs['msg']=$gets['SubmitResult']['msg'];
			$rs['msg']=\PhalApi\T("发送失败");
            return $rs;
         }

		return $rs;
    }
    
    /* 数值处理 */
    function NumberFormat($num){
		if($num>=10000){
            $num=round($num/10000,1).'w';
		}
        
		return $num;
	}

    /* 获取用户累计映票 */
    function getUserVotestotal($uid){
        $info =\PhalApi\DI()->notorm->users
				->select('votestotal')
				->where('id = ?', $uid)
				->fetchOne();
        return $info;
    }
    
    /* 时长处理 */
	function handellength($cha,$type=0){
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
        if($type==1){
            
        }
        
        
		if($cha<60){
			return \PhalApi\T('{s}秒',['s'=>$s]);
		}else if($iz<60){
			return \PhalApi\T('{i}分钟{s}秒',['i'=>$iz,'s'=>$s]);
		}else if($hz<24){
			return \PhalApi\T('{h}小时{i}分钟',['h'=>$hz,'i'=>$i]);
		}else{
			return \PhalApi\T('{d}天{h}小时{i}分钟',['d'=>$dz,'h'=>$h,'i'=>$i]);
		}
	}
    
    /* 毫秒时间戳 */
    function getMillisecond(){
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectimes = substr($msectime,0,13);
    }
    
    /* 年龄段 */
    function getAges($type){
        
        $ages=[
            '1'=>[0,strtotime('1980-01-01')],
            '2'=>[strtotime('1980-01-01'),strtotime('1990-01-01')],
            '3'=>[strtotime('1990-01-01'),strtotime('2000-01-01')],
            '4'=>[strtotime('2000-01-01'),strtotime('2010-01-01')],
            '5'=>[strtotime('2010-01-01'),strtotime('2020-01-01')],
        ];
        
        return isset($ages[$type])?$ages[$type]:[];
    }
    
    /* 处理上架时间 用于显示 */
	function handelsvctm($svctm){
        $nowtime=time();
        $today_start=strtotime(date('Ymd',$nowtime));
        $svctm_start=strtotime(date('Ymd',$svctm));
        
        if($today_start<$svctm_start){
            $length=($svctm_start - $today_start) / (60*60*24);
        
            $hs=date('H:i',$svctm);
            if($length==0){
                return \PhalApi\T('今天').' '.$hs;
            }

            if($length==1){
                return \PhalApi\T('明天').' '.$hs;
            }

            if($length==2){
                return \PhalApi\T('后天').' '.$hs;
            }
            
            return date("m-d",$svctm).' '.$hs;
            
        }
            
        $length=($today_start - $svctm_start) / (60*60*24);
    
        $hs=date('H:i',$svctm);
        if($length==0){
            return \PhalApi\T('今天').' '.$hs;
        }

        if($length==1){
            return \PhalApi\T('昨天').' '.$hs;
        }

        if($length==2){
            return \PhalApi\T('前天').' '.$hs;
        } 
        return date("m-d",$svctm).' '.$hs;
        
        
	}
    
    /* 获取身份标识 */
    function getIdentityList(){
        $key='getidentity';
		$list=getcaches($key);
		if(!$list){
			$list= \PhalApi\DI()->notorm->identity->select('*')->order("list_order asc")->fetchAll();
            if($list){
                setcaches($key,$list);
            }else{
				delcache($key);
			}
		}
        
        
        return $list;
    }
    function getIdentity($identity){
        $info=[];
        
        if($identity==''){
            return $info;
        }
        
        $identitys=explode(',',$identity);
        
        $list=getIdentityList();
        
        foreach($list as $k=>$v){
            if(in_array($v['id'],$identitys)){
                unset($v['list_order']);
                $info[]=$v;
            }
        }

        return $info;
    }
    
    /* 获取专长领域 */
    function getSignoryList(){
        $key='getsignory';
		$list=getcaches($key);
		if(!$list){
			$list= \PhalApi\DI()->notorm->signory->select('*')->order("list_order asc")->fetchAll();
            if($list){
                setcaches($key,$list);
            }else{
				delcache($key);
			}
		}
        
        
        return $list;
    }
    function getSignory($signoryid){
        $info=(object)[];
        
        if($signoryid<1){
            return $info;
        }
        $list=getSignoryList();
        
        foreach($list as $k=>$v){
            if($v['id']==$signoryid){
                unset($v['list_order']);
                $info=$v;
                break;
            }
        }

        return $info;
    }
    
    /* 星级 */
    function getStarLevel($stars=0,$comments=0){
        if(!$stars || !$comments){
            return '5.0';
        }
        
        $level=floor( $stars /$comments * 10) * 0.1;
        
        return (string)$level;
    }
    
    /* 更新进度 */
   function setLesson($uid,$courseid,$lessonid=0){
        $nowtime=time();
        $isview=\PhalApi\DI()->notorm->course_views->where(['uid'=>$uid,'courseid'=>$courseid,'lessonid'=>$lessonid])->fetchOne();
        if($isview){
            \PhalApi\DI()->notorm->course_views->where(["id"=>$isview['id']])->update(['addtime'=>$nowtime]);
            return !1;
        }
        
        $course=\PhalApi\DI()->notorm->course->select('sort,type,paytype,lessons,uid')->where(["id"=>$courseid])->fetchOne();
        if(!$course){
            return !1;
        }
        
        $sort=$course['sort'];
        
        $data=[
            'uid'=>$uid,
            'sort'=>$sort,
            'courseid'=>$courseid,
            'lessonid'=>$lessonid,
            'addtime'=>$nowtime
        ];
        \PhalApi\DI()->notorm->course_views->insert($data);
        
        $nums=\PhalApi\DI()->notorm->course_views->where(['uid'=>$uid,'courseid'=>$courseid])->count();
        if($nums<2){
            /* 同一课程下的课时 记一次课程学习数 */
            \PhalApi\DI()->notorm->course->where(["id"=>$courseid])->update(['views' => new \NotORM_Literal("views + 1")]);
        }
        
        
        $isexist=\PhalApi\DI()->notorm->course_users->where(['uid'=>$uid,'courseid'=>$courseid])->fetchOne();
        if(!$isexist){
            /*  */
            $status=0;
            $paytype=$course['paytype'];
            if($paytype==0){
                $status=1;
            }
            $data2=[
                'uid'=>$uid,
                'sort'=>$course['sort'],
                'paytype'=>$paytype,
                'courseid'=>$courseid,
                'liveuid'=>$course['uid'],
                'status'=>$status,
                'addtime'=>$nowtime,
                'paytime'=>$nowtime,
            ];
            \PhalApi\DI()->notorm->course_users->insert($data2);
            
            $isexist=\PhalApi\DI()->notorm->course_users->where(['uid'=>$uid,'courseid'=>$courseid])->fetchOne();
        }
        
        if($lessonid>0){
            \PhalApi\DI()->notorm->course_users->where(['id'=>$isexist['id']])->update(['lessons' => new \NotORM_Literal("lessons + 1")]);
            
            $lessons= \PhalApi\DI()->notorm->course_users->select('lessons')->where(['id'=>$isexist['id']])->fetchOne();
            if($lessons['lessons']>=$course['lessons']){
                /* 看完 */
                \PhalApi\DI()->notorm->course_users->where(['id'=>$isexist['id']])->update(['step'=>2]);
            }else{
                \PhalApi\DI()->notorm->course_users->where(['id'=>$isexist['id']])->update(['step'=>1]);
            }
        }else{
            \PhalApi\DI()->notorm->course_users->where(['id'=>$isexist['id']])->update(['step'=>2]);
        }
    }
    
    /* 腾讯云短信验证码 */
    function sendCodeByTx($mobile,$code){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $config = getConfigPri();
        
        require_once API_ROOT.'/../sdk/tencentcloud/TCloudAutoLoader.php';
        
        $tx_api_secretid= $config['tx_api_secretid'];
        $tx_api_secretkey= $config['tx_api_secretkey'];
        $tx_sms_sdkappid= $config['tx_sms_sdkappid'];
        $tx_sms_sign= $config['tx_sms_sign'];
        $tx_sms_tempid= $config['tx_sms_tempid'];
        
        $mobile='+86'.$mobile;
        $phonenums=[$mobile];
        
        $params=[$code];

        try {
            /* 必要步骤：
             * 实例化一个认证对象，入参需要传入腾讯云账户密钥对secretId，secretKey。
             * 这里采用的是从环境变量读取的方式，需要在环境变量中先设置这两个值。
             * 你也可以直接在代码中写死密钥对，但是小心不要将代码复制、上传或者分享给他人，
             * 以免泄露密钥对危及你的财产安全。
             * CAM密匙查询: https://console.cloud.tencent.com/cam/capi*/

            $cred = new \TencentCloud\Common\Credential($tx_api_secretid, $tx_api_secretkey);

            // 实例化一个http选项，可选的，没有特殊需求可以跳过
            // $httpProfile = new \TencentCloud\Common\Profile\HttpProfile();
            // $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
            // $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
            // $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)

            // 实例化一个client选项，可选的，没有特殊需求可以跳过
            // $clientProfile = new \TencentCloud\Common\Profile\ClientProfile();
            // $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
            // $clientProfile->setHttpProfile($httpProfile);

            // 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
            // $client = new SmsClient($cred, "ap-shanghai", $clientProfile);
            $client = new \TencentCloud\Sms\V20190711\SmsClient($cred, "ap-shanghai");

            // 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
            $req = new \TencentCloud\Sms\V20190711\Models\SendSmsRequest();

            /* 填充请求参数,这里request对象的成员变量即对应接口的入参
             * 你可以通过官网接口文档或跳转到request对象的定义处查看请求参数的定义
             * 基本类型的设置:
             * 帮助链接：
             * 短信控制台: https://console.cloud.tencent.com/sms/smslist
             * sms helper: https://cloud.tencent.com/document/product/382/3773 */

            /* 短信应用ID: 短信SdkAppid在 [短信控制台] 添加应用后生成的实际SdkAppid，示例如1400006666 */
            $req->SmsSdkAppid = $tx_sms_sdkappid;
            /* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
            $req->Sign = $tx_sms_sign;
            /* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
            $req->ExtendCode = "0";
            /* 下发手机号码，采用 e.164 标准，+[国家或地区码][手机号]
             * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
            $req->PhoneNumberSet = $phonenums;
            /* 国际/港澳台短信 senderid: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
            $req->SenderId = "";
            /* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
            $req->SessionContext = "";
            /* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
            $req->TemplateID = $tx_sms_tempid;
            /* 模板参数: 若无模板参数，则设置为空*/
            $req->TemplateParamSet = $params;

            // 通过client对象调用DescribeInstances方法发起请求。注意请求方法名与请求对象是对应的
            // 返回的resp是一个DescribeInstancesResponse类的实例，与请求对象对应
            $resp = $client->SendSms($req);

            // 输出json格式的字符串回包
            $res=$resp->toJsonString();
            
            //{"SendStatusSet":[{"SerialNo":"2019:6180501101329406318","PhoneNumber":"+8613053838131","Fee":1,"SessionContext":"","Code":"Ok","Message":"send success"}],"RequestId":"69a550c3-74e9-4be7-b5bb-5856b7c36daa"}
            
            $res_a=json_decode($res,true);
            $nums=0;
            $nums_e=0;
            foreach($res_a['SendStatusSet'] as $k=>$v){
                if($v['Code']!='Ok'){
                    $nums_e++;
                }
                $nums++;
            }
            // print_r($res);
            
            // file_put_contents(API_ROOT.'/../data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 res:'.json_encode($res)."\r\n",FILE_APPEND);
            
            if($nums==$nums_e){
                $rs['code']=1002;
                //$rs['msg']=$gets['SubmitResult']['msg'];
                $rs['msg']=\PhalApi\T("发送失败");
                return $rs;
            }
            
            // 也可以取出单个值。
            // 你可以通过官网接口文档或跳转到response对象的定义处查看返回字段的定义
            // print_r($resp->TotalCount);
        }
        catch(\TencentCloud\Common\Exception\TencentCloudSDKException $e) {
            // echo $e;
            file_put_contents(API_ROOT.'/../data/log/sendCode_tx_api_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 e:'.json_encode($e)."\r\n",FILE_APPEND);
            
            $rs['code']=1002;
			//$rs['msg']=$gets['SubmitResult']['msg'];
			$rs['msg']=\PhalApi\T("发送失败");
            return $rs;
        }
        

		return $rs;
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
