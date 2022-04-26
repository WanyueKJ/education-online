(function () {

    /* 统计框和图表区域 */
    var current_range = $("#range-input").val();
    updateUserData(current_range); //展示框数据

    //更新x轴时间单位
    $.ajax({
        type: "POST",
        url: "getlastDate",
        data: {date: current_range},
        success:function (data){
            getEchartsData(data); //获取并填充数据
        }
    })

    //获取付费用户表格数据
    getPayTableData(current_range);


    layui.use(['layer', 'laydate'], function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#range-input'
            ,done: function(value, date, endDate){
                var range = value;
                // 切换日期, 调用接口更新展示框数据
                updateUserData(range);

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

          laydate.render({
            elem: '#range-pay-input'
            ,done: function(value, date, endDate){
                var pay_range = value;
                //获取付费用户表格数据

                getPayTableData(pay_range);
            }
          });
    })

    /**
     * 更新用户分析数据(方框区域)
     * @param range
     */
    function updateUserData(range) {

        $.ajax({
            type: "POST",
            url: "userDataAnalyse",
            data: {range: range},
            success: function (data) {
                var ret = JSON.parse(data);

                var day_reg = ret['day_reg'];
                //新增用户
                $(".new-reg").text(parseInt(day_reg['day_reg_num']));
                $(".new-ratio").text(parseInt(day_reg['day_ratio']));
                //判断大还是小, 显示对应箭头图标
                var newRatioIcon = $(".new-ratio-icon");

                if ((day_reg['reg_flag'] != undefined) &&
                    (day_reg['reg_flag'].toString() === 'big')) {

                    if (newRatioIcon.hasClass('icon-triangle-bottom')) {
                        newRatioIcon.removeClass('icon-triangle-bottom');
                    }
                    newRatioIcon.addClass('icon-triangle-top');

                } else {
                    if (newRatioIcon.hasClass('icon-triangle-top')) {
                        newRatioIcon.removeClass('icon-triangle-top');
                    }
                    newRatioIcon.addClass('icon-triangle-bottom');
                }


                //活跃用户
                var active_name = ret['active_name'];
                $(".new-active").text(parseInt(active_name['active_name_num']));
                $(".new-active-ratio").text(parseInt(active_name['active_ratio']));
                //判断
                if ((active_name['active_flag'] != undefined) &&
                    (active_name['active_flag'].toString() === 'big')) {
                    $(".new-ratio-icon-active").addClass('icon-triangle-top');
                } else {
                    $(".new-ratio-icon-active").addClass('icon-triangle-bottom');
                }


                //累计用户数量
                var user_all = ret['user_all'];
                $(".new-all").text(parseInt(user_all['user_all_num']));
                $(".new-all-ratio").text(parseInt(user_all['user_all_ratio']));
                //判断
                if ((user_all['user_all_flag'] != undefined) &&
                    (user_all['user_all_flag'].toString() === 'big')) {
                    $(".new-ratio-icon-all").addClass('icon-triangle-top');
                } else {
                    $(".new-ratio-icon-all").addClass('icon-triangle-bottom');
                }

                //当天付费用户数量
                var day_pay = ret['day_pay'];
                $(".new-pay").text(parseInt(day_pay['day_pay_num']));
                $(".new-pay-ratio").text(parseInt(day_pay['pay_ratio']));
                //判断
                if ((day_pay['day_pay_flag'] != undefined) &&
                    (day_pay['day_pay_flag'].toString() === 'big')) {
                    $(".new-ratio-icon-pay").addClass('icon-triangle-top');
                } else {
                    $(".new-ratio-icon-pay").addClass('icon-triangle-bottom');
                }

                //累计付费用户数量
                var pay_all = ret['pay_all'];
                $(".new-pay-all").text(parseInt(pay_all['pay_all_num']));
                $(".new-pay-all-ratio").text(parseInt(pay_all['pay_all_ratio']));
                if ((pay_all['pay_all_flag'] != undefined) &&
                    (pay_all['pay_all_flag'].toString() === 'big')) {
                    $(".new-ratio-icon-pay-all").addClass('icon-triangle-top');
                } else {
                    $(".new-ratio-icon-pay-all").addClass('icon-triangle-bottom');
                }

            },
            error: function (res) {

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
            date.setMonth(date.getMonth() - 1);
        }

        var y = date.getFullYear(),
            m = date.getMonth() + 1,
            d = date.getDate(),
            formatwdate = y + '-' + m + '-' + d;

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

        } else if (month_31.includes(m_str)) {

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
            current_wdate = y + '-' + m + '-' + d;
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
                current_wdate = y + '-' + m + '-' + d;
                arr.push(current_wdate);
            }

        }

        return arr;

    }


    /**
     * 填充echarts统计 用户分析
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
                data: ['新增用户', '活跃用户', '累计用户', '付费用户', '累计付费用户'],
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
                    name: '新增用户',
                    type: 'line',
                    stack: '总量',
                    data: data['reg_num']
                },
                {
                    name: '活跃用户',
                    type: 'line',
                    stack: '总量',
                    data: data['active_num']
                },
                {
                    name: '累计用户',
                    type: 'line',
                    stack: '总量',
                    data: data['all_num']
                },
                {
                    name: '付费用户',
                    type: 'line',
                    stack: '总量',
                    data: data['pay_num']
                },
                {
                    name: '累计付费用户',
                    type: 'line',
                    stack: '总量',
                    data: data['pay_all_num']
                }

            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        echarts_basic.setOption(echarts_basic_option);

    }


    /**
     * 获取填充到用户分析echarts表的数据
     * @param arr_x
     */
    function getEchartsData(arr_x) {
        $.ajax({
            url: '/admin/Main/getUserEcharts',
            type: 'POST',
            data: {arr_x: arr_x},
            dataType: 'json',
            success: function (data) {
                //填充数据
                userEchars(arr_x, data);
            },
            error: function () {

            }
        })
    }


    /**
     * 获取付费用户表格数据
     * @param range 时间
     */
    function getPayTableData(range) {

        $.ajax({
            type: "POST",
            url: "/admin/Main/getPayTableData",
            data: {range: range},
            success: function (data) {
                var ret = JSON.parse(data);
                //全部成交用户
                $(".user-num-all").text(parseInt(ret['all']['user_num']));
                $(".ratio-all").text(parseInt(ret['all']['ratio']));
                $(".unit-price-all").text(parseInt(ret['all']['unit_price']));
                $(".settlement-all").text(parseInt(ret['all']['settlement']));
                $(".settlement-rate-all").text(parseInt(ret['all']['settlement_rate']));

                //新付费用户
                $(".user-num-new").text(parseInt(ret['new']['user_num']));
                $(".ratio-new").text(parseInt(ret['new']['ratio']));
                $(".unit-price-new").text(parseInt(ret['new']['unit_price']));
                $(".settlement-new").text(parseInt(ret['new']['settlement']));
                $(".settlement-rate-new").text(parseInt(ret['new']['settlement_rate']));

                //老付费用户
                $(".user-num-old").text(parseInt(ret['old']['user_num']));
                $(".ratio-old").text(parseInt(ret['old']['ratio']));
                $(".unit-price-old").text(parseInt(ret['old']['unit_price']));
                $(".settlement-old").text(parseInt(ret['old']['settlement']));
                $(".settlement-rate-old").text(parseInt(ret['old']['settlement_rate']));

            },
            error: function (res) {

            }

        });

    }

    window.onresize = () => {
        let barchart = echarts.getInstanceByDom(document.getElementById('echarts_basic'));
        barchart.resize();
    }
})()