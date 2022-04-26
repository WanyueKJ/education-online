    var toid=0;
    var touid=0;
    var toname='';
    var tocontent='';
    var totype=0;
    var recorder=null;
    var recorder_time=null;
    var audio_time=null;
    var isLocal=false;

    
    function handelRes(data){
        if(data.url!=''){
            layer.msg(data.msg,{},function(){
                location.href=data.url;
            });
        }else{
            layer.msg(data.msg);
        }
    }
    function nodejsInit(){
        $.ajax({
            url:'/student/liveing/setNodeInfo',
            type:'POST',
            data:{user_type:_DATA.roominfo.user_type},
            dataType:'json',
            success:function(data){
                if(data.code==0){
                    handelRes(data);
                    return !1;
                }
                
                var socket_data={uid:_DATA.userinfo.id,token:data.data.token,stream:stream,roomnum:_DATA.teacherinfo.id};
                Socket.nodejsInit(socket_data);
            },
            error:function(e){
                layer.msg('信息错误');
            }
        })
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
    
    /* 直播倒计时 */
    function liveCountdown(){
        var time_t=_DATA.roominfo.times;
        timer=window.setInterval(function() {
            if (time_t > 0) {
                $('.liveing_c_c_t_time').show();
                var data_time=handelTime(time_t);
                var html='';
                if(data_time.d!=0){
                    html+=data_time.d+'天';
                }

                html+=data_time.h+'时';

                html+=data_time.i+'分';
                
                html+=data_time.s+'秒';
                $('.js_time').html(html);
                time_t--;
            } else {
                window.clearInterval(timer);
                timer = null;
                $('.live_time').html('未开播');
            }
        },1e3);
    }
    
    /*判断浏览器版本**/
    var checkBrowser={
        myBrowser:function(){
            var isPlay=true;
            var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
            if (userAgent.indexOf("Chrome") > -1){ //谷歌浏览器
                var arr = navigator.userAgent.split(' '); 
                var chromeVersion = '';
                for(var i=0;i < arr.length;i++){
                    if(/chrome/i.test(arr[i]))
                    chromeVersion = arr[i]
                }
                if(chromeVersion){
                    if(Number(chromeVersion.split('/')[1].split('.')[0])>70){
                        isPlay=false;
                    }
                }
            }
            if (userAgent.indexOf("Safari") > -1) {
                isPlay=false;
            }
            return isPlay;
        },
        isChrome:function(){
            var isPlay=0;
            var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
            if (userAgent.indexOf("Chrome") > -1){ //谷歌浏览器
                var arr = navigator.userAgent.split(' '); 
                var chromeVersion = '';
                for(var i=0;i < arr.length;i++){
                    if(/chrome/i.test(arr[i]))
                    chromeVersion = arr[i]
                }
                if(chromeVersion){
                    if(Number(chromeVersion.split('/')[1].split('.')[0])>72){
                        isPlay=1;
                    }else{
                        isPlay=-1;
                    }
                }
            }
            
            return isPlay;
        }
    }
    var chat={
        isPutQuestions:0,//是否提问
        js_send:$('#js_send'),
        js_msg_explain:$('#js_msg_explain'),
        js_msg_talk:$('#js_msg_talk'),
        js_msg_qa:$('#js_msg_qa'),
        js_audio:$('.liveing_audio'),
        stopspeak:$('#stopspeak'),
        putQuestions:$('#putQuestions'),
        tab:$('.liveing_c_r_tab'),
        lastid_0:0,
        lastid_1:0,
        lastid_2:0,
        isscroll_0:1,
        isscroll_1:1,
        isscroll_2:1,
        init:function(){
            var _this=this;
            _this.getMsg(0);
            _this.getMsg(1);
            _this.getMsg(2);
            
            /* 处理禁言 */
            if(_DATA.roominfo.isshup){
                $(":checkbox[id='stopspeak']").prop("checked",true);
            }
            
            /* 选项卡 */
            _this.tab.on('click','li',function(){
                var _that=$(this);
                if(_that.hasClass('on')){
                    return !1;
                }
                
                var type=_that.data('type');
                _that.siblings().removeClass('on');
                _that.addClass('on');
                $('.liveing_c_r_tab_bd').hide().eq(_that.index()).show();

                $('.send_area').css('display','flex');
                if(type == 2 && _DATA.roominfo.user_type==0){ //问答区，隐藏发送按钮
                    $('.send_area').css('display','none');
                }
                _this.js_send.data('type',type);
                if(type==0){
                    //_this.js_send.parent().find('textarea').attr('placeholder','输入讨论内容');
                }
                
                if(type==1){
                    //_this.js_send.parent().find('textarea').attr('placeholder','输入讲解内容');
                }
                
                if(type==2){
                    //_this.js_send.parent().find('textarea').attr('placeholder','输入回复内容');
                }
                if(type!=2){
                    _this.js_msg_qa.find('li').removeClass('on');
                    
                    toid=0;
                    touid=0;
                    toname='';
                    tocontent='';
                }
            });
            
            /* 滚动监听 */
            _this.js_msg_talk.scroll(function(){  
                var srollPos = _this.js_msg_talk.scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)  
                if(srollPos <= 50 ) {  
                    _this.getMsg(0);
                }  
            }); 
            _this.js_msg_explain.scroll(function(){  
                var srollPos = _this.js_msg_explain.scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)  
                if(srollPos <= 50 ) {  
                    _this.getMsg(1);
                }  
            }); 
            _this.js_msg_qa.scroll(function(){  
                var srollPos = _this.js_msg_qa.scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)  
                if(srollPos <= 50 ) {  
                    _this.getMsg(2);
                }  
            }); 

            /* 提问 */
            _this.putQuestions.on('click',function(){
                _this.goPutQuestions();
            })

            
            /* 禁言 */
            _this.stopspeak.on('click',function(){
                _this.Shutup();
            })
            
            /* 发送信息 */
            _this.js_send.on('click',function(){
                var _that=$(this);
                var type=_that.data('type');
                var txt=_that.siblings('textarea').val();
                
                if(_DATA.roominfo.islive==2){
                    layer.msg('直播已结束，不能发言');
                    return !1;
                }
                
                if(_DATA.roominfo.isshup==1 && _DATA.roominfo.user_type==0){
                    layer.msg('直播禁言中...');
                    return ;
                }

                if(type==2 && _DATA.roominfo.user_type==0){
                    layer.msg('只有老师才能回复');
                    return !1;
                }

                if(txt==''){
                    layer.msg('请输入发言内容');
                    return !1;
                }
                var data={
                    type:0,
                    content:txt,
                    'status':0,
                };
                
                if(type==2 && _DATA.roominfo.user_type==0){
                    return ;
                }

                if(_this.isPutQuestions    == 1){
                    data.status = 1;
                }
                
                _this.sendMsg(_that,data);


            })
            
            /* 问答 */
            _this.js_msg_qa.on('click','.qa_b',function(){
                var _that=$(this);
                var qa_li=_that.parent('li');
                if(qa_li.hasClass('on')){
                    qa_li.removeClass('on');
                    toid=0;
                    touid=0;
                    toname='';
                    tocontent='';
                    
                    //_this.js_send.parent().addClass('no');
                    //_this.js_send.attr('disabled','disabled');
                    //_this.js_send.parent().find('textarea').attr('placeholder','输入选择回复内容');
                }else{
                    qa_li.siblings().removeClass('on');
                    qa_li.addClass('on');
                    
                    toid=_that.data('chatid');
                    touid=_that.data('touid');
                    toname=_that.data('toname');
                    tocontent=_that.data('content');
                    
                    //_this.js_send.parent().removeClass('no');
                    //_this.js_send.removeAttr('disabled');
                    //_this.js_send.parent().find('textarea').attr('placeholder','回复:'+tocontent);
                }
                
                
            })
            
            /* 语音录制-开始 */
            _this.js_audio.on('click','.audio_start .js_audio_img',function(){
                
                if(_DATA.roominfo.islive==2){
                    layer.msg('直播已结束，不能发言');
                    return !1;
                }
                
                if(_DATA.roominfo.isshup==1){
                    layer.msg('直播禁言中...');
                    return ;
                }

                var _that=$(this);

                _that.parent().hide();
                if(!recorder){
                   _this.js_audio.find('.audio_error').show();
                   return !1;
                }
                _this.js_audio.find('.audio_ing').show();
                
                var audio = document.querySelectorAll('audio');
                for(var i = 0; i < audio.length; i++){
                    if(!audio[i].paused){
                        audio[i].pause();
                    }
                }
                recorder.start();
                _this.createRecorderTime();
            })
            
            /* 语音录制-取消 */
            _this.js_audio.on('click','.audio_cancel',function(){
                var _that=$(this);
                layer.confirm('确定要取消这条录音么',{},function(index){
                    layer.close(index);
                    _that.parent().parent().hide();
                    _this.js_audio.find('.audio_start').show();
                    _this.clearRecorderTime();
                })
                
            })
            
            /* 语音录制-完成 */
            _this.js_audio.on('click','.audio_ok',function(){
                var _that=$(this);
                _that.parent().parent().hide();
                _this.js_audio.find('.audio_end').show();
                recorder.stop();
                _this.clearRecorderTime();
            })
            
            /* 语音录制-发送 */
            _this.js_audio.on('click','.audio_send',function(){
                var _that=$(this);
                recorder.getBlob(function(blob){
                    // console.log(blob);
                    // console.log(recorder);
                    
                    /* var audio = document.createElement('audio');
                        audio.src = URL.createObjectURL(blob);
                        audio.controls = true;
                        $('.liveing_audio').append(audio); */
                        
                    var formData = new FormData(); 
                        formData.append('file', blob);
                        formData.append('courseid', courseid);
                        formData.append('lessonid', lessonid);
                        formData.append('audio_time', audio_time);
                    $.ajax({ 
                        type: 'post', 
                        url: '/student/liveing/addAudio', //上传文件的请求路径必须是绝对路劲
                        data: formData, 
                        dataType:'json',
                        cache: false, 
                        processData: false, 
                        contentType: false, 
                        success:function(data){
                            if(data.code==0){
                                handelRes(data);
                                return !1;
                            }
                            
                            var chatinfo=data.data;
                            var msg={
                                    "msg":[
                                        {
                                            "_method_":"SendMsg",
                                            "action":"1",
                                            "uid":''+_DATA.userinfo.id,
                                            "user_nickname":''+_DATA.userinfo.user_nickname,
                                            "avatar":''+_DATA.userinfo.avatar,
                                            "liveuid":''+_DATA.teacherinfo.id,
                                            "chatid":''+chatinfo.chatid,
                                            "type":''+chatinfo.type,
                                            "content":''+chatinfo.content,
                                            "url":''+chatinfo.url,
                                            "length":''+chatinfo.length,
                                            "status":''+chatinfo.status,
                                            "toname":'',
                                            "tocontent":'',
                                            "equipment":"pc",
                                            "msgtype":"0"
                                        }
                                    ],
                                    "retcode":"000000",
                                    "retmsg":"OK"
                                };
                            // console.log(msg);
                            Socket.emitData('broadcast',msg);
                            
                            _this.js_audio.find('.audio_end').hide();
                            _this.js_audio.find('.audio_start').show();
                        },
                        error:function(e){

                        }
                    })
                   
                },function(msg){ 
                   
                });
            })
            
            /* 音频处理 */
            $(".msg").on('click','.msg_c_audio',function(){
                var _this=$(this);
                var audio_i=_this.find('i');
                var audio=_this.find('audio')[0];

                if(audio_i.hasClass('play')){
                    /* 播放中暂停 */
                    audio.pause();
                }else{
                    /* 开始播放 */
                    $('.msg').find('audio').each(function(){
                        $(this).siblings('i').removeClass('play');
                        if($(this)[0]!=audio){
                            $(this)[0].currentTime=0;
                            $(this)[0].pause();
                        }
                    });
                    
                    audio.play();
                    audio.addEventListener("playing", function(){//播放状态Doing
                        // console.log('play');
                        audio_i.addClass('play');
                    });
                    audio.addEventListener("pause", function(){ //暂停状态Doing
                        // console.log('pause');
                        audio_i.removeClass('play');
                    });
                    audio.addEventListener("ended", function(){ //播放完
                        // console.log('ended');
                        audio_i.removeClass('play');
                    });
                }
            })
        },
        getMsg:function(type=0){
            var _this=this;
            var lastid=0;
            if(type==0){
                if(!_this.isscroll_0){
                    return !1;
                }
                _this.isscroll_0=0;
                lastid=_this.lastid_0;
            }
            
            if(type==1){
                if(!_this.isscroll_1){
                    return !1;
                }
                _this.isscroll_1=0;
                lastid=_this.lastid_1;
            }
            
            if(type==2){
                if(!_this.isscroll_2){
                    return !1;
                }
                _this.isscroll_2=0;
                lastid=_this.lastid_2;
            }
            
            $.ajax({
                url:'/student/liveing/getChat',
                type:'POST',
                data:{courseid:courseid,lessonid:lessonid,type:type,lastid:lastid,liveuid:_DATA.teacherinfo.id},
                dataType:'json',
                error:function(e){
                    if(type==0){
                        _this.isscroll_0=1;
                    }
                    
                    if(type==1){
                        _this.isscroll_1=1;
                    }
                    
                    if(type==2){
                        _this.isscroll_2=1;
                    }
                },
                success:function(data){

                    if(data.code==0){
                        handelRes(data);
                        return !1;
                    }
                    var uid= _DATA.userinfo.id;
                    var list=data.data;
                    var html='';
                    var nums=list.length;
                    for(var i=0;i<nums;i++){
                        var v=list[i];
                        /* 更新为第一条的ID */
                        if(i==0){
                            if(type==0){
                                _this.uplastid0(v.id);
                            }
                            if(type==1){
                                _this.uplastid1(v.id);
                            }
                            if(type==2){
                                _this.uplastid2(v.id);
                            }
                            
                        }
                        var isme= uid==v.uid ? 1:0;
                        var meisteacher= _DATA.roominfo.user_type>0 ? 1:0;
                        var to_usertype= v.user_type;
                        var toisteacher= to_usertype>0 ? 1:0;
                        
                        var data_status=v.status;
                        
                        var msg_name=v.user_nickname;
                        var msg_avatar=v.avatar;
                        var msg_content=v.content;
                        msg_content=emojiToImg(msg_content);
                        
                        var li_css='';
                        if(isme){
                            li_css+='msg_right ';
                        }
                        
                        if(type==2){
                            li_css+='qa ';
                        }
                        
                        html+='<li class="'+li_css+'">';
                        
                        var u_html='<div class="msg_n"><img src="'+msg_avatar+'"><span>'+msg_name+'</span>';
                        if(to_usertype==1){
                            u_html+='<span style="color:#43D9A7">讲师</span>';
                        }
                        if(to_usertype==2){
                            u_html+='<span style="color:#43D9A7">辅导</span>';
                        }
                        u_html+='</div>';
                        
                        html+=u_html;
                        html+='<div class="msg_p">';
                        
                        if(data_status==2){
                            /* 回答 */
                            html+='<div class="msg_c">\
                                        <div class="msg_c_r">\
                                            <div class="msg_c_r_b"></div>\
                                            <div class="msg_c_r_c">\
                                                <div class="msg_c_r_c_n">'+v.toinfo.user_nickname+'</div>';
                            if(v.toinfo.type==1){
                                html+='         <div class="msg_c_r_c_c">\
                                                    <div class="msg_audio">\
                                                        <div class="msg_c_audio">\
                                                            <i></i> <span>'+v.toinfo.length+'”</span>\
                                                            <audio preload="auto" src="'+v.toinfo.url+'"></audio>\
                                                        </div>\
                                                    </div>\
                                                </div>';
                                
                            }else{
                                var msg_tocontent= typeof(v.toinfo.content) == 'undefined' ? '':v.toinfo.content;
                                msg_tocontent=emojiToImg(msg_tocontent);
                                
                                html+='         <div class="msg_c_r_c_c">'+msg_tocontent+'</div>';
                            }
                            html+='         </div>\
                                        </div>\
                                        @'+v.toinfo.user_nickname+' '+msg_content+'\
                                    </div>';
                                    
                            

                            
                        }else if(v.type==1){
                            html+='<div class="msg_audio">\
                                        <div class="msg_c_audio">\
                                            <i></i> <span>'+v.length+'”</span>\
                                            <audio preload="auto" src="'+v.url+'"></audio>\
                                        </div>\
                                    </div>';
                        }else{
                            html+='<p class="msg_c">'+msg_content+'</p>';
                        }
                        
                        
                        
                        html+='</div>';
                        
                        if(type==2 && data_status==1){
                            if(meisteacher){
                                html+='<div class="qa_b" data-chatid="'+v.id+'" data-touid="'+v.uid+'" data-toname="'+msg_name+'" data-content="'+v.content+'">\
                                            <button>回复</button>\
                                        </div>';
                            }
                        }
                        html+='</li>';
                    }
                    
                    if(type==0){
                        _this.js_msg_talk.find('ul').prepend(html);
                        if(lastid==0){
                            _this.js_msg_talk.scrollTop( _this.js_msg_talk[0].scrollHeight );
                        }
                        
                    }
                    
                    if(type==1){
                        _this.js_msg_explain.find('ul').prepend(html);
                        if(lastid==0){
                            _this.js_msg_explain.scrollTop( _this.js_msg_explain[0].scrollHeight );
                        }
                    }
                    
                    if(type==2){
                        _this.js_msg_qa.find('ul').prepend(html);
                        if(lastid==0){
                            _this.js_msg_qa.scrollTop( _this.js_msg_qa[0].scrollHeight );
                        }
                    }
                    
                    if(nums>0){
                        if(type==0){
                            _this.isscroll_0=1;
                        }
                        
                        if(type==1){
                            _this.isscroll_1=1;
                        }
                        
                        if(type==2){
                            _this.isscroll_2=1;
                        }
                    }
                }
            });
        },
        uplastid0:function(lastid,type=0){
            var _this=this;
            if(type==1){
                /* 聊天更新 */
                if(_this.lastid_0==0){
                    _this.lastid_0=lastid;
                }
            }else{
                _this.lastid_0=lastid;
            }
        },
        uplastid1:function(lastid,type=0){
            var _this=this;
            if(type==1){
                /* 聊天更新 */
                if(_this.lastid_1==0){
                    _this.lastid_1=lastid;
                }
            }else{
                _this.lastid_1=lastid;
            }
        },
        uplastid2:function(lastid,type=0){
            var _this=this;
            if(type==1){
                /* 聊天更新 */
                if(_this.lastid_2==0){
                    _this.lastid_2=lastid;
                }
            }else{
                _this.lastid_2=lastid;
            }
        },
        sendMsg:function(_that,data){
            var data_post={
                liveuid:_DATA.teacherinfo.id,
                courseid:courseid,
                lessonid:lessonid,
                user_type:_DATA.roominfo.user_type,
            };
            //var data=Object.assign(str,create); //js 合并对象
            var data_post= $.extend(data_post, data); //jq 合并对象
            $.ajax({
                url:'/student/liveing/setChat',
                type:'POST',
                data:data_post,
                dataType:'json',
                success:function(data){
                    if(data.code==0){
                        handelRes(data);
                        return !1;
                    }
                    
                    _that.siblings('textarea').val('');
                    
                    var chatinfo=data.data;
                    var msg={
                            "msg":[
                                {
                                    "_method_":"SendMsg",
                                    "action":"1",
                                    "uid":''+_DATA.userinfo.id,
                                    "user_nickname":''+_DATA.userinfo.user_nickname,
                                    "avatar":''+_DATA.userinfo.avatar,
                                    "liveuid":''+_DATA.teacherinfo.id,
                                    "chatid":''+chatinfo.chatid,
                                    "type":''+chatinfo.type,
                                    "content":''+chatinfo.content,
                                    "url":''+chatinfo.url,
                                    "length":''+chatinfo.length,
                                    "status":''+chatinfo.status,
                                    "toname":''+data_post.toname,
                                    "tocontent":''+data_post.tocontent,
                                    "user_type":''+_DATA.roominfo.user_type,
                                    "equipment":"pc",
                                    "msgtype":"0"
                                }
                            ],
                            "retcode":"000000",
                            "retmsg":"OK"
                        };
                    // console.log(msg);
                    Socket.emitData('broadcast',msg);
                    
                },
                error:function(e){
                    
                }
            })
        },
        appendMsg:function(data){
            var _this=this;
            var uid = _DATA.userinfo.id;
            var html='';
            var isme= uid==data.uid ? 1:0;
            var meisteacher= _DATA.roominfo.user_type>0 ? 1:0;
            var to_usertype= data.user_type;
            var toisteacher= to_usertype>0 ? 1:0;
            var data_status=data.status;
            var type=data.type;
            
            var msg_name=data.user_nickname;
            var msg_avatar=data.avatar;
            var msg_content=data.content;
            var msg_tocontent= typeof(data.tocontent) == 'undefined' ? '':data.tocontent;
            msg_content=emojiToImg(msg_content);
            msg_tocontent=emojiToImg(msg_tocontent);
            /* 处理 讲解区 讨论区信息 */
            if(isme){
                /* 自己发的 */
                html+='<li class="msg_right">';
            }else{
                /* 别人发的 */
                html+='<li>';
            }
            

            var u_html='<div class="msg_n"><img src="'+msg_avatar+'"><span>'+msg_name+'</span>';
            if(to_usertype==1){
                u_html+='<span style="color:#43D9A7">讲师</span>';
            }
            if(to_usertype==2){
                u_html+='<span style="color:#43D9A7">辅导</span>';
            }
            u_html+='</div>';
            
            html+=u_html;
            html+='<div class="msg_p">';
           
            
            if(data_status==2){
                /* 回答 */
                html+='<div class="msg_c">\
                                        <div class="msg_c_r">\
                                            <div class="msg_c_r_b"></div>\
                                            <div class="msg_c_r_c">\
                                                <div class="msg_c_r_c_n">'+data.toname+'</div>\
                                                <div class="msg_c_r_c_c">'+msg_tocontent+'</div>\
                                            </div>\
                                        </div>\
                                        @'+data.toname+' '+msg_content+'\
                                    </div>';
            }else if(type==1){
                html+='<div class="msg_audio">\
                                        <div class="msg_c_audio">\
                                            <i></i> <span>'+data.length+'”</span>\
                                            <audio preload="auto" src="'+data.url+'"></audio>\
                                        </div>\
                                    </div>';
            }else{
                html+='<p class="msg_c">'+msg_content+'</p>';
            }
            
            html+='</div>';
            html+='</li>';
            
            if(toisteacher){
                _this.js_msg_explain.find('ul').append(html);
            }
            
            /* 处理问答区 */
            if(data_status==1){
                /* 提问 */
                var html2='';
                    if(isme){
                        /* 自己发的 */
                        html2+='<li class="msg_right qa">';
                    }else{
                        /* 别人发的 */
                        html2+='<li class="qa">';
                    }
                    html2+=u_html;
                    html2+='<div class="msg_p">';
                    if(type==1){
                        html2+='<div class="msg_audio">\
                                        <div class="msg_c_audio">\
                                            <i></i> <span>'+data.length+'”</span>\
                                            <audio preload="auto" src="'+data.url+'"></audio>\
                                        </div>\
                                    </div>';
                    }else{
                        html2+='<p class="msg_c">'+msg_content+'</p>';
                    }
                    html2+='</div>';
                    
                    if(meisteacher){
                        html2+='<div class="qa_b" data-chatid="'+data.chatid+'" data-touid="'+data.uid+'" data-toname="'+data.user_nickname+'" data-content="'+data.content+'">\
                                    <button>回复</button>\
                                </div>';
                    }
                    html2+='</li>';
                 _this.js_msg_qa.find('ul').append(html2);
            }
            if(data_status==2){
                /* 回复 */
                var html2='';
                    if(isme){
                        /* 自己发的 */
                        html2+='<li class="msg_right">';
                    }else{
                        /* 别人发的 */
                        html2+='<li>';
                    }
                    html2+=u_html;
                    html2+='<div class="msg_p">';
                    html2+='<div class="msg_c">\
                                    <div class="msg_c_r">\
                                        <div class="msg_c_r_b"></div>\
                                        <div class="msg_c_r_c">\
                                            <div class="msg_c_r_c_n">'+data.toname+'</div>\
                                            <div class="msg_c_r_c_c">'+msg_tocontent+'</div>\
                                        </div>\
                                    </div>\
                                    @'+data.toname+' '+msg_content+'\
                                </div>';
                    html2+='</div>';
                    html2+='</li>';
                 _this.js_msg_qa.find('ul').append(html2);
            }
            
            _this.js_msg_talk.find('ul').append(html);
            
            _this.scrollH();
        },
        scrollH:function(){
            var _this=this;
            _this.js_msg_explain.scrollTop( _this.js_msg_explain[0].scrollHeight );
            _this.js_msg_talk.scrollTop( _this.js_msg_talk[0].scrollHeight );
            _this.js_msg_qa.scrollTop( _this.js_msg_qa[0].scrollHeight );
        },
        createRecorderTime:function(){
            var _this=this;
            if(recorder_time){
                clearInterval(recorder_time);
                recorder_time=null;
            }
            var time=0;
            recorder_time=setInterval(function(){
                time++;
                
                if(time>= 10*60){
                    _this.js_audio.find('.audio_ok').click();
                    return !1;
                }
                var time_data=handelTime(time);
                _this.js_audio.find('.audio_ing .js_audio_time span').html(time_data.i+':'+time_data.s);
                audio_time=time;
            },1000);
        },
        clearRecorderTime:function(){
            var _this=this;
            if(recorder_time){
                clearInterval(recorder_time);
                recorder_time=null;
            }
        },
        goPutQuestions:function(){ //提问
            var _this=this;
            if(_this.isPutQuestions == 0){ //提问
                _this.isPutQuestions = 1;
            }else{
                _this.isPutQuestions = 0;
            }
        },
        Shutup:function(){
            var _this=this;
            
            var ischecked=_this.stopspeak.is(':checked');
            var isshup='';
            if(ischecked==true){ //表示要进行禁言
                if(_DATA.roominfo.isshup==1){
                    layer.msg('禁言中，请不要重复禁言');
                    $(":checkbox[id='stopspeak']").prop("checked",true);
                    return ;
                }

                isshup=1;
            }else{ //要进行取消禁言
                if(_DATA.roominfo.isshup==0){
                    layer.msg('非禁言，请不要重复非禁言');
                    $(":checkbox[id='stopspeak']").prop("checked",false);
                    return ;
                }

                isshup=0;
            }

            $.ajax({
                type: "POST",
                url:'/student/liveing/roomShutup',
                data:{courseid:courseid,lessonid:lessonid,type:isshup},
                dataType:'json',
                error: function(request)
                {
                    layer.msg("数据请求失败");
                },
                success: function(data)
                {
                    if(data.code==0){
                        if(isshup==0){
                            $(":checkbox[id='stopspeak']").prop("checked",false);
                        }else{
                            $(":checkbox[id='stopspeak']").prop("checked",true);
                        }
                        
                        handelRes(data);
                        return !1;
                    }
                    layer.msg(data.msg);

                    _DATA.roominfo.isshup=isshup;

                    if(isshup==1){
                        var msg='已开启禁言';
                    }else{
                        var msg='已关闭禁言';
                    }
                    
                    var msg = '{"msg":[{"_method_":"roomShutup","action":"'+isshup+'","uid":"'+_DATA.userinfo.id+'","avatar":"'+_DATA.userinfo.avatar+'","user_nickname":"'+_DATA.userinfo.user_nickname+'"}],"retcode":"000000","retmsg":"OK"}';
                    
                    Socket.emitData('broadcast',msg);	
                }
            })
        }
    }
    
    /* PPT处理 */
    var ppt={
        isSwiper:0,
        galleryThumbs:null,
        galleryTop:null,
        ppt_show:$('#ppt_show'),
        ppt_list:$('.ppt_list'),
        init:function(){
            var _this=this;
            $('body').on('click','.ppt_add',function(){
                $('#upload').empty();
                var input = '<input type="file" id="ppt_file" name="file" accept="image/*"/>';
                $('#upload').html(input);
                var index='ppt_file';
                var iptt=document.getElementById(index);
                if(window.addEventListener) { // Mozilla, Netscape, Firefox
                    iptt.addEventListener('change',function(){
                        _this.upload();
                    },false);
                }else{
                    iptt.attachEvent('onchange',function(){
                        _this.upload();
                    });
                }
                $('#'+index).click();
            })
            _this.ppt_list.on('click','.ppt_del',function(){
                var _that=$(this);
                var pptid=_that.data('pptid');
                _this.del(pptid);
            })
        },
        upload:function(){
            var _this=this;
            var id= 'ppt_file';
            
            var file = document.getElementById('ppt_file').files[0]; 

            var formData = new FormData(); 
            formData.append('file', file);
            formData.append('courseid', courseid);
            formData.append('lessonid', lessonid);
    
            $.ajax({
                url: '/student/liveing/addPPT',
                type:'POST',
                data: formData,
                dataType: 'json',
                cache: false, 
                processData: false, 
                contentType: false, 
                error: function(data) {
                    //console.log()
                    layer.msg("上传失败");
                },
                success: function(data) {
                    if(data.code==0){
                        handelRes(data);
                        return !1;
                    }
                    
                    _this.addppts(data.data);
                    
                    var msg={
                        "msg":[
                            {
                                "_method_":"setPPT",
                                "action":"0",
                                "pptid":''+data.data.id,
                                "thumb":''+data.data.thumb,
                                "equipment":"pc",
                                "msgtype":"0"
                            }
                        ],
                        "retcode":"000000",
                        "retmsg":"OK"
                    };
                    // console.log(msg);
                    Socket.emitData('broadcast',msg);
                } 
            })
        },
        del:function(pptid){
            var _this=this;
            $.ajax({
                url:'/student/liveing/delPPT',
                type:'POST',
                data:{courseid:courseid,lessonid:lessonid,pptid:pptid},
                dataType:'json',
                success:function(data){
                   if(data.code==0){
                       handelRes(data);
                       return !1;
                   }
                   
                   _this.delppts(pptid);
                   
                   var msg={
                        "msg":[
                            {
                                "_method_":"setPPT",
                                "action":"1",
                                "pptid":''+pptid,
                                "thumb":'',
                                "equipment":"pc",
                                "msgtype":"0"
                            }
                        ],
                        "retcode":"000000",
                        "retmsg":"OK"
                    };
                    // console.log(msg);
                    Socket.emitData('broadcast',msg);
                        
                },
                error:function(e){
                    layer.msg('网络错误');
                }
            });
        },
        addppts:function(pptdata){
            var _this=this;
            ppts.push(pptdata);
            
            _this.ppt_list.find('.ppt_add').parents('.swiper-slide').remove();
            $('.ppt_list_title').find('.ppt_list_add').show();
            
            var html1='';
            var html2='';
            var t=ppts.length;
            html1+='<div class="swiper-slide ppt_'+pptdata.id+'" style="background-image:url('+pptdata.thumb+')"></div>';
            html2+='<div class="swiper-slide ppt_'+pptdata.id+'" >\
                        <div class="ppt_list_u" style="background-image:url('+pptdata.thumb+')">';
            if(_DATA.roominfo.user_type==1){
                html2+='    <div class="ppt_del" data-pptid="'+pptdata.id+'">删除</div>';
            }
            html2+='    </div>\
                        <div class="ppt_list_t">\
                              '+t+'\
                         </div>\
                    </div>';
                    
            _this.galleryTop.appendSlide(html1);
            _this.galleryThumbs.appendSlide(html2);
            
            _this.galleryThumbs.update();
            _this.galleryTop.update();
        },
        delppts:function(pptid){
            var _this=this;
            var ppts2=[];
            var nums=ppts.length;
            var v='';
            for(var i=0;i<nums;i++){
                v=ppts[i];
                if(v.id!=pptid){
                    ppts2.push(v);
                }else{
                    _this.galleryThumbs.removeSlide(i);
                    _this.galleryTop.removeSlide(i);
                }
            }
            
            _this.galleryThumbs.update();
            _this.galleryTop.update();
            
            ppts=ppts2;
            
            /* 更新序号 */
            var nums=ppts.length;
            var v='';
            for(var i=0;i<nums;i++){
                var t=i+1;
                _this.ppt_list.find('.ppt_list_t').eq(i).text(t);
            }
            
            if(_DATA.roominfo.user_type==1){
                if(ppts.length>0){
                    $('.ppt_list_title').find('.ppt_list_add').show();
                }else{
                    $('.ppt_list_title').find('.ppt_list_add').hide();
                    
                    if(_this.ppt_list.find('.ppt_add').length==0){
                        var d='<div class="swiper-slide" >\
                                    <div class="ppt_list_add ppt_add">\
                                        <div class="ppt_list_add_img add_ppt"></div>\
                                        <div class="ppt_list_add_t">\
                                        打开课件\
                                        </div>\
                                    </div>\
                                </div>';
                        _this.ppt_list.find('.swiper-wrapper').append(d);
                    }
                    
                }
            }
        },
        pptSwiper:function(){
            var _this=this;
            _this.galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                navigation: {
                    nextEl: '.ppt_list .swiper-button-next',
                    prevEl: '.ppt_list .swiper-button-prev',
                },
              
            });
            _this.galleryTop = new Swiper('.gallery-top', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.gallery-top .swiper-button-next',
                    prevEl: '.gallery-top .swiper-button-prev',
                },
                thumbs: {
                    swiper: _this.galleryThumbs
                },
                on:{
                    slideChange: function(){
                        _this.changePPT(this.activeIndex);
                        // console.log(this.activeIndex);
                    },
                },
            });
            
            if( _DATA.roominfo.user_type!=1 && _DATA.roominfo.pptindex>0){
                _this.slideChange(_DATA.roominfo.pptindex);
            }
        },
        
        changePPT:function(activeIndex){
            if(_DATA.roominfo.user_type==1){
                $.ajax({
                    url:'/student/liveing/upPPTindex',
                    type:'POST',
                    data:{courseid:courseid,lessonid:lessonid,activeIndex:activeIndex},
                    dataType:'json',
                    success:function(data){
                        if(data.code==0){
                            handelRes(data);
                            return !1;
                        }
                       
                        var msg = '{"msg":[{"_method_":"setPPT","action":"2","index":"'+activeIndex+'","uid":"'+_DATA.userinfo.id+'","ct":"","source":"PC"}],"retcode":"000000","retmsg":"OK"}';
                        Socket.emitData('broadcast',msg);
                            
                    },
                    error:function(e){
                        layer.msg('网络错误');
                    }
                });
            
                
            }
        },
        slideChange:function(activeIndex){
            var _this=this;
            //activeIndex  从0开始
            _this.galleryTop.slideTo(activeIndex);
        },
        
        showPPT:function(type=0){
            var _this=this;
            _this.ppt_show.show();
            _this.ppt_list.show();
            if( _this.isSwiper == 0 || type==1 ){
                _this.pptSwiper();
                _this.isSwiper=1;
            }
            
            if(_DATA.roominfo.user_type==1){
                if(ppts.length>0){
                    $('.ppt_list_title').find('.ppt_list_add').show();
                }else{
                    $('.ppt_list_title').find('.ppt_list_add').hide();
                    
                    if(_this.ppt_list.find('.ppt_add').length==0){
                        var d='<div class="swiper-slide" >\
                                    <div class="ppt_list_add ppt_add">\
                                        <div class="ppt_list_add_img add_ppt"></div>\
                                        <div class="ppt_list_add_t">\
                                        打开课件\
                                        </div>\
                                    </div>\
                                </div>';
                        _this.ppt_list.find('.swiper-wrapper').append(d);
                    }
                    
                }
            }
        },
        hidePPT:function(){
            var _this=this;
            
            _this.ppt_show.hide();
            _this.ppt_list.hide();
            $('.ppt_list_title').find('.ppt_list_add').hide();
        }
    };
    
    //进入声网房间
    function joinAgora(){
        //先检查一下是否和现在的浏览器相匹配
        var isgo=AgoraRTC.checkSystemRequirements();
        if(!isgo){
            alert('当前浏览器不支持，请更换浏览器或者升级该浏览器，如:谷歌浏览器');
            return ;
        }
        var client = AgoraRTC.createClient({mode: 'live', codec: "h264"});

        rtc.client=client;

        //加载声网回调
        handleEvents(rtc);
        //初始化房间
        rtc.client.init(option.appID, function () {
            // 初始化成功房间后老师和学生加入房间
            rtc.client.join(option.token, option.channel,parseInt(_DATA.userinfo.id) , function (uid) {
                // console.log("join channel: " + option.channel + " success, uid: " + uid);
                rtc.params.uid = uid;
            }, function(err) {
                layer.msg('进入房间失败请稍后重试');
                // console.error("client join failed", err)
            })
            
        }, function (err) {
            // console.log("AgoraRTC client init failed", err);
            layer.msg('房间初始化失败，请稍后重试');
        });

    }

    //声网的一些回调
    function handleEvents (rtc) {
        //本地推流回调
        rtc.client.on("stream-published", function(evt) {
            // console.log('本地流推了');
            // console.log(evt.stream);
            // console.log(evt.stream.getId());
            
            rtc.published = true;
        })

        //异地推流成功后回调
        rtc.client.on("stream-added", function(evt) {
            // console.log('异地流推了');
            // console.log(evt);
            var remoteStream = evt.stream;
            var id = remoteStream.getId();
            
            //接受远端流
            if(id==option.channel_screen){ //共享屏幕推流
                if( _DATA.roominfo.user_type!=1 ){ //如果你不是老师就接受流
                    rtc.client.subscribe(remoteStream, function (err) {
                        console.log("stream subscribe failed", err);
                    })
                }
            }else{
                if (id !== _DATA.userinfo.id) {
                    rtc.client.subscribe(remoteStream, function (err) {
                        console.log("stream subscribe failed", err);
                    })
                }
            }
        })

        //接受远端成功回调之后播放流
        rtc.client.on("stream-subscribed", function (evt) {
            //console.log(evt);
            var remoteStream = evt.stream;
            var id = remoteStream.getId();
            console.log('接受远端流--'+id);
            if(id!=option.channel_screen){
                rtc.remoteStreams.push(remoteStream);
                console.log('stream-subscribed remote-uid: ', id);
                
                remoteStream.play("liveing_play_bd",{fit: "contain"},function(errState){
                    var isPlay=checkBrowser.myBrowser();
                    if (errState && errState.status !== "aborted" && isPlay==false){
                        $('.live_play').css('display','block');
                        layer.msg('由于浏览器的限制，需要手动点击老师画面进入播放');

                        $('.live_play').click(function(){
                            remoteStream.resume().then(
                                function (result) {
                                    $('.live_play').css('display','none');
                                    layer.msg('播放成功');
                                }).catch(
                                function (reason) {
                                    layer.msg('播放失败');
                            });
                        })
                    }

    
                });
            
            }else{  //老师共享屏幕的流
                // console.log(evt);
                if(_DATA.roominfo.user_type!=1){
                    rtc.remoteStreams_screen.push(remoteStream);
                    //获取信息之后再播放
                    Live.upScreen(1);
                    remoteStream.play("screen",{fit: "contain"},function(errState){
                        var isPlay=checkBrowser.myBrowser();
                        if (errState && errState.status !== "aborted" && isPlay==false){
                        }
                    });
                    console.log('stream-subscribed remote-uid: ', id);
                }
                
            }

        })

        //推流结束回调
        rtc.client.on("stream-removed", function (evt) {
            var remoteStream = evt.stream;
            var id = remoteStream.getId();

            remoteStream.stop();
            if(id!=option.channel_screen){
                
                rtc.remoteStreams = rtc.remoteStreams.filter(function (stream) {
                    return stream.getId() != id
                })
                
                rtc.client.unsubscribe(remoteStream, function (err) {
                    console.log("stream unsubscribe failed", err);
                })
            }else{ //共享屏幕推流结束
                rtc.remoteStreams_screen = rtc.remoteStreams_screen.filter(function (stream) {
                    return stream.getId() != id
                })
    
                rtc.client.unsubscribe(remoteStream, function (err) {
                    console.log("stream unsubscribe failed", err);
                })
                
                teacherscreen.upScreen(0);
            } 
        })

        //关闭音频轨道回调
        rtc.client.on("mute-audio", function(evt) {
            var uid = evt.uid;
            console.log("mute audio:" + uid);
            //alert("mute audio:" + uid);
        });

        //开启音频轨道回调
        rtc.client.on("unmute-audio", function (evt) {
            var uid = evt.uid;
            console.log("unmute audio:" + uid);
            
        });

        //关闭视频轨道回调
        rtc.client.on("mute-video", function (evt) {
            var uid = evt.uid;
            console.log("mute video" + uid);
        })

        
        //开启视频轨道回调
        rtc.client.on("unmute-video", function (evt) {
            var uid = evt.uid;
            console.log("unmute video:" + uid);
        })

        //有用户离开房间
        rtc.client.on("peer-leave", function(evt) {
            //console.log('有人离线了');
            var uid = evt.uid;
            var reason = evt.reason;

            console.log("remote user left ", uid, "reason: ", reason);
            for(var i=0;i<rtc.remoteStreams.length;i++){
                if(rtc.remoteStreams[i].getId()==uid){ //掉线的这个人正在连麦
                    rtc.remoteStreams[i].stop();
                    if(uid==_DATA.teacherinfo.id){ //老师掉线了
                        layer.msg('老师离开了房间');

                    }
                    
                    rtc.client.unsubscribe( rtc.remoteStreams[i], function (err) {
                        console.log("stream unsubscribe failed", err);
                    })
    
                    break;
                }
            }

            rtc.remoteStreams = rtc.remoteStreams.filter(function (stream) {
                return stream.getId() != uid
            })
            
            //如果在共享屏幕需要停止推流
            //老师离线了
            if(rtc.remoteStreams_screen!='' && uid==option.channel_screen){
                //学生相应的做出操作
                Live.upScreen(0);

                rtc.client.unsubscribe( rtc.remoteStreams_screen[0], function (err) {
                    console.log("stream unsubscribe failed", err);
                })

                rtc.remoteStreams_screen = rtc.remoteStreams_screen.filter(function (stream) {
                    return stream.getId() != option.channel_screen
                })
            }
        });
    }
    
    /* 预览 */
    function preview(type=0){
        //创建流
        var localStream = AgoraRTC.createStream({
            streamID: option.uid,
            audio:true,
            video:true,
            screen:false,
            microphoneId: selectedMicrophoneId,
            cameraId: selectedCameraId
        });
        rtc.localStream=localStream;
        //初始化流
        rtc.localStream.init(function () {
            isLocal=true;
            console.log("init local stream success");
            rtc.localStream.play("liveing_play_bd",{fit: "contain"},function(errState){
                var isPlay=checkBrowser.myBrowser();
                if (errState && errState.status !== "aborted" && isPlay==false){
                    layer.msg('由于浏览器的限制，需要手动点击老师画面进入播放');
                    // var html='<img class="body_right_video_play" src="/static/student/images/white/play.png">';
                    // $('#liveing_play_bd').append(html);
                    
                    document.querySelector("#liveing_play_bd").onclick=function(){
                        rtc.localStream.resume().then(
                            function (result) {
                                console.log('恢复成功：' + result);
                            }).catch(
                            function (reason) {
                                //layer.msg('播放失败，建议使用最新谷歌浏览器');
                                console.log('恢复失败：' + reason);
                        });
                    }
                }
            });
            
            if(type==1){
                startPush();
            }
            
        }, function (err) {
            layer.msg('请检查麦克风和摄像头是否正常');
            console.error("init local stream failed ", err);
        })
    }
    
    //开始推流上课
    function startPush(){
        //成功之后推流
        if (!rtc.client) {
            layer.msg('房间初始化失败，请重新进入房间');
            return;
        }
        
        
        // if (rtc.published) {
        //     layer.msg('你已经推流了，请结束推流后重新开播');
        //     return;
        // }
        // var oldState = rtc.published;

        // publish localStream
        rtc.client.publish(rtc.localStream, function (err) {
            rtc.published = false;
            layer.msg('本地推流失败请重新进入房间进行推流');
            console.log("publish failed");
            console.error(err);
            return ;
        })
        
        
    }
    
    //结束推流下课
    function endPush(){
        // console.log(rtc.localStream);
        rtc.client.unpublish(rtc.localStream, function(err) {
            console.log(err);
        })  
        
        //结束下课然后断流
        if(rtc.localStream){
            rtc.localStream.stop();
            rtc.localStream.close();
        }

        rtc.localStream = null;
        
        // console.log("client end push success");

        //如果正在共享屏幕
        if(rtc.localStream_screen){
            Live.stopScreen();
        }
    }
    
    /* 直播处理 */
    var Live={
        live_issubmit:0,
        current:0,
        control_list:$('.live_control_list'),
        js_camera:$('.js_camera'),
        js_ware:$('.js_ware'),
        js_share:$('.js_share'),
        js_startlive:$('#js_startlive'),
        js_continue:$('#js_continue'),
        js_endlive:$('#js_endlive'),
        play_bd:$('#liveing_play_bd'),
        play_bd_s:$('#liveing_play_bd_s'),
        js_ppt_title:$('.js_ppt_title'),
        shareing:$('.shareing'),
        screen:$('#screen'),
        init:function(){
            var _this=this;
            
            _this.js_startlive.on('click',function(){
                _this.startLive();
            })
            
            _this.js_continue.on('click',function(){
                if(_DATA.roominfo.livetype==5){
                    
                    if (!isLocal) {
                        layer.msg('请等待出现画面后再次点击');
                        return;
                    }
                
                    startPush();
                }
                _this.js_continue.hide();
                _this.js_endlive.show();
            })
            
            _this.js_endlive.on('click',function(){
                layer.confirm('确定要结束授课么',{},function(index){
                    layer.close(index);
                    _this.endlive();
                })
            })
            
            /* 切换到摄像头 */
            _this.js_camera.on('click',function(){
                var _that=$(this);
                if(_that.hasClass('on')){
                    return !1;
                }
                
                if(_this.current==2){
                    _this.stopScreen();
                    return !1;
                }
                
                _that.siblings().removeClass('on')
                _that.addClass('on');
                
                _this.showCamera();
                _this.upMode(0);
                
                
            })
            
            /* 切换到课件 */
            _this.js_ware.on('click',function(){
                var _that=$(this);
                if(_that.hasClass('on')){
                    return !1;
                }
                
                if(!rtc.published){
                    layer.msg('请先上课');
                    return !1;
                }
                
                if(_this.current==1){
                    return !1;
                }
                if(_this.current==2){
                    _this.stopScreen();
                    return !1;
                }
                
                _that.siblings().removeClass('on')
                _that.addClass('on');
                
                _this.showWare();
                _this.upMode(1);
            })
            
            /* 切换到共享屏幕 */
            _this.js_share.on('click',function(){
                var _that=$(this);
                if(_that.hasClass('on')){
                    return !1;
                }
                
                if(!rtc.published){
                    layer.msg('请先上课');
                    return !1;
                }
                var isChrome=checkBrowser.isChrome();
                if(isChrome==0){
                    layer.msg('屏幕共享功能仅支持Chrome浏览器，请更换浏览器');
                    return !1;
                }
                
                if(isChrome==-1){
                    layer.msg('Chrome浏览器版本过低，请更新至最新版');
                    return !1;
                }
                
                layer.confirm('切换成屏幕共享模式后，将停止摄像头及课件的直播，是否继续？',{},function(index){
                    layer.close(index);
                    
                    _that.siblings().removeClass('on')
                    _that.addClass('on');
                    
                    _this.upMode(2);
                    _this.showScreen();
                    _this.setScreen();
                })
                
            })
        },
        startLive:function(){
            var _this=this;
            if(_this.live_issubmit){
                return !1;
            }
            
            if(_DATA.roominfo.livetype==5){
                if(!selectedMicrophoneId || !selectedCameraId || selectedMicrophoneId=='' || selectedCameraId==''){
                    layer.msg('请检查麦克风和摄像头是否正常');
                    return !1;
                }
                
                if (!isLocal) {
                    layer.msg('请等待出现画面后再次点击');
                    return;
                }
                
            }
            _this.live_issubmit=1;
            $.ajax({
                type: "POST",
                url:'/student/liveing/startlive',
                data:{courseid:courseid,lessonid:lessonid},
                dataType:'json',
                error: function(e)
                {
                    _this.live_issubmit=0;
                    layer.msg("数据请求失败");
                },
                success: function(data)
                {
                    _this.live_issubmit=0;
                    if(data.code==0){
                        handelRes(data);
                        return !1;
                    }
                    _this.js_startlive.hide();
                    _this.js_endlive.show();
                    $('.live_status').html('上课中');
                    
                    if(_DATA.roominfo.livetype==5){
                        startPush();
                    }
                    
                }
            })
        },
        endlive:function(){
            $.ajax({
               url:'/student/liveing/endlive',
               type:'POST',
               data:{courseid:courseid,lessonid:lessonid},
               dataType:'json',
               success:function(data){
                   if(data.code==0){
                       handelRes(data);
                       return !1;
                   }
                   
                   var msg={
                            "msg":[
                                {
                                    "_method_":"StartEndLive",
                                    "action":"18",
                                    "ct":'授课结束',
                                    "equipment":"pc",
                                    "msgtype":"0"
                                }
                            ],
                            "retcode":"000000",
                            "retmsg":"OK"
                        };
                        // console.log(msg);
                        Socket.emitData('broadcast',msg);
                        
                   layer.msg('授课结束',{},function(){
                       if(lessonid>0){
                           location.href='/student/course/lesson?courseid='+courseid;
                       }else{
                           location.href='/student/live/index';
                       }
                   });
               },
               error:function(e){
                   layer.msg('网络错误');
               }
            });
        },
        
        upMode:function(livemode){
            $.ajax({
               url:'/student/liveing/upMode',
               type:'POST',
               data:{courseid:courseid,livemode:livemode},
               dataType:'json',
               success:function(data){
                   if(data.code==0){
                       handelRes(data);
                       return !1;
                   }
                   
                   var msg = '{"msg":[{"_method_":"changeMode","action":"'+livemode+'","uid":"'+_DATA.userinfo.id+'","ct":"","source":"PC"}],"retcode":"000000","retmsg":"OK"}';
                    Socket.emitData('broadcast',msg);
               },
               error:function(e){
                   layer.msg('网络错误');
               }
            });
        },
        setScreen:function(){
            /* 共享屏幕 */
            var _this=this;
            //老师停止推流
            if(rtc.localStream){
                if(rtc.client){
                    rtc.client.unpublish(rtc.localStream, function(err) {
                        console.log(err);
                    })
                }
                // stop stream
                rtc.localStream.stop();
                // close stream
                rtc.localStream.close();
            }
            rtc.localStream = null;

            _this.upScreen(1);
            var client_screen = AgoraRTC.createClient({mode: 'live', codec: "h264"});

            rtc.client_screen=client_screen;
            //加载声网回调
            _this.handleEventsScreen(rtc);
            //初始化房间
            rtc.client_screen.init(option.appID, function () {
                //console.log("初始化共享屏幕房间成功");
                // 初始化成功房间后添加一个共享屏幕机器人
                rtc.client_screen.join(option.token, option.channel,option.channel_screen, function (uid) {
                    console.log("join channel_screen: " + option.channel_screen + " success, uid: " + uid);
                    rtc.params_screen.uid = uid;

                    //加入房间之后老师直接推共享屏幕的流
                    //创建流
                    var localStream_screen = AgoraRTC.createStream({
                        streamID: option.channel_screen,
                        audio:true,
                        video:false,
                        screen:true
                    });
                    rtc.localStream_screen=localStream_screen;

                    _this.handleEventsScreenStream(rtc);
                    //初始化流
                    rtc.localStream_screen.init(function () {
                        console.log("init localStream_screen stream success");
                        rtc.localStream_screen.play("screen",{fit: "contain"},function(errState){
                            // var isPlay=checkBrowser.myBrowser();
                            // if (errState && errState.status !== "aborted" && isPlay==false){
                            // }
                        });
                        //成功之后推流
                        if (!rtc.client_screen) {
                            layer.msg('房间初始化失败，请重新进入房间');
                            return;
                        }
                        // if (rtc.published) {
                        //     layer.msg('你已经推流了，请结束推流后重新开播');
                        //     return;
                        // }
                        // var oldState = rtc.published;

                        // publish localStream
                        rtc.client_screen.publish(rtc.localStream_screen, function (err) {
                            //rtc.published = oldState;
                            layer.msg('共享屏幕推流失败请重新开始共享屏幕房间进行推流');
                            console.log("publish_screen failed");
                            console.error(err);

                            return ;
                        })

                        //rtc.published = true;
                    }, function (err) {
                        //layer.msg('请检查浏览器是否支持，建议使用谷歌浏览器进行操作');
                        _this.stopScreen();
                        console.error("init local_screen stream failed ", err);
                    })
                }, function(err) {
                    layer.msg('进入共享屏幕房间失败请稍后重试');
                    _this.stopScreen();
                    console.error("client_screen join failed", err)
                })
                
            }, function (err) {
                _this.stopScreen();
                console.log("AgoraRTC client_screen init failed", err);

                layer.msg('共享屏幕房间初始化失败，请稍后重试');
            });
        },

        //共享屏幕的一些声网回调
        handleEventsScreen:function(rtc) {
            //本地推流回调
            rtc.client_screen.on("stream-published", function(evt) {
                // console.log('本地共享屏幕流推了');
                // console.log(evt.stream);

                //共享屏幕推流之后发送socket给学生，让他们进来
                if(_DATA.roominfo.user_type==1){
                    var msg = '{"msg":[{"_method_":"ShareScreen","action":"0","uid":"'+_DATA.userinfo.id+'","ct":"","source":"PC","channel_screen":"'+option.channel_screen+'"}],"retcode":"000000","retmsg":"OK"}';
                    Socket.emitData('broadcast',msg);
                }
                
            })
        },

        //屏幕共享回调
        handleEventsScreenStream:function(rtc){
            var _this=this;
            //屏幕共享停止回调
            rtc.localStream_screen.on("stopScreenSharing",function(){
                //共享屏幕结束推流之后发送socket给学生，让他们出去
                if(_DATA.roominfo.user_type==1){
                    var msg = '{"msg":[{"_method_":"ShareScreen","action":"1","uid":"'+_DATA.userinfo.id+'","ct":"","source":"PC","channel_screen":"'+option.channel_screen+'"}],"retcode":"000000","retmsg":"OK"}';
                    Socket.emitData('broadcast',msg);	
                    _this.stopScreen();
                }
            })
        },

        //共享屏幕结束后的一些操作
        stopScreen:function(){
            var _this=this;
            //老师离开房间
            if(rtc.localStream_screen){
                // stop stream
                rtc.localStream_screen.stop();
                // close stream
                rtc.localStream_screen.close();
            }
            rtc.localStream_screen = null;

            rtc.remoteStreams_screen = [];

            _this.upScreen(0);
            if(rtc.client_screen){
                rtc.client_screen.leave(function () {

                }, function (err) {
                    console.log("channel leave failed");
                    console.error(err);
                })
            }
            _this.quitScreen();
            console.log('屏幕共享停止了');
        },
        quitScreen:function(){
            var _this=this;
            layer.confirm('当前直播已暂停，请重新选择共享窗口或切换为摄像头模式', {
                btn: ['切换共享窗口','更换摄像头模式'], //按钮
                closeBtn:0,
                title:'提示',
            }, function(index){
                layer.close(index);
                _this.setScreen();
            }, function(index){
                layer.close(index);
                $('.shareing').hide();

                
                _this.js_camera.siblings().removeClass('on')
                _this.js_camera.addClass('on');
                
                _this.showCamera();
                _this.upMode(0);
                
                preview(1);
                
            });
        },
        upScreen:function(type=0){
            var _this=this;
            if(type==1){
                _this.screen.show();
            }else{
                _this.screen.hide().html('');
            }
        },
        showCamera:function(){
            var _this=this;
            
            _this.play_bd_s.hide();
            _this.play_bd.removeClass('small').show();
            
            _this.js_ppt_title.html('摄像头直播中');
                    
            ppt.hidePPT();
            
            _this.shareing.hide();
            _this.screen.hide().html('');
            
            _this.current=0;
        },
        showWare:function(){
            var _this=this;
            _this.play_bd_s.show();
            _this.play_bd.addClass('small').show();
            
            _this.js_ppt_title.html('图片展示中');
            
            ppt.showPPT();
            
            _this.screen.hide().html('');
            _this.shareing.hide();
            
            _this.current=1;
        },
        showScreen:function(){
            var _this=this;
            
            _this.play_bd_s.hide();
            _this.play_bd.html('').hide();
            _this.js_ppt_title.html('屏幕分享中');
            
            ppt.hidePPT();
            
            _this.shareing.show();
            _this.current=2;
        }
    };
    
    /* 用户列表 */
    var Student={
        liveing:$('.liveing'),
        js_nums:$('#js_usernums'),
        init:function(){
            var _this=this;
        },
        getNums:function(){
            var _this=this;
            $.ajax({
               url:'/student/liveing/getUserListNum',
               type:'POST',
               data:{stream:stream},
               dataType:'json',
               success:function(data){
                   if(data.code==0){
                       handelRes(data);
                       return !1;
                   }
                   var nums=data.data.nums;
                   _this.js_nums.html(nums);
               },
               error:function(e){
                   layer.msg('网络错误');
               }
            });
        },



    };

