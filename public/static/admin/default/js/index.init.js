/**
 * 系统更新异步请求步骤
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @param   {[string]}        url   [url地址]
 * @param   {[string]}        opt   [操作类型（url 获取下载地址， download_system 下载系统包， download_upgrade 下载升级包， upgrade 更新操作）]
 * @param   {[string]}        msg   [提示信息]
 */
function SystemUpgradeRequestHandle (params) {
    // 参数处理
    if ((params || null) == null) {
        Prompt(window['lang_operate_params_error'] || '操作参数有误');
        return false;
    }
    var url = params.url || null;
    var opt = params.opt || 'url';
    var msg = params.msg || window['lang_get_loading_tips'] || '正在获取中...';

    // 加载提示
    AMUI.dialog.loading({ title: msg });

    // ajax
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: 305000,
        data: { "opt": opt },
        success: function (result) {
            if ((result || null) != null && result.code == 0) {
                switch (opt) {
                    // 获取下载地址
                    case 'url':
                        params['opt'] = 'download_system';
                        params['msg'] = window['lang_system_download_loading_tips'] || '系统包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载系统包
                    case 'download_system':
                        params['opt'] = 'download_upgrade';
                        params['msg'] = window['lang_upgrade_download_loading_tips'] || '升级包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载升级包
                    case 'download_upgrade':
                        params['opt'] = 'upgrade';
                        params['msg'] = window['lang_update_loading_tips'] || '正在更新中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 更新完成
                    case 'upgrade':
                        Prompt(result.msg, 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                        break;
                }
            } else {
                AMUI.dialog.loading('close');
                Prompt(((result || null) == null) ? (window['lang_error_text'] || '异常错误') : (result.msg || (window['lang_error_text'] || '异常错误')));
            }
        },
        error: function (xhr, type) {
            AMUI.dialog.loading('close');
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'));
        }
    });
}

/**
 * 基础数据总数
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        data      [数据]
 */
function EchartsBaseCount (data) {
    $('.base-user-count').text(data.user_count);
    $('.base-order-count').text(data.order_count);
    $('.base-order-sale-count').text(data.order_sale_count);
    $('.base-order-complete-total').text(data.order_complete_total);
}

/**
 * echarts 样式配置
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        data      [数据]
 * @param   {radius}        data      [圆角，默认顶部：top，可设置上top下down左left右right]
 */
