<?php
// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/09 08:22
// +----------------------------------------------------------------------
namespace App\Api;

use PhalApi\Api;
use App\Domain\Cart as Domain_Cart;

header("Access-Control-Allow-Origin: *");

/**
 * 购物车
 */

class Cart extends Api {

	public function getRules() {
        return array(
            'buy' => array(
                'payid' => array('name' => 'payid', 'type' => 'int', 'desc' => '支付方式ID'),
                'addrid' => array('name' => 'addrid', 'type' => 'int', 'desc' => '地址ID'),
                'method' => array('name' => 'method', 'type' => 'int', 'desc' => '下单方式，0直接购买'),
                'goods' => array('name' => 'goods', 'type' => 'string', 'desc' => '商品信息 json串 格式：[{"type":"类型，0课程","typeid":"课程ID"}]'),
                'source' => array('name' => 'source', 'type' => 'string', 'desc' => ''),
            ),
        );
	}


    
    /**
	 * 支付方式
	 * @desc 用于获取支付方式列表
	 * @return int code 操作码，0表示成功
	 * @return array info 支付方式
     * @return string info[].id 1支付宝2微信
     * @return string info[].name 名称
     * @return string info[].thumb 图标
	 * @return string msg 提示信息
	 */
	public function getPayList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$configpri=\App\getConfigPri();
        $aliapp_switch=$configpri['aliapp_switch'];
        $wx_switch=$configpri['wx_switch'];
        
        $paylist=[];
        
        if($aliapp_switch==1){
            $paylist[]=[
                'id'=>'1',
                'name'=>\PhalApi\T('支付宝支付'),
                'thumb'=>\App\get_upload_path("/static/app/pay/ali.png"),
            ];
        }
        
        if($wx_switch==1){
            $paylist[]=[
                'id'=>'2',
                'name'=>\PhalApi\T('微信支付'),
                'thumb'=>\App\get_upload_path("/static/app/pay/wx.png"),
            ];
        }
	 
		$rs['info']=$paylist;
		return $rs;
	}
    
    /**
	 * 下单(不仅限于购物车)
	 * @desc 用于下单购买
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].orderid 订单号
	 * @return string info[0].money 金额
	 * @return object info[0].ali 支付宝信息
	 * @return string info[0].ali.partner 合作者ID
	 * @return string info[0].ali.seller_id  账号
	 * @return string info[0].ali.key  PKCS8密钥
	 * @return object info[0].wx 微信信息
	 * @return string info[0].wx.appid 微信Appid
	 * @return string info[0].wx.noncestr 随机数
	 * @return string info[0].wx.package 固定数据
	 * @return string info[0].wx.partnerid 商户ID
	 * @return string info[0].wx.prepayid 支付ID
	 * @return string info[0].wx.timestamp 时间戳
	 * @return string msg 提示信息
	 */
	public function buy() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=\App\checkNull($this->uid);
		$token=\App\checkNull($this->token);
		$payid=\App\checkNull($this->payid);
		$addrid=\App\checkNull($this->addrid);
		$method=\App\checkNull($this->method);
		$goods=\App\checkNull($this->goods);

		if($uid<1 || $payid<1 || $payid>2 || $goods==''){
			$rs['code']=1001;
			$rs['msg']=\PhalApi\T('信息错误');
			return $rs;
		}
        
        $checkToken=\App\checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = \PhalApi\T('您的登陆状态失效，请重新登陆！');
			return $rs;
		}
        
		
		$domain = new Domain_Cart();
		$res = $domain->buy($uid,$payid,$addrid,$method,$goods);
        
		return $res;
	}





}
