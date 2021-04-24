// 表单初始化
FromInit('form.form-validation-store-accounts');

/**
 * 商品参数数据创建
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-02
 * @desc    description
 * @param   {[int]}           type  [展示类型（0,1,2）]
 * @param   {[string]}        name  [参数名称]
 * @param   {[string]}        value [参数值]
 */
function ParametersItemHtmlCreated(type, name, value)
{
    var index = parseInt(Math.random()*1000001);
    var html = '<tr class="parameters-line-'+index+'">';
        html += '<td class="am-text-middle">';
        html += '<select name="parameters_type[]" class="am-radius chosen-select" data-validation-message="请选择商品参数展示类型">';
        html += '<option value="0" '+(type == 0 ? 'selected' : '')+'>全部</option>';
        html += '<option value="1" '+(type == 1 || type == undefined ? 'selected' : '')+'>详情</option>';
        html += '<option value="2" '+(type == 2 ? 'selected' : '')+'>基础</option>';
        html += '</select>';
        html += '</td>';
        html += '<td class="am-text-middle">';
        html += '<input type="text" name="parameters_name[]" placeholder="参数名称" value="'+(name || '')+'" data-validation-message="请填写参数名称" maxlength="160" required />';
        html += '</td>';
        html += '<td class="am-text-middle">';
        html += '<input type="text" name="parameters_value[]" placeholder="参数值" value="'+(value || '')+'" maxlength="200" data-validation-message="请填写参数值" />';
        html += '</td>';
        html += '<td class="am-text-middle">';
        html += '<span class="am-text-xs cr-blue c-p am-margin-right-sm line-move" data-type="top">上移</span>';
        html += '<span class="am-text-xs cr-blue c-p am-margin-right-sm line-move" data-type="bottom">下移</span>';
        html += '<span class="am-text-xs cr-red c-p line-remove">移除</span></td>';
        html += '</tr>';

    // 数据添加
    var $parameters_table = $('.parameters-table');
    $parameters_table.append(html);

    // select组件初始化
    $parameters_table.find('.parameters-line-'+index+' .chosen-select').chosen({
        inherit_select_classes: true,
        enable_split_word_search: true,
        search_contains: true,
        no_results_text: '没有匹配到结果'
    });
}

/**
 * 动态数据表格高度处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-11-29
 * @desc    description
 */
function FormTableHeightHandle()
{
    // 表格内容
    if($('.am-table-scrollable-horizontal').length > 0)
    {
        // 页面右侧总内容容器高度
        var height = $('.content-right .content-top').outerHeight(true) || 0;
        $('.content-right').css('height', 'calc(100% - '+height+'px)');

        // 内容高度
        var height_top = $('.form-table-operate-top').outerHeight(true) || 0;
        var height_bottom = $('.form-table-operate-bottom').outerHeight(true) || 0;
        $('.am-table-scrollable-horizontal').css('max-height', 'calc(100% - '+(height_top+height_bottom)+'px)');
    }
    // 表格内容外围高度
    if($('.form-validation-search').length > 0)
    {
        var height = $('.form-table-content .am-pagination').outerHeight(true) || 0;
        $('.form-validation-search').css('height', 'calc(100% - '+height+'px)');
    }
}

/**
 * 软件更新异步请求步骤
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @param   {[string]}        url       [url地址]
 * @param   {[string]}        type      [操作类型（plugins、payment、webtheme、minitheme）]
 * @param   {[string]}        value     [操作标识值]
 * @param   {[string]}        opt       [操作类型（url 获取下载地址， download 下载插件包， upgrade 安装插件包）]
 * @param   {[string]}        key       [操作key（仅download和install需要）]
 * @param   {[string]}        terminal  [小程序需要的指定端值]
 * @param   {[string]}        msg       [提示信息]
 */
function PackageUpgradeRequestHandle(params)
{
    // 参数处理
    if((params || null) == null)
    {
        Prompt('操作参数有误');
        return false;
    }
    var url = params.url || null;
    var type = params.type || null;
    var value = params.value || null;
    var key = params.key || '';
    var terminal = params.terminal || '';
    var opt = params.opt || 'url';
    var msg = params.msg || '正在获取中...';

    // 加载提示
    AMUI.dialog.loading({title: msg});

    // ajax
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        timeout: 305000,
        data: {"plugins_type":type, "plugins_value":value, "plugins_terminal":terminal, "opt":opt, "key":key},
        success: function(result)
        {
            if((result || null) != null && result.code == 0)
            {
                switch(opt)
                {
                    // 获取下载地址
                    case 'url' :
                        params['key'] = result.data;
                        params['opt'] = 'download';
                        params['msg'] = '正在下载中...';
                        PackageUpgradeRequestHandle(params);
                        break;

                    // 下载插件包
                    case 'download' :
                        params['key'] = result.data;
                        params['opt'] = 'upgrade';
                        params['msg'] = '正在更新中...';
                        PackageUpgradeRequestHandle(params);
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
                Prompt(((result || null) == null) ? '返回数据格式错误' : (result.msg || '异常错误'));
            }
        },
        error: function(xhr, type)
        {
            AMUI.dialog.loading('close');
            Prompt(HtmlToString(xhr.responseText) || '异常错误');
        }
    });
}

/**
 * 打开商店帐号绑定弹窗
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-04-24
 * @desc    description
 */
function StoreAccountsPopupOpen()
{
    $('#store-accounts-popup').modal({
        closeViaDimmer: false
    });
}

