<div align=center><img src="https://qiniugw.sdwanyue.com/github_big_logo.png" width="590" height="212"/> </div>


### 项目说明（如果对你有用，请给个star！）
##### <a target="_blank" href="http://doc.sdwanyue.com/wanyue_open_web/2006714">项目文档</a> |  <a target="_blank" href="http://doc.sdwanyue.com/wanyue_open_web/2006716">部署文档</a>  |  <a target="_blank" href="http://doc.sdwanyue.com/wanyue_open_web/2107615">常见问题</a>

---

### 系统演示
- 首页地址: <a target="_blank" href="https://demo.sdwanyue.com">https://demo.sdwanyue.com</a>
- 学生端地址: <a target="_blank" href="https://demo.sdwanyue.com">https://demo.sdwanyue.com</a> 账号:13800000000 密码:123456
- 教师端地址: <a target="_blank" href="https://demo.sdwanyue.com/teacher">https://demo.sdwanyue.com/teacher</a> 账号:13866666666 密码:123456
- 后台地址: <a target="_blank" href="https://demo.sdwanyue.com/admin">https://demo.sdwanyue.com/admin</a> 账号: demo 密码: 123456

### Uni-app版地址
   - 接口框架地址: 本项目根目录下phalapi目录
   - 仓库地址: <a target="_blank" href="https://gitee.com/WanYueKeJi/wanyue_education_uniapp">点击此处</a>
   ![展示图](https://qiniugw.sdwanyue.com/git_eq.png"展示图.png") 
   
### 项目介绍
万岳在线教育经过对教育市场的长期调研，综合当下各大线上教培机构对于教育平台的功能需求，着重于用户体验，自主研发了一套集知识付费、直播授课、网校建设等功能为一体的万岳在线教育系统，满足用户对于公开课、大班课、小班课、职业培训等多种线上教学活动的场景需求。
* 所有使用到的框架或者组件都是基于开源项目，代码保证100%开源。
* 系统功能通用，无论是个人还是企业都可以利用该系统快速搭建一个属于自己的在线教育平台。

系统采用TP5.1+Jquery+Bootstrap+Mysql核心技术，系统功能介绍[查看](http://doc.sdwanyue.com/wanyue_open_web/2006714)，专业售后技术团队，让您二开无忧。

### 技术亮点
```
    1.后台应用ThinkCMF快速生成现代化表单； 
    2.PHPExcel数据导出,导出表格更加美观,可视；
    3.支持微信/支付宝支付,支付接入更加快捷,简单；
    4.后台多任务窗口化操作界面；
    5.内置强大灵活的权限管理；
    6.内置组合数据,系统配置,管理碎片化数据；
    7.客户端完善的交互效果和动画；
    8.高频数据缓存； 
    9.内置PhalApi接口框架,前后端分离更方便；
    10.WebSocket长连接减少CPU及内存使用及网络堵塞，减少请求响应时长；
    11.支持队列降低流量高峰，解除耦合，高可用;
    12.无需安装, clone下来即可直接使用, 完全100%真开源；
```
### 功能展示
 ![展示图](https://qiniugw.sdwanyue.com/github_big_function_all2.png "展示图.png")
 

### 前台主要功能介绍
#### 讲师PC端
* 登录页面，可通过手机号码及验证码或密码进行登录，支持qq、微信帐号登录
* 首页功能，展示老师头像、名称，我的直播课数量，我的课程数量，常用功能以及常见问题
* 直播课堂，

    1.语音大班课
            语音大班课内，讲师可进入课程根据视频和语音对学生进行讲解
            
    2.视频大班课
            视频大班课讲师可以进行开启摄像头、上传ppt图片课件、屏幕共享操作
* 账号，展示头像、昵称等账号信息，可编辑头像、昵称


#### 学生PC端
* 首页，

    1.搜索 可根据课程、老师名称关键字查询搜索
    
    2.轮播图 后台添加的轮播图展示
    
    3.直播课堂 直播课展示列表, 直播分为三种类型（语音+视频、语音+音频、语音+ppt+摄像头直播）

    4.精选内容 内容展示列表，包含封面、标题、内容类型（视频、音频、图文）

* 精选内容， 

    1.详情页 由上往下依次展示封面图、课程标题，学习人数、主讲老师与辅导老师、课程类型（分为密码、免费、付费三种类型)等
    
    2.查看详情 上部为视频、音频播放区域，视频、音频支持进度条滑动查看，视频可全屏观看，播放区域下方为内容标题、简介、发布时间等信息和详细内容展示（支持图文）

 * 直播课堂，
    
    1.语音大班课 
    
    2.视频大班课 
* 选课中心，
    
    1.选课中心与app内的选择学习阶段一致，可根据学段、年纪、科目、类别来搜索对应课程




### 后台主要功能介绍
* 设置
  
  1.网站信息 网站的相关信息及SEO设置
    
  2.私密设置 配置系统的重要参数（包登录配置、直播配置、云存储设置）
    
  3.幻灯片管理 首页轮播banner图管理配置
  
  4.上传设置 上传信息相关配置
  
  5.文件存储 文件存储信息相关配置
  
* 用户管理
    
  1.管理组 编辑后台管理员身份及权限等相关配置
    
  2.用户列表 平台所有注册用户信息列表，可进行编辑、设置讲师资格、禁用、删除等操作

  3.教师列表 后台将普通用户设置为讲师的用户显示在此处
  
* 插件中心

  1.插件列表 查看编辑应用插件信息

* 内容管理
    
  1.文章管理 网站相关文章配置
  
  2.分类管理 文章分类管理
  
  3.页面管理 联系我们、关于我们等单页内容配置
    
* 订单管理 

  1.展示用户购买课程后展示的会员名称、购买价格、订单号、需要线下邮寄教材的收货信息与支付方式与状态

* 知识管理
  
  1.学级分类 登录页的学习阶段分类，可在后台自行添加
  2.付费内容列表 展示添加的所有需要付费/免费的文章内容，可查看平台所有内容信息。
  
* 大班课列表
  
  1.语音大班课 
  
  2.视频大班课 

 
  
  
  ### 页面展示
  
  ![展示图](https://qiniugw.sdwanyue.com/github_big_function1111.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_function2222.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_function3333.png "展示图.png")
  
  #### 统计页面
  ![展示图](https://qiniugw.sdwanyue.com/github_big_user_anal.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_商品分析.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_deal_anal.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_data_view.png "展示图.png")
  
    
  #### 直播课页面
  ![展示图](https://qiniugw.sdwanyue.com/github_big_live_student.png "展示图.png")
  ![展示图](https://qiniugw.sdwanyue.com/github_big_live_yuyin_student.png "展示图.png")
  
  #### 功能对比
  ![展示图](https://qiniugw.sdwanyue.com/github_big_gongneng_list2.png "展示图.png")
  

### 商业合作
* 如果你想使用功能更完善的教育系统，请联系QQ客服: 2415408120获取专业版
* 如果您想基于教育系统进行定制开发，我们提供有偿定制服务支持！
* 其他合作模式不限，欢迎来撩！
* 官网地址：[http://www.sdwanyue.com](http://www.sdwanyue.com)
                
  
### 联系我们（加客服经理微信或QQ，免费获取sql脚本）

<div style='height: 130px'>
    <img class="kefu_weixin" style="float:left;" src="https://qiniugw.sdwanyue.com/github_big_weixinlianxi.png" width="602" height="123"/>
    <div style="float:left;">
        <p>QQ：2415408120</p>
        <p>QQ群：995910672</p>
    </div>
</div>
<a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=JShAyXeoKqg2lWFEUSElxELImhjeMG4y&jump_from=webapi"><img border="0" src="https://qiniugw.sdwanyue.com/github_big_group_a.png" alt="万岳在线教育讨论群" title="万岳在线教育讨论群"></a> 可加

> QQ群：995910672


![展示图](https://qiniugw.sdwanyue.com/github_big_公众号.png "展示图.png")