function EchartsStyle (data, radius) {
    var gradient_color = [
        ['#71E7E9', '#2FC7C9'],
        ['#C8AFF8', '#9A77DD'],
        ['#1CBEF6', '#4378FF'],
        ['#FFD2AE', '#F7A05A'],
        ['#FAAAB0', '#E35E67'],
        ['#A9C0F5', '#5C7ECB'],
        ['#F9F2B0', '#E5CF0C'],
        ['#DFF3B1', '#97B552']
    ]
    var borderRadius = [40, 40, 0, 0];
    if (radius && radius.length > 0) {
        borderRadius = radius
    }

    var new_data = data.map(function (item, index) {
        if((gradient_color[index] || null) != null) {
            var item_style = {
                itemStyle: {
                    borderRadius: borderRadius,  // 设置柱状图边框的圆角大小，单位为像素
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
            }
            return Object.assign({}, item, item_style);
        }
        return item;
    });
    return new_data;
};
/**
 * 订单成交金额走势
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        title_arr [标题]
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsOrderProfit (title_arr, name_arr, data) {
    var new_data = EchartsStyle(data);
    var chart = echarts.init(document.getElementById('echarts-order-profit'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data: title_arr,
            left: '0%'
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack', 'tiled'] },
                restore: { show: true },
                saveAsImage: { name: window['lang_order_transaction_amount_name'] || '订单成交金额走势', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [
            {
                type: 'category',
                axisLine: {
                    show: true, // 显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                },
                boundaryGap: false,
                data: name_arr
            }
        ],
        yAxis: [
            {
                type: 'value',
                splitArea: { //是否显示echarts背景分隔区域
                    show: false
                },
                axisLine: {
                    show: false,// 不显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                }
            }
        ],
        series: new_data
    };
    chart.setOption(option);
    return chart;
}

/**
 * 订单交易走势
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        title_arr [标题]
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsOrderTrading (title_arr, name_arr, data) {
    var new_data = EchartsStyle(data);
    var chart = echarts.init(document.getElementById('echarts-order-trading'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data: title_arr,
            left: '0%'
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack', 'tiled'] },
                restore: { show: true },
                saveAsImage: { name: window['lang_order_trading_trend_name'] || '订单交易走势', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [
            {
                type: 'category',
                axisLine: {
                    show: true, // 显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                },
                boundaryGap: false,
                data: name_arr
            }
        ],
        yAxis: [
            {
                type: 'value',
                splitArea: { //是否显示echarts背景分隔区域
                    show: false
                },
                axisLine: {
                    show: false,// 不显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                }
            }
        ],
        series: new_data
    };
    chart.setOption(option);
    return chart;
}

/**
 * 热销商品
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        title_arr [标题]
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsGoodsHot (data) {
    var chart = echarts.init(document.getElementById('echarts-goods-hot'), 'macarons');
    var option = {
        title: {
            subtext: window['lang_goods_hot_tips'] || '仅显示前13条商品',
            x: 'center',
            bottom: '3%'
        },
        legend: {
            orient: 'vertical',
            left: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} : {c} ({d}%)"
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore: { show: false },
                saveAsImage: { name: window['lang_goods_hot_name'] || '热销商品', show: true }
            }
        },
        calculable: true,
        series: [
            {
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                itemStyle: {
                  borderRadius: 4,
                  borderColor: '#fff',
                  borderWidth: 1
                },
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: 12,
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: data
            }
        ]
    };
    chart.setOption(option);
    return chart;
}

/**
 * 支付方式
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        title_arr [标题]
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsPayType (title_arr, name_arr, data) {
    var new_data = EchartsStyle(data);
    var chart = echarts.init(document.getElementById('echarts-pay-type'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: title_arr,
            left: '0%'
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: false },
                saveAsImage: { name: window['lang_payment_name'] || '支付方式', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        calculable: true,
        xAxis: [
            {
                type: 'category',
                axisLine: {
                    show: true, // 显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                },
                axisLabel: {
                    rotate: 45
                },
                boundaryGap: false,
                data: name_arr
            }
        ],
        yAxis: [
            {
                type: 'value',
                splitArea: { //是否显示echarts背景分隔区域
                    show: false
                },
                axisLine: {
                    show: false,// 不显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                }
            }
        ],
        series: new_data
    };
    chart.setOption(option);
    return chart;
}

/**
 * 订单地域分布
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsOrderMapWholeCountry (name_arr, data) {
    var chart = echarts.init(document.getElementById('echarts-map-whole-country'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: []
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: true },
                saveAsImage: { name: window['lang_order_region_name'] || '订单地域分布', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value',
            splitArea: { //是否显示echarts背景分隔区域
                show: false
            },
            axisLine: {
                show: true, // 显示坐标线
                lineStyle: { // 坐标颜色
                    color: '#eee'
                }
            },
            axisLabel: {
                textStyle: {
                    color: '#ccc' // 设置颜色为红色
                }
            },
            boundaryGap: [0, 0.01]
        },
        yAxis: {
            type: 'category',
            splitArea: { //是否显示echarts背景分隔区域
                show: false
            },
            axisLine: {
                show: false,// 不显示坐标线
                lineStyle: { // 坐标颜色
                    color: '#eee'
                }
            },
            axisLabel: {
                textStyle: {
                    color: '#5F9AD6' // 设置颜色
                }
            },
            data: name_arr
        },
        series: [
            {
                name: '',
                type: 'bar',
                data: data,
                itemStyle: {
                    normal: {
                        borderRadius: [0, 40, 40, 0],  // 设置柱状图边框的圆角大小，单位为像素
                        // 定制颜色显示（按顺序）
                        // 超出定制颜色则返回随机
                        color: function (params) {
                            var colorList = ['#1B7A56', '#8BC8F0', '#4E95EE', '#0AC3C2', '#929AE6', '#29C66F', '#526EC9', '#4DD5DA', '#60B1DC', '#0092D3'];
                            if (colorList[params.dataIndex] == undefined) {
                                return "#" + Math.floor(Math.random() * (256 * 256 * 256 - 1)).toString(16);
                            } else {
                                return colorList[params.dataIndex];
                            }
                        }
                    }
                }
            }
        ]
    };
    chart.setOption(option);
    return chart;
}

/**
 * 新增用户
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsNewUser (name_arr, data) {
    var gradient_color = [
        ['#1CBEF6', '#4378FF'],
    ]
    var borderRadius = [40, 40, 0, 0];
    var new_data = data.map(function (item, index) {
        if((gradient_color[index] || null) != null) {
            var item_style = {
                itemStyle: {
                    borderRadius: borderRadius,  // 设置柱状图边框的圆角大小，单位为像素
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
            }
            return Object.assign({}, item, item_style);
        }
        return item;
    });
    var chart = echarts.init(document.getElementById('echarts-new-user'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: []
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: false },
                saveAsImage: { name: window['lang_new_user_name'] || '新增用户', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        calculable: true,
        xAxis: [
            {
                type: 'category',
                axisLine: {
                    show: true, // 显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                },
                axisLabel: {
                    rotate: 45
                },
                boundaryGap: false,
                data: name_arr
            }
        ],
        yAxis: [
            {
                type: 'value',
                splitArea: { //是否显示echarts背景分隔区域
                    show: false
                },
                axisLine: {
                    show: false,// 不显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                }
            }
        ],
        series: new_data
    };
    chart.setOption(option);
    return chart;
}


/**
 * 下单用户
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[array]}        name_arr  [名称]
 * @param   {[array]}        data      [数据]
 */
function EchartsBuyUser (name_arr, data) {
    var gradient_color = [
        ['#C8AFF8', '#9A77DD'],
    ]
    var borderRadius = [40, 40, 0, 0];
    var new_data = data.map(function (item, index) {
        if((gradient_color[index] || null) != null) {
            var item_style = {
                itemStyle: {
                    borderRadius: borderRadius,  // 设置柱状图边框的圆角大小，单位为像素
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0, color: gradient_color[index][0] // 渐变色从正上方开始，颜色为#42b983
                    }, {
                        offset: 1, color: gradient_color[index][1] // 指100%处的颜色，颜色为#4a82c9
                    }])
                },
            }
            return Object.assign({}, item, item_style);
        }
        return item;
    });
    var chart = echarts.init(document.getElementById('echarts-buy-user'), 'macarons');
    var option = {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: []
        },
        toolbox: {
            show: window_is_toolbox,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: false },
                saveAsImage: { name: window['lang_buy_user_name'] || '下单用户', show: true }
            }
        },
        grid: {
            left: '2%',
            right: '2%',
            bottom: '3%',
            containLabel: true
        },
        calculable: true,
        xAxis: [
            {
                type: 'category',
                axisLine: {
                    show: true, // 显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                },
                axisLabel: {
                    rotate: 45
                },
                boundaryGap: false,
                data: name_arr
            }
        ],
        yAxis: [
            {
                type: 'value',
                splitArea: { //是否显示echarts背景分隔区域
                    show: false
                },
                axisLine: {
                    show: false,// 不显示坐标线
                    lineStyle: { // 坐标颜色
                        color: '#999'
                    }
                }
            }
        ],
        series: new_data
    };
    chart.setOption(option);
    return chart;
}


