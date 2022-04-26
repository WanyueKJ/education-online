<?php /*a:2:{s:76:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/admin/main/index.html";i:1645779015;s:73:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/public/header.html";i:1645779015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/icon.css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/",
            WEB_ROOT: "/",
            JS_ROOT: "static/js/",
            APP: '<?php echo app('request')->module(); ?>'/*当前应用名*/
        };
    </script>
    <script src="/themes/admin_htcyltd/public/assets/js/jquery-1.10.2.min.js"></script>
    <script src="/themes/admin_htcyltd/public/assets/js/bootstrap.min.js"></script>
    <script src="/static/js/jquery.validate/jquery.validate.js"></script>
    <script src="/static/js/ajaxForm.js"></script>
    <script src="/themes/admin_htcyltd/public/layuiadmin/layui/layui.js"></script>
    <script src="/themes/admin_htcyltd/public/assets/js/animation.js"></script>
    
    <?php if(APP_DEBUG): ?>
        <style>
            #think_page_trace_open {
                z-index: 9999;
            }
        </style>
    <?php endif; ?>
<link rel="stylesheet" href="/static/css/iconfont.css">
<link rel="stylesheet" href="/static/css/main.css">
<link rel="stylesheet" href="/themes/admin_htcyltd/public/assets/css/animation.css">

</head>

<body>
<!--<div class="wrap">-->
    <div class="statistics basic">
        <div class="bd">
            <div class="bd_title">
                <div class="dropdown">
                    <div class="dropdown_list">
                        <ul class="layui-nav" id="tabHeader">
                            <li data-type="1" class="layui-nav-item">
                                <a href="<?php echo url('admin/main/dataview'); ?>">数据概况</a>
                            </li>
                            <li data-type="2" class="layui-nav-item">
                                <a href="<?php echo url('admin/main/dealanalysis'); ?>">交易分析</a>
                            </li>
                            <li data-type="3" class="layui-nav-item">
                                <a href="<?php echo url('admin/main/goodsanalysis'); ?>">商品分析</a>
                            </li>

                            <li data-type="4" class="layui-nav-item <?php if($focus_flag == 'index'): ?>focus_li<?php endif; ?>" >
                                <a href="<?php echo url('admin/main/index'); ?>">用户分析</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="data_select">
                    <h4>用户概况及趋势</h4>
                    <div id="search-date">
                        <span class="iconfont icon-riqi"></span>
                        <input id="range-input" type="text" name="range"
                               value="<?php echo date('Y-m-d',time()); ?>">
                    </div>
                </div>
            </div>

            <!-- 统计框区域 -->
            <div class="basic_list clear">
                <ul>
                    <li class="active on" id="linkage_1" data-type="1">
                        <div class="basic_list_t">新增用户 <span class="iconfont icon-wenhao" id="icon-wenhao_11"></span></div>
                        <div class="basic_list_n"><span class="new-reg">0</span></div>
                        <div class="basic_list_c">
                            较前一天
                            <span>
                                <i class="new-ratio-icon iconfont"></i>
                                <span class="new-ratio">0</span>
                                <span>%</span>
                            </span>
                        </div>
                    </li>

                    <li class="active" data-type="2">
                        <div class="basic_list_t">活跃用户 <span class="iconfont icon-wenhao" id="icon-wenhao_12"></span></div>
                        <div class="basic_list_n"><span class="new-active">0</span></div>
                        <div class="basic_list_c">较前一天
                            <span>
                                <i class="new-ratio-icon-active iconfont"></i>
                                <span class="new-active-ratio">0</span>
                                <span>%</span>
                            </span>
                        </div>
                    </li>

                    <li class="active" data-type="4">
                        <div class="basic_list_t">累计用户 <span class="iconfont icon-wenhao" id="icon-wenhao_13"></span></div>
                        <div class="basic_list_n"><span class="new-all">0</span></div>
                        <div class="basic_list_c">较前一天
                            <span>
                                <i class="new-ratio-icon-all iconfont"></i>
                                <span class="new-all-ratio">0</span>
                                <span>%</span>
                            </span>
                        </div>
                    </li>

                    <li class="active" data-type="3">
                        <div class="basic_list_t">付费用户 <span class="iconfont icon-wenhao" id="icon-wenhao_14"></span></div>
                        <div class="basic_list_n"><span class="new-pay">0</span></div>
                        <div class="basic_list_c">较前一天
                            <span>
                                <i class="new-ratio-icon-pay iconfont"></i>
                                <span class="new-pay-ratio">0</span>
                                <span>%</span>
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="basic_list_t">累计付费用户 <span class="iconfont icon-wenhao" id="icon-wenhao_15"></span></div>
                        <div class="basic_list_n"><span class="new-pay-all">0</span></div>
                        <div class="basic_list_c">较前一天
                            <span>
                                <i class="new-ratio-icon-pay-all iconfont"></i>
                                <span class="new-pay-all-ratio">0</span>
                                <span>%</span>
                            </span>
                        </div>
                    </li>

                </ul>
            </div>

            <div id="echarts_basic" style="min-width:1000px;height:300px;"></div>

            <!--付费用户区域-->
            <div class="pay-user-wrap">
                <div class="data_select">
                    <h4>付费用户</h4>
                    <div id="search-pay-date">
                        <span class="iconfont icon-riqi"></span>
                        <input id="range-pay-input" type="text" name="range"
                               value="<?php echo date('Y-m-d',time()); ?>">
                    </div>
                </div>

                <!--用户表-->
                <table class="layui-table table table-hover table-bordered pay-users">
                    <thead>
                    <tr>
                        <th align="center">客户类型</th>
                        <th align="center">用户数 <span class="iconfont icon-wenhao" id="icon-wenhao_1"></span></th>
                        <th align="center">用户数占比 <span class="iconfont icon-wenhao" id="icon-wenhao_2"></span></th>
                        <th align="center">客单价 <span class="iconfont icon-wenhao" id="icon-wenhao_3"></span></th>
                        <th align="center">结算金额(元) <span class="iconfont icon-wenhao" id="icon-wenhao_4"></span></th>
                        <th align="center">结算转化率 <span class="iconfont icon-wenhao" id="icon-wenhao_5"></span></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>全部成交用户</td>
                            <td class="user-num-all">0</td>
                            <td><span class="ratio-all">0</span>%</td>
                            <td class="unit-price-all">0</td>
                            <td class="settlement-all">0.00</td>
                            <td><span class="settlement-rate-all">0</span>%</td>
                        </tr>
                        <tr>
                            <td>新付费用户</td>
                            <td class="user-num-new">0</td>
                            <td><span class="ratio-new">0</span>%</td>
                            <td class="unit-price-new">0</td>
                            <td class="settlement-new">0.00</td>
                            <td><span class="settlement-rate-new">0</span>%</td>
                        </tr>
                        <tr>
                            <td>老付费用户</td>
                            <td class="user-num-old">0</td>
                            <td><span class="ratio-old">0</span>%</td>
                            <td class="unit-price-old">0</td>
                            <td class="settlement-old">0.00</td>
                            <td><span class="settlement-rate-old">0</span>%</td>
                        </tr>

                    </tbody>
                </table>

            </div>

        </div>
    </div>
