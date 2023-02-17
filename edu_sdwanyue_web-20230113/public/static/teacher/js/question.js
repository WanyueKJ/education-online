const Q_select={
    select_bd:$('.question_select'),
    select_close:$('.question_select_close'),
    init:function (__this=null,callback=null){
        let _this=this;
        _this.select_close.on('click',function (){
            layer.closeAll();
        })
        _this.select_bd.on('click','.question_select_btn',function (){
            let _that=$(this);
            let type=_that.data('type');
            layer.closeAll();
            if (typeof callback == 'function') {
                callback.apply(__this, [type]);
            }
        })
    },
    show:function (){
        let _this=this;

        layer.closeAll();
        layer.open({
            type: 1,
            title:false,
            closeBtn:0,
            area: ['400px', '280px'],
            content: _this.select_bd
        });
    }
};

const Question={
    question:$('.question'),
    question_total:$('.question_total'),
    question_class:$('.question_c_l'),
    question_t_close:$('.question_t_close'),
    q_type:$('#q_type'),
    q_name:$('#q_name'),
    q_search:$('.q_search'),
    question_list:$('.question_m_m'),
    checkall:$('#checkall'),
    js_q_add:$('#js_q_add'),
    list_select:{},
    list_select_old:{},
    list:{},
    page:1,
    type:0,  //0课堂测试  1作业
    classid:0,
    keyword:'',
    init:function (__this=null,callback=null,type=0){
        let _this=this;
        _this.type=type;
        _this.question_class.on('click','>ul>li',function (){
            let _that=$(this);
            if(_that.hasClass('on')){
                return !1;
            }
            _that.siblings().removeClass('on');
            _that.addClass('on');
            _this.question_class.find('.q_l_s li').removeClass('on');
        })

        _this.question_class.on('click','.q_l_s li',function (){
            let _that=$(this);
            if(_that.hasClass('on')){
                return !1;
            }
            _that.siblings().removeClass('on');
            _that.addClass('on');
            _this.q_type.val('');
            _this.q_name.val('');
            _this.classid=_that.data('id');
            _this.page=1;
            _this.getList();
        })

        _this.q_type.on('change',function (){
            _this.page=1;
            _this.getList();
        })

        _this.q_search.on('click',function (){
            _this.page=1;
            _this.keyword=_this.q_name.val();
            _this.getList();
        })

        _this.question_list.on('change','input[type=checkbox]',function (){
            let _that=$(this);
            let id=_that.val();

            _this.checkIsAll();
            if (true == _that.is(":checked")) {
                _this.addSelect(id);
            }else{
                _this.delSelect(id);
            }
        })

        _this.checkall.on('change',function (){
            if (true == $(this).is(":checked")) {
                _this.checkAll(1);
            }else{
                _this.checkAll(0);
            }
        })
        _this.js_q_add.on('click',function (){
            if (typeof callback == 'function') {
                callback.apply(__this, [_this.list_select]);
            }
        })

        _this.question_t_close.on('click',function (){
            _this.close();
        })
        //_this.show();
    },
    getClass:function (){
        let _this=this;
        $.ajax({
            url:'/teacher/question/getclass',
            type:'POST',
            data:{},
            dataType:'json',
            error:function (e){

            },
            success:function (data){
                if(data.code==0){
                    layer.msg(data.msg);
                    return !1;
                }
                let list=data.data.list;
                let total=data.data.total;
                let html='';
                for(let i=0;i<list.length;i++){
                    let v=list[i];
                    html+='<li>';
                    html+='<div class="question_c_l_n">'+v.name+'<span>'+v.nums+'</span></div>';
                    html+='<ul class="q_l_s">';
                    for(let m=0;m<v.list.length;m++){
                        let v2=v.list[m];
                        html+='<li data-id="'+v2.id+'">'+v2.name+'<span>'+v2.nums+'</span></li>';
                    }

                    html+='</ul>';
                    html+='</li>';
                }

                _this.question_class.find('ul').html(html);
                _this.question_total.find('span').html(total);
            }
        })
    },
    getList:function (){
        let _this=this;
        let type=_this.q_type.val();

        $.ajax({
            url:'/teacher/question/getQuestion',
            type:'POST',
            data:{classid:_this.classid,type:type,keyword:_this.keyword,page:_this.page},
            dataType:'json',
            error:function (e){

            },
            success:function (data){
                if(data.code==0){
                    layer.msg(data.msg);
                    return !1;
                }
                let list=data.data.list;
                let total=data.data.total;
                let nums=data.data.nums;
                let html='';
                let list2={};
                for(let i=0;i<list.length;i++){
                    let v=list[i];
                    let checked='';
                    if(_this.checkSelect(v.id)){
                        checked='checked';
                    }
                    if(_this.checkSelectOld(v.id)){
                        checked='checked disabled';
                    }else if(_this.type==0 && v.type==3 ){
                        checked='disabled';
                    }else{
                        list2[v.id]=v;
                    }
                    html+='<li class="q_li">\n' +
                        '   <input type="checkbox" name="q_id[]" value="'+v.id+'" id="q_'+v.id+'" '+checked+'>\n' +
                        '   <label for="q_'+v.id+'"></label>\n' +
                        '   <span class="q_li_type">（'+v.type_name+'）</span>\n' +
                        '   <span class="q_li_name" title="'+v.title+'">'+v.title+'</span>\n' +
                        '</li>';

                }

                _this.question_list.find('ul').html(html);

                _this.checkIsAll();
                _this.setPage(total,nums);
                _this.list=list2;
            }
        })
    },
    setPage:function (total,limit){
        let _this=this;
        if(_this.page!=1){
            return !1;
        }
        layui.use('laypage', function(){
            let laypage = layui.laypage;
            //执行一个laypage实例
            laypage.render({
                elem: 'q_page'
                ,count: total
                ,limit: limit
                ,prev: '<'
                ,next: '>'
                ,layout: ['prev','page','next','skip']
                ,jump: function(obj, first){
                    //obj包含了当前分页的所有参数，比如：
                    //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                    //console.log(obj.limit); //得到每页显示的条数
                    //首次不执行
                    if(!first){
                        //do something
                        _this.page=obj.curr;
                        _this.getList();
                    }
                }
            });
        });
    },
    addSelect:function (id){
        let _this=this;
        let list=_this.list;
        let v=list && list[id] || 0;
        if(v==0){
            return !1;
        }
        let add={};
        add[id]=v;

        _this.list_select=$.extend(_this.list_select,add);
    },
    delSelect:function (id){
        let _this=this;
        delete  _this.list_select[id];
    },
    checkSelect:function (id){
        let _this=this;
        let list=_this.list_select;
        let v=list && list[id] || 0;
        if(v!=0){
            return 1;
        }

        return 0;
    },
    checkSelectOld:function (id){
        let _this=this;
        let list=_this.list_select_old;
        let v=list && list[id] || 0;
        if(v!=0){
            return 1;
        }

        return 0;
    },
    checkIsAll:function (){
        let _this=this;
        let obj2=_this.question_list.find('input[type=checkbox]:not(:disabled)');
        if(obj2.length==0){
            _this.checkall.prop('checked',false);
            return !1;
        }
        let obj=_this.question_list.find('input[type=checkbox]:not(:disabled):not(:checked)');
        if(obj.length>0){
            _this.checkall.prop('checked',false);
        }else{
            _this.checkall.prop('checked',true);
        }
    },
    checkAll:function(type=1){
        let _this=this;
        let list=_this.list;
        if(type==1){
            _this.list_select=$.extend(_this.list_select,list);
            _this.question_list.find('input[type=checkbox]:not(:disabled)').prop('checked',true);
        }else{
            for(let k in list){
                delete  _this.list_select[k];
            }
            _this.question_list.find('input[type=checkbox]:not(:disabled)').prop('checked',false);
        }
    },
    getSelect:function (){
        let _this=this;
        return _this.list_select;
    },
    show:function (list_select={}){
        let _this=this;
        _this.list_select_old=list_select;
        _this.list_select={};
        _this.page=1;
        _this.classid=0;
        _this.getClass();
        _this.getList();

        layer.closeAll();
        layer.open({
            type: 1,
            title:false,
            closeBtn:0,
            area: ['800px', '620px'],
            content: _this.question
        });
    },
    close:function (){
        let _this=this;
        _this.list={};
        _this.list_select={};
        _this.list_select_old={};
        layer.closeAll();

    }
};