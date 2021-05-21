<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/05 10:40
// +----------------------------------------------------------------------
namespace App\Domain;

use App\Domain\Addr as Domain_Addr;
use App\Domain\Course as Domain_Course;
use App\Model\Cart as Model_Cart;

class Cart
{

    /* 列表 */
    public function getList($where)
    {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $nowtime = time();
        $list2   = [];
        $isaddr  = 0;

        $model = new Model_Cart();
        $list  = $model->getList($where);

        $Domain_Course = new Domain_Course();

        foreach ($list as $k => $v) {

            $type   = $v['type'];
            $typeid = $v['typeid'];
            if ($type != 1) {

                $where2 = [
                    'status>=?'     => 1,
                    'shelvestime<?' => $nowtime,
                    'id'            => $typeid
                ];
                $info   = $Domain_Course->getList(1, $where2);
                if ($info && isset($info[0])) {
                    $pinfo = $info[0];

                    $pinfo['carttype'] = '0';
                    $pinfo['isselect'] = $v['isselect'];
                    $pinfo['cartid']   = $v['id'];

                    if ($pinfo['ismaterial'] == 1) {
                        $isaddr = 1;
                    }

                    $list2[] = $pinfo;
                }

            }
        }

        $info = [
            'isaddr' => (string)$isaddr,
            'list'   => $list2,
        ];

        $rs['info'][0] = $info;

        return $rs;
    }

    /* 添加 */
    public function add($data)
    {
        $rs = array('code' => 0, 'msg' => \PhalApi\T('添加成功'), 'info' => array());

        $model = new Model_Cart();

        $where2 = [
            'uid'    => $data['uid'],
            'type'   => $data['type'],
            'typeid' => $data['typeid'],
        ];
        $nums2  = $model->getNums($where2);
        if (!$nums2) {
            $res = $model->add($data);
            if (!$res) {
                $rs['code'] = 1002;
                $rs['msg']  = \PhalApi\T('添加失败，请重试');
                return $rs;
            }
        } else {
            $data2 = [
                'isselect' => 1,
            ];
            $model->update($where2, $data2);
        }

        $where = [
            'uid' => $data['uid'],
        ];
        $nums  = $model->getNums($where);

        $info['nums'] = $nums;

        $rs['info'][0] = $info;

        return $rs;
    }

    /* 数量 */
    public function getNums($where)
    {

        $model = new Model_Cart();
        $nums  = $model->getNums($where);

        return $nums;
    }

    /* 更新 */
    public function update($where, $data)
    {

        $model = new Model_Cart();
        $list  = $model->update($where, $data);

        return $list;
    }

    /* 删除 */
    public function del($where)
    {

        $model = new Model_Cart();
        $list  = $model->del($where);

        return $list;
    }

    /* 付费 */
    public function buy($uid, $payid, $addrid, $method, $goods)
    {

        $rs = array('code' => 0, 'msg' => \PhalApi\T('操作成功'), 'info' => array());

        $money   = 0;
        $isaddr  = 0;
        $goods_a = json_decode($goods, true);
        if (!$goods_a) {
            $rs['code'] = 1003;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        if (!is_array($goods_a)) {
            $rs['code'] = 1004;
            $rs['msg']  = \PhalApi\T('信息错误');
            return $rs;
        }

        $Domain_Course = new Domain_Course();

        foreach ($goods_a as $k => $v) {
            $type   = isset($v['type']) ? $v['type'] : 0;
            $typeid = isset($v['typeid']) ? $v['typeid'] : 0;

            if ($typeid == 0) {
                $rs['code'] = 1008;
                $rs['msg']  = \PhalApi\T('信息错误');
                return $rs;
            }

            if ($type != 1) {

                $where = [
                    'id' => $typeid,
                ];
                $info  = $Domain_Course->getDetaild($where);
                if (!$info) {
                    $rs['code'] = 1005;
                    $rs['msg']  = \PhalApi\T('含有已下架课程');
                    return $rs;
                }

                $where2 = [
                    'uid'      => $uid,
                    'courseid' => $typeid,
                    'status'   => 1,
                ];

                $isbuy = $Domain_Course->getBuy($where2);
                if ($isbuy) {
                    $rs['code'] = 1006;
                    $rs['msg']  = \PhalApi\T('含有已购买的课程');
                    return $rs;
                }

                if ($info['ismaterial'] == 1) {
                    $isaddr = 1;
                }

                $money += $info['payval'];
            }
        }

        $orderid = $uid . '_' . date('ymdHis') . rand(100, 999);

        $order_data = [
            'uid'     => $uid,
            'type'    => $payid,
            'money'   => $money,
            'orderno' => $orderid,
            'status'  => 0,
            'addtime' => time(),
        ];

        if ($isaddr == 1) {
            if ($addrid < 1) {
                $rs['code'] = 1007;
                $rs['msg']  = \PhalApi\T('请选择收货地址');
                return $rs;
            }

            $Domain_Addr = new Domain_Addr();
            $where4      = [
                'uid' => $uid,
                'id'  => $addrid,
            ];
            $addr_info   = $Domain_Addr->getInfo($where4);
            if (!$addr_info) {
                $rs['code'] = 1008;
                $rs['msg']  = \PhalApi\T('收货地址错误');
                return $rs;
            }

            $order_data['issend']        = 0;
            $order_data['addr_name']     = $addr_info['name'];
            $order_data['addr_mobile']   = $addr_info['mobile'];
            $order_data['addr_province'] = $addr_info['province'];
            $order_data['addr_city']     = $addr_info['city'];
            $order_data['addr_area']     = $addr_info['area'];
            $order_data['addr']          = $addr_info['addr'];
        }

        $model = new Model_Cart();
        $res   = $model->addOrder($order_data);
        if ($res === false) {
            $rs['code'] = 1006;
            $rs['msg']  = \PhalApi\T('下单失败，请重试');
            return $rs;
        }

        foreach ($goods_a as $k => $v) {
            $type   = isset($v['type']) ? $v['type'] : 0;
            $typeid = isset($v['typeid']) ? $v['typeid'] : 0;

            $data_good = [
                'uid'     => $uid,
                'orderno' => $orderid,
                'type'    => $type,
                'typeid'  => $typeid,
            ];
            $model->addOrderGood($data_good);
        }

        $configpri = \App\getConfigPri();

        $ali = [
            'partner'   => '',
            'seller_id' => '',
            'key'       => '',
        ];
        $wx  = [
            'appid'     => '',
            'noncestr'  => '',
            'package'   => '',
            'partnerid' => '',
            'prepayid'  => '',
            'timestamp' => '',
        ];
        if (!isset($source)) {
            if ($payid == 1) {
                /* 支付宝 */
                $ali = [
                    'partner'   => $configpri['aliapp_partner'],
                    'seller_id' => $configpri['aliapp_seller_id'],
                    'key'       => $configpri['aliapp_key'],
                ];
            }
            if ($payid == 2) {
                /* 微信 */
                $url = \App\get_upload_path('/appapi/cartpay/notify_wx');
                $res = \App\wxPay($orderid, $money, $url, '购买课程');
                if ($res['code'] != 0) {
                    return $res;
                }
                $wx = $res['info'];
            }

            $rs['info'][0]['ali'] = $ali;
            $rs['info'][0]['wx']  = $wx;
        }

        $rs['info'][0]['orderid'] = $orderid;
        $rs['info'][0]['money']   = (string)$money;
        return $rs;
    }
}
