<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Course extends NotORM {

    /* 科目分类 */
    public function getClass() {

        $list=\PhalApi\DI()->notorm->course_class
            ->select('*')
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }

    /* 全部课程ID */
    public function getAll($where) {

        $list=\PhalApi\DI()->notorm->course
            ->select('id,uid')
            ->where($where)
            ->fetchAll();

        return $list;
    }

    /* 课程列表 */
    public function getList($where,$order,$p,$nums) {

        if($p<1){
            $p=1;
        }

        if($nums==0){
            $nums=40;
        }
        $start=($p-1) * $nums;

        $list=\PhalApi\DI()->notorm->course
            ->select('*')
            ->where($where)
            ->order($order)
            ->limit($start,$nums)
            ->fetchAll();

        return $list;
    }


    /* 课程详情 */
    public function getDetail($where,$field='*') {

        $info=\PhalApi\DI()->notorm->course
            ->select($field)
            ->where($where)
            ->fetchOne();

        return $info;
    }


    /* 更新星级、评论 */
    public function upStar($where,$stars=1,$comments=1) {

        $rs=\PhalApi\DI()->notorm->course
            ->where($where)
            ->update(['stars' => new \NotORM_Literal("stars + {$stars}"),'comments' => new \NotORM_Literal("comments + {$comments}")]);
        if($rs){
            $info=\PhalApi\DI()->notorm->course
                ->select('stars,comments')
                ->where($where)
                ->fetchOne();

            $star=\App\getStarLevel($info['stars'],$info['comments']);

            \PhalApi\DI()->notorm->course
                ->where($where)
                ->update(['star' => $star]);
        }


        return $rs;
    }

    /* 全部购买记录 */
    public function getBuyAll($where=[]) {


        $info=\PhalApi\DI()->notorm->course_users
            ->select('liveuid,courseid')
            ->where($where)
            ->fetchAll();

        return $info;
    }

    /* 获取购买记录 */
    public function getBuyList($p=1,$where=[],$order='id desc') {

        if($p<1){
            $p=1;
        }

        $nums=20;

        $start=($p-1) * $nums;

        $info=\PhalApi\DI()->notorm->course_users
            ->select('*')
            ->where($where)
            ->order($order)
            ->limit($start,$nums)
            ->fetchAll();

        return $info;
    }

    /* 获取购买课程IDs */
    public function getMyBuyIds($where=[]) {

        $info=\PhalApi\DI()->notorm->course_users
            ->select('courseid')
            ->where($where)
            ->fetchAll();

        return $info;
    }

    /* 获取购买记录信息 */
    public function getBuy($where) {

        $info=\PhalApi\DI()->notorm->course_users
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

    /* 添加购买记录 */
    public function addBuy($data) {

        $rs=\PhalApi\DI()->notorm->course_users
            ->insert($data);


        return $rs;
    }

    /* 更新购买记录 */
    public function upBuy($where,$data) {

        $rs=\PhalApi\DI()->notorm->course_users
            ->where($where)
            ->update($data);


        return $rs;
    }

    /* 课时列表 */
    public function getLessonList($where) {

        $list=\PhalApi\DI()->notorm->course_lesson
            ->select('id,uid,courseid,pid,type,name,des,url,istrial,islive,starttime')
            ->where($where)
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }

    /* 课时详情 */
    public function getLesson($where) {

        $info=\PhalApi\DI()->notorm->course_lesson
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

    /* 学习记录 */
    public function getView($where) {

        $info=\PhalApi\DI()->notorm->course_views
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->fetchAll();

        return $info;
    }

    /* 学习记录信息 */
    public function getViewInfo($where) {

        $info=\PhalApi\DI()->notorm->course_views
            ->select('*')
            ->where($where)
            ->order('addtime desc')
            ->fetchOne();

        return $info;
    }

    /* 学级分类 */
    public function getGrade() {

        $list=\PhalApi\DI()->notorm->course_grade
            ->select('*')
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }

    /* 单个学级分类 */
    public function getGradeInfo($where) {

        $info=\PhalApi\DI()->notorm->course_grade
            ->select('*')
            ->where($where)
            ->fetchOne();

        return $info;
    }

    //获取新闻列表
    public function getNews()
    {
        $list = \PhalApi\DI()->notorm->news
            ->select('*')
            ->order('list_order asc')
            ->fetchAll();

        return $list;
    }


    //获取新闻详情
    public function getNewsDetail($where)
    {
        $info = \PhalApi\DI()->notorm->news
            ->select('*')
            ->where($where)
            ->fetchOne();

        //访问量加1
        \PhalApi\DI()->notorm->news->where($where)->update(['views' => new \NotORM_Literal("views + 1")]);

        return $info;
    }

}
