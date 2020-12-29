<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class User extends NotORM
{

    /**
     * 用户全部信息
     * @param $uid
     * @return mixed
     */
    public function getBaseInfo($uid)
    {
        $info = \PhalApi\DI()->notorm->users
            ->select("id,user_nickname,avatar,avatar_thumb,sex,signature,coin,consumption,birthday,type,school,experience,feature")
            ->where('id=?', $uid)
            ->fetchOne();
        return $info;
    }

    /**
     * 检测用户昵称
     * @param $uid
     * @param $name
     * @return int
     */
    public function checkNickname($uid, $name)
    {
        $isexist = \PhalApi\DI()->notorm->users
            ->select("id")
            ->where('id!=?  and user_nickname=?', $uid, $name)
            ->fetchOne();
        if ($isexist) {
            return 1;
        }

        return 0;
    }

    /**
     * 检测性别
     * @param $uid
     * @return int
     */
    public function checkSex($uid)
    {
        $isexist = \PhalApi\DI()->notorm->users
            ->select("sex")
            ->where('id!=?', $uid)
            ->fetchOne();
        if ($isexist && $isexist['sex'] != 0) {
            return 1;
        }

        return 0;
    }


    /**
     * 用户信息更新
     * @param $uid
     * @param $data
     * @return int
     */
    public function upUserInfo($uid, $data)
    {
        $rs = 0;
        if ($data) {
            $rs = \PhalApi\DI()->notorm->users
                ->where('id=? ', $uid)
                ->update($data);
        }

        return $rs;
    }

    /**
     * 设置关注
     * @param $uid
     * @param $touid
     * @return mixed
     */
    public function setAttent($uid, $touid)
    {

        $data = [
            'uid'     => $uid,
            'touid'   => $touid,
            'addtime' => time(),
        ];

        $list = \PhalApi\DI()->notorm->users_attention
            ->insert($data);

        return $list;
    }

    /**
     * 取消关注
     * @param $uid
     * @param $touid
     * @return mixed
     */
    public function delAttent($uid, $touid)
    {

        $where = [
            'uid'   => $uid,
            'touid' => $touid,
        ];

        $list = \PhalApi\DI()->notorm->users_attention
            ->where($where)
            ->delete();

        return $list;
    }

    /**
     * 关注列表
     * @param $where
     * @param $p
     * @return mixed
     */
    public function getAttention($where, $p)
    {

        if ($p < 1) {
            $p = 1;
        }

        $nums  = 50;
        $start = ($p - 1) * $nums;

        $list = \PhalApi\DI()->notorm->users_attention
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->limit($start, $nums)
            ->fetchAll();

        return $list;
    }

    /**
     * 所有关注、粉丝用户
     * @param $where
     * @return mixed
     */
    public function getAllAttention($where)
    {

        $list = \PhalApi\DI()->notorm->users_attention
            ->select('*')
            ->where($where)
            ->fetchAll();

        return $list;
    }

    /**
     * 根据条件获取用户ID
     * @param $where
     * @return mixed
     */
    public function getUsers($where)
    {

        $list = \PhalApi\DI()->notorm->users
            ->select('id')
            ->where($where)
            ->fetchAll();

        return $list;
    }

    /**
     * 根据条件获取单个用户信息
     * @param $where
     * @return mixed
     */
    public function getUsersInfo($where)
    {

        $info = \PhalApi\DI()->notorm->users
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

}