<!--</div>-->
<script src="/static/js/admin.js"></script>
<script src="/static/js/echarts/echarts.min.js"></script>
<?php 
    $lang_set=defined('LANG_SET')?LANG_SET:'';
    $thinkcmf_version=cmf_version();
 
    \think\facade\Hook::listen('admin_before_body_end',null,false);
 ?>

<script src="/static/js/admin_index.js"></script>

<script type="text/javascript">
    $(function(){
        $('#icon-wenhao_11').mouseover(function(){
            var that = this;
            layer.tips('筛选时间内，与前一天对比新增的学生和老师', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_12').mouseover(function(){
            var that = this;
            layer.tips('筛选时间内， web端访问过系统课程的学生', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_13').mouseover(function(){
            var that = this;
            layer.tips('筛选时间内， 访问过系统的老师和学生', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_14').mouseover(function(){
            var that = this;
            layer.tips('筛选时间内， 所有的付费学生', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_15').mouseover(function(){
            var that = this;
            layer.tips('筛选时间内， 所有的付费学生', that,{tips: 1,time:100*1000});
        })

        $('#icon-wenhao_1').mouseover(function(){
            var that = this;
            layer.tips('新付费用户-用户数：筛选时间之后学生付费用户<br>老付费用户-用户数：筛选时间之前的学生付费用户', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_2').mouseover(function(){
            var that = this;
            layer.tips('新付费用户-用户数占比：新付费用户数 / 全部用户数(老师和学生) <br>老付费用户-用户数占比：筛选时间内，老付费用户数 / 全部用户数(老师和学生)', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_3').mouseover(function(){
            var that = this;
            layer.tips('全部付费用户-客单价：总学生付费金额/总学生数 <br>新付费用户-客单价：新学生结算金额/老学生数 <br> 老付费用户-客单价：老付费学生结算金额/老学生数', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_4').mouseover(function(){
            var that = this;
            layer.tips('全部付费用户-结算金额(元)：全部学生的结算金额之和 <br> 新付费用户-结算金额(元)：新学生的结算金额之和 <br> 老付费用户-结算金额(元)：老学生结算金额之和', that,{tips: 1,time:100*1000});
        })
        $('#icon-wenhao_5').mouseover(function(){
            var that = this;
            layer.tips('全部付费用户-结算转化率：有付费订单即为100% 否则为0 <br> 新付费用户-结算转化率：有付费订单即为100% 否则为0 <br> 老付费用户-结算转化率：有付费订单即为100% 否则为0', that,{tips: 1,time:100*1000});
        })

        $('.icon-wenhao').mouseout(function(){
            var that = this;
            layer.close(layer.index)
        })
    })
</script>


</body>
</html>






