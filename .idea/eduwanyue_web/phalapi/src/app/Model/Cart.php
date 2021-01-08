<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Cart extends NotORM {


    /* 添加订单 */
    public function addOrder($data) {
		
		$rs=\PhalApi\DI()->notorm->orders
				->insert($data);

		return $rs;
	}

    /* 数量 */
    public function getNums($where) {

        $nums=\PhalApi\DI()->notorm->cart
            ->where($where)
            ->count();

        return $nums;
    }

    /* 添加订单商品 */
	public function addOrderGood($data) {
		
		$rs=\PhalApi\DI()->notorm->orders_good
				->insert($data);

		return $rs;
	}
	
}
