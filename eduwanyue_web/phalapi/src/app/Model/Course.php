<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Course extends NotORM
{

    /**
     * 科目分类
     * @return mixed
     */
    public function getClass()
    {

        $list = \PhalApi\DI()->notorm->course_class
            ->select('*')
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }

    /**
     * 全部课程ID
     * @param $where
     * @return mixed
     */
    public function getAll($where)
    {

        $list = \PhalApi\DI()->notorm->course
            ->select('id,uid')
            ->where($where)
            ->fetchAll();

        return $list;
    }

    /**
     * 课程列表
     * @param $where 查询条件
     * @param $order 排序条件
     * @param $p 页码
     * @param $nums 数量
     * @return mixed
     */
    public function getList($where, $order, $p, $nums)
    {

        if ($p < 1) {
            $p = 1;
        }

        if ($nums == 0) {
            $nums = 20;
        }
        $start = ($p - 1) * $nums;

        $list = \PhalApi\DI()->notorm->course
            ->select('id,uid,sort,type,name,thumb,paytype,payval,status,starttime,lessons,islive,ismaterial,views')
            ->where($where)
            ->order($order)
            ->limit($start, $nums)
            ->fetchAll();

        return $list;
    }


    /**
     * 课程详情
     * @param $where 条件
     * @param string $field 字段
     * @return mixed
     */
    public function getDetail($where, $field = '*')
    {

        $info = \PhalApi\DI()->notorm->course
            ->select($field)
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /**
     * 全部购买记录
     * @param array $where 条件
     * @return mixed
     */
    public function getBuyAll($where = [])
    {

        $info = \PhalApi\DI()->notorm->course_users
            ->select('liveuid,courseid')
            ->where($where)
            ->fetchAll();

        return $info;
    }


    /**
     * 获取购买记录
     * @param int $p 页码
     * @param array $where 条件
     * @param string $order 排序条件
     * @return mixed
     */
    public function getBuyList($p = 1, $where = [], $order = 'id desc')
    {

        if ($p < 1) {
            $p = 1;
        }

        $nums = 20;
        $start = ($p - 1) * $nums;

        $info = \PhalApi\DI()->notorm->course_users
            ->select('*')
            ->where($where)
            ->order($order)
            ->limit($start, $nums)
            ->fetchAll();

        return $info;
    }


    /**
     * 获取购买课程IDs
     * @param array $where 条件
     * @return mixed
     */
    public function getMyBuyIds($where = [])
    {

        $info = \PhalApi\DI()->notorm->course_users
            ->select('courseid')
            ->where($where)
            ->fetchAll();

        return $info;
    }


    /**
     * 获取购买记录信息
     * @param $where 条件
     * @return mixed
     */
    public function getBuy($where)
    {

        $info = \PhalApi\DI()->notorm->course_users
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /**
     * 添加购买记录
     * @param $data
     * @return mixed
     */
    public function addBuy($data)
    {

        $rs = \PhalApi\DI()->notorm->course_users
            ->insert($data);
        return $rs;
    }


    /**
     * 更新购买记录
     * @param $where
     * @param $data
     * @return mixed
     */
    public function upBuy($where, $data)
    {

        $rs = \PhalApi\DI()->notorm->course_users
            ->where($where)
            ->update($data);

        return $rs;
    }


    /**
     * 课时列表
     * @param $where
     * @return mixed
     */
    public function getLessonList($where)
    {

        $list = \PhalApi\DI()->notorm->course_lesson
            ->select('id,uid,courseid,pid,type,name,des,url,istrial,islive,starttime')
            ->where($where)
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }


    /**
     * 课时详情
     * @param $where
     * @return mixed
     */
    public function getLesson($where)
    {

        $info = \PhalApi\DI()->notorm->course_lesson
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /**
     * 学习记录
     * @param $where
     * @return mixed
     */
    public function getView($where)
    {

        $info = \PhalApi\DI()->notorm->course_views
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->fetchAll();

        return $info;
    }


    /**
     * 学习记录信息
     * @param $where
     * @return mixed
     */
    public function getViewInfo($where)
    {

        $info = \PhalApi\DI()->notorm->course_views
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->fetchOne();

        return $info;
    }


    /**
     * 学级分类
     * @return mixed
     */
    public function getGrade()
    {

        $list = \PhalApi\DI()->notorm->course_grade
            ->select('*')
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }


    /**
     * 单个学级分类
     * @param $where
     * @return mixed
     */
    public function getGradeInfo($where)
    {

        $info = \PhalApi\DI()->notorm->course_grade
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }


}
