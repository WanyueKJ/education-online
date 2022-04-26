<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Teacher extends NotORM {

    /**
     * 教师列表
     * @param $p
     * @param $where
     * @param $order
     * @param $nums
     * @return mixed
     */
	public function getTeachers($p,$where,$order,$nums) {
        
        if($p<1){
            $p=1;
        }
        if($nums==0){
            $nums=20;
        }
        
        $start=($p-1) * $nums;

		$list=\PhalApi\DI()->notorm->users
				->select('id,user_nickname,avatar,avatar_thumb,sex,signature,birthday,type,signoryid,identity')
                ->where('type=1 and user_status!=0 ')
                ->where($where)
				->order($order)
                ->limit($start,$nums)
				->fetchAll();

		return $list;
	}

    /**
     * 讲师信息
     * @param $uid
     * @return mixed
     */
	public function getInfo($uid) {
		$info=\PhalApi\DI()->notorm->users
				->select("id,user_nickname,avatar,avatar_thumb,sex,signature,birthday,type,signoryid,identity,school,experience,feature")
				->where('id=?',$uid)
				->fetchOne();
		return $info;
	}

}