$(function()
{
    // 插件更新操作事件
    $(document).on('click', '.package-upgrade-event', function()
    {
        // 参数处理
        var json = $(this).attr('data-json') || null;
        if(json != null)
        {
            var json = JSON.parse(decodeURIComponent(json)) || null;
        }
        var name = $(this).data('name') || null;
        var type = $(this).data('type') || null;
        var value = $(this).data('value') || null;
        var terminal = $(this).data('terminal') || '';
        if(name == null || type == null || value == null || json == null)
        {
            Prompt('操作事件参数配置有误');
            return false;
        }
        
        // 数据处理打开弹窗
        var $modal = $('#package-upgrade-modal');
        $modal.find('.upgrade-name').text(name);
        $modal.find('.upgrade-date').text(' '+json.add_time);
        $modal.find('.upgrade-version').text(' '+json.version_new);
        $modal.find('.am-scrollable-vertical').html(json.describe.join('<br />'));
        $modal.find('.package-upgrade-submit').attr('data-type', type).attr('data-value', value).attr('data-terminal', terminal);
        $modal.modal({
            closeViaDimmer: false,
            width: 310,
            height: 257
        });
    });

    // 插件更新操作确认
    $(document).on('click', '.package-upgrade-submit', function()
    {
        // 基础配置、url、插件类型、标识值、小程序终端类型
        var url = $(this).attr('data-url') || null;
        var type = $(this).attr('data-type') || null;
        var value = $(this).attr('data-value') || null;
        var terminal = $(this).attr('data-terminal') || '';
        if(url == null || type == null || value == null)
        {
            Prompt('操作参数有误');
            return false;
        }
        $('#package-upgrade-modal').modal('close');
        PackageUpgradeRequestHandle({"url":url, "type":type, "value":value, "terminal":terminal});
    });

    // 商店帐号绑定事件
    $(document).on('click', '.store-accounts-event', function()
    {
        StoreAccountsPopupOpen();
    });

    // 商品规格和参数上下移动
    $('.specifications-table,.parameters-table').on('click', '.line-move', function()
    {
        // 类型
        var type = $(this).data('type') || null;
        if(type == null)
        {
            Prompt('操作类型配置有误');
            return false;
        }

        // 索引
        var count = $(this).parents('table').find('tbody tr').length; 
        var index = $(this).parents('tr').index() || 0;
        var $parent = $(this).parents('tr');
        switch(type)
        {
            // 上移
            case 'top' :
                if(index == 0)
                {
                    Prompt('已到最顶部');
                    return false;
                }
                $parent.prev().insertAfter($parent);
                break;

            // 下移
            case 'bottom' :
                if(index >= count-1)
                {
                    Prompt('已到最底部');
                    return false;
                }
                $parent.next().insertBefore($parent);
                break;

            // 默认
            default :
                Prompt('操作类型配置有误');
        }
    });

    // 商品参数添加
    var $parameters_table = $('.parameters-table');
    $('.parameters-line-add').on('click', function()
    {
        // 追加内容
        ParametersItemHtmlCreated();
    });

    // 商品参数移除
    $parameters_table.on('click', '.line-remove', function()
    {
        $(this).parents('tr').remove();
    });

    // 商品参数配置信息复制
    var $parameters_copy_modal = $('#parameters-quick-copy-modal');
    var clipboard = new ClipboardJS('.parameters-quick-copy',
    {
        text: function()
        {
            // 获取商品参数配置信息
            var data = [];
            $parameters_table.find('tbody tr').each(function(k, v)
            {
                data.push({
                    "type": $(this).find('td:eq(0) select').val(),
                    "name": $(this).find('td:eq(1) input').val(),
                    "value": $(this).find('td:eq(2) input').val(),
                });
            });
            data = JSON.stringify(data);
            $parameters_copy_modal.find('textarea').val(data);
            return data;
        }
    });
    clipboard.on('success', function(e)
    {
        Prompt('复制成功', 'success');
    });
    clipboard.on('error', function(e)
    {
        // 复制失败则开启复制窗口，让用户自己复制
        $parameters_copy_modal.modal({
            width: 200,
            height: 135
        });
    });
    // 点击选中复制的值
    $parameters_copy_modal.find('textarea').on('click', function()
    {
        $(this).select();
    });

    // 商品参数快捷操作
    var $parameters_quick_config = $('.parameters-quick-config');
    $parameters_quick_config.find('button').on('click', function()
    {
        // 配置数据
        var data = $parameters_quick_config.find('textarea').val() || null;
        if(data == null)
        {
            Prompt('请先粘贴配置信息');
            return false;
        }

        // 异常处理、防止json格式错误
        try {
            data = JSON.parse(data);
        } catch(e) {
            Prompt('配置格式错误');
            return false;
        }
        if(data.length <= 0)
        {
            Prompt('配置为空');
            return false;
        }

        // 数据生成
        $parameters_table.find('tbody').html('');
        for(var i in data)
        {
            var type = (data[i]['type'] == undefined) ? 1 : data[i]['type'];
            var name = data[i]['name'] || '';
            var value = data[i]['value'] || '';
            ParametersItemHtmlCreated(type, name, value);
        }
        $('#parameters-quick-container').dropdown('close');
        Prompt('生成成功', 'success');
    });

    // 商品参数清空
    $('.parameters-quick-remove').on('click', function()
    {
        $parameters_table.find('tbody').html('');
    });


    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        // 动态数据表格高度处理
        FormTableHeightHandle();
    });
    // 动态数据表格高度处理
    FormTableHeightHandle();
});