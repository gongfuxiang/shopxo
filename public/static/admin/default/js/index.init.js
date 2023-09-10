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
function SystemUpgradeRequestHandle(params)
{
    // 参数处理
    if((params || null) == null)
    {
        Prompt(window['lang_operate_params_error'] || '操作参数有误');
        return false;
    }
    var url = params.url || null;
    var opt = params.opt || 'url';
    var msg = params.msg || window['lang_get_loading_tips'] || '正在获取中...';

    // 加载提示
    AMUI.dialog.loading({title: msg});

    // ajax
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: 305000,
        data: {"opt":opt},
        success: function(result)
        {
            if((result || null) != null && result.code == 0)
            {
                switch(opt)
                {
                    // 获取下载地址
                    case 'url' :
                        params['opt'] = 'download_system';
                        params['msg'] = window['lang_system_download_loading_tips'] || '系统包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载系统包
                    case 'download_system' :
                        params['opt'] = 'download_upgrade';
                        params['msg'] = window['lang_upgrade_download_loading_tips'] || '升级包正在下载中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 下载升级包
                    case 'download_upgrade' :
                        params['opt'] = 'upgrade';
                        params['msg'] = window['lang_update_loading_tips'] || '正在更新中...';
                        SystemUpgradeRequestHandle(params);
                        break;

                    // 更新完成
                    case 'upgrade' :
                        Prompt(result.msg, 'success');
                        setTimeout(function()
                        {
                            window.location.reload();
                        }, 1500);
                        break;
                }
            } else {
                AMUI.dialog.loading('close');
                Prompt(((result || null) == null) ? (window['lang_error_text'] || '异常错误') : (result.msg || (window['lang_error_text'] || '异常错误')));
            }
        },
        error: function(xhr, type)
        {
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
function EchartsBaseCount(data)
{
    $('.base-user-count').text(data.user_count);
    $('.base-order-count').text(data.order_count);
    $('.base-order-sale-count').text(data.order_asle_count);
    $('.base-order-complete-total').text(data.order_complete_total);
}

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
function EchartsOrderProfit(title_arr, name_arr, data)
{
    var chart = echarts.init(document.getElementById('echarts-order-profit'), 'macarons');
    var option = {
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data: title_arr
        },
        toolbox: {
            show : (__is_mobile__ == 1) ? false : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: true},
                saveAsImage : {name: window['lang_order_transaction_amount_name'] || '订单成交金额走势', show: true}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : name_arr
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : data
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
function EchartsOrderTrading(title_arr, name_arr, data)
{
    var chart = echarts.init(document.getElementById('echarts-order-trading'), 'macarons');
    var option = {
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data: title_arr
        },
        toolbox: {
            show : (__is_mobile__ == 1) ? false : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: true},
                saveAsImage : {name: window['lang_order_trading_trend_name'] || '订单交易走势', show: true}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : name_arr
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : data
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
function EchartsGoodsHot(data)
{
    var chart = echarts.init(document.getElementById('echarts-goods-hot'), 'macarons');
    var option = {
        title : {
            subtext: window['lang_goods_hot_tips'] || '仅显示前30条商品',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{b} : {c} ({d}%)"
        },
        toolbox: {
            show : (__is_mobile__ == 1) ? false : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
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
                restore : {show: false},
                saveAsImage : {name: window['lang_goods_hot_name'] || '热销商品', show: true}
            }
        },
        calculable : true,
        series : [
            {
                type:'pie',
                radius : '55%',
                center: ['50%', '60%'],
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
function EchartsPayType(title_arr, name_arr, data)
{
    var chart = echarts.init(document.getElementById('echarts-pay-type'), 'macarons');
    var option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data: title_arr
        },
        toolbox: {
            show : (__is_mobile__ == 1) ? false : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: false},
                saveAsImage : {name: window['lang_payment_name'] || '支付方式', show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : name_arr
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : data
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
function EchartsOrderMapWholeCountry(name_arr, data)
{
    var chart = echarts.init(document.getElementById('echarts-map-whole-country'), 'macarons');
    var option = {
        title: {
            text: '',
            subtext: window['lang_order_region_tips'] || '仅显示30条数据'
        },
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
            show : (__is_mobile__ == 1) ? false : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {name: window['lang_order_region_name'] || '订单地域分布', show: true}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value',
            boundaryGap: [0, 0.01]
        },
        yAxis: {
            type: 'category',
            data: name_arr
        },
        series: [
            {
                name: '',
                type: 'bar',
                data: data,
                itemStyle: {
                    normal: {
                        // 定制颜色显示（按顺序）
                        // 超出定制颜色则返回随机
                        color: function(params) {
                            var colorList = ['#C33531','#EFE42A','#64BD3D','#EE9201','#29AAE3','#B74AE5','#0AAF9F','#E89589','#16A085','#4A235A','#C39BD3','#F9E79F','#BA4A00','#ECF0F1','#616A6B','#EAF2F8','#4A235A','#3498DB','#00BCD4','#FF9800','#E63A75','#3F51B5','#1CC0A0','#795548','#CDDC39'];
                            if(colorList[params.dataIndex] == undefined)
                            {
                                return "#"+Math.floor(Math.random()*(256*256*256-1)).toString(16);
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
 * 图表更新
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-30
 * @desc    description
 * @param   {[object]}        e [操作对象]
 */
var chart_object = [];
function EchartsInit(e)
{
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
        data: {"type":type, "start":start, "end":end, "value": value},
        success: function(res)
        {
            e.button('reset');
            $.AMUI.progress.done();
            if(res.code == 0)
            {
                var chart = null;
                switch(type)
                {
                    // 基础数据总数
                    case 'base-count' :
                        EchartsBaseCount(res.data);
                        break;

                    // 订单成交金额走势
                    case 'order-profit' :
                        var chart = EchartsOrderProfit(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 订单交易走势
                    case 'order-trading' :
                        var chart = EchartsOrderTrading(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 热销商品
                    case 'goods-hot' :
                        var chart = EchartsGoodsHot(res.data.data);
                        break;

                    // 支付方式
                    case 'pay-type' :
                        var chart = EchartsPayType(res.data.title_arr, res.data.name_arr, res.data.data);
                        break;

                    // 订单地域分布
                    case 'order-whole-country' :
                        var chart = EchartsOrderMapWholeCountry(res.data.name_arr, res.data.data);
                        break;

                    default :
                        var msg = window['lang_operate_params_error'] || '操作类型未定义';
                        console.info(msg+'['+type+']')
                }
                
                // 图表对象存储
                if(chart !== null)
                {
                    chart_object.push(chart);
                }
            } else {
                Prompt(res.msg);
            }
        },
        error: function(xhr, type)
        {
            e.button('reset');
            $.AMUI.progress.done();
            Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
        }
    });
}

$(function()
{
    // 检查更新
    var $inspect_upgrade_popup = $('#inspect-upgrade-popup');
    $(document).on('click', '.inspect-upgrade-submit', function()
    {
        // 基础信息
        AMUI.dialog.loading({title: window['lang_upgrade_check_loading_tips'] || '正在获取最新内容、请稍候...'});

        // ajax请求
        $.ajax({
            url: RequestUrlHandle($(this).data('url')),
            type: 'POST',
            dataType: 'json',
            timeout: 30000,
            data: {},
            success: function(result)
            {
                AMUI.dialog.loading('close');
                if(result.code == 0 && typeof(result.data) == 'object')
                {
                    // html内容处理
                    // 基础信息
                    // 是否存在数据、网络不通将返回空数据
                    if((result.data || null) != null)
                    {
                        var upgrade_version_name = window['lang_upgrade_version_name'] || '更新版本：';
                        var upgrade_date_name = window['lang_upgrade_date_name'] || '更新日期：';
                        var html = '<p class="upgrade-title">';
                            html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                            html += '<span class="am-margin-left-xs">'+result.data.title+'</span>';
                            html += '</p>';
                            html += '<div class="am-alert upgrade-base">';
                            html += '<span class="upgrade-ver">'+upgrade_version_name+result.data.version_new+'</span>';
                            html += '<span class="upgrade-date am-margin-left-sm">'+upgrade_date_name+result.data.add_time+'</span>';
                            // 是否带指定链接和链接名称
                            if((result.data.go_title || null) != null && (result.data.go_url || null) != null)
                            {
                                html += '<a href="'+result.data.go_url+'" class="upgrade-go-detail am-margin-left-lg" target="_blank">'+result.data.go_title+'</a>';
                            }
                            html += '</div>';

                            // 提示信息
                            if((result.data.tips || null) != null)
                            {
                                html += '<div class="am-alert am-alert-danger">';
                                html += '<p class="am-text-danger">'+result.data.tips+'</p>';
                                html += '</div>';
                            }

                            // 更新内容介绍
                            if((result.data.content || null) != null && result.data.content.length > 0)
                            {
                                html += '<div class="am-alert am-alert-secondary upgrade-content-item">';
                                html += '<ul>';
                                for(var i in result.data.content)
                                {
                                    html += '<li>'+result.data.content[i]+'</li>';
                                }
                                html += '</ul>';
                                html += '</div>';
                            }
                    } else {
                        var html = '<p class="upgrade-title am-text-center am-margin-top-xl am-padding-top-xl">';
                            html += '<i class="am-icon-info-circle am-icon-md am-text-warning"></i>';
                            html += '<span class="am-margin-left-xs">'+result.msg+'</span>';
                            html += '</p>';
                    }
                    $inspect_upgrade_popup.find('.upgrade-content').html(html);

                    // 是否支持在线自动更新
                    if((result.data.is_auto || 0) == 1)
                    {
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
            error: function(xhr, type)
            {
                AMUI.dialog.loading('close');
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
        });
    });

    // 系统更新确认
    $(document).on('click', '.inspect-upgrade-confirm', function()
    {
        $inspect_upgrade_popup.modal('close');
        SystemUpgradeRequestHandle({"url": $(this).data('url')});
    });

    // 初始化
    $('.content-right .echarts-where-submit').each(function(k ,v)
    {
        if(parseInt($(this).parents('.right-operate').data('init')) == 1)
        {
            EchartsInit($(this));
        }
    });

    // 基础条件值改变事件
    $(document).on('change', '.echarts-title select[name="value"]', function()
    {
        $(this).parent().find('button.echarts-where-submit').trigger('click');
    });

    // 条件确认
    $(document).on('click', '.echarts-where-submit', function()
    {
        EchartsInit($(this));
    });

    // 快捷时间
    $(document).on('click', '.quick-time a', function()
    {
        // 参数判断
        var start = $(this).data('start') || '';
        var end = $(this).data('end') || '';
        var is_empty_time = parseInt($(this).parents('.right-operate').data('empty-time')) || 0;
        if(is_empty_time == 0 && (start == '' || end == ''))
        {
            Prompt(window['lang_operate_params_error'] || '快捷时间配置有误');
            return false;
        }

        // 时间
        var $time = $(this).parent().next();
        if(!$time.find('button').is(':disabled'))
        {
            $time.find('input[name="time_start"]').val(start);
            $time.find('input[name="time_end"]').val(end);
            $time.find('button.echarts-where-submit').trigger('click');
        }
    });

    // 浏览器大小改变则实时更新图表大小
    window.onresize = function()
    {
        if(chart_object.length > 0)
        {
            for(var i in chart_object)
            {
                chart_object[i].resize();
            }
        }
    };
});