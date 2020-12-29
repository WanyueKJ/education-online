<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Ware extends NotORM {
	/* 课件列表 */
	public function getList($where) {
		
		$list=\PhalApi\DI()->notorm->course_ware
                ->select('id,name,url,size,addtime')
                ->where($where)
                ->order('id desc')
				->fetchAll();

		return $list;
	}
	
	
}
