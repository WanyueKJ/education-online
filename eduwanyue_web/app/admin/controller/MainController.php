<?php
// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/10/26 09:08
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\CourseModel;
use app\admin\model\CourseUsersModel;
use app\admin\model\Menu;
use app\admin\model\UsersModel;
use cmf\controller\AdminBaseController;


/*
 * 后台欢迎页
 * Class MainController
 * @package app\admin\controller
 */
class MainController extends AdminBaseController
{


    /**今天全天收入
     * @var array
     */
    protected $todayPayAddNum = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];


    /**
     * 昨天全天收入
     * @var array
     */
    protected $yePayAddNum = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];


    /**
     * 订单金额分布数组
     * @var array
     */
    protected $tradeNoNum = [0, 0, 0, 0, 0, 0, 0];


    /**
     *  后台欢迎页(用户分析)
     */
    public function index()
    {
        $dashboardWidgets = [];
        $widgets          = cmf_get_option('admin_dashboard_widgets');

        $defaultDashboardWidgets = [
            '_SystemCmfHub'           => ['name' => 'CmfHub', 'is_system' => 1],
            '_SystemCmfDocuments'     => ['name' => 'CmfDocuments', 'is_system' => 1],
            '_SystemMainContributors' => ['name' => 'MainContributors', 'is_system' => 1],
            '_SystemContributors'     => ['name' => 'Contributors', 'is_system' => 1],
            '_SystemCustom1'          => ['name' => 'Custom1', 'is_system' => 1],
            '_SystemCustom2'          => ['name' => 'Custom2', 'is_system' => 1],
            '_SystemCustom3'          => ['name' => 'Custom3', 'is_system' => 1],
            '_SystemCustom4'          => ['name' => 'Custom4', 'is_system' => 1],
            '_SystemCustom5'          => ['name' => 'Custom5', 'is_system' => 1],
        ];

        if (empty($widgets)) {
            $dashboardWidgets = $defaultDashboardWidgets;
        } else {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    $dashboardWidgets['_System' . $widget['name']] = ['name' => $widget['name'], 'is_system' => 1];
                } else {
                    $dashboardWidgets[$widget['name']] = ['name' => $widget['name'], 'is_system' => 0];
                }
            }

            foreach ($defaultDashboardWidgets as $widgetName => $widget) {
                $dashboardWidgets[$widgetName] = $widget;
            }


        }

        $dashboardWidgetPlugins = [];

        $hookResults = hook('admin_dashboard');

        if (!empty($hookResults)) {
            foreach ($hookResults as $hookResult) {
                if (isset($hookResult['width']) && isset($hookResult['view']) && isset($hookResult['plugin'])) { //验证插件返回合法性
                    $dashboardWidgetPlugins[$hookResult['plugin']] = $hookResult;
                    if (!isset($dashboardWidgets[$hookResult['plugin']])) {
                        $dashboardWidgets[$hookResult['plugin']] = ['name' => $hookResult['plugin'], 'is_system' => 0];
                    }
                }
            }
        }

        $smtpSetting = cmf_get_option('smtp_setting');

        $this->assign('dashboard_widgets', $dashboardWidgets);
        $this->assign('dashboard_widget_plugins', $dashboardWidgetPlugins);
        $this->assign('has_smtp_setting', empty($smtpSetting) ? false : true);

        $this->assign('focus_flag', 'index');

        return $this->fetch();

    }


    /**
     * 用户数据统计(用户概况及趋势)
     * @param void
     * @return array
     */
    public function userDataAnalyse()
    {

        // 根据传入的时间获取开始结束时间戳
        $range = strtotime(input('range')) ?? time();
        //选中的天数
        $day_start = $range;
        $day_end   = mktime(0, 0, 0, date('m', $range), date('d', $range) + 1, date('Y', $range)) - 1; //23:59:59

        //昨天
        $yesterday_start = mktime(0, 0, 0, date('m', $range), date('d', $range) - 1, date('Y', $range));
        $yesterday_end   = mktime(0, 0, 0, date('m', $range), date('d', $range), date('Y', $range)) - 1;

        /*用户概况及趋势*/
        //新增用户数量、活跃用户数量(浏览过商品详情的用户数量)、累计用户数量、付费用户数量、累计付费用户数量(course_users表paytype值为1的用户是付费用户)
        //各自较前一天增长或下降的百分比

        $userAllData  = UsersModel::whereTime('create_time', '<=', $day_end)->select(); //累计用户数量
        $userAllNum   = count($userAllData);
        $dayReg       = 0;
        $yesterdayReg = 0;
        foreach ($userAllData as $k => $v) {
            if ($v['create_time'] >= $day_start &&
                $v['create_time'] <= $day_end) {
                $dayReg++;
            }

            if ($v['create_time'] >= $yesterday_start &&
                $v['create_time'] <= $yesterday_end) {
                $yesterdayReg++;
            }

        }

        //与前一天新增用户数量比较(小等大都有可能)
        if ($yesterdayReg == 0) {
            if ($dayReg != 0) {
                $dayRatio = $dayReg * 100;
            } else {
                $dayRatio = 0;
            }
        } else {
            $dayRatio = round(($dayReg / $yesterdayReg), 2) * 100;
        }
        if ($dayReg < $yesterdayReg) {
            $reg_flag = 'small';
        } else {
            $reg_flag = 'big';
        }

        //活跃用户数量
        $activeNum = db('student_log')
            ->whereTime('created_at', 'between', [$day_start, $day_end])
            ->count();
        //前一天活跃用户数量
        $yesterdayActiveNum = db('student_log')
            ->whereTime('created_at', 'between', [$yesterday_start, $yesterday_end])
            ->count();
        //与前一天活跃用户数量比较(小等大都有可能)
        if ($yesterdayActiveNum == 0) {
            if ($activeNum != 0) {
                $activeRatio = $activeNum;
            } else {
                $activeRatio = 0;
            }
        } else {
            $activeRatio = round(($activeNum / $yesterdayActiveNum), 2) * 100;
        }

        if ($activeNum < $yesterdayActiveNum) {
            $active_flag = 'small';
        } else {
            $active_flag = 'big';
        }

        //前一天累计用户数量
        $yesterdayUserAllNum = $userAllNum - $dayReg;
        //与前一天累计新增用户数量比较(一定大于等于昨天)
        if ($yesterdayUserAllNum == 0) {
            if ($dayReg != 0) {
                $userAllRatio = $dayReg * 100;
            } else {
                $userAllRatio = 0;
            }
        } else {
            $userAllRatio = round(($dayReg / $yesterdayUserAllNum), 2) * 100;
        }

        //累计付费用户数量
        $payAllData   = CourseUsersModel::whereTime('paytime', '<=', $day_end)->group('uid')->select();
        $payAllNum    = count($payAllData);
        $dayPay       = 0;
        $yesterdayPay = 0;
        foreach ($payAllData as $k => $v) {
            if ($v['paytime'] >= $day_start &&
                $v['paytime'] <= $day_end) {
                $dayPay++;
            }

            if ($v['paytime'] >= $yesterday_start &&
                $v['paytime'] <= $yesterday_end) {
                $yesterdayPay++;
            }
        }

        //与前一天付费用户数量之比(小等大都有可能)
        if ($yesterdayPay == 0) {
            if ($dayPay != 0) {
                $payRatio = $dayPay;
            } else {
                $payRatio = 0;
            }
        } else {
            $payRatio = round(($dayPay / $yesterdayPay), 2) * 100;
        }
        if ($dayPay < $yesterdayPay) {
            $dayPayFlag = 'small';
        } else {
            $dayPayFlag = 'big';
        }

        //与前一天累计付费用户数量比较(一定大于等于昨天)
        $yesterdayPayAllNum = $payAllNum - $dayPay;
        if ($yesterdayPayAllNum == 0) {
            if ($dayPay != 0) {
                $payAllRatio = $dayPay;
            } else {
                $payAllRatio = 0;
            }
        } else {
            $payAllRatio = round(($dayPay / $yesterdayPayAllNum), 2) * 100;
        }

        $analyse = [
            'day_reg'     => [
                'day_reg_num' => $dayReg, //新增用户数量
                'day_ratio'   => $dayRatio, //与前一天新增用户数量之比
                'reg_flag'    => $reg_flag, //比较
            ],
            'active_name' => [
                'active_name_num' => $activeNum, //活跃用户数量
                'active_ratio'    => $activeRatio, //与前一天活跃用户数量之比
                'active_flag'     => $active_flag, //比较
            ],
            'user_all'    => [
                'user_all_num'   => $userAllNum, //累计用户数量
                'user_all_ratio' => $userAllRatio, //与前一天新增用户数量之比
                'user_all_flag'  => 'big', //一定大于等于昨天
            ],
            'day_pay'     => [
                'day_pay_num'  => $dayPay, //当天付费用户数量
                'pay_ratio'    => $payRatio,   //与前一天付费用户数量之比
                'day_pay_flag' => $dayPayFlag, //比较
            ],
            'pay_all'     => [
                'pay_all_num'   => $payAllNum, //累计付费用户数量
                'pay_all_ratio' => $payAllRatio, //与前一天累计付费用户数量之比
                'pay_all_flag'  => 'big', //一定大于等于昨天
            ]

        ];

        echo json_encode($analyse, JSON_UNESCAPED_UNICODE);

    }


    /**
     * 获取填充到用户分析echarts图表的数据
     */
    public function getUserEcharts()
    {
        $arrX = input('arr_x') ?? [];
        if (!$arrX) {
            echo json_encode([]);
            exit;
        }

        $firstDate = date("Y-m-d", strtotime("-3 days", strtotime($arrX[0])));
        array_unshift($arrX, $firstDate);

        //(注册时传入的时间戳带时分秒, 参数中的时间戳只有年月日)
        //新增用户数量(每3天累加一次)
        $userData = UsersModel::whereTime('create_time', [$arrX[0], $arrX[9]])->column(['id', 'create_time']);
        $ymdTime  = [];
        foreach ($userData as $k => $v) {
            $ymdTime[] = date('Y-m-d', $v); //转为年月日
        }
        $regNumArr = array_count_values($ymdTime); //相同注册日期出现的次数

        $newReg = [];
        foreach ($arrX as $k => $v) {
            if (isset($regNumArr[$v])) {
                $newReg[$v] = $regNumArr[$v];
            } else {
                $newReg[$v] = 0;
            }
        }

        //所有用户活跃数据
        $activeDatas = db('student_log')->select()->toArray();

        //所有用户付费数据(X坐标轴的时间段内)
        $payAllData = CourseUsersModel::whereTime('paytime', [$arrX[0], $arrX[9]])->group('uid')->select();

        $regNumEnd    = []; //用户数量容器(3天一算) 从第一个3天开始算,所以初始为0
        $activeNumEnd = []; //活跃用户容器(3天一算)
        $allNumEnd    = []; //累计用户容器(截至到最初)
        $payNumEnd    = []; //付费用户容器(3天一算)
        $payAllNumEnd = []; //累计付费用户容器(截至到最初)
        foreach ($arrX as $k => $v) {
            if ($k == 0) {
                continue; //从第二个单位开始统计(第一个是占位用, 统计往前3天的)
            }
            $time    = strtotime($v);
            $preTime = strtotime($arrX[$k - 1]);

            /* 新用户数量 */
            $new_reg_num = 0;
            foreach ($newReg as $kk => $vv) {

                $k_time = strtotime($kk);
                if ($k_time >= $preTime && $k_time < $time) {
                    //大于等于上个单位, 小于本单位(范围共3天)
                    $new_reg_num += $vv;
                } else {
                    $new_reg_num += 0;
                }
            }
            $regNumEnd[$k - 1] = $new_reg_num;

            /*活跃用户数量(浏览过商品详情的用户)*/
            $active_num = 0;
            foreach ($activeDatas as $k_a => $v_a) {
                $ka_time = $v_a['created_at'];
                if ($ka_time >= $preTime && $ka_time < $time) {
                    //范围3天
                    $active_num++;
                }
            }

            $activeNumEnd[$k - 1] = $active_num;

            /*累计用户数量(是累计不是范围)*/
            $all_num = 0;
            foreach ($userData as $k_u => $v_u) {
                $ku_time = $v_u;
                if ($ku_time < $time) {
                    $all_num++;
                }
            }

            $allNumEnd[$k - 1] = $all_num;

            /*每隔3天的付费用户*/
            $pay_num     = 0;
            $pay_all_num = 0;
            foreach ($payAllData as $k_p => $v_p) {
                $kp_time = $v_p['paytime'];
                if ($kp_time < $time) {
                    if ($kp_time >= $preTime) {
                        //范围3天
                        $pay_num++;
                    }
                    $pay_all_num++;
                }

            }

            $payNumEnd[$k - 1]    = $pay_num;
            $payAllNumEnd[$k - 1] = $pay_all_num;

        }


        $echartsData = [
            'reg_num'     => $regNumEnd, //新增用户
            'active_num'  => $activeNumEnd, //活跃用户
            'all_num'     => $allNumEnd,   //累计用户
            'pay_num'     => $payNumEnd,   //付费用户
            'pay_all_num' => $payAllNumEnd, //累计付费用户
        ];


        echo json_encode($echartsData, JSON_UNESCAPED_UNICODE);

    }


    /*
     * 获取付费用户数据(用户分析)
     * @return json
     */
    public function getPayTableData()
    {

        // 时间参数
        $range = strtotime(input('range')) ?? time();

        //客户类型	用户数 	用户数占比 	客单价 	结算金额(元) 结算转化率

        //全部已成交用户 去重uid
        $payUsersData = CourseUsersModel::where('paytype', 1)->where('paytime', '>', 0)->group('uid')->select();

        /* 用户数 */
        $allPay = count($payUsersData); //所有成交用户数量
        $oldPay = 0; //老付费用户 所选时间之前有付费记录的用户
        $newPay = 0; //新付费用户 所选时间之前没有付费记录的用户

        $allPrice = 0; // 全部价 (结算金额)
        $oldPrice = 0; //老付费用户全部价 (结算金额)
        $newPrice = 0; //新付费用户全部价 (结算金额)
        foreach ($payUsersData as $k => $v) {
            if ($v['paytime'] < $range) {
                $oldPay++;
                $oldPrice += $v['money'];
            } else {
                $newPay++;
                $newPrice += $v['money'];
            }

            $allPrice += $v['money'];
        }

        $allUsersNum = UsersModel::count();

        /* 用户数占比 */
        if ($allUsersNum != 0) {

            $allPayRatio = round(($allPay / $allUsersNum), 2) * 100;
            $oldPayRatio = round(($oldPay / $allUsersNum), 2) * 100;
            $newPayRatio = round(($newPay / $allUsersNum), 2) * 100;
        } else {

            $allPayRatio = $allPay;
            $oldPayRatio = $oldPay;
            $newPayRatio = $newPay;
        }

        /* 客单价 */
        if ($allPay != 0) {
            $unitPriceAllUser = round(($allPrice / $allPay), 2);
        } else {
            $unitPriceAllUser = 0;
        }
        if ($oldPay != 0) {
            $unitPriceOldUser = round(($oldPrice / $oldPay), 2);
        } else {
            $unitPriceOldUser = 0;
        }
        if ($newPay != 0) {
            $unitPriceNewUser = round(($newPrice / $newPay), 2);
        } else {
            $unitPriceNewUser = 0;
        }

        /*结算转化率 100%*/
        $data = [
            'all' => [
                'user_num'        => $allPay,  //用户数
                'ratio'           => $allPayRatio, //用户数占比
                'unit_price'      => $unitPriceAllUser, //客单价
                'settlement'      => $allPrice, //结算金额
                'settlement_rate' => $allPrice ? 100 : 0, //结算转化率
            ],
            'old' => [
                'user_num'        => $oldPay,
                'ratio'           => $oldPayRatio,
                'unit_price'      => $unitPriceOldUser,
                'settlement'      => $oldPrice,
                'settlement_rate' => $oldPrice ? 100 : 0,
            ],
            'new' => [
                'user_num'        => $newPay,
                'ratio'           => $newPayRatio,
                'unit_price'      => $unitPriceNewUser,
                'settlement'      => $newPrice,
                'settlement_rate' => $newPrice ? 100 : 0,
            ]

        ];


        echo json_encode($data);

    }





    /****************************数据概况区域******************************/

    /*
     * 用户概况
     * @return mixed 数据概况数据
     */
    public function dataview()
    {
        //今天
        $day_end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1; //23:59:59
        //昨天
        $yday_start = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $yday_end   = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;

        //今日收入(元) 昨日全天收入(元)
        $incomes     = CourseUsersModel::whereTime('paytime', [$yday_start, $day_end])->select();
        $yeIncome    = 0; //昨天收入
        $todayIncome = 0; //今天收入

        foreach ($incomes as $k => $v) {
            if ($v['paytime'] < $yday_end) { //昨天
                $yeIncome += $v['money'];
                //echarts数据
                $h = sprintf('%d', date('H', $v['paytime']));
                $this->getUserPayYeAddNum($h);

            } else { //今天
                $todayIncome += $v['money'];
                //echarts数据 分小时
                $h = sprintf('%d', date('H', $v['paytime']));
                $this->getUserPayTodayAddNum($h);
            }

        }

        //付费用户数
        $payUserData = array_column($incomes->toArray(), NULL, 'uid'); //去重;
        $payUserData = array_values($payUserData); //去重;
        $yePayNum    = 0; //昨天付费用户数
        $todayPayNum = 0; //今日付费用户数
        foreach ($payUserData as $k => $v) {
            if ($v['paytime'] < $yday_end) {
                $yePayNum++;
            } else {
                $todayPayNum++;
            }
        }


        //浏览量(student_log)
        $visitsData = db('student_log')->whereTime('created_at', [$yday_start, $day_end])->select();
        $yeView     = 0; //昨天浏览量
        $todayView  = 0; //今天浏览量
        foreach ($visitsData as $k => $v) {
            if ($v['created_at'] < $yday_end) {
                $yeView++;
            } else {
                $todayView++;
            }
        }

        $groupData = array_column($visitsData->toArray(), NULL, 'student_id'); //去重
        $groupData = array_values($groupData); //去除关联索引

        // 访客数
        //加昨日全天
        $yeVisit    = 0; //昨天访客数
        $todayVisit = 0; //今天访客数
        foreach ($groupData as $k => $v) {
            if ($v['created_at'] < $yday_end) {
                $yeVisit++;
            } else {
                $todayVisit++;
            }
        }

        //支付订单数 取付费用户数
        $this->assign([
            'today_income'   => $todayIncome, //今天全天收入
            'ye_income'      => $yeIncome, //昨天全天收入
            'today_pay_num'  => $todayPayNum, //今天付费用户数
            'ye_pay_num'     => $yePayNum,  //昨日付费用户数
            'today_view'     => $todayView, //今天浏览量
            'ye_view'        => $yeView,   //昨天浏览量
            'ye_visit'       => $yeVisit,  //昨天访客数
            'today_visit'    => $todayVisit, //今天访客数
            'ye_trade_no'    => $yePayNum, //昨天订单数
            'today_trade_no' => $todayPayNum, //今天订单数
            'today_pay_add'  => json_encode($this->todayPayAddNum), //今日全天收入
            'ye_pay_add'     => json_encode($this->yePayAddNum), //昨天全天收入
            'focus_flag'     => 'data_view',
        ]);


        return $this->fetch('data_view');

    }


    /**
     * 获取运营概况数据(数据概况页下方5个方框)
     * @desc //环比计算公式
     *       //A = 6月的销售额
     *       //B = 6月前一个相邻周期5月的销售额
     *       //6月销售额的环比 = （A - B ）/ B * 100%
     * @return array 结算金额(元) 访客数 支付转化率 客单价 付费用户数 各自日环比 周环比
     */
    public function getYunSurveyData()
    {
        $range   = strtotime(input('range')) ?? time();
        $day_end = mktime(0, 0, 0, date('m', $range), date('d', $range) + 1, date('Y', $range)) - 1; //23:59:59

        //昨天
        $yesterday_start = mktime(0, 0, 0, date('m', $range), date('d', $range) - 1, date('Y', $range));
        $yesterday_end   = mktime(0, 0, 0, date('m', $range), date('d', $range), date('Y', $range)) - 1;
        //上周同一天
        $preWeekDay = strtotime(input('range') . " -1 week");

        //结算金额(元) 访客数 支付转化率 客单价 付费用户数 [昨天、今天]
        $payUsersData = CourseUsersModel::where('paytype', 1)
            ->whereTime('paytime', [$yesterday_start, $day_end])
            ->select();

        $todaySettleMoney = 0; //当天结算金额累加
        $todayPayNum      = 0; //今天支付数量
        $yePayNum         = 0; //昨天支付数量
        $yeSettleMoney    = 0; //上一天结算金额累加
        foreach ($payUsersData as $k => $v) {
            if ($v['paytime'] < $yesterday_end) {
                $yeSettleMoney += $v['money'];
                $yePayNum++;
            } else {
                $todaySettleMoney += $v['money'];
                $todayPayNum++;
            }
        }

        //结算金额(日环比 和昨天对比)
        if ($yeSettleMoney != 0) {
            $settleRatio = abs(round((($todaySettleMoney - $yeSettleMoney) / $yeSettleMoney), 2));
        } else {
            $settleRatio = $todaySettleMoney;
        }
        $settleDayFlag = ($todaySettleMoney > $yeSettleMoney) ? 'big' : 'small';

        //结算金额(周环比 和上周同一天对比)
        $preWeekDaySettleData  = CourseUsersModel::whereTime('paytime', $preWeekDay)->select();
        $preWeekDaySettleMoney = 0;
        $preWeekUnitPayCount   = 0; //上周同一天 支付数量
        foreach ($preWeekDaySettleData as $k => $v) {
            $preWeekDaySettleMoney += $v['money'];
            $preWeekUnitPayCount++;
        }

        if ($preWeekDaySettleMoney != 0) {
            $settleRatioWeek = abs(round((($todaySettleMoney - $preWeekDaySettleMoney) / $preWeekDaySettleMoney), 2));
        } else {
            $settleRatioWeek = $todaySettleMoney;
        }

        $settleWeekFlag = ($todaySettleMoney > $preWeekDaySettleMoney) ? 'big' : 'small';


        /* 访客数 */
        $visitsData    = db('student_log')->whereTime('created_at', [$yesterday_start, $day_end])->select();
        $yeVisitNum    = 0; //昨天访客数
        $todayVisitNum = 0; //今天访客数
        foreach ($visitsData as $k => $v) {
            if ($v['created_at'] < $yesterday_end) {
                $yeVisitNum++;
            } else {
                $todayVisitNum++;
            }
        }
        //访客数日环比
        if ($yeVisitNum != 0) {
            $settleRatioVisit = abs(round((($todayVisitNum - $yeVisitNum) / $yeVisitNum), 2));
        } else {
            $settleRatioVisit = $todayVisitNum;
        }

        $visitDayFlag = ($todayVisitNum > $yeVisitNum) ? 'big' : 'small';

        //访客数周环比
        $preWeekDayVisitNum = db('student_log')->whereTime('created_at', $preWeekDay)->group('student_id')->count();

        if ($preWeekDayVisitNum != 0) {
            $settleRatioWeekVisit = abs(round((($todayVisitNum - $preWeekDayVisitNum) / $preWeekDayVisitNum), 2));
        } else {
            $settleRatioWeekVisit = $todayVisitNum;
        }

        $visitWeekFlag = ($todayVisitNum > $preWeekDayVisitNum) ? 'big' : 'small';

        /*支付转化率 100% 暂没有打折*/

        //客单价 今天总结算金额 / 今天所有支付数量
        if ($todayPayNum != 0) {
            $unitPrice = round(($todaySettleMoney / $todayPayNum), 2);
        } else {
            $unitPrice = 0;
        }
        /*客单价日环比*/
        if ($yePayNum != 0) {
            $yeUnitPrice = round(($yeSettleMoney / $yePayNum), 2);
        } else {
            $yeUnitPrice = 0;
        }

        if ($yeUnitPrice != 0) {
            $settleRatioUnitPrice = round((abs($unitPrice - $yeUnitPrice) / $yeUnitPrice), 2);
        } else {
            $settleRatioUnitPrice = $unitPrice;
        }

        $unitPriceDayFlag = ($unitPrice > $yeUnitPrice) ? 'big' : 'small';

        /*客单价上周同一天 上周同一天总结算金额 / 上周同一天所有支付数量*/
        if ($preWeekUnitPayCount != 0) {
            $preWeekUnitPrice = round(($preWeekDaySettleMoney / $preWeekUnitPayCount), 2);
        } else {
            $preWeekUnitPrice = 0;
        }

        /*客单价周环比 本周-上周 / 上周 */
        if ($preWeekUnitPrice != 0) {
            $settleRatioUnitPriceWeek = round((abs($unitPrice - $preWeekUnitPrice) / $preWeekUnitPrice), 2);
        } else {
            $settleRatioUnitPriceWeek = 0;
        }

        $unitPriceWeekFlag = ($unitPrice > $preWeekUnitPrice) ? 'big' : 'small';


        //付费用户数
        $payGroupByUid = array_column($payUsersData->toArray(), NULL, 'uid');
        $payGroupByUid = array_values($payGroupByUid); //去重uid;

        // 付费用户数
        $yePayUserNum    = 0; //昨天付费用户数
        $todayPayUserNum = 0; //今天付费用户数
        foreach ($payGroupByUid as $k => $v) {
            if ($v['paytime'] < $yesterday_end) {
                $yePayUserNum++;
            } else {
                $todayPayUserNum++;
            }
        }

        /*付费用户数日环比*/
        if ($yePayUserNum != 0) {
            $settleRatioPayUserNum = round((abs($todayPayUserNum - $yePayUserNum) / $yePayUserNum), 2);
        } else {
            $settleRatioPayUserNum = $todayPayUserNum;
        }

        $payUserDayFlag = ($todayPayUserNum > $yePayUserNum) ? 'big' : 'small';

        /*付费用户数周环比*/
        //上周同一时间 付费用户数
        $payGroupByUidPreWeek = array_column($preWeekDaySettleData->toArray(), NULL, 'uid');
        $payGroupByUidPreWeek = array_values($payGroupByUidPreWeek);

        $preWeekPayUserNum = count($payGroupByUidPreWeek);

        //付费用户数周环比
        if ($preWeekPayUserNum != 0) {
            $settleRatioPayUserWeek = round((abs($todayPayUserNum - $preWeekPayUserNum) / $preWeekPayUserNum), 2);
        } else {
            $settleRatioPayUserWeek = $todayPayUserNum;
        }

        $payUserWeekFlag = ($todayPayUserNum > $preWeekPayUserNum) ? 'big' : 'small';


        /** 结算金额 $todaySettleMoney 日环比 $settleRatio 周环比 $settleRatioWeek **/
        /* 访客数 $todayVisitNum 日环比 $settleRatioVisit 周环比 $settleRatioWeekVisit */
        /*支付转化率 100%*/
        /* 客单价 $unitPrice 日环比 $settleRatioUnitPrice 周环比 $settleRatioUnitPriceWeek */
        /* 付费用户数 $todayPayUserNum 日环比 $settleRatioPayUserNum 周环比 $settleRatioPayUserWeek */

        $data = [
            'settle'          => [
                'settle_money' => $todaySettleMoney,
                'day_ratio'    => $settleRatio,
                'week_ratio'   => $settleRatioWeek,
                'day_flag'     => $settleDayFlag,
                'week_flag'    => $settleWeekFlag,
            ],
            'visit'           => [
                'today_visit_num' => $todayVisitNum,
                'day_ratio'       => $settleRatioVisit,
                'week_ratio'      => $settleRatioWeekVisit,
                'day_flag'        => $visitDayFlag,
                'week_flag'       => $visitWeekFlag,
            ],
            'user_unit_price' => [
                'unit_price' => $unitPrice,
                'day_ratio'  => $settleRatioUnitPrice,
                'week_ratio' => $settleRatioUnitPriceWeek,
                'day_flag'   => $unitPriceDayFlag,
                'week_flag'  => $unitPriceWeekFlag,
            ],
            'pay_user'        => [
                'pay_user_num' => $todayPayUserNum,
                'day_ratio'    => $settleRatioPayUserNum,
                'week_ratio'   => $settleRatioPayUserWeek,
                'day_flag'     => $payUserDayFlag,
                'week_flag'    => $payUserWeekFlag,
            ]

        ];


        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }


    /**
     * 获取指标趋势echarts数据
     */
    public function getDataViewEcharts()
    {
        $arrX = input('arr_x') ?? [];
        if (!$arrX) {
            echo json_encode([]);
            exit;
        }

        $firstDate = date("Y-m-d", strtotime("-3 days", strtotime($arrX[0])));
        array_unshift($arrX, $firstDate);

        //(注册时传入的时间戳带时分秒, 参数中的时间戳只有年月日)
        //新增结算金额数量(每3天累加一次)
        $settleData = CourseUsersModel::whereTime('paytime', [$arrX[0], $arrX[9]])->select()->toArray();


        $settleEnd    = []; //结算金额容器(3天一算)
        foreach ($arrX as $k => $v) {
            if ($k == 0) {
                continue; //从第二个单位开始统计(第一个是占位用, 统计往前3天的)
            }
            $time    = strtotime($v);
            $preTime = strtotime($arrX[$k - 1]);

            /* 结算金额 */
            $new_settle_num = 0;
            foreach ($settleData as $kk => $vv) {

                $v_time = $vv['paytime'];
                if ($v_time >= $preTime && $v_time < $time) {
                    //大于等于上个单位, 小于本单位(范围共3天)
                    $new_settle_num += $vv['money'];
                } else {
                    $new_settle_num += 0;
                }
            }
            $settleEnd[$k - 1] = $new_settle_num;

        }


        $echartsData = [
            'settle'     => $settleEnd, //结算金额
        ];


        echo json_encode($echartsData, JSON_UNESCAPED_UNICODE);

    }







    /**
     * 获取用户今天增加的数量
     * @param $h
     */
    private function getUserPayTodayAddNum($h)
    {
        // data: ['0', '1', '2', '3', '4', ....'23'],
        if ($h < 1) {
            $this->todayPayAddNum[1]++;
        } elseif ($h >= 1 && $h < 2) {
            $this->todayPayAddNum[2]++;
        } elseif ($h >= 2 && $h < 3) {
            $this->todayPayAddNum[3]++;
        } elseif ($h >= 3 && $h < 4) {
            $this->todayPayAddNum[4]++;
        } elseif ($h >= 4 && $h < 5) {
            $this->todayPayAddNum[5]++;
        } elseif ($h >= 5 && $h < 6) {
            $this->todayPayAddNum[6]++;
        } elseif ($h >= 6 && $h < 7) {
            $this->todayPayAddNum[7]++;
        } elseif ($h >= 7 && $h < 8) {
            $this->todayPayAddNum[8]++;
        } elseif ($h >= 8 && $h < 9) {
            $this->todayPayAddNum[9]++;
        } elseif ($h >= 9 && $h < 10) {
            $this->todayPayAddNum[10]++;
        } elseif ($h >= 10 && $h < 11) {
            $this->todayPayAddNum[11]++;
        } elseif ($h >= 11 && $h < 12) {
            $this->todayPayAddNum[12]++;
        } elseif ($h >= 12 && $h < 13) {
            $this->todayPayAddNum[13]++;
        } elseif ($h >= 13 && $h < 14) {
            $this->todayPayAddNum[14]++;
        } elseif ($h >= 14 && $h < 15) {
            $this->todayPayAddNum[15]++;
        } elseif ($h >= 15 && $h < 16) {
            $this->todayPayAddNum[16]++;
        } elseif ($h >= 16 && $h < 17) {
            $this->todayPayAddNum[17]++;
        } elseif ($h >= 17 && $h < 18) {
            $this->todayPayAddNum[18]++;
        } elseif ($h >= 18 && $h < 19) {
            $this->todayPayAddNum[19]++;
        } elseif ($h >= 19 && $h < 20) {
            $this->todayPayAddNum[20]++;
        } elseif ($h >= 20 && $h < 21) {
            $this->todayPayAddNum[21]++;
        } elseif ($h >= 21 && $h < 22) {
            $this->todayPayAddNum[22]++;
        } elseif ($h >= 22 && $h < 23) {
            $this->todayPayAddNum[23]++;
        }

    }


    /**
     * 获取用户昨天增加的数量
     * @param $h
     */
    private function getUserPayYeAddNum($h)
    {
        // data: ['0', '1', '2', '3', '4', ....'23'],
        if ($h < 1) {
            $this->yePayAddNum[1]++;
        } elseif ($h >= 1 && $h < 2) {
            $this->yePayAddNum[2]++;
        } elseif ($h >= 2 && $h < 3) {
            $this->yePayAddNum[3]++;
        } elseif ($h >= 3 && $h < 4) {
            $this->yePayAddNum[4]++;
        } elseif ($h >= 4 && $h < 5) {
            $this->yePayAddNum[5]++;
        } elseif ($h >= 5 && $h < 6) {
            $this->yePayAddNum[6]++;
        } elseif ($h >= 6 && $h < 7) {
            $this->yePayAddNum[7]++;
        } elseif ($h >= 7 && $h < 8) {
            $this->yePayAddNum[8]++;
        } elseif ($h >= 8 && $h < 9) {
            $this->yePayAddNum[9]++;
        } elseif ($h >= 9 && $h < 10) {
            $this->yePayAddNum[10]++;
        } elseif ($h >= 10 && $h < 11) {
            $this->yePayAddNum[11]++;
        } elseif ($h >= 11 && $h < 12) {
            $this->yePayAddNum[12]++;
        } elseif ($h >= 12 && $h < 13) {
            $this->yePayAddNum[13]++;
        } elseif ($h >= 13 && $h < 14) {
            $this->yePayAddNum[14]++;
        } elseif ($h >= 14 && $h < 15) {
            $this->yePayAddNum[15]++;
        } elseif ($h >= 15 && $h < 16) {
            $this->yePayAddNum[16]++;
        } elseif ($h >= 16 && $h < 17) {
            $this->yePayAddNum[17]++;
        } elseif ($h >= 17 && $h < 18) {
            $this->yePayAddNum[18]++;
        } elseif ($h >= 18 && $h < 19) {
            $this->yePayAddNum[19]++;
        } elseif ($h >= 19 && $h < 20) {
            $this->yePayAddNum[20]++;
        } elseif ($h >= 20 && $h < 21) {
            $this->yePayAddNum[21]++;
        } elseif ($h >= 21 && $h < 22) {
            $this->yePayAddNum[22]++;
        } elseif ($h >= 22 && $h < 23) {
            $this->yePayAddNum[23]++;
        }

    }




    /******************交易分析区域*****************/

    /**
     * 交易分析
     * @return mixed
     */
    public function dealAnalysis()
    {
        cmf_clear_cache();

        $this->assign('focus_flag', 'deal_anal');
        return $this->fetch('deal_analysis');
    }


    /**
     * 获取交易构成echarts数据
     * @return 老付费用户 新付费用户数量 结算金额 较前一月涨幅 用户数
     */
    public function getDealEcharts()
    {

        $range     = strtotime(input('range')) ?? time();
        $rangeDate = input('range');
        /*交易构成echarts数据*/
        $payUserData = CourseUsersModel::group('uid')->select(); //所有数据

        $oldPayNum = 0; //老付费用户数量 所选时间之前的数量
        $newPayNum = 0; //新付费用户数量 所选时间之后的数量
        $oldPrice  = 0; //结算金额 老付费
        $newPrice  = 0; //结算金额 新付费

        //上一个月 时间
        $preMonthTime = strtotime(input('range') . " -1 month");
        $preOldPayNum = 0; //上月老付费用户数量
        $preNewPayNum = 0; //上月新付费用户数量
        $preOldPrice  = 0; //上月老付费结算金额
        $preNewPrice  = 0; //上月新付费结算金额


        /*交易构成右侧表格*/
        foreach ($payUserData as $k => $v) {
            if ($v['paytime'] < $range) {
                $oldPayNum++;
                $oldPrice += $v['money'];
            } else {
                $newPayNum++;
                $newPrice += $v['money'];
            }

            if ($v['paytime'] < $preMonthTime) {
                $preOldPayNum++;
                $preOldPrice += $v['money'];
            }

            if (($v['paytime'] >= $preMonthTime) && ($v['paytime'] < $range)) {
                $preNewPayNum++;
                $preNewPrice += $v['money'];
            }

        }

        //老结算金额涨幅
        if ($preOldPrice != 0) {
            $oldMoneyInc = round((($oldPrice - $preOldPrice) / $preOldPrice), 2);
        } else {
            $oldMoneyInc = $oldPrice;
        }

        //新结算金额涨幅
        if ($preNewPrice != 0) {
            $newMoneyInc = round((($newPrice - $preNewPrice) / $preNewPrice), 2);
        } else {
            $newMoneyInc = $newPrice;
        }

        //老付费用户数涨幅
        if ($preOldPayNum != 0) {
            $oldPayNumInc = round((($oldPayNum - $preOldPayNum) / $preOldPayNum), 2);
        } else {
            $oldPayNumInc = $oldPayNum;
        }

        //新付费用户数涨幅
        if ($preNewPayNum != 0) {
            $newPayNumInc = round((($newPayNum - $preNewPayNum) / $preNewPayNum), 2);
        } else {
            $newPayNumInc = $newPayNum;
        }

        //             结算金额(元)	较前一月涨幅	  用户数	        较前一月涨幅
        // 老付费用户	$oldPrice	$oldMoneyInc  $oldPayNum	    $oldPayNumInc
        // 新付费用户	$newPrice	$newMoneyInc  $newPayNum	    $newPayNumInc

        /*订单金额分布*/
        $day_end = mktime(0, 0, 0, date('m', $range), date('d', $range) + 1, date('Y', $range)) - 1; //23:59:59
        //0-50 50-100 100-200 200-500 500-1000 1000-1500 大于1500
        $currentMoney = CourseUsersModel::whereTime('paytime', [$range, $day_end])->where('money', '>', 0)->column('money');

        foreach ($currentMoney as $k => $v) {
            if (($v > 0) && ($v < 50)) {
                $this->tradeNoNum[0]++;
            } elseif (($v >= 50) && ($v < 100)) {
                $this->tradeNoNum[1]++;
            } elseif (($v >= 100) && ($v < 200)) {
                $this->tradeNoNum[2]++;
            } elseif (($v >= 200) && ($v < 500)) {
                $this->tradeNoNum[3]++;
            } elseif (($v >= 500) && ($v < 1000)) {
                $this->tradeNoNum[4]++;
            } elseif (($v >= 1000) && ($v < 1500)) {
                $this->tradeNoNum[5]++;
            } else {
                $this->tradeNoNum[6]++;
            }

        }

        $data = [
            //饼
            'pancake'  => [
                'old_price' => $oldPrice,
                'new_price' => $newPrice
            ],
            //表格
            'table'    => [
                'old_price'       => $oldPrice,
                'old_money_inc'   => $oldMoneyInc,
                'old_pay_num'     => $oldPayNum,
                'old_pay_num_inc' => $oldPayNumInc,
                'new_price'       => $newPrice,
                'new_money_inc'   => $newMoneyInc,
                'new_pay_num'     => $newPayNum,
                'new_pay_num_inc' => $newPayNumInc
            ],
            //订单金额分布
            'trade_no' => $this->tradeNoNum
        ];

        echo json_encode($data);

    }


    /*
     * 商品分析区域
     * @return mixed
     */
    public function goodsanalysis()
    {

        //0内容1课程2语音直播3视频直播
        //在售数量
        $onSaleGoods = CourseModel::where('sort', 'neq', 1)->select();
        $onSaleCount = count($onSaleGoods);

        /*内容 type 1图文2视频3音频*/
        $tuWenCount = 0;
        $videoCount = 0;
        $voiceCount = 0;

        $yupptCount   = 0;  //大班课语音PPT
        $yuVideoCount = 0; //大班课语音视频
        $yuVoiceCount = 0; //大班课语音音频
        $liveCount    = 0; //视频直播
        foreach ($onSaleGoods as $k => $v) {
            if ($v['sort'] == 0) {
                //内容
                if ($v['type'] == 1) {
                    $tuWenCount++;
                } elseif ($v['type'] == 2) {
                    $videoCount++;
                } else {
                    $voiceCount++;
                }
            }

            //\r\n语音直播形式，1语音ppt2语音视频3语音音频\r\n视频直播形式，5视频直播
            if ($v['sort'] == 2) {
                //语音直播(大班课)
                if ($v['type'] == 1) {
                    $yupptCount++;
                } elseif ($v['type'] == 2) {
                    $yuVideoCount++;
                } elseif ($v['type'] == 3) {
                    $yuVoiceCount++;
                }
            }

            if ($v['sort'] == 3) {
                //视频直播
                if ($v['type'] == 5) {
                    $liveCount++;
                }
            }

        }

        $this->assign([
            'on_sale_count' => $onSaleCount,
            'tu_wen'        => $tuWenCount,
            'voice_count'   => $voiceCount,
            'video_count'   => $videoCount,
            'yu_ppt'        => $yupptCount,
            'yu_voice'      => $yuVoiceCount,
            'yu_video'      => $yuVideoCount,
            'live'          => $liveCount,
            'focus_flag'    => 'goods_anal'
        ]);


        return $this->fetch('goods_analysis');
    }


    /**
     * 获取商品排行(数据)
     * 商品浏览排行TOP5
     * 排名    名称    浏览用户数    转化率
     * 商品销量排行TOP5
     *  排名    名称    付费用户数    结算金额
     */
    public function getGoodsOrder()
    {
        $range = strtotime(input('range')) ?? time();

        /*商品浏览排行TOP5*/
        $goodOrderData = db('student_log')->field('DISTINCT count( * ) AS view, course_id')
            ->where('course_id', 'neq', 0)
            ->group('course_id')->order('view DESC')->limit(5)->select()->toArray();

        $courseNames   = CourseModel::where('sort', 'neq', 1)->column(['id', 'name']);
        $buyCourseData = CourseUsersModel::where('paytype', 1)->select();

        foreach ($goodOrderData as $k => $v) {
            $courseId = $v['course_id'];

            $goodOrderData[$k]['course_name'] = $courseNames[$courseId] ?? '未知';

            //转化率 (购买用户 / 浏览用户) * 100
            $buyCount = 0;
            foreach ($buyCourseData as $kk => $vv) {
                if ($vv['courseid'] == $courseId) {
                    $buyCount++;
                }
            }

            if ($v['view'] != 0) {
                $goodOrderData[$k]['zhuan_ratio'] = round(($buyCount / $v['view']), 2) * 100;
            } else {
                $goodOrderData[$k]['zhuan_ratio'] = $buyCount;
            }

        }


        /*销量排行TOP5*/
        $courseBuyData = CourseUsersModel::field('DISTINCT count( * ) AS pay_num, sum(money) as moneys, courseid')
            ->where('money', 'neq', 0)
            ->group('courseid')->order('pay_num DESC')->limit(5)->select()->toArray();

        foreach ($courseBuyData as $k => $v) {
            $courseBuyData[$k]['course_name'] = $courseNames[$v['courseid']] ?? '未知';

        }

        $data = [
            'view_order' => $goodOrderData,
            'sale_order' => $courseBuyData
        ];

        echo json_encode($data, JSON_UNESCAPED_UNICODE);


    }


    public function dashboardWidget()
    {
        $dashboardWidgets = [];
        $widgets          = $this->request->param('widgets/a');
        if (!empty($widgets)) {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 1]);
                } else {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 0]);
                }
            }
        }

        cmf_set_option('admin_dashboard_widgets', $dashboardWidgets, true);

        $this->success('更新成功!');

    }


}
