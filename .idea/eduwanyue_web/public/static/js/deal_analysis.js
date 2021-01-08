(function () {

    /* 统计框和图表区域 */
    var current_range = $("#range-input").val();
    //更新x轴时间单位
    getEchartsData(current_range);//获取并填充数据


    $("#range-input").change(function () {
        var range = this.value;
        /* 更新echarts数据*/
        getEchartsData(range); //获取并填充数据

    });





    /**
     * 填充echarts统计 交易构成
     * @param data echarts数据
     */
    function dealEcharts(data) {
        // 基于准备好的dom，初始化echarts实例
        var echarts_basic = echarts.init(document.getElementById('echarts_basic'));
        // 指定图表的配置项和数据
        var echarts_basic_option = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                top: 40,
                right: 120,
                data: ['老付费用户', '新付费用户']
            },
            color: ['#507CFA', '#FCDA1B'],
            series: [
                {
                    name: '',
                    type: 'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        {value: data['old_price'], name: '老付费用户'},
                        {value: data['new_price'], name: '新付费用户'},
                    ]
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        echarts_basic.setOption(echarts_basic_option);

    }


    /**
     * 填充右侧表格
     * @param data 右侧表格数据
     */
    function rightTable(table) {
        //             结算金额(元)	较前一月涨幅	  用户数	        较前一月涨幅
        // 老付费用户	$oldPrice	$oldMoneyInc  $oldPayNum	    $oldPayNumInc
        $(".user-num-all").text(table['old_price']);
        $(".ratio-all").text(table['old_money_inc']);
        $(".unit-price-all").text(table['old_pay_num']);
        $(".settlement-rate-all").text(table['old_pay_num_inc']);

        // 新付费用户	$newPrice	$newMoneyInc  $newPayNum	    $newPayNumInc
        $(".user-num-new").text(table['new_price']);
        $(".ratio-new").text(table['new_money_inc']);
        $(".unit-price-new").text(table['new_pay_num']);
        $(".settlement-rate-new").text(table['new_pay_num_inc']);

    }



    /**
     * 填充echarts统计 订单金额分布
     * @param data echarts数据
     */
    function tradeNoEcharts(data) {
        // 基于准备好的dom，初始化echarts实例
        var echarts_trade = echarts.init(document.getElementById('echarts_trade_no'));
        // 指定图表的配置项和数据
        var echarts_trade_option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['订单数'],
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
                data: ['0-50', '50-100', '100-200', '200-500', '500-1000', '1000-1500', '大于1500']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: '订单数',
                    type: 'line',
                    stack: '总量',
                    data: data
                },

            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        echarts_trade.setOption(echarts_trade_option);


    }




    /**
     * 获取填充到交易构成和订单金额分布echarts表的数据
     * @param range
     */
    function getEchartsData(range) {
        console.log(3333);
        $.ajax({
            url: '/admin/Main/getDealEcharts',
            type: 'POST',
            data: {range: range},
            cache:false,
            dataType: 'json',
            success: function (data) {

                //填充交易构成数据(饼状图)
                dealEcharts(data['pancake']);
                //填充右侧表格
                rightTable(data['table']);
                //填充订单金额分布
                tradeNoEcharts(data['trade_no']);
            },
            error: function () {

            }
        })
    }





})()