/**
 * 统计数据查询
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[object]}        e [操作对象]
 */
var window_is_toolbox = $(window).width() > 900;
var chart_object = [];
function EchartsQuery (e) {
    // 类型
    var type = e.parents('.right-operate').data('type');
    var value = e.parents('.echarts-title').find('select[name="value"]').val() || '';

    // 时间
    var $time = e.parent();
    var start = $time.find('input[name="time_start"]').val() || '';
    var end = $time.find('input[name="time_end"]').val() || '';

    // ajax
    e.button('loading');
    $.AMUI.progress.start();
    $.ajax({
        url: RequestUrlHandle($('.content-right').data('url')),
        type: 'POST',
        dataType: 'json',
        timeout: 30000,
        data: { "type": type, "start": start, "end": end, "value": value },
        success: function (res) {
            e.button('reset');
            $.AMUI.progress.done();
            if (res.code == 0) {
                var chart = null;
                switch (type) {
                    // 基础数据总数
                    case 'base-count':
                        EchartsBaseCount(res.data);
                        break;

                    // 订单成交金额走势
                    case 'order-profit':
                        var chart = EchartsOrderProfit(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 订单交易走势
                    case 'order-trading':
                        var chart = EchartsOrderTrading(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 热销商品
                    case 'goods-hot':
                        var chart = EchartsGoodsHot(res.data.data);
                        break;

                    // 支付方式
                    case 'pay-type':
                        var chart = EchartsPayType(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 订单地域分布
                    case 'order-whole-country':
                        var chart = EchartsOrderMapWholeCountry(res.data.name_arr, res.data.data);
                        break;

                    // 新增用户
                    case 'new-user':
                        var chart = EchartsNewUser(res.data.name_arr, res.data.data);
                        break;

                    // 下单用户
                    case 'buy-user':
                        var chart = EchartsBuyUser(res.data.name_arr, res.data.data);
                        break;

                    default:
                        var msg = window['lang_operate_params_error'] || '操作类型未定义';
                        console.info(msg + '[' + type + ']')
                }

                // 图表对象存储
                if (chart !== null) {
                    chart_object.push(chart);
                }
            } else {
                Prompt(res.msg);
            }
        },
        error: function (xhr, type) {
            e.button('reset');
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

/**
 * 统计数据初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-08-12
 * @desc    description
 */
function EchartsInit () {
    var start = $('input[name="time_start"]').val();
    var end = $('input[name="time_end"]').val();
    var value = $('.content-right .echarts-title select[name="value"]').val() || '';
    $.ajax({
        url: RequestUrlHandle($('.content-right').data('url')),
        type: 'POST',
        dataType: 'json',
        timeout: 30000,
        data: { type: 'all', start: start, end: end, value: value },
        success: function (res) {
            $.AMUI.progress.done();
            if (res.code == 0) {
                for(var i in res.data) {
                    var data = res.data[i];
                    var chart = null;
                    switch (i) {
                        // 订单成交金额走势
                        case 'order_profit':
                            var chart = EchartsOrderProfit(data.title_arr, data.name_arr, data.data);
                            break;

                        // 订单交易走势
                        case 'order_trading':
                            var chart = EchartsOrderTrading(data.title_arr, data.name_arr, data.data);
                            break;

                        // 热销商品
                        case 'goods_hot':
                            var chart = EchartsGoodsHot(data.data);
                            break;

                        // 支付方式
                        case 'pay_type':
                            var chart = EchartsPayType(data.title_arr, data.name_arr, data.data);
                            break;

                        // 订单地域分布
                        case 'order_whole_country':
                            var chart = EchartsOrderMapWholeCountry(data.name_arr, data.data);
                            break;

                        // 新增用户
                        case 'new_user':
                            var chart = EchartsNewUser(data.name_arr, data.data);
                            break;

                        // 下单用户
                        case 'buy_user':
                            var chart = EchartsBuyUser(data.name_arr, data.data);
                            break;
                    }
                }
                // 图表对象存储
                if (chart !== null) {
                    chart_object.push(chart);
                }
            } else {
                Prompt(res.msg);
            }
        },
        error: function (xhr, type) {
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

$(function () {
    // 检查更新
    var $inspect_upgrade_popup = $('#inspect-upgrade-popup');
    $(document).on('click', '.inspect-upgrade-submit', function () {
        // 基础信息
        AMUI.dialog.loading({ title: window['lang_upgrade_check_loading_tips'] || '正在获取最新内容、请稍候...' });

        // ajax请求
        $.ajax({
            url: RequestUrlHandle($(this).data('url')),
            type: 'POST',
            dataType: 'json',
            timeout: 30000,
            data: {},
            success: function (result) {
                AMUI.dialog.loading('close');
                if (result.code == 0 && typeof (result.data) == 'object') {
                    // html内容处理
                    // 基础信息
                    // 是否存在数据、网络不通将返回空数据
                    if ((result.data || null) != null) {
                        var upgrade_version_name = window['lang_upgrade_version_name'] || '更新版本：';
                        var upgrade_date_name = window['lang_upgrade_date_name'] || '更新日期：';
                        var html = '<p class="upgrade-title">';
                        html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                        html += '<span class="am-margin-left-xs">' + result.data.title + '</span>';
                        html += '</p>';
                        html += '<div class="am-alert upgrade-base">';
                        html += '<span class="upgrade-ver">' + upgrade_version_name + result.data.version_new + '</span>';
                        html += '<span class="upgrade-date am-margin-left-sm">' + upgrade_date_name + result.data.release_time + '</span>';
                        // 是否带指定链接和链接名称
                        if ((result.data.go_title || null) != null && (result.data.go_url || null) != null) {
                            html += '<a href="' + result.data.go_url + '" class="upgrade-go-detail am-margin-left-lg" target="_blank">' + result.data.go_title + '</a>';
                        }
                        html += '</div>';

                        // 提示信息
                        if ((result.data.tips || null) != null) {
                            html += '<div class="am-alert am-alert-danger">';
                            html += '<p class="am-text-danger">' + result.data.tips + '</p>';
                            html += '</div>';
                        }

                        // 更新内容介绍
                        if ((result.data.content || null) != null && result.data.content.length > 0) {
                            html += '<div class="am-alert am-alert-secondary upgrade-content-item">';
                            html += '<ul>';
                            for (var i in result.data.content) {
                                html += '<li>' + result.data.content[i] + '</li>';
                            }
                            html += '</ul>';
                            html += '</div>';
                        }
                    } else {
                        var html = '<p class="upgrade-title am-text-center am-margin-top-xl am-padding-top-xl">';
                        html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                        html += '<span class="am-margin-left-xs">' + result.msg + '</span>';
                        html += '</p>';
                    }
                    $inspect_upgrade_popup.find('.upgrade-content').html(html);

                    // 是否支持在线自动更新
                    if ((result.data.is_auto || 0) == 1) {
                        $inspect_upgrade_popup.find('.inspect-upgrade-confirm').removeClass('am-hide');
                    } else {
                        $inspect_upgrade_popup.find('.inspect-upgrade-confirm').addClass('am-hide');
                    }

                    // 打开弹窗
                    $inspect_upgrade_popup.modal('open');
                } else {
                    Prompt(result.msg);
                }
            },
            error: function (xhr, type) {
                AMUI.dialog.loading('close');
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    // 系统更新确认
    $(document).on('click', '.inspect-upgrade-confirm', function () {
        $inspect_upgrade_popup.modal('close');
        SystemUpgradeRequestHandle({ "url": $(this).data('url') });
    });

    // 统计数据初始化
    EchartsInit();

    // 基础条件值改变事件
    $(document).on('change', '.echarts-title select[name="value"]', function () {
        $(this).parents('.echarts-title').find('button.echarts-where-submit').trigger('click');
    });

    // 条件确认
    $(document).on('click', '.echarts-where-submit', function () {
        var $time = $(this).parent();
        var start = $time.find('input[name="time_start"]').val();
        var end = $time.find('input[name="time_end"]').val();
        $(this).parent().parent().find('.quick-time a').each((k, v) => {
            if ($(v).data('start') === start && $(v).data('end') === end) {
                $(v).addClass('am-active');
                if ($(v).hasClass('time')) {
                    $(v).parents('.dropdown-more').find('.more-btn').addClass('am-active');
                }
            } else {
                if (!$(v).hasClass('more-btn')) {
                    $(v).removeClass('am-active');
                }
            }
        })
        EchartsQuery($(this));
    });

    // 快捷时间
    $(document).on('click', '.quick-time a', function () {
        if (!$(this).hasClass('more-btn')) {
            if ($(this).hasClass('time')) {
                $(this).parent().parent().find('a').removeClass('am-active');
                $(this).addClass('am-active');
                $(this).parents('.dropdown-more').siblings().removeClass('am-active')
                $(this).parents('.dropdown-more').find('.more-btn').addClass('am-active');
            } else {
                $(this).addClass('am-active').siblings().removeClass('am-active');
                $(this).parent().find('.dropdown-more a').removeClass('am-active');
            }
            // 参数判断
            var start = $(this).data('start') || '';
            var end = $(this).data('end') || '';
            var is_empty_time = parseInt($(this).parents('.right-operate').data('empty-time')) || 0;
            if (is_empty_time == 0 && (start == '' || end == '')) {
                Prompt(window['lang_operate_params_error'] || '快捷时间配置有误');
                return false;
            }

            // 时间
            var $time = $(this).parents('.right-operate');
            if (!$time.find('button').is(':disabled')) {
                $time.find('input[name="time_start"]').val(start);
                $time.find('input[name="time_end"]').val(end);
                $time.find('button.echarts-where-submit').trigger('click');
            }
        }
    });

    // 浏览器大小改变则实时更新图表大小
    window.onresize = function () {
        if (chart_object.length > 0) {
            for (var i in chart_object) {
                chart_object[i].resize();
            }
        }
    };
    // 图表切换(订单成交金额走势----订单交易走势)
    $('.echarts-tabs span').on('click', function () {
        if (!$(this).hasClass('am-active')) {
            $(this).addClass('am-active');
            $(this).siblings().removeClass('am-active')
            var key = $(this).data('key');
            $('.' + key.trim()).removeClass('am-hide');
            $('.' + key.trim()).siblings().addClass('am-hide');
            if (key === 'order-profit') {
                $('#echarts-order-profit').addClass('echarts-tabs-change-active');
                $('#echarts-order-trading').removeClass('echarts-tabs-change-active');
            } else if (key === 'order-trading') {
                $('#echarts-order-trading').addClass('echarts-tabs-change-active');
                $('#echarts-order-profit').removeClass('echarts-tabs-change-active');
            } else if (key === 'new-user') {
                $('#echarts-new-user').addClass('echarts-tabs-change-active');
                $('#echarts-buy-user').removeClass('echarts-tabs-change-active');
            } else if (key === 'buy-user') {
                $('#echarts-buy-user').addClass('echarts-tabs-change-active');
                $('#echarts-new-user').removeClass('echarts-tabs-change-active');
            }
        }
    });
    // 首页常用功能事件
    $(document).on('click', '.shortcut-menu-list .item', function () {
        var url = $(this).data('url') || null;
        var type = $(this).data('type');
        var key = $(this).data('key');
        var name = $(this).data('name');

        // 子级调用父级菜单
        parent.IframeCommonAdminMenuOpen(url, name, key, type);
    });
});