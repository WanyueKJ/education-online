<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Live extends NotORM
{

    /**
     * 课程直播信息
     * @param $where
     * @return mixed
     */
    public function getLiveCourse($where)
    {

        $info = \PhalApi\DI()->notorm->course
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /**
     * 课时直播信息
     * @param $where
     * @return mixed
     */
    public function getLiveLesson($where)
    {

        $info = \PhalApi\DI()->notorm->course_lesson
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /**
     * PPT图片
     * @param $where
     * @return mixed
     */
    public function getPPT($where)
    {

        $list = \PhalApi\DI()->notorm->course_ppt
            ->select('*')
            ->where($where)
            ->order('id asc')
            ->fetchAll();

        return $list;
    }

    /**
     * 禁言
     * @param $where
     * @return mixed
     */
    public function getShutup($where)
    {

        $info = \PhalApi\DI()->notorm->live_shutup
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

    /**
     * 踢人
     * @param $where
     * @return mixed
     */
    public function getKick($where)
    {

        $info = \PhalApi\DI()->notorm->live_kick
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

    /**
     * 写入聊天
     * @param $data
     * @return mixed
     */
    public function setChat($data)
    {

        $info = \PhalApi\DI()->notorm->live_chat
            ->insert($data);

        return $info;
    }

    /**
     * 获取聊天记录
     * @param $where
     * @param string $order
     * @return mixed
     */
    public function getChat($where, $order = 'id desc')
    {

        $nums = 20;
        $list = \PhalApi\DI()->notorm->live_chat
            ->select('*')
            ->where($where)
            ->order($order)
            ->limit(0, $nums)
            ->fetchAll();

        return $list;
    }

    /**
     * 更新购买记录
     * @param $where
     * @param $data
     * @return mixed
     */
    public function upCourse($where, $data)
    {

        $rs = \PhalApi\DI()->notorm->course
            ->where($where)
            ->update($data);

        return $rs;
    }

    /**
     * 更新购买记录
     * @param $where
     * @param $data
     * @return mixed
     */
    public function upLesson($where, $data)
    {

        $rs = \PhalApi\DI()->notorm->course_lesson
            ->where($where)
            ->update($data);

        return $rs;
    }


}
