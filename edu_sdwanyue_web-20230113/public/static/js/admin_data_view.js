(function(){

    // 数据概况echarts图表

    /* 统计框和图表区域 */
    var current_range = $("#range-yun-input").val();
    updateYunData(current_range); //下方运营概况展示框数据

    //更新x轴时间单位
    $.ajax({
        type: "POST",
        url: "getlastDate",
        data: {date: current_range},
        success:function (data){
            getEchartsData(data); //获取并填充数据
        }
    })


    layui.use(['layer', 'laydate'], function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#range-yun-input'
            ,done: function(value, date, endDate){
                var range = value;
               // 切换日期, 调用接口更新下方运营概况展示框数据
                updateYunData(range);

                /* 更新echarts数据*/
                $.ajax({
                    type: "POST",
                    url: "getlastDate",
                    data: {date: range},
                    success:function (data){
                        getEchartsData(data); //获取并填充数据
                    }
                })
            }
          });

    })


    /**
     * 更新下方运营概况展示框数据(方框区域)
     * @param range
     */
    function updateYunData(range) {

        $.ajax({
            type: "POST",
            url:"/admin/Main/getYunSurveyData",
            data:{range:range},
            success:function(data){
                var ret = JSON.parse(data);

                var settle = ret['settle'];
                /************结算金额*****************/
                $(".settle-money").text(settle['settle_money']);

                //判断大还是小, 显示对应箭头图标
                var seDayIcon = $(".settle-day-ratio-icon");

                // 日环比
                $(".settle-day-new-ratio").text(settle['day_ratio']);
                if ((settle['day_flag'] != undefined) &&
                    (settle['day_flag'] == 'big')) {

                    if (seDayIcon.hasClass('icon-triangle-bottom')) {
                        seDayIcon.removeClass('icon-triangle-bottom');
                    }
                    seDayIcon.addClass('icon-triangle-top');

                } else {
                    if (seDayIcon.hasClass('icon-triangle-top')) {
                        seDayIcon.removeClass('icon-triangle-top');
                    }
                    seDayIcon.addClass('icon-triangle-bottom');
                }

                // 周环比
                $(".settle-week-new-ratio").text(settle['week_ratio']);

                var seWeekIcon = $(".settle-week-ratio-icon");

                if ((settle['week_flag'] != undefined) &&
                    (settle['week_flag'] == 'big')) {

                    if (seWeekIcon.hasClass('icon-triangle-bottom')) {
                        seWeekIcon.removeClass('icon-triangle-bottom');
                    }
                    seWeekIcon.addClass('icon-triangle-top');

                } else {
                    if (seWeekIcon.hasClass('icon-triangle-top')) {
                        seWeekIcon.removeClass('icon-triangle-top');
                    }
                    seWeekIcon.addClass('icon-triangle-bottom');
                }


                /**************** 访客数 ***************/
                var visit = ret['visit'];
                $(".new-visit").text(visit['today_visit_num']);

                //日环比
                $(".new-visit-day-num").text(visit['day_ratio']);

                var viDayIcon = $(".visit-day-icon");

                if ((visit['day_flag'] != undefined) &&
                    (visit['day_flag'] == 'big')) {

                    if (viDayIcon.hasClass('icon-triangle-bottom')) {
                        viDayIcon.removeClass('icon-triangle-bottom');
                    }
                    viDayIcon.addClass('icon-triangle-top');

                } else {
                    if (viDayIcon.hasClass('icon-triangle-top')) {
                        viDayIcon.removeClass('icon-triangle-top');
                    }
                    viDayIcon.addClass('icon-triangle-bottom');
                }


                //周环比
                $(".new-visit-week-num").text(visit['week_ratio']);

                var viWeekIcon = $(".visit-week-icon");

                if ((visit['week_flag'] != undefined) &&
                    (visit['week_flag'] == 'big')) {

                    if (viWeekIcon.hasClass('icon-triangle-bottom')) {
                        viWeekIcon.removeClass('icon-triangle-bottom');
                    }
                    viWeekIcon.addClass('icon-triangle-top');

                } else {
                    if (viWeekIcon.hasClass('icon-triangle-top')) {
                        viWeekIcon.removeClass('icon-triangle-top');
                    }
                    viWeekIcon.addClass('icon-triangle-bottom');
                }


                /****************** 支付转化率 *********************/
                if (settle['settle_money'] != 0) {
                    //结算金额不为0 则转化率100% 没有打折
                    $(".new-zhuan-ratio").text("100%");
                    $(".new-zhuan-day-ratio").text("100%");
                    $(".new-zhuan-week-ratio").text("100%");
                }


                /****************** 客单价 ********************/
                var unit = ret['user_unit_price'];
                $(".unit-price").text(unit['unit_price']);

                //日环比
                $(".unit-day-ratio").text(unit['day_ratio']);

                var unDayIcon = $(".unit-day-icon");

                if ((unit['day_flag'] != undefined) &&
                    (unit['day_flag'] == 'big')) {

                    if (unDayIcon.hasClass('icon-triangle-bottom')) {
                        unDayIcon.removeClass('icon-triangle-bottom');
                    }
                    unDayIcon.addClass('icon-triangle-top');

                } else {
                    if (unDayIcon.hasClass('icon-triangle-top')) {
                        unDayIcon.removeClass('icon-triangle-top');
                    }
                    unDayIcon.addClass('icon-triangle-bottom');
                }


                //周环比
                $(".unit-week-ratio").text(unit['week_ratio']);

                var unWeekIcon = $(".unit-week-icon");

                if ((unit['week_flag'] != undefined) &&
                    (unit['week_flag'] == 'big')) {

                    if (unWeekIcon.hasClass('icon-triangle-bottom')) {
                        unWeekIcon.removeClass('icon-triangle-bottom');
                    }
                    unWeekIcon.addClass('icon-triangle-top');

                } else {
                    if (unWeekIcon.hasClass('icon-triangle-top')) {
                        unWeekIcon.removeClass('icon-triangle-top');
                    }
                    unWeekIcon.addClass('icon-triangle-bottom');
                }



                /*********** 付费用户数 ************/
                var pay_user = ret['pay_user'];
                $(".new-pay-all").text(pay_user['pay_user_num']);

                //日环比
                $(".new-pay-day-ratio").text(unit['day_ratio']);

                var payDayIcon = $(".new-pay-icon");

                if ((pay_user['day_flag'] != undefined) &&
                    (pay_user['day_flag'] == 'big')) {

                    if (payDayIcon.hasClass('icon-triangle-bottom')) {
                        payDayIcon.removeClass('icon-triangle-bottom');
                    }
                    payDayIcon.addClass('icon-triangle-top');

                } else {
                    if (payDayIcon.hasClass('icon-triangle-top')) {
                        payDayIcon.removeClass('icon-triangle-top');
                    }
                    payDayIcon.addClass('icon-triangle-bottom');
                }


                //周环比
                $(".new-pay-week-ratio").text(pay_user['week_ratio']);

                var payWeekIcon = $(".new-pay-week-icon");

                if ((pay_user['week_flag'] != undefined) &&
                    (pay_user['week_flag'] == 'big')) {

                    if (payWeekIcon.hasClass('icon-triangle-bottom')) {
                        payWeekIcon.removeClass('icon-triangle-bottom');
                    }
                    payWeekIcon.addClass('icon-triangle-top');

                } else {
                    if (payWeekIcon.hasClass('icon-triangle-top')) {
                        payWeekIcon.removeClass('icon-triangle-top');
                    }
                    payWeekIcon.addClass('icon-triangle-bottom');
                }




            },
            error:function(res){

            }

        });

    }


    /**更新时间x轴数组
     * @param range 日期 年月日
     * @returns {Array}
     */
    function getTimeX(range) {

        var date = new Date(range);

        if (date.getMonth <= 1) {
            date.setMonth(12);
            date.setFullYear(date.getFullYear() - 1);
        } else {
            date.setMonth(date.getMonth()-1);
        }

        var y = date.getFullYear(),
            m = date.getMonth()+1,
            d = date.getDate(),
            formatwdate = y+'-'+m+'-'+d;

        // 2月份只有28天
        var month_31 = ['1', '3', '5', '7', '8', '10', '12'],  //31天的月
            month_30 = ['4', '6', '9', '11']; //30天的月

        var arr = [];
        arr.push(formatwdate); //初始日期

        var m_str = m.toString();

        if (month_30.includes(m_str)) {

            var flag_one = 27,
                flag_two = 30,
                flag_three = 29,
                flag_four = 28;

        }
        else if (month_31.includes(m_str)) {

            var flag_one = 28,
                flag_two = 31,
                flag_three = 30,
                flag_four = 29;

        } else if (m_str == '2') {

            var flag_one = 25,
                flag_two = 28,
                flag_three = 27,
                flag_four = 26;

        } else {
            return;
        }

        var current_wdate;

        while (d <= flag_one) {
            d += 3;
            current_wdate = y+'-'+m+'-'+d;
            arr.push(current_wdate);
        }

        if (arr.length < 10) { //转入下一个月

            //d = 30 下一个day应该是3, d = 29 下一个day应该是2, d = 28 下一个day应该是1
            if (d == flag_two) {
                d = 0;
            } else if (d == flag_three) {
                d = -1;
            } else if (d == flag_four) {
                d = -2;
            } else {
                d = 0;
            }

            m++;
            while (d <= flag_one && arr.length < 10) {
                d += 3;
                current_wdate = y+'-'+m+'-'+d;
                arr.push(current_wdate);
            }

        }

        return arr;

    }


    /**
     * 填充echarts统计 实时概况
     * @param arr_x
     * @param data echarts数据
     */
    function userEchars(arr_x, data) {

        // 基于准备好的dom，初始化echarts实例
        var echarts_basic = echarts.init(document.getElementById('echarts_basic'));
        // 指定图表的配置项和数据
        var echarts_basic_option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['今日收入', '昨日收入'],
                right: "20"
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                //范围最近1个月, 单位每3天一个
                data: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
                    '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: '今日收入',
                    type: 'line',
                    stack: '总量',
                    data: JSON.parse(today_pay_num)
                },
                {
                    name: '昨日收入',
                    type: 'line',
                    stack: '总量',
                    data: JSON.parse(ye_pay_num)
                },

            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        echarts_basic.setOption(echarts_basic_option);

    }


    /**
     * 填充echarts统计 指标趋势
     * @param arr_x
     * @param data echarts数据
     */
    function targetEchars(arr_x, data) {
        // 基于准备好的dom，初始化echarts实例
        var echarts_basic = echarts.init(document.getElementById('echarts_target'));
        // 指定图表的配置项和数据
        var echarts_basic_option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['结算金额(元)'],
                right: "20"
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                //范围最近1个月, 单位每3天一个
                data: arr_x
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: '结算金额(元)',
                    type: 'line',
                    stack: '总量',
                    data: data['settle']
                }

            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        echarts_basic.setOption(echarts_basic_option);

    }



    /**
     * 获取填充到echarts表的数据
     * @param arr_x
     */
    function getEchartsData(arr_x){
        $.ajax({
            url:'/admin/Main/getDataViewEcharts',
            type:'POST',
            data:{arr_x:arr_x},
            dataType:'json',
            success:function(data){
                console.log(data);
                //填充数据
                // (实时概况 临时占位)
                userEchars(arr_x, []);
                //指标趋势
                targetEchars(arr_x, data);
            },
            error:function(){

            }
        })
    }














})()