$(function(){
    Wind.css('layer');
    Wind.use('layer',function(){
        /* 录音控件 */
        if(_DATA.roominfo.livetype != 5 ){
            recorder = new Recorder({
                sampleRate: 44100, //采样频率，默认为44100Hz(标准MP3采样率)
                bitRate: 128, //比特率，默认为128kbps(标准MP3质量)
                success: function(){ //成功回调函数
                    
                },
                error: function(msg){ //失败回调函数
                    //layer.msg(msg);
                    layer.msg("未检测到麦克风相关使用权限");
                    recorder=null;
                },
                fix: function(msg){ //不支持H5录音回调函数
                    layer.msg(msg);
                    recorder=null;
                }
            });
        }
        
        if(_DATA.roominfo.livetype==5){
            // ppt.pptSwiper();
            joinAgora();
            if(_DATA.roominfo.user_type==1){
                /* 讲师检测设备 */
                stratCheck();
            }
        }
    });
    
    
    /* 加入socket */
    nodejsInit();
    
    chat.init();
    ppt.init();
    Live.init();
    Student.init();

    if(_DATA.roominfo.islive==0){
        liveCountdown();
    }
    /* 非PPT */
    if(_DATA.roominfo.livetype!=1 && _DATA.roominfo.livetype!=5){
        $('.ppt_list').show();
        var data_play={
            autoplay: false,
            poster: _DATA.roominfo.thumb,
            autoplayMuted: false,
            ignores: [],
        };
        xgPlay('liveing_play_bd',_DATA.roominfo.pull,data_play);
    }
    
    if(_DATA.roominfo.livetype==1){
        ppt.showPPT();
    }
    
    if(_DATA.roominfo.livemode==1){
        Live.showWare();
    }
    
    if(_DATA.roominfo.livemode==2){
        Live.showScreen();
    }
    
    $(".js_check_ok").click(function(){
        closeCheck();
        preview();
    })
    
    
})

















