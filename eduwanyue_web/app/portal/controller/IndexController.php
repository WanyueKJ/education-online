<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use app\admin\model\CourseModel;
use app\admin\model\IpGardeModel;
use cmf\controller\StudentBaseController;
use think\Db;

class IndexController extends StudentBaseController
{

    public function initialize()
    {
        parent::initialize();
    }

    // 首页
    public function index()
    {
        $data = $this->request->param();

        $isBackLog = 0;
        if (isset($data['isBackLog'])) {
            $isBackLog = $data['isBackLog'];
        }

        $ip = get_client_ip();

        $info = IpGardeModel::where(['ip' => $ip])->find();

        if ($info) {
            $gradeid = $info['gardeid'];
        } else {
            $gradeid = 0;
        }

        $url        = $this->siteUrl . '/api/?s=Home.GetIndex&gradeid=' . $gradeid;
        $index_info = curl_get($url);

        $list = Db::name('slide_item')->field('id,title,image,url')->where('status=1 and slide_id=5')
            ->order('list_order asc')->select()->toArray();

        foreach ($list as $k => $v) {
            $v['image'] = get_upload_path($v['image']);
            $list[$k]   = $v;
        }
        $index_info['data']['info']['0']['silide'] = $list;


        //lessonlist部分
        //查找学段
        $xdlist = Db::name('course_grade')->field('id,name')->where(['pid' => 0])->order('list_order asc')->select();

        $xdid = 0; //第一个学段的id
        if ($xdlist) {
            $xdid = $xdlist[0]['id'];
        }

        //查找第一个学段的年级
        $njlist = array();
        $njid   = 0;
        if ($xdid != 0) {
            $njlist = Db::name('course_grade')->field('id,name')->where(['pid' => $xdid])->order('list_order asc')->select();
            if ($njlist) {
                $njid = $njlist[0]['id'];
            }
        }

        $nowtime = time();
        $neirong = [];
        if (isset($data['keywords'])) {
            $keywords = $data['keywords'];

            $userinfo = session('student');

            if ($userinfo) {
                $gradeid = $userinfo['gradeid'];
            } else {

                $ip = get_client_ip();

                $info    = IpGardeModel::where(['ip' => $ip])->find();
                $gradeid = $info['gardeid'];
            }


            //." and name like '%".$keywords."%'"
        //    $where = "gradeid=" . $gradeid . " and status>=1 and shelvestime<" . $nowtime;
			
			$where=[
				['gradeid','=',$gradeid],
				['status','>=',1],
				['shelvestime','<',$nowtime],
			];
			
            $list  = CourseModel::where($where)->order('list_order asc,id desc')->limit(8)->select()->toArray();

            foreach ($list as $k => $v) {

                $isN     = strpos($v['name'], $keywords);
                $teacher = getUserInfo($v['uid']);
                $isT     = strpos($teacher['user_nickname'], $keywords);
                if ($isN === false && $isT === false) { //不包含

                    unset($list[$k]);
                    continue;
                }

                $v = handelInfo($v);

                $userinfo           = getUserInfo($v['uid']);
                $v['user_nickname'] = $userinfo['user_nickname'];
                $v['avatar']        = $userinfo['avatar'];

                $list[$k] = $v;

                if ($v['sort'] == 0) {
                    $neirong[$k] = $v;
                }
            }

            $list = array_values($list);

            $isMore = 0;
        } else {
            $keywords = '';
        //    $list = CourseModel::where('sort = 1 and gradeid = ' . $njid . ' and status >= 1 and shelvestime <' . $nowtime)
		$map = [
			['sort','=',1],
			['gradeid','=',$njid],
			['status','>=',1],
			['shelvestime','<',$nowtime],
		];
            $list = CourseModel::where($map)
                ->order('list_order asc,id desc')
                ->limit(8)
                ->select();
            foreach ($list as $k => $v) {
                $v = handelInfo($v);

                $userinfo           = getUserInfo($v['uid']);
                $v['user_nickname'] = $userinfo['user_nickname'];
                $v['avatar']        = $userinfo['avatar'];

                $list[$k] = $v;
                if ($v['sort'] == 0) {
                    $neirong[$k] = $v;
                }
            }

            $isMore = 0;
            if (count($list) >= 20) {
                $isMore = 1;
            }

        }

        $this->assign([
            'xdlist'   => $xdlist,
            'xdid'     => $xdid,
            'njlist'   => $njlist,
            'njid'     => $njid,
            'lesslist' => $list,
            'isMore'   => $isMore,
            'navid'    => 1,
            'keywords' => $keywords,
            'neirong'  => $neirong
        ]);


        $this->assign([
            'index_info' => $index_info['data']['info']['0'],
            'isBackLog'  => $isBackLog,
            'navid'      => 0,
        ]);

        return $this->fetch(":index");
    }



}

