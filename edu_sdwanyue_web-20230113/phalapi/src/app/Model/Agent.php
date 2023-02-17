<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Agent extends NotORM {

    /* 邀请码 */
    public function getAgent($where,$field='*') {

        $list=\PhalApi\DI()->notorm->agent_code
            ->select($field)
            ->where($where)
            ->fetchOne();

        return $list;
    }
    /* 添加邀请码 */
    public function setCode($data){
        $list=\PhalApi\DI()->notorm->agent_code
            ->insert($data);

        return $list;
    }

    /* 更新邀请码 */
    public function upCode($where,$data){
        $list=\PhalApi\DI()->notorm->agent_code
            ->where($where)
            ->update($data);

        return $list;
    }
    /* 邀请人数 */
    public function getAgentNums($where) {

        $list=\PhalApi\DI()->notorm->agent
            ->where($where)
            ->count();

        return $list;
    }


    /* 邀请人数 */
    public function getAgentList($where,$p=1,$field='*') {

        $nums=20;
        if($p<1){
            $p=1;
        }
        $start=($p-1)*$nums;
        $list=\PhalApi\DI()->notorm->agent
            ->select($field)
            ->where($where)
            ->order('id desc')
            ->limit($start,$nums)
            ->fetchAll();

        return $list;
    }

    /* 邀请关系 */
    public function getAgentInfo($where,$field='*'){
        $info=\PhalApi\DI()->notorm->agent
            ->select($field)
            ->where($where)
            ->fetchOne();

        return $info;
    }

    /* 设置邀请关系 */
    public function addAgent($data){
        $info=\PhalApi\DI()->notorm->agent
            ->insert($data);

        return $info;
    }
    /* 删除邀请关系 */
    public function delAgent($where){
        $info=\PhalApi\DI()->notorm->agent
            ->where($where)
            ->delete();

        return $info;
    }
}
