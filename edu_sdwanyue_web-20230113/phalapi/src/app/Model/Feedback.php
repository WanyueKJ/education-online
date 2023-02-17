<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Feedback extends NotORM {
	/* 新增 */
	public function add($data) {
		
		$list=\PhalApi\DI()->notorm->feedback
				->insert($data);

		return $list;
	}
	
	
}
