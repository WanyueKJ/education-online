<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Cart extends NotORM {


    /* 列表 */
    public function getList($where) {

        $list=\PhalApi\DI()->notorm->cart
            ->select('*')
            ->where($where)
            ->order('id desc')
            ->fetchAll();

        return $list;
    }

    /* 数量 */
    public function getNums($where) {

        $nums=\PhalApi\DI()->notorm->cart
            ->where($where)
            ->count();

        return $nums;
    }

    /* 新增 */
    public function add($data) {

        $list=\PhalApi\DI()->notorm->cart
            ->insert($data);

        return $list;
    }

    /* 更新 */
    public function update($where,$data) {

        $list=\PhalApi\DI()->notorm->cart
            ->where($where)
            ->update($data);

        return $list;
    }

    /* 删除 */
    public function del($where) {

        $list=\PhalApi\DI()->notorm->cart
            ->where($where)
            ->delete();

        return $list;
    }

    /* 添加订单 */
    public function addOrder($data) {

        $rs=\PhalApi\DI()->notorm->orders
            ->insert($data);

        return $rs;
    }

    /* 添加订单商品 */
    public function addOrderGood($data) {

        $rs=\PhalApi\DI()->notorm->orders_good
            ->insert($data);

        return $rs;
    }

	
}
