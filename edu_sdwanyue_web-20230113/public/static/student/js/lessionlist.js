$(function(){

    var currentUrl = window.location.href;
    //搜索处理
    if(currentUrl.indexOf('keywords=')!=-1) {
        vl=decodeURI(currentUrl.slice(currentUrl.indexOf('keywords=')+'keywords='.length));
        $('.middle .ul').hide();
        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        $.ajax({
            url:'/student/Lessionlist/sear',
            type:'POST',
            data:{keywords:vl},
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                swi_tch(data)
                layer.close(index);
            }
        });
        return false;
    }
    var location = '';
    if (currentUrl.indexOf('lessionlist')!=-1) {
        chooselb(99); 
    } else {
        // 首页显示所有分类
        location = 'index';
        chooselb(99);
    }
    //切换学段
    $('.less_top #xd').on('click','.li',function(){
        var that = $(this);

        var isHav = that.hasClass('active');
        var id = that.data('id');
        if(isHav == false){
            $('.less_top #xd .li').removeClass('active');
            $('.less_top #xd .li').find('a').removeClass('white');
            that.addClass('active');
            that.find('a').addClass('white');

            choosexd(id);
        }
    })

    //切换年级
    $('.less_top #nj').on('click','.li',function(){
        var that = $(this);
        var id = $('#lb .active').data('id');
        var isHav = that.hasClass('active');
        $('.less_list ul').html('');
        $(".less_list_nei ul").html('');
        if(isHav == false){
            $('.less_top #nj .li').removeClass('active');
            that.addClass('active');
            chooselb(id);
        }
    })

    //切换类别
    $('.less_top #lb').on('click','.li',function(){
        var that = $(this);

        var isHav = that.hasClass('active');
        var id = that.data('id');
        if(isHav == false){
            $('.less_top #lb .li').removeClass('active');
            $('.less_top #lb .li').find('a').removeClass('white');
            that.addClass('active');
            that.find('a').addClass('white');
            chooselb(id);
        }
    })

    //点击查看更多
    $('.less_list').on('click','.look_more',function(){
        page = page+1;

        $.ajax({
            url:'/student/Lessionlist/getNextList',
            type:'POST',
            data:{njid:njid,kmid:kmid,lbid:lbid,p:page},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

                var lesshtml = replaceHtml(data);

                $('.less_list ul').append(lesshtml);
            }
        });
    })

    function chooselb(id){
        if(location=='index'){
            if (id == 3 ) {
                $(".less_list").show();
                $(".less_list_nei").hide();
                $('.new_list').show();
            } else if(id == 4) {
                $(".less_list").hide();
                $(".less_list_nei").show();
            }else{
                $(".less_list_nei").show();
                $(".less_list").show();
            }
        }else{
            $(".less_list").show();
        }
        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        lbid = id;
        page = 1;
        njid = $('#nj .active').data('id');
        $.ajax({
            url:'/student/Lessionlist/ChooseNj',
            type:'POST',
            data:{njid:njid,lbid:lbid},
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                swi_tch(data)
                layer.close(index);
            }
        });

    }
    function choosexd(id){

        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        xdid = id;
        page = 1;

        $.ajax({
            url:'/student/Lessionlist/GetGrade',
            type:'POST',
            data:{xdid:xdid,lbid:lbid},
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                $('.less_top .right').eq(1).html('');
                var njhtml = '';
                njid = data.data.gradeid;

                for(var i=0;i<data.data.njlist.length;i++){
                    var newclass = '';
                    if(i==0){
                        newclass = 'active';
                    }
                    njhtml+='<div class="li '+newclass+'" data-id="'+data.data.njlist[i].id+'">'+data.data.njlist[i].name+'</div>';
                }
                console.log(njhtml);
                $('.less_top .right').eq(1).html(njhtml);


                swi_tch(data)
                layer.close(index);
            }
        });
    }


    //课程数据替换
    function replaceHtml(data){
        var lesshtml = '';
        for(var i=0;i<data.data.lesslist.length;i++){

            var isB = '';
            if(data.data.lesslist[i].ismaterial == 1){
                isB = '<img src="../../static/student/images/index/book.png"><text class="text2">含教材</text>';
            }

            var isL = '';
            if(data.data.lesslist[i].lesson == '正在直播'){
                isL = '<text class="text1" style="color: #38DAA6;">'+data.data.lesslist[i].lesson+'</text>';
            }else{

                if(data.data.lesslist[i].sort == 0){
                    isL = '<text class="text1" style="border: 1px solid #969696;padding: 0 2px 0 2px;">'+data.data.lesslist[i].lesson+'</text>';
                }else{
                    isL = '<text class="text1">'+data.data.lesslist[i].lesson+'</text>';

                }

            }

            var rt = '';
            var ahref = '';
            if(data.data.lesslist[i].sort == 0){
                rt = '内容';
                ahref = '<a href="/student/detail/substance?id='+data.data.lesslist[i].id+'">';
            }else if(data.data.lesslist[i].sort == 1){
                rt = '课程';
                ahref = '<a href="/student/detail/class?id='+data.data.lesslist[i].id+'">';
            }else{
                rt = '直播';
                ahref = '<a href="/student/detail/live?id='+data.data.lesslist[i].id+'">';
            }

            var payV = '';
            if(data.data.lesslist[i].paytype == 0){
                payV = '<text class="mian">免费</text>';
            }else if(data.data.lesslist[i].paytype == 1){
                payV = '<text class="money">￥'+data.data.lesslist[i].payval+'</text>';
            }else{
                payV = '<text class="mi">密码</text>';
            }


            lesshtml+= '<li>'+ahref+'<div class="content">\
                                        <div class="top" style="background: url('+data.data.lesslist[i].thumb+') no-repeat;background-size: cover;">\
                                            <div class="tip">'+rt+'</div>\
                                        </div>\
                                        <div class="title">'+data.data.lesslist[i].name+'</div>\
                                        <div class="information">'+isL+isB+'</div>\
                                        <div class="bottom">\
                                            <img class="img1" src="'+data.data.lesslist[i].avatar+'">\
                                            <text class="name">'+data.data.lesslist[i].user_nickname+'</text>'+payV+'</div>\
                                    </div>\
                                </a>\
                            </li>';
        }

        return lesshtml;
    }
    //内容选择处理
    function swi_tch(data){
        var dabanData = {
            'data': {
                'lesslist':[]
            }
        };
        var neiData = {
            'data': {
                'lesslist': []
            }
        };
        if(typeof data.data.lesslist !== 'undefined'){
            var less = data.data.lesslist;
            for (let index in less) {
                if (less[index].sort != 0) {
                    //大班课
                    dabanData.data.lesslist.push(less[index]);
                } else {
                    neiData.data.lesslist.push(less[index]);
                }
            }
        }
        if (location == 'index') {
            dabanData.data.lesslist = dabanData.data.lesslist.slice(0, 8);
            neiData.data.lesslist = neiData.data.lesslist.slice(0, 8);
        }
        //直播搜索处理
        if(currentUrl.indexOf('keywords=')!=-1){
            var lessListHtml = replaceHtml(data);
            $(".less_list ul").html(lessListHtml);
            return;
        }

        //首页和选课中心处理
        if(data==null){
            $('.less_list ul').html('');
            $(".less_list_nei ul").html('');
        }
        if(lbid == 3) {
            var lesshtml = replaceHtml(dabanData);
            $('.less_list ul').html(lesshtml);
        }else if(lbid == 99){
            if(location != 'index'){
                var lessListHtml = replaceHtml(data);
                $(".less_list ul").html(lessListHtml);
            }else{
                var lesshtml = replaceHtml(dabanData);
                var lessListHtml = replaceHtml(neiData);
                $('.less_list ul').html(lesshtml);
                $(".less_list_nei ul").html(lessListHtml);
            }

        }else {
            var lessListHtml = replaceHtml(neiData);
            if(location != 'index'){
                $(".less_list ul").html(lessListHtml);
            }else{
                $(".less_list_nei ul").html(lessListHtml);
            }
            
        }
    }
})