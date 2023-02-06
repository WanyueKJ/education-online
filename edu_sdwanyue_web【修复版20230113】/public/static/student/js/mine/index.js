$(function () {

    //课程切换类型
    $('.my_class_title li').click(function () {
        $(this).addClass('my_active');
        $(this).siblings().removeClass('my_active');

        var index = $(this).index();

        type = index;
        index++;
        $('.my_right_button > .my_content > div:nth-child(' + index + ')').show();
        $('.my_right_button > .my_content > div:nth-child(' + index + ')').siblings().hide();
    });

    //课程点击查看更多
    $('.my_right .kc .packs').on('click','.look_more',function(){

        if(type == 0){
            page0 = page0+1;
            page = page0;
        }else if(type == 1){
            page1 = page1+1;
            page = page1;
        }else if(type == 2){
            page2 = page2+1;
            page = page2;
        }else{
            page3 = page3+1;
            page = page3;
        }
        
        $.ajax({
            url:__SITEURL__+'/api/?s=Course.GetMyCourse',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,type:type,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var list = data.data.info;
                var lesshtml = replaceHtml(list,type);
                $('.packs ul').eq(type).append(lesshtml);
            }
        });


    })

    //直播点击查看更多
    $('.my_right .live .packs').on('click','.look_more',function(){

        page = page+1;
        $.ajax({
            url:__SITEURL__+'/api/?s=Course.GetMyBuy',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,sort:2,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var list = data.data.info;
                var lesshtml = replaceHtml(list,0);
                $('.packs ul').append(lesshtml);
            }
        });


    })

    //内容点击查看更多
    $('.my_right .cont .packs').on('click','.look_more',function(){

        page = page+1;
        $.ajax({
            url:__SITEURL__+'/api/?s=Course.GetMyBuy',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,sort:0,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var list = data.data.info;
                var lesshtml = replaceHtml(list,0);
                $('.packs ul').append(lesshtml);
            }
        });


    })   

    //课程数据替换
    function replaceHtml(data,eq){
        var lesshtml = '';
        for(var i=0;i<data.length;i++){
            var type = '';
            var lesson = '';
            var ahref='';
            if(data[i].sort == 0){ //是内容
                type = '内容'; 
                lesson ='<text class="text2">'+data[i].lesson+'</text>'
                ahref = '<a href="/student/detail/substance?id='+data[i].id+'">';

            }else if(data[i].sort == 1){ //课程
                type = '课程'; 
                lesson = '<text class="text1">'+data[i].lesson+'</text>';
                ahref = '<a href="/student/detail/class?id='+data[i].id+'">';

            }else{ //是直播
                type = '直播'; 
                lesson = '<text class="text1">'+data[i].lesson+'</text>';
                ahref = '<a href="/student/detail/live?id='+data[i].id+'">';
            }

            var payval = '';
            if(data[i].paytype == 0){
                payval = '<text class="mian">免费</text>';
            }else if(data[i].paytype == 1){
                payval = '<text class="money">已付费</text>';
            }else{
                payval = '<text class="mi">密码</text>';
            }

            
            lesshtml+='<li>'+ahref+'<div class="content">\
                                <div class="top" style="background: url('+data[i].thumb+') no-repeat;background-size: cover;">\
                                    <div class="tip">'+type+'</div>\
                                </div>\
                                <div class="title">'+data[i].name+'</div>\
                                <div class="information">'+lesson+'</div>\
                                <div class="bottom">\
                                    <img class="img1" src="'+data[i].avatar+'">\
                                    <span>'+data[i].user_nickname+'</span>'+payval+'</div>\
                            </div>\
                        </a>\
                    </li>';
        }

        $('.my_container .my_right .packs').eq(eq).find('.look_more').remove();
        if(data.length >=20){
            $('.my_container .my_right .packs').eq(eq).append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
        }


        return lesshtml;
    }







    /************我的课件 */
    //点击下载课件
    $('.my_right_button .li .caozuo').click(function(){
        var name=$(this).data('name');
        var url=$(this).data('url');

        var a = document.createElement('a');          // 创建一个a节点插入的document
        var event = new MouseEvent('click')           // 模拟鼠标click点击事件
        a.download = name                  // 设置a节点的download属性值
        a.href = url;                                 // 将图片的src赋值给a节点的href
        a.target = "_blank";
        a.dispatchEvent(event)                        // 触发鼠标点击事件
    })




    /*******我的账户*/
    //点击修改头像
    $('.my_container .usermess_info .chooseimg').click(function(){
        $('#upload').empty();
        var input = '<input type="file" id="ware_file" name="file" accept="image/*"/>';
        $('#upload').html(input);
        var index='ware_file';
        var iptt=document.getElementById(index);
        if(window.addEventListener) { // Mozilla, Netscape, Firefox
            iptt.addEventListener('change',function(){
                uploadImg();
            },false);
        }else{
            iptt.attachEvent('onchange',function(){
                uploadImg();
            });
        }
        $('#'+index).click();
    })


    //点击修改信息
    $('.my_container .save').click(function(){
        var newuser_nickname = $('.my_container .usermess_name .inputname').val();

        var fields = {};
        if(newuser_nickname == user_nickname && avatar==''){
            layer.msg('请选择修改内容');
            return ;
        }

        
        if(newuser_nickname != user_nickname){
            fields.user_nickname = newuser_nickname;
        }

        if(avatar!=''){
            fields.avatar = avatar;
        }

        fields = JSON.stringify(fields);

        $.ajax({
            url:__SITEURL__+'/api/?s=User.UpUserInfo',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,fields:fields},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.data.msg);
                if(data.data.code==0){
                    setTimeout(function(){
                        location.reload();
                    },1500)
                }
            }
        });
    })
    

    
    function uploadImg(){
        var file = $('#upload').find('#ware_file')[0].files[0];

        var formData = new FormData(); 
        formData.append('file', file);
        
        $.ajax({ 
            url: '/student/mine/uploadImg',
            type: 'post', 
            data: formData, 
            dataType:'json',
            cache: false, 
            processData: false, 
            contentType: false, 
            success:function(data){

                if(data.code==0){
                    layer.msg(data.msg);
                    return !1;
                }
                layer.msg(data.msg);
                $('.my_container .usermess_info .chooseimg').attr('src',data.data.url);
                avatar = data.data.avatar;
                // layer.msg(data.msg,{},function(){
                //     $('.my_container .usermess_info .chooseimg').attr('src',data.data.url);
                //     avatar = data.data.avatar;
                // })
            },
            error:function(e){
                layer.msg("网络错误");
            }
        })
    }






    /******我的消息 */
    $('.my_container .message_nav .nav_li').click(function(){

        $('.my_container .message_nav .nav_li').removeClass('active');
        $('.my_container .message_nav .nav_li').find('.heng').addClass('none');
        $('.my_container .message_nav .nav_li').css('color','#969696');

        $(this).addClass('active');
        $(this).find('.heng').removeClass('none');
        $(this).css('color','#000');

        var index = $(this).index();

        $('.my_container .nav_li_list').addClass('none');
        $('.my_container .nav_li_list').eq(index).removeClass('none');



    })





    /******我关注的讲师 */
    //点击关注
    $('.my_container .mess').on('click','.a1_0',function(){
        var _this = $(this);
        var touid = _this.data('touid');
        SetAttentDet(_this,touid);
        
    })


    function SetAttentDet(_this,touid){

        if(userinfoj == ''){
            layer.msg('请先登录');
            return ;
        }

        var uid = userinfoj.id;
        var token = userinfoj.token;
        $.ajax({
            url:__SITEURL__+'/api/?s=User.SetAttent',
            type:'POST',
            data:{uid:uid,touid:touid,token:token},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                layer.msg(data.data.msg);
                if(data.data.code == 0){
                    if(data.data.info[0].isattent == 0){ //取消
                        _this.html('+ 关注');
                        _this.css('color','#38DAA6');
                    }else{ //关注
                        _this.html('已关注');
                        _this.css('color','#969696');
                    }
                }
            }
        });

    }


    //点击查看更多
    $('.my_container .follows').on('click','.look_more',function(){
        page = page+1;

        $.ajax({
            url:__SITEURL__+'/api/?s=User.GetFollow',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                var html = replaceList(data.data.info);

                $('.my_container .mess').append(html);
            }
        });

    })


    function replaceList(data){
        var html = '';
        for(var i=0;i<data.length;i++){

            var isI = '';

            if(data[i]['identitys'] != ''){
                isI = '<div class="iden" style="background:'+data[i]['identitys'][0]['colour']+';">'+data[i]['identitys'][0]['name']+'</div>';
            }

            var isA = '';
            isA = '<a class="a1_0" data-touid="'+data[i].id+'" href="javacript:void(0);">已关注</a>';
            
            html+= '<div class="followsli">\
                        <div class="avatar">\
                            <img class="img1" src="'+data[i].avatar+'">\
                            <img class="img2" src="../../static/student/images/teachers/v.png">\
                        </div>\
                        <div class="name">'+data[i].user_nickname+'</div>\
                        <div class="lskd">'+isI+'</div>\
                        <div class="bottom">'+isA+'<a class="a2" href="/student/teachers/detail?touid='+data[i].id+'">进入主页</a>\
                        </div>\
                    </div>';
        }


        $('.look_more').remove();
        if(data.length >=50){
            $('.my_container .follows').append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
        }


        return html;
    }

    //我的作业点击查看更多
    $('.homework').on('click','.look_more',function(){

        page = page+1;
        if(type == 0){
            var url = __SITEURL__+'/api/?s=Task.GetWait';
        }else if(type == 1){
            var url = __SITEURL__+'/api/?s=Task.GetComplete';
        }else{
        }   var url = __SITEURL__+'/api/?s=Task.GetTimeout';
        
        $.ajax({
            url:url,
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var list = data.data.info;
                var lesshtml = replaceHomeworkHtml(list,type);
                $('.my_homework_content').append(lesshtml);
            }
        });


    })

    function replaceHomeworkHtml(data,type){
        var html = '';
        for(var i=0;i<data.length;i++){
            var vo = data[i];
            var status = '';
            if(type == 0){
                status = '<span class="my_homework_content_li_top_text2">未提交</span>';
            }else if(type == 1){
                if(vo.isreview == 0){
                    status ='<span class="my_homework_content_li_top_text2">待批阅</span>';
                }else{
                    status = '<span class="my_homework_content_li_top_text3">\
                                <div class="my_homework_content_li_top_text3_1">'+vo.score+'</div>\
                                <img class="my_homework_content_li_top_text3_img" src="../../static/student/images/mine/twoheng.png">\
                            </span>';
                }
            }else{
                status = '<span class="my_homework_content_li_top_text2">已超时</span>';
            }
            html+='<div class="my_homework_content_li">\
                    <a href="/student/homeworkinfo?id='+vo.id+'">\
                        <div class="my_homework_content_li_top">\
                            <img class="my_homework_content_li_top_img" src="../../static/student/images/mine/homework.png">\
                            <span class="my_homework_content_li_top_text1 line_one">'+vo.name+'</span>'+status+'</div>\
                        <div class="my_homework_content_li_middle">\
                            <span class="my_homework_content_li_middle_text1">关联课程:</span>\
                            <span class="my_homework_content_li_middle_text2 line_one">'+vo.coursename+'</span>\
                        </div>\
                        <div class="my_homework_content_li_bottom">\
                            <span class="my_homework_content_li_bottom_text1">'+vo.classname+'</span>\
                            <span class="my_homework_content_li_bottom_text2">|</span>\
                            <span class="my_homework_content_li_bottom_text3">提交截止时间:'+vo.end_time+'</span>\
                        </div>\
                    </a>\
                </div>';
        }



        $('.homework .look_more').remove();
        if(data.length >=20){
            $('.homework').append('<div class="look_more">查看更多...</div>');
        }


        return html;
    }

    /**作业页面点击返回 */
    $('.homeworkinfo_top_back_text').click(function(){
        window.history.back(-1); 
    })


    /**做作业的操作**/
    $('.homeworkinfo_content_right_li_content_x').click(function(){ //做选择题
        var _this = $(this);
        var key = _this.data('key');
        var type = _this.data('type');
        var answer = _this.data('answer');


        if(_this.hasClass('homeworkinfo_content_right_li_content_x_s')){ //证明选中了，要取消选中
            chooseAnswer(key,type,answer,0,_this);
            
        }else{
            chooseAnswer(key,type,answer,1,_this);

        }


    })

    //选择题的一些选中、取消选中操作
    function chooseAnswer(key,type,answer,status,_this){

        if(status==0){ //取消选中

            if(type == 1){ //单选
                answerlist[key].rs='';
            }else{ //多选

                var oldanswer = answerlist[key].rs;

                var arr = oldanswer.split(',');
                var str = '';
                for(var i=0;i<arr.length;i++){
                    if(arr[i]!=answer){
                        str+=arr[i]+',';
                    }
                }
                var str = str.substr(0,(str.length)-1);

                answerlist[key].rs = str;
            }

            
        }else{

            if(type == 1){ //单选
                answerlist[key].rs=''+answer;
                _this.siblings().removeClass('homeworkinfo_content_right_li_content_x_s');
                _this.addClass('homeworkinfo_content_right_li_content_x_s');
            }else{ //多选
                if(answerlist[key].rs==''){
                    answerlist[key].rs=answerlist[key].rs+answer;
                }else{
                    answerlist[key].rs=answerlist[key].rs+','+answer;
                }
                _this.addClass('homeworkinfo_content_right_li_content_x_s');
            }
        }

    }

    $('.homeworkinfo_content_right_li_content_p').click(function(){ //做判断题
        var _this = $(this);
        var key = _this.data('key');
        var answer = _this.data('answer');
        answerlist[key].rs = ''+answer;

        if(_this.hasClass('homeworkinfo_content_right_li_content_p_s')){ //证明选中了，要取消选中
            _this.removeClass('homeworkinfo_content_right_li_content_p_s');
        
        }else{
            _this.siblings().removeClass('homeworkinfo_content_right_li_content_p_s');
            _this.addClass('homeworkinfo_content_right_li_content_p_s');

        }
    })

    $('.homeworkinfo_content_right_li_content_t_i').bind('input propertychange',function(e){ //做填空题

        var _this = $(this);
        var k = _this.parent().data('k');
        var key = _this.parent().data('key');

        if(answerlist[k].rs == ''){
            answerlist[k].rs = [];

            answerlist[k].rs[key] = _this.val();
        }else{
            answerlist[k].rs[key] = _this.val();
        }
    })

    $('.homeworkinfo_content_right_li_content_j_i').bind('input propertychange',function(e){ //简答题

        var _this = $(this);
        var k = _this.parent().data('k');

        answerlist[k].rs = _this.val();

        if(answerlist_over){
            answerlist_over[k].rs = _this.val();
        }

    })


    $('.homeworkinfo_content_right_li_content_j_p').click(function(){ //简答题上传图片

        if(status == 1 && isUpdate == false){
            return ;
        }
        

        var _this = $(this);
        var k = _this.parent().data('k');


        $('#photo_'+k).empty();
        var input = '<input type="file" id="photo_file_'+k+'" name="file" accept="image/*"/>';
        $('#photo_'+k).html(input);
        var index='photo_file_'+k;
        var iptt=document.getElementById(index);
        if(window.addEventListener) { // Mozilla, Netscape, Firefox
            iptt.addEventListener('change',function(){
                uploadPhoto(k,_this);
            },false);
        }else{
            iptt.attachEvent('onchange',function(){
                uploadPhoto(k,_this);
            });
        }
        $('#'+index).click();
    })

    function uploadPhoto(k,_this){
        var file = $('#photo_'+k).find('#photo_file_'+k)[0].files[0];

        var formData = new FormData(); 
        formData.append('file', file);
        
        $.ajax({ 
            url: '/student/mine/uploadImg',
            type: 'post', 
            data: formData, 
            dataType:'json',
            cache: false, 
            processData: false, 
            contentType: false, 
            success:function(data){

                if(data.code==0){
                    layer.msg(data.msg);
                    return !1;
                }

                _this.attr('src',data.data.url);
                _this.parent().children('.homeworkinfo_content_right_li_content_p_del').removeClass('none');
                answerlist[k].img = data.data.url;
                if(answerlist_over){
                    answerlist_over[k].img = data.data.url;
                }

            },
            error:function(e){
                layer.msg("网络错误");
            }
        })
    }

    $('.homeworkinfo_content_right_li_content_p_del').click(function(){ //简答题删除图片
        var _this = $(this);
        var k = _this.parent().data('k');

        _this.addClass('none');

        _this.parent().children('.homeworkinfo_content_right_li_content_j_p').attr('src','../../static/student/images/mine/addphoto.png')
        answerlist[k].img = '';
        if(answerlist_over){
            answerlist_over[k].img = '';
        }

    })

    $('.homeworkinfo_content_right_li_content_j_y').click(function(){ //简答题点击录音
        var _this=$(this);

        if(!recorder){
            layer.msg('当前设备或浏览器不支持录音,请更换设备或浏览器后重试');
            return !1;
        }

        if(isAudio!=false){
            layer.msg('同时只能录制一个');
            return !1;
        }

        isAudio = true;
        _this.hide();

        var audio = document.querySelectorAll('audio');
        for(var i = 0; i < audio.length; i++){
            if(!audio[i].paused){
                audio[i].pause();
            }
        }

        _this.parent().children('.homeworkinfo_content_right_li_audio_ing').show();

        

        recorder.start();
        createRecorderTime(_this);

    })

    function createRecorderTime(_this){ //录音定时
        if(recorder_time){
            clearInterval(recorder_time);
            recorder_time=null;
        }
        var time=0;
        recorder_time=setInterval(function(){
            time++;
            
            if(time>= 60){
                _this.parent().find('.homeworkinfo_content_right_li_audio_ok').click();
                return !1;
            }
            var time_data=handelTime(time);
            _this.parent().find('.homeworkinfo_content_right_li_js_audio_time span').html(time_data.s);
            audio_time=time;
        },1000);
    }

    /* 处理时长 */
    function handelTime(time){
        var s=time%60;
        var i_t=Math.floor(time/60);
        var i=i_t%60;
        var h_t=Math.floor(i_t/60);
        var h=h_t%24;
        var d=Math.floor(h_t/24);
        
        if(s<10){
            s='0'+s;
        }
        if(i<10){
            i='0'+i;
        }
        if(h<10){
            h='0'+h;
        }
        var data={
            d:d,
            h:h,
            i:i,
            s:s
        };
        return data;
    }
    /* 语音录制-取消 */
    $('.audio_cancel').click(function(){
        var _this=$(this);
        layer.confirm('确定要取消这条录音么',{},function(index){
            layer.close(index);
            isAudio = false;
            _this.parent().parent().hide();
            _this.parent().parent().parent().find('.homeworkinfo_content_right_li_content_j_y').show();
            _this.parent().find('.homeworkinfo_content_right_li_js_audio_time span').html('00');
            clearRecorderTime();
        })
        
    })

    function clearRecorderTime(){
        if(recorder_time){
            clearInterval(recorder_time);
            recorder_time=null;
        }
        audio_time = null;
    }
    /* 语音录制-完成 */
    $('.homeworkinfo_content_right_li_audio_ok').click(function(){
        var _this=$(this);
        var k = _this.parent().parent().parent().data('k');

        isAudio = false;
        _this.parent().parent().hide();
        recorder.stop();

        recorder.getBlob(function(blob){
            var formData = new FormData(); 
                formData.append('file', blob);
            $.ajax({ 
                type: 'post', 
                url: '/student/mine/addAudio', //上传文件的请求路径必须是绝对路劲
                data: formData, 
                dataType:'json',
                cache: false, 
                processData: false, 
                contentType: false, 
                success:function(data){
                    if(data.code==0){
                        layer.msg(data.msg);
                        _this.parent().parent().parent().find('.homeworkinfo_content_right_li_content_j_y').show();
                        return !1;
                    }

                    _this.parent().parent().parent().find('#myAudio_'+k).removeClass('hide');
                    _this.parent().parent().parent().find('#myAudio_'+k).attr('src',data.data);
                    _this.parent().parent().parent().find('.homeworkinfo_content_right_li_content_a_del').removeClass('none');
                    _this.parent().find('.homeworkinfo_content_right_li_js_audio_time span').html('00');
                    answerlist[k].audio = data.data;
                    answerlist[k].audio_time = audio_time;

                    if(answerlist_over){
                        answerlist_over[k].audio = data.data;
                        answerlist_over[k].audio_time = audio_time;
                    }

                    clearRecorderTime();
                },
                error:function(e){

                }
            })
            
        },function(msg){ 
            
        });
    })

    $('.homeworkinfo_content_right_li_content_a_del').click(function(){ //简答题删除录音
        var _this = $(this);
        var k = _this.parent().data('k');

        _this.addClass('none');

        _this.parent().find('.homeworkinfo_content_right_li_audio').addClass('hide');
        _this.parent().find('.homeworkinfo_content_right_li_content_j_y').show();
        _this.parent().find('.homeworkinfo_content_right_li_js_audio_time span').html('00');
        answerlist[k].audio = '';
        answerlist[k].audio_time = '';


        if(answerlist_over){
            answerlist_over[k].audio = '';
            answerlist_over[k].audio_time = '';
        }


    })

    //点击提交作业
    $('.homeworkinfo_content_right_submit').click(function(){
        var _this = $(this);
        var result = JSON.stringify(answerlist);

        $.ajax({
            url:__SITEURL__+'/api/?s=Task.SetTask',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,taskid:taskid,result:result},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.data.msg);
                if(data.data.code==0){
                    location.reload();
                }
            }
        });
    })



    //点击订正
    $('.homeworkinfo_content_right_over_update').click(function(){

        isUpdate = true;
        $('.homeworkinfo_content_right_li_content_p_del').removeClass('none');
        $('.homeworkinfo_content_right_li_content_a_del').removeClass('none');

        $('.homeworkinfo_content_right_over_update').addClass('none');
        $('.homeworkinfo_content_right_over_submit').removeClass('none');

        $('.homeworkinfo_content_right_li_content_j_i').attr('disabled',false)
    })


    //点击订正提交
    $('.homeworkinfo_content_right_over_submit').click(function(){

        var result = JSON.stringify(answerlist_over);

        $.ajax({
            url:__SITEURL__+'/api/?s=Task.UpTask',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,taskid:taskid,result:result},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.data.msg);
                if(data.data.code==0){
                    location.reload();
                }
            }
        });
    })






    //错题本点击开始录入
    $('.my_wrongbook_lu').click(function(){
        $('.my_wrongbook_add').removeClass('none')
    })

    //错题本点击取消录入
    $('.my_wrongbook_add_content_bottom_quxaio').click(function(){
        window.history.back(-1)
    })

    //点击添加错题图片
    $('.my_wrongbook_add_content_add').click(function(){
        if(content != ''){
            return ;
        }
        
        var _this = $(this);
        $('#wrong_question').empty();
        var input = '<input type="file" id="wrong_question_file" name="file" accept="image/*"/>';
        $('#wrong_question').html(input);
        var index='wrong_question_file';
        var iptt=document.getElementById(index);
        if(window.addEventListener) { // Mozilla, Netscape, Firefox
            iptt.addEventListener('change',function(){
                var file = $('#wrong_question').find('#wrong_question_file')[0].files[0];
                upload(file).then(res=>{
                    _this.attr('src',res);
                    content = res;
                },err=>{
                    layer.msg(err)
                });
            },false);
        }else{
            iptt.attachEvent('onchange',function(){
                var file = $('#wrong_question').find('#wrong_question_file')[0].files[0];
                upload(file).then(res=>{
                    _this.attr('src',res);
                    content = res;
                },err=>{
                    layer.msg(err)
                });
            });
        }
        $('#'+index).click();
    })

    //点击添加错题答案图片
    $('.my_wrongbook_add_content_answer').on('click','.my_wrongbook_add_content_add_question',function(){
        var _this = $(this);
        var k = _this.parent().index();
        if(answer[k]){
            return ;
        }

        var _this = $(this);
        $('#wrong_answer'+k).empty();
        var input = '<input type="file" id="wrong_question_file'+k+'" name="file" accept="image/*"/>';
        $('#wrong_answer'+k).html(input);
        var index='wrong_question_file'+k;
        var iptt=document.getElementById(index);
        if(window.addEventListener) { // Mozilla, Netscape, Firefox
            iptt.addEventListener('change',function(){
                var file = $('#wrong_answer'+k).find('#wrong_question_file'+k)[0].files[0];
                upload(file).then(res=>{
                    _this.parent().find('.my_wrongbook_add_content_add_question_del').removeClass('none');
                    _this.attr('src',res);
                    answer[k] = res;

                    
                    if(k<2){
                        var html = '<div class="my_wrongbook_add_content_answer_li">\
                                    <div id="wrong_answer'+(k+1)+'" style="display: none;"></div>\
                                    <img class="my_wrongbook_add_content_add_question_del" src="../../static/student/images/mine/del.png">\
                                    <img class="my_wrongbook_add_content_add_question" src="../../static/student/images/mine/wrong_next_add.png">\
                                </div>';
                        $('.my_wrongbook_add_content_answer').append(html);
                    }

                },err=>{
                    layer.msg(err)
                });
            },false);
        }else{
            iptt.attachEvent('onchange',function(){
                var file = $('#wrong_question').find('#wrong_question_file')[0].files[0];
                upload(file).then(res=>{
                    _this.parent().find('.my_wrongbook_add_content_add_question_del').removeClass('none');
                    _this.attr('src',res);
                    answer[k] = res;
                    
                    if(k<2){
                        var html = '<div class="my_wrongbook_add_content_answer_li">\
                                    <div id="wrong_answer'+(k+1)+'" style="display: none;"></div>\
                                    <img class="my_wrongbook_add_content_add_question_del" src="../../static/student/images/mine/del.png">\
                                    <img class="my_wrongbook_add_content_add_question" src="../../static/student/images/mine/wrong_next_add.png">\
                                </div>';
                        $('.my_wrongbook_add_content_answer').append(html);
                    }
                },err=>{
                    layer.msg(err)
                });
            });
        }
        $('#'+index).click();
    })

    //点击删除错题答案图片
    $('.my_wrongbook_add_content_answer').on('click','.my_wrongbook_add_content_add_question_del',function(){
        var _this = $(this);
        var k = _this.parent().index();
        answer.splice(k,1);
        var z = 0;
        for (var j in answer) {
            answer[z] = answer[j];
            z++;
        }
        if(k!=0){
            _this.parent().remove();
        }else{
            _this.addClass('none');
            _this.parent().find('.my_wrongbook_add_content_add_question').attr('src','../../static/student/images/mine/wrong_question.png');
        }
    })

    //错题备注
    $('.my_wrongbook_add_content_textarea').bind('input propertychange',function(e){

        var _this = $(this);
        des = _this.val()
    })
    //点击返回
    $('.my_wrongbook_back').click(function(){
        window.history.back(-1)
    })
    //点击提交错题
    $('.my_wrongbook_add_content_bottom_sure').click(function(){


        if(content=='' || answer==''){
            layer.msg('请上传错题');
            return ;
        }

        answer = JSON.stringify(answer);
        $.ajax({
            url:__SITEURL__+'/api/?s=Wrongbook.Add',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,content:content,answer:answer,des:des},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                layer.msg(data.data.msg);
                if(data.data.code==0){
                    setTimeout(function(){
                        location.reload();
                    },1500)

                }
            }
        });

    })
    function upload(file){

        var formData = new FormData(); 
        formData.append('file', file);
        
        return new Promise((resolve, reject) => {
            $.ajax({ 
                url: '/student/mine/uploadImg',
                type: 'post', 
                data: formData, 
                dataType:'json',
                cache: false, 
                processData: false, 
                contentType: false, 
                success:function(data){
    
                    if(data.code==0){
                        return reject(data.msg);
                    }
                    return resolve(data.data.url);
    
                },
                error:function(e){
                    layer.msg("网络错误");
                }
            })
        })

    }

    //我的错题击查看更多
    $('.wrongbook').on('click','.look_more',function(){

        page = page+1;

        $.ajax({
            url:__SITEURL__+'/api/?s=Wrongbook.GetList',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,status:type,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var list = data.data.info;
                var lesshtml = replaceWrongbookHtml(list);
                $('.my_wrongbook_content').append(lesshtml);
            }
        });


    })

    function replaceWrongbookHtml(data){
        var html = '';
        for(var i=0;i<data.length;i++){
            var vo = data[i];
            var type = '';
            if(vo.type == 0){
                type = '<img class="my_wrongbook_content_li_top_img" src="'+vo.content+'">';
            }else{
                type = '<span class="my_wrongbook_content_li_top_title">（'+type_listj[vo.content.type]+'）<span class="black">'+vo.content.title+'</span></span>';
            }

            var status = '';
            if(vo.status == 0){
                status = '<span class="my_wrongbook_content_li_bottom_status">未掌握</span>';
            }else{
                status = '<span class="my_wrongbook_content_li_bottom_status1">已掌握</span>';
            }

            html+= '<div class="my_wrongbook_content_li">\
                        <a href="/student/mine/wrongbookinfo?id='+vo.id+'">\
                            <div class="my_wrongbook_content_li_top">'+type+'</div>\
                            <div class="my_wrongbook_content_li_bottom">\
                                <span class="my_wrongbook_content_li_bottom_date">'+vo.addtime+'</span>'+status+'</div>\
                        </a>\
                    </div>';
        }



        $('.wrongbook .look_more').remove();
        if(data.length >=20){
            $('.wrongbook').append('<div class="look_more">查看更多...</div>');
        }
        return html;
    }

    //点击查看详情
    $('.my_wrongbook_info_look').click(function(){
        var type = $(this).data('type');
        $(this).addClass('none');
        $('.my_wrongbook_info_look_'+type).removeClass('none');
    })

    //标记为掌握
    $('.my_wrongbook_info_sure').click(function(){
        $.ajax({
            url:__SITEURL__+'/api/?s=Wrongbook.SetGrasp',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,id:id},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                layer.msg(data.data.msg);
                if(data.data.code==0){
                    setTimeout(function(){
                        location.reload();
                    },1500)

                }
            }
        });
    })

    //删除错题
    $('.my_wrongbook_info_del').click(function(){
        $.ajax({
            url:__SITEURL__+'/api/?s=Wrongbook.Del',
            type:'POST',
            data:{uid:userinfoj.id,token:userinfoj.token,id:id},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                layer.msg(data.data.msg);
                if(data.data.code==0){
                   window.history.back(-1);

                }
            }
        });
    })
});