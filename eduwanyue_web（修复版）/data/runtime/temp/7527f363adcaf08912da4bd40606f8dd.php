<?php /*a:8:{s:70:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/portal/index.html";i:1650620118;s:69:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/public/head.html";i:1645779015;s:77:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/head.html";i:1645779015;s:79:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/header.html";i:1650619471;s:78:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/grade.html";i:1645779015;s:78:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/login.html";i:1645779015;s:79:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/footer.html";i:1645779015;s:80:"/www/wwwroot/edudemo.sdwanyue.com/themes/simpleboot3/student/public/scripts.html";i:1645779015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo (isset($site_info['site_name']) && ($site_info['site_name'] !== '')?$site_info['site_name']:''); ?></title>
    
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<!-- Set render engine for 360 browser -->
<meta name="renderer" content="webkit">

<!-- No Baidu Siteapp-->
<meta http-equiv="Cache-Control" content="no-siteapp"/>

<!-- HTML5 shim for IE8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->
<link rel="icon" href="/favicon.png" type="image/png">
<link rel="shortcut icon" href="/favicon.png" type="image/png">
<link href="/static/home/css/common.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
    //全局变量
    var GV = {
        ROOT: "/",
        WEB_ROOT: "/",
        JS_ROOT: "static/js/"
    };
</script>
<script src="/static/js/jquery.js"></script>
<script src="/static/js/wind.js"></script>
	
    <link href="/static/student/css/common.css" rel="stylesheet" type="text/css">
<link href="/static/swiper/swiper.min.css" rel="stylesheet" type="text/css">
<link href="/static/student/css/pick-pcc.min.1.0.1.css" rel="stylesheet" type="text/css">

    <link href="/static/student/css/index.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="common_header_nav">
    <div class="content">
        <div class="left">
            <img src="/static/images/logo.png">

            <text><?php echo $site_info['site_name']; ?></text>
        </div>
        <div class="middle">
            <ul>
                <li>
                    <a href="/" class="olda <?php if($navid == 0): ?>on<?php endif; ?>">
                        首页
                        <div class="none <?php if($navid == 0): ?>block<?php endif; ?>"></div>
                    </a>
                </li>
                <li>
                    <a href="/student/teachers/index" class="olda <?php if($navid == 20): ?>on<?php endif; ?>">
                        师资力量
                        <div class="none <?php if($navid == 20): ?>block<?php endif; ?>"></div>
                    </a>
                </li>				
                <li>
                    <a href="/student/lessionlist/index" class="olda <?php if($navid == 1): ?>on<?php endif; ?>">
                        选课中心
                        <div class="none <?php if($navid == 1): ?>block<?php endif; ?>"></div>
                    </a>
                </li>

                <li>
                    <a href="/student/index/appdownload" class="olda <?php if($navid == 2): ?>on<?php endif; ?>">
                        APP下载
                        <div class="none <?php if($navid == 2): ?>block<?php endif; ?>"></div>
                    </a>
                </li>

            </ul>
        </div>
        <div class="right">
            <div class="search">
                <input type="password" hidden autocomplete=“new-password”>
                <input id="searchval" type="text" value="<?php echo $keywords; ?>" placeholder="课程、讲师" />
                <button id="search">搜索</button>
            </div>

            <div class="login">
                <?php if($isLog == 0): ?>
                    <div class="log_or_reg">登录/注册</div>
                <?php endif; if($isLog == 1): ?>

                    <div class="login_info">
                        <img class="avatar" src="<?php echo $userinfo['avatar']; ?>">

                        <div class="drop_down none">
                            <div class="gard"><a id="gard" href="javacript:void(0);"><?php echo $userinfo['gradename']; ?></a></div>
                            <div class="li1"><a href="/student/mine/index">我的课程</a></div>
                            <div class="li2"><a id="logout" href="javacript:void(0);">退出登录</a></div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>


</div>
<div class="common_garde <?php if($isIp == 0): ?>block<?php endif; ?>">
    <div class="content">
        <div class="tips">
            <div class="text1">请选择你想看的学习阶段</div>
            <div class="text2">随时可以更改，请放心选择</div>
        </div>
        <div class="grade">
            <div class="list">
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $keyid = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($keyid % 2 );++$keyid;?>
                    <div class="left"><?php echo $item['name']; ?></div>
                    <div class="right">
                        <?php if(is_array($item['list']) || $item['list'] instanceof \think\Collection || $item['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item1): $mod = ($i % 2 );++$i;?>
                            <div data-id="<?php echo $item1['id']; ?>" style="cursor:pointer;" class="li <?php if($gradeid == $item1['id'] || ($gradeid == 0 && $keyid == 1 && $key == 0 && $gradeid != $item1['id'])): ?>on<?php endif; ?>"><?php echo $item1['name']; ?></div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
</div>
    <!--登录页面-->
    <div class="common_login none">
        <div class="content">
            <div class="close">
                <img class="imgx" src="/static/student/images/common/login_cha.png">
            </div>
            
            <div class="sitename">登录<?php echo $site_info['site_name']; ?>课堂</div>

            <div class="info">
                <div class="type">
                    
                    <div class="logintype">
                        <a href="javacript:void(0);">
                            <text class="c969696 black">密码登录</text>
                            <div class="heng"></div>
                        </a>
                    </div>
                    <div class="logintype">
                        <a href="javacript:void(0);">
                            <text class="c969696">验证码登录</text>
                            <div class="heng none"></div>
                        </a>
                    </div>
                </div>

                <div class="inputs">
                    <input type="text" class="input1" value="" placeholder="请输入手机号" value="13800000000" />
                    <input type="password" class="input2" value="" placeholder="请输入密码" value="123456" />
                    <div class="forget">忘记密码</div>
                </div>

                <div class="inputs1 none">
                    <input class="input3" value="" placeholder="请输入手机号" />
                    <div>
                        <input class="input4" value="" placeholder="请输入验证码" />
                        <div class="getLoginCode"><a href="javacript:void(0);" class="on">获取验证码</a></div>
                    </div>
                    <div class="forget">忘记密码</div>
                </div>


                <button class="login">登录</button>

                <div class="tips">还没有账号？<text class="goreg"><a href="javacript:void(0);" class="on">立即注册</a></text></div>

                <div class="third">
                    <?php if(in_array('wx',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="wx_img" src="/static/student/images/common/login_wx.png">
                            <div class="text">微信登录</div>
                        </div>
                    <?php endif; if(in_array('QQ',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="qq_img" src="/static/student/images/common/login_qq.png">
                            <div class="text">QQ登录</div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <div class="content none">
            <div class="close">
                <img class="imgfan" src="/static/student/images/common/login_fan.png">
            </div>
            
            <div class="sitename">忘记密码</div>

            <div class="info">

                <div class="inputs3">
                    <input type="text" class="input8" value="" placeholder="请输入手机号" />
                    <div>
                        <input type="text" class="input9" value="" placeholder="请输入验证码" />
                        <div class="getForgetCode"><a href="javacript:void(0);" class="on">获取验证码</a></div>
                    </div>

                    <input type="password" class="input10" value="" placeholder="请设置密码" />
                </div>

                <button class="fortrue">确定</button>


                <div class="third" style="margin-top: 45px;">
                    <?php if(in_array('wx',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="wx_img" src="/static/student/images/common/login_wx.png">
                            <div class="text">微信登录</div>
                        </div>
                    <?php endif; if(in_array('QQ',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="qq_img" src="/static/student/images/common/login_qq.png">
                            <div class="text">QQ登录</div>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
        <div class="content none">
            <div class="close">
                <img class="imgx" src="/static/student/images/common/login_cha.png">
            </div>
            

            <div class="sitename">欢迎注册<?php echo $site_info['site_name']; ?>课堂</div>



            <div class="info">

                <div class="inputs2">
                    <input type="text" class="input5" value="" placeholder="请输入手机号" />
                    <div>
                        <input type="text" class="input6" value="" placeholder="请输入验证码" />
                        <div class="getRegCode"><a href="javacript:void(0);" class="on">获取验证码</a></div>
                    </div>

                    <input type="password" class="input7" value="" placeholder="请设置密码" />
                </div>

                <button class="reg">立即注册</button>

                <div class="tips">已有账号？<text class="gologin"><a href="javacript:void(0);" class="on">马上登陆</a></text></div>

                <div class="third">
                    <?php if(in_array('wx',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="wx_img" src="/static/student/images/common/login_wx.png">
                            <div class="text">微信登录</div>
                        </div>
                    <?php endif; if(in_array('QQ',$configpri['login_type'])): ?>
                        <div class="li">
                            <img class="qq_img" src="/static/student/images/common/login_qq.png">
                            <div class="text">QQ登录</div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<!--banner-->
<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php if(is_array($index_info['silide']) || $index_info['silide'] instanceof \think\Collection || $index_info['silide'] instanceof \think\Paginator): $i = 0; $__LIST__ = $index_info['silide'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <div class="swiper-slide"><a href="<?php echo $item['url']; ?>"><img class="img" src="<?php echo $item['image']; ?>"></a></div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</div>

<div class="body kaiyuan_body">

    <div class="less_top kaiyuan_less_top" <?php if($keywords != ''): ?>style="display:none;"<?php endif; ?>>
        <div class="middle">
            <div class="ul" style="position: relative;top: 20px;margin-top: 0;">
                <div class="left">学段</div>
                <div id="xd" class="right">
                    <?php if(is_array($xdlist) || $xdlist instanceof \think\Collection || $xdlist instanceof \think\Paginator): $i = 0; $__LIST__ = $xdlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <div class="li <?php if($key == 0): ?>active<?php endif; ?>" data-id="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="ul" style="margin-top: 30px;">
                <div class="left">年级</div>
                <div id="nj" class="right">
                    <?php if(is_array($njlist) || $njlist instanceof \think\Collection || $njlist instanceof \think\Paginator): $i = 0; $__LIST__ = $njlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <div class="li <?php if($key == 0): ?>active<?php endif; ?>" data-id="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>

                </div>
            </div>

            <div class="ul">
                <div class="left">类别</div>
                <div id="lb" class="right">
                    <div class="li active" data-id="99">全部</div>
                    <div class="li" data-id="3">直播</div>
                    <div class="li" data-id="4">内容</div>
                </div>
            </div>
        </div>
    </div>

    <div class="zhibo_wrap">
        <div class="less_list less_list_daban kaiyuan_less_list list kaiyuan_info_list" <?php if($keywords != ''): ?>style="margin-top:80px;"<?php endif; ?>>
        <div class="tips">
            <span>直播课堂</span>
            <div style="display: inline-block; width: 92%; text-align: right;">
                <a style="color:#9e9a9a" href="<?php echo url('student/lessionlist/index',['flag' => '3']); ?>">更多></a>
            </div>
        </div>
        <ul>
            <?php if(is_array($lesslist) || $lesslist instanceof \think\Collection || $lesslist instanceof \think\Paginator): $i = 0; $__LIST__ = $lesslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <li>
                    <a href="/student/detail/class?id=<?php echo $item['id']; ?>">
                        <div class="content">
                            <div class="top" style="background: url(<?php echo $item['thumb']; ?>) no-repeat;background-size: cover;">
                                <div class="tip">课程</div>
                            </div>
                            <div class="title"><?php echo $item['name']; ?></div>
                            <div class="information">
                                <text class="text1"><?php echo $item['lesson']; ?></text>
                                <?php if($item['ismaterial'] == 1): ?>
                                    <img src="/static/student/images/index/book.png"><text class="text2">含教材</text>
                                <?php endif; ?>

                            </div>
                            <div class="bottom">
                                <img class="img1" src="<?php echo $item['avatar']; ?>">
                                <text class="name"><?php echo $item['user_nickname']; ?></text>
                                <?php if($item['paytype'] == 0): ?>
                                    <text class="mian">免费</text>
                                <?php endif; if($item['paytype'] == 1): ?>
                                    <text class="money">￥<?php echo $item['payval']; ?></text>
                                <?php endif; if($item['paytype'] == 2): ?>
                                    <text class="mi">密码</text>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>


   </div>


    <div class="less_list_nei kaiyuan_less_list list kaiyuan_info_list" <?php if($keywords != ''): ?>style="margin-top:80px;"<?php endif; ?>>
    <div class="tips">
        <span>精选内容</span>
        <div style="display: inline-block; width: 92%; text-align: right;">
            <a style="color:#9e9a9a" href="<?php echo url('student/lessionlist/index',['flag' => '4']); ?>">更多></a>
        </div>
    </div>
    <ul>
        <?php if(is_array($neirong) || $neirong instanceof \think\Collection || $neirong instanceof \think\Paginator): $i = 0; $__LIST__ = $neirong;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
            <li>
                <a href="/student/detail/class?id=<?php echo $item['id']; ?>">
                    <div class="content">
                        <div class="top" style="background: url(<?php echo $item['thumb']; ?>) no-repeat;background-size: cover;">
                            <div class="tip">课程</div>
                        </div>
                        <div class="title"><?php echo $item['name']; ?></div>
                        <div class="information">
                            <text class="text1"><?php echo $item['lesson']; ?></text>
                            <?php if($item['ismaterial'] == 1): ?>
                                <img src="/static/student/images/index/book.png"><text class="text2">含教材</text>
                            <?php endif; ?>

                        </div>
                        <div class="bottom">
                            <img class="img1" src="<?php echo $item['avatar']; ?>">
                            <text class="name"><?php echo $item['user_nickname']; ?></text>
                            <?php if($item['paytype'] == 0): ?>
                                <text class="mian">免费</text>
                            <?php endif; if($item['paytype'] == 1): ?>
                                <text class="money">￥<?php echo $item['payval']; ?></text>
                            <?php endif; if($item['paytype'] == 2): ?>
                                <text class="mi">密码</text>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>

    </div>

        <!-- 新闻资讯 -->
  

    <div class="less_list_nei kaiyuan_less_list list kaiyuan_info_list" <?php if($keywords != ''): ?>style="margin-top:80px;"<?php endif; ?>>
            <div class="tips">新闻资讯</div>
            <div class="list">
                <?php if(is_array($newsList) || $newsList instanceof \think\Collection || $newsList instanceof \think\Paginator): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
                    <a target="_blank" href="<?php echo url('student/Press/read',['id'=>$value['id']]); ?>">
                        <div class="item">

                            <div class="middle">
                                <div class="name">
                                  <?php echo $value['name']; ?>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="left">
                                    【<?php echo $value['add_time']; ?>】
                                </div>

                            </div>
                        </div>
                    </a>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </div>

            <?php if(!$newsList): ?>
                <div class="empty_data" style="text-align: center;margin: 50px auto;height: 100px;">
                    <img src="/static/student/images/empty_data.png" alt="">
                    <div style="color: #D7D7D7;">
                        暂无内容
                    </div>
                </div>
            <?php endif; ?>
        </div> 

    

    <div class="right-nav">
        <img class="right-img" src="/static/images/qq_qun.png" alt="">
        <p>加入QQ群</p>
        <p>学习更轻松</p>
    </div>

</div>

<div class="common_footer">
    <div class="content">
        <div class="left">
            <img src="/static/images/logo.png">
            <div class="text"><?php echo $site_info['site_name']; ?></div>
        </div>
        <div class="next">
            <div class="text">官方QQ群</div>
                <img src="/static/images/qq_qun.png">
        </div>
        <div class="middle">
            <div class="text">官方微信</div>
            <?php if(isset($site_info['wx_url']) && $site_info['wx_url'] != ''): ?>
                <img src="<?php echo $site_info['wx_url']; ?>">
            <?php endif; ?>

        </div>
        <div class="right">
            <div class="right_guanyu_title">关于我们</div>
            <div class="text1"><a href="<?php echo url('student/index/liaojie', ['flag' => 1]); ?>">了解万岳</a></div>
            <div class="text2"><a href="<?php echo url('student/index/liaojie', ['flag' => 2]); ?>">联系我们</a></div>
            <div class="text3"><a href="<?php echo url('student/index/liaojie', ['flag' => 3]); ?>">如何购买</a></div>
        </div>
    </div>

    <div class="you_url">
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li"><text>友情链接</text></div>
        <div class="li" style="border: 0;"><text>友情链接</text></div>

    </div>
    <div class="copyright">
        版权信息 <?php echo $site_info['copyright']; ?>
    </div>
</div>
<script>
    var __SITEURL__ = "<?php echo $site_info['site_url']; ?>";
    var __SITEINFO__ = <?php echo $site_infoj; ?>;
    var userinfoj = <?php echo $userinfoj; ?>; //用户信息

</script>
<script src="/static/js/layer/layer.js"></script>
<script src="/static/student/js/common.js"></script>
<script src="/static/swiper/swiper.min.js"></script>


<script>
    var isBackLog = '<?php echo $isBackLog; ?>';
    var xdid = "<?php echo $xdid; ?>"; //学段id
    var njid = "<?php echo $njid; ?>"; //年级id
    var kmid = '0'; //科目id
    var lbid = '1'; //类别id
    var page = 1; //页码
    var isMore = 0;
    var pageFlag = 0; //当前页面内没有更多按钮, 此标志置0

</script>
<script src="/static/student/js/index.js"></script>
<script src="/static/student/js/lessionlist.js"></script>
<script type="text/javascript">


    $('.gotop').on('click', function (e) {
        e.preventDefault();
        $("html,body").animate({scrollTop: 0}, 750);
    });
    
    $(".weixin-wrap").on('mouseover', function (e) {
        $(".weixin-img").show();
    }).mouseout(function () {
        $(".weixin-img").hide();
    });


    // 动态绑定
    $('.kaiyuan_info_list ul').delegate('li','mouseover',function(){
        $(this).css("position", "relative");
        $(this).css("top", "-10px");
    }).delegate('li','mouseout',function(){
        $(this).css("position", "");
        $(this).css("top", "");
    });
    
    

</script>

</body>
</html>
