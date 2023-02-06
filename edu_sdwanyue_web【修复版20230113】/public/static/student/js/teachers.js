$(function(){   


    //切换科目
    $('.teachers_content .left').on('click','.li',function(){
        var that = $(this);

        var isHav = that.hasClass('on');
        var id = that.data('id');
        var name = that.data('name');
        if(isHav == false){
            page = 1;
            classid = id;
            $('.teachers_content .left .li').removeClass('on');
            that.addClass('on');
            $('.teachers_content .right #classname').html(name);

            $.ajax({
                url:'/student/Teachers/chooseTeachers',
                type:'POST',
                data:{id:id},
                //dataType:'json',
                error:function(e){
                    layer.msg('网路错误');
                },
                success:function(data){
            
                    var html = replaceHtml(data);

                    $('.teachers_content .right .mess').html(html);
                }
            });
        }
    })
    

    //点击查看更多
    $('.teachers_content .right').on('click','.look_more',function(){
        page = page+1;

        $.ajax({
            url:'/student/Teachers/chooseTeachers',
            type:'POST',
            data:{id:classid,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                var html = replaceHtml(data);

                $('.teachers_content .right .mess').append(html);
            }
        });
    })


    //点击关注用户
    $('.teachers_content .right .mess').on('click','.a1',function(){
        var touid = $(this).data('uid');
        var _this = $(this);

        SetAttent(_this,touid);
        
    })


    
    //点击取消用户
    $('.teachers_content .right .mess').on('click','.a1_0',function(){
        var touid = $(this).data('uid');
        var _this = $(this);

        SetAttent(_this,touid);
        

    })

    function SetAttent(_this,touid){

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
                        _this.parent().find('.a2').before('<a class="a1" data-uid="'+touid+'" href="javacript:void(0);">+ 关注</a>');
                        _this.remove();
                    }else{ //关注
                        _this.parent().find('.a2').before('<a class="a1_0" data-uid="'+touid+'" href="javacript:void(0);">已关注</a>');
                        _this.remove();
                    }
                }
            }
        });

  


    }



    //课程数据替换
    function replaceHtml(data){
        var html = '';
        for(var i=0;i<data.data.length;i++){

            var isI = '';

            if(data.data[i]['identityinfos'] != ''){
                isI = '<div class="iden" style="background:'+data.data[i]['identityinfos'][0]['colour']+';">'+data.data[i]['identityinfos'][0]['name']+'</div>';
            }

            var isA = '';
            if(data.data[i].isAttent == 1){
                isA = '<a class="a1_0" data-uid="'+data.data[i].id+'" href="javacript:void(0);">已关注</a>';
            }else{
                isA = '<a class="a1" data-uid="'+data.data[i].id+'" href="javacript:void(0);">+ 关注</a>';
            }
            
            html+= '<div class="li">\
                        <div class="avatar">\
                            <img class="img1" src="'+data.data[i].avatar+'">\
                            <img class="img2" src="../../static/student/images/teachers/v.png">\
                        </div>\
                        <div class="name">'+data.data[i].user_nickname+'</div>\
                        <div class="lskd">'+isI+'</div>\
                        <div class="bottom">'+isA+'<a class="a2" href="/student/teachers/detail?touid='+data.data[i].id+'">进入主页</a>\
                        </div>\
                    </div>';
        }


        $('.look_more').remove();
        if(data.data.length >=20){
            $('.teachers_content .right').append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
        }


        return html;
    }


    /***********教师详情页面 */

    //点击切换介绍
    $('.detail_teacher .bcontent .nav .js').click(function(){
        $('.detail_teacher .bcontent .nav .bhkc').removeClass('b_fff');
        $('.detail_teacher .bcontent .nav .bhkc').addClass('b_f6f6');

        $(this).removeClass('b_f6f6');
        $(this).addClass('b_fff');

        
        $('.detail_teacher .bcontent .info').removeClass('none');
        $('.detail_teacher .bcontent .classlist').addClass('none');

    })

    $('.detail_teacher .bcontent .nav .bhkc').click(function(){
        $('.detail_teacher .bcontent .nav .js').removeClass('b_fff');
        $('.detail_teacher .bcontent .nav .js').addClass('b_f6f6');

        $(this).removeClass('b_f6f6');
        $(this).addClass('b_fff');

        
        $('.detail_teacher .bcontent .info').addClass('none');
        $('.detail_teacher .bcontent .classlist').removeClass('none');

    })

    //老师页面点击取消、关注用户
    $('.detail_teacher .top').on('click','.attented',function(){
        var _this = $(this);
        SetAttentDet(_this);
        
    })


    function SetAttentDet(_this){

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
    $('.detail_teacher').on('click','.look_more',function(){
        page = page+1;
        $.ajax({
            url:__SITEURL__+'/api/?s=Course.GetTeacherCourse',
            type:'GET',
            data:{uid:userinfoj.id,token:userinfoj.token,touid:touid,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
        
                var list = data.data.info;
                var html = '';

                for(var i=0;i<list.length;i++){

                    var text = '';
                    if(list[i].sort == 0){
                        text = '内容';
                    }else if(list[i].sort == 1){
                        text = '课程';
                    }else{
                        text = '直播';
                    }

                    var ismate = '';
                    if(list[i].ismaterial ==1){
                        ismate = '<text class="text2"><img class="book" src="../../static/student/images/index/book.png"><text class="tips">含教材</text></text>';
                    }

                    var typetext = '';
                    if(list[i].paytype ==0){
                        typetext = '<text class="mian">免费</text>';
                    }else if(list[i].paytype ==1){
                        typetext = '<text class="money">￥'+list[i].payval+'</text>';
                    }else{
                        typetext = '<text class="mi">密码</text>';
                    }
                    html+='<div class="li" data-courseid="'+list[i].id+'" data-sort="'+list[i].sort+'">\
                            <div class="thumbdiv" style="background: url('+list[i].thumb+') no-repeat;background-size: cover;">\
                                <div class="tips">'+text+'</div>\
                            </div>\
                            <div class="mess">\
                                <div class="des">'+list[i].name+'</div>\
                                <div class="msg">\
                                    <text class="text1">'+list[i].lesson+'</text>'+ismate+'</div>\
                                <div class="bottom">\
                                    <img class="img1" src="'+list[i].avatar+'">\
                                    <text class="name">'+list[i].user_nickname+'</text>'+typetext+'</div>\
                            </div>\
                        </div>';
                }


                $('.detail_teacher .classlist').append(html);

                $('.look_more').remove();
                if(list.length >=20){
                    $('.detail_teacher .classlist').append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
                }
            }
        });
    })


    //点击查看课程详情
    $('.detail_teacher .bcontent .classlist').on('click','.li',function(){

        var courseid = $(this).data('courseid');
        var sort = $(this).data('sort');

        if(sort == 0){ //内容
            location.href="/student/Detail/substance?id="+courseid;
        }else if(sort == 1){ //课程
            location.href="/student/Detail/class?id="+courseid;
        }else{
            location.href="/student/Detail/live?id="+courseid;
        }
    })

    
})