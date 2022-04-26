<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Message extends NotORM {
	
	public function getList($where,$nums) {
        
        // $nums=20;
		
		$list=\PhalApi\DI()->notorm->message
                ->select('id,content,addtime')
				->where($where)
                ->order('id desc')
                ->limit($nums)
				->fetchAll();

		return $list;
	}
	
	
}
