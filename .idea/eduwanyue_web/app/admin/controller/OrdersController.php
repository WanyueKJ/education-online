<?php

/**
 * 订单管理
 */

namespace app\admin\controller;

use app\admin\model\CourseModel;
use cmf\controller\AdminBaseController;
use think\Db;

class OrdersController extends AdminbaseController
{

    /*
     * 支付状态
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getStatus($k = '')
    {
        $status = array(
            '0' => '未支付',
            '1' => '已支付',
        );
        if ($k === '') {
            return $status;
        }

        return isset($status[$k]) ? $status[$k] : '';
    }

    /*
     * 支付方式
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getTypes($k = '')
    {
        $type = array(
            '1' => '支付宝',
            '2' => '微信',
        );
        if ($k === '') {
            return $type;
        }

        return isset($type[$k]) ? $type[$k] : '';
    }

    /*
     * 订单列表
     * @return mixed
     */
    function index()
    {
        $data = $this->request->param();
        $map  = [];

        $start_time = isset($data['start_time']) ? $data['start_time'] : '';
        $end_time   = isset($data['end_time']) ? $data['end_time'] : '';

        if ($start_time != "") {
            $map[] = ['addtime', '>=', strtotime($start_time)];
        }

        if ($end_time != "") {
            $map[] = ['addtime', '<=', strtotime($end_time) + 60 * 60 * 24];
        }

        $status = $data['status'] ?? '';
        if ($status != '') {
            $map[] = ['status', '=', $status];
        }

        $type = $data['type'] ?? '';
        if ($type != '') {
            $map[] = ['type', '=', $type];
        }

        $uid = $data['uid'] ?? '';
        if ($uid != '') {
            $map[] = ['uid', '=', $uid];
        }

        $keyword = $data['keyword'] ?? '';
        if ($keyword != '') {
            $map[] = ['orderno|trade_no', '=', $keyword];
        }

        $lists = Db::name("orders")->where($map)->order("id desc")
            ->paginate(20, false, ['query' => input()]);

        $lists->each(function ($v, $k) {
            $v['userinfo'] = getUserInfo($v['uid']);
            return $v;
        });

        $lists->appends($data);
        $page = $lists->render();

        $this->assign([
            'lists'  => $lists,
            'page'   => $page,
            'status' => $this->getStatus(),
            'type'   => $this->getTypes()
        ]);

        return $this->fetch();
    }

    /**
     * 商品类型
     * @param string $k 键
     * @return array|mixed|string
     */
    protected function getGoodsTypes($k = '')
    {
        $type = array(
            '0' => '课程',
        );
        if ($k === '') {
            return $type;
        }

        return isset($type[$k]) ? $type[$k] : '';
    }

    /*
     * 商品
     * @return mixed
     */
    function goods()
    {
        $data = $this->request->param();
        $map  = [];

        $orderno = $data['orderno'] ?? '';

        $map[] = ['orderno', '=', $orderno];

        $lists = Db::name("orders_good")->where($map)->order("id desc")->paginate(20, false, ['query' => input()]);

        $lists->each(function ($v, $k) {
            $type   = $v['type'];
            $typeid = $v['typeid'];
            $name   = '';
            if ($type != 1) {
                $info = CourseModel::where(['id' => $typeid])->find();
                if ($info) {
                    $name = $info['name'];
                }
            }

            $v['name'] = $name;
            return $v;
        });

        $lists->appends($data);
        $page = $lists->render();

        $this->assign([
            'lists' => $lists,
            'page'  => $page,
            'type'  => $this->getGoodsTypes()
        ]);

        return $this->fetch();
    }

    /*
     * 确认支付
     */
    function setPay()
    {
        $id = $this->request->param('id', 0, 'intval');

        $info = DB::name('orders')->where("id={$id}")->find();
        if (!$info) {
            $this->error("标记失败！");
        }

        $this->success("标记成功！");

    }

    /*
     * 标记发货
     */
    function setSend()
    {
        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('orders')->where(['id' => $id, 'issend' => 0])->update(['issend' => 1, 'sendtime' => time()]);
        if ($rs === false) {
            $this->error("标记失败！");
        }

        $this->success("标记成功！");

    }

    /*
     * 删除
     */
    function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $rs = DB::name('orders')->where("id={$id}")->delete();
        if ($rs === false) {
            $this->error("删除失败！");
        }

        $this->success("删除成功！");

    }

}
