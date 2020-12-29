 $(function(){

    var currentUrl = window.location.href;
    var urlArr = currentUrl.split('.com');

    var flag = 0;
    var location = '';
     for (let idx in urlArr) {
         if (urlArr[idx].indexOf("neirong") != '-1')  {
             flag++;
         }
     }
     if (flag > 0) {

         $('.zhibo-info').removeClass('active');
         $('.zhibo-info').find('a').removeClass('white');
         $('.neirong-info').addClass('active');
         $('.neirong-info').find('a').addClass('white');
         chooselb(4);
     } else if (urlArr.length > 1 && (urlArr[1] != "/") && (urlArr[1] != "?")) {
        chooselb(3); //选课中心默认显示直播分类
    } else {
        // 首页显示所有分类
        chooselb(99);
        location = 'index';
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

        var isHav = that.hasClass('active');
        var id = that.data('id');
        if(isHav == false){
            $('.less_top #nj .li').removeClass('active');
            $('.less_top #nj .li').find('a').removeClass('white');
            that.addClass('active');
            that.find('a').addClass('white');

            choosenj(id);
        }
    })

    //切换科目
    $('.less_top #km').on('click','.li',function(){
        var that = $(this);

        var isHav = that.hasClass('active');
        var id = that.data('id');
        if(isHav == false){
            $('.less_top #km .li').removeClass('active');
            $('.less_top #km .li').find('a').removeClass('white');
            that.addClass('active');
            that.find('a').addClass('white');

            choosekm(id);
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

    function choosexd(id){

        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        xdid = id;
        page = 1;

        $.ajax({
            url:'/student/Lessionlist/GetGrade',
            type:'POST',
            data:{xdid:xdid,kmid:kmid,lbid:lbid},
            //dataType:'json',
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

                $('.less_top .right').eq(1).html(njhtml);


                var lesshtml = replaceHtml(data);

                $('.less_list ul').html(lesshtml);
                layer.close(index);
            }
        });
    }


    function choosenj(id){
        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        njid = id;
        page = 1;

        $.ajax({
            url:'/student/Lessionlist/ChooseNj',
            type:'POST',
            data:{njid:njid,kmid:kmid,lbid:lbid},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                var lesshtml = replaceHtml(data);
                $('.less_list ul').html(lesshtml);
                layer.close(index);
            }
        });
    }


    function choosekm(id){

        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        kmid = id;
        page = 1;


        $.ajax({
            url:'/student/Lessionlist/ChooseNj',
            type:'POST',
            data:{njid:njid,kmid:kmid,lbid:lbid},
            //dataType:'json',
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){
                var lesshtml = replaceHtml(data);
                $('.less_list ul').html(lesshtml);
                layer.close(index);
            }
        });


    }

    function chooselb(id){

         //只在首页隐藏显示
        if (location == 'index') {
            if (id == 3) {
                $(".less_list_nei").hide();
                $(".less_list").show();
            } else if(id == 4) {
                $(".less_list").hide();
                $(".less_list_nei").show();
            } else if(id == 99) {
                $(".less_list").show();
                $(".less_list_nei").show();
            }
        }

        var index = layer.load(1, {
            shade: [0.3,'#000'] //0.1透明度的白色背景
        });
        lbid = id;
        page = 1;

        $.ajax({
            url:'/student/Lessionlist/ChooseNj',
            type:'POST',
            data:{njid:njid,kmid:kmid,lbid:lbid},
            error:function(e){
                layer.msg('网路错误');
            },
            success:function(data){

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
                var less = data.data.lesslist;
                for (let index in less) {
                    if (less[index].sort != 0) {
                        //大班课
                        dabanData.data.lesslist.push(less[index]);
                    } else {
                        neiData.data.lesslist.push(less[index]);
                    }
                }

                if (location == 'index') {
                    dabanData.data.lesslist = dabanData.data.lesslist.slice(0, 8);
                    neiData.data.lesslist = neiData.data.lesslist.slice(0, 8);
                }
                var lesshtml = replaceHtml(dabanData);
                $('.less_list ul').html(lesshtml);

                var lessListHtml = replaceHtml(neiData);
                if (flag > 0) {
                    $(".less_list ul").html(lessListHtml);
                } else {
                    $(".less_list_nei ul").html(lessListHtml);
                }

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


        $('.look_more').remove();
        if(data.data.lesslist.length >=20){
            $('.less_list').append('<div class="look_more"><a style="color:#9e9a9a" href="javacript:void(0);">查看更多...</a></div>');
        }


        return lesshtml;
    }

})