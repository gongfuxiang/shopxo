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
function ParametersItemHtmlCreated (type, name, value) {
    // 参数容器
    var $parameters_table = $('.parameters-table');

    // 拼接html
    var index = parseInt(Math.random() * 1000001);
    var html = '<tr class="parameters-line-' + index + '">';
    html += '<td class="am-text-middle">';
    html += '<select name="parameters_type[]" class="am-radius chosen-select" data-validation-message="' + $parameters_table.data('type-message') + '" data-is-clearout="0">';
    html += '<option value="0" ' + (type == 0 ? 'selected' : '') + '>' + $parameters_table.data('type-all-name') + '</option>';
    html += '<option value="1" ' + (type == 1 || type == undefined ? 'selected' : '') + '>' + $parameters_table.data('type-detail-name') + '</option>';
    html += '<option value="2" ' + (type == 2 ? 'selected' : '') + '>' + $parameters_table.data('type-base-name') + '</option>';
    html += '</select>';
    html += '</td>';
    html += '<td class="am-text-middle">';
    html += '<input type="text" name="parameters_name[]" placeholder="' + $parameters_table.data('params-name') + '" value="' + (name || '') + '" data-validation-message="' + $parameters_table.data('params-message') + '" maxlength="160" class="am-radius" data-is-clearout="0" required />';
    html += '</td>';
    html += '<td class="am-text-middle">';
    html += '<input type="text" name="parameters_value[]" placeholder="' + $parameters_table.data('value-message') + '" value="' + (value || '') + '" maxlength="200" data-validation-message="' + $parameters_table.data('value-message') + '" class="am-radius" data-is-clearout="0" />';
    html += '</td>';
    html += '<td class="am-text-middle">';
    html += '<a href="javascript:;" class="am-text-xs am-text-primary am-margin-right-sm line-move" data-type="top">' + $parameters_table.data('move-top-name') + '</a> ';
    html += '<a href="javascript:;" class="am-text-xs am-text-primary am-margin-right-sm line-move" data-type="bottom">' + $parameters_table.data('move-bottom-name') + '</a> ';
    html += '<a href="javascript:;" class="am-text-xs am-text-primary line-remove">' + $parameters_table.data('remove-name') + '</a>';
    html += '</td>';
    html += '</tr>';

    // 数据添加
    $parameters_table.append(html);

    // select组件初始化
    $parameters_table.find('.parameters-line-' + index + ' .chosen-select').chosen({
        inherit_select_classes: true,
        enable_split_word_search: true,
        search_contains: true,
        no_results_text: window['lang_chosen_select_no_results_text']
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
function FormTableHeightHandle () {
    // 表格内容
    if ($('.am-table-scrollable-horizontal').length > 0) {
        // 内容高度
        var body_margin_top = parseInt($('.content-right > .content').css('margin-top').replace('px', '') || 0);
        var body_margin_bottom = parseInt($('.content-right > .content').css('margin-bottom').replace('px', '') || 0);
        var body_padding_top = parseInt($('.content-right > .content').css('padding-top').replace('px', '') || 0);
        var body_padding_bottom = parseInt($('.content-right > .content').css('padding-bottom').replace('px', '') || 0);
        var content_top = $('.form-table-content-top').outerHeight(true) || 0;
        var operate_top = $('.form-table-operate-top').outerHeight(true) || 0;
        var operate_bottom = $('.form-table-operate-bottom').outerHeight(true) || 0;
        var page = $('.form-table-content .am-pagination').outerHeight(true) || 0;
        $('.am-table-scrollable-horizontal').css('height', 'calc(100vh - ' + (body_margin_top + body_margin_bottom + body_padding_top + body_padding_bottom + content_top + operate_top + operate_bottom + page) + 'px)');
    }
}

/**
 * 软件安装异步请求步骤
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-22
 * @desc    description
 * @desc    description
 * @param   {[string]}        url                   [url地址]
 * @param   {[string]}        type                  [操作类型（plugins、payment、webtheme、minitheme、design、diy）]
 * @param   {[string]}        id                    [插件商店id]
 * @param   {[string]}        opt                   [操作类型（url 获取下载地址， download 下载插件包， install 安装插件包）]
 * @param   {[string]}        key                   [操作key（仅download和install需要）]
 * @param   {[string]}        terminal              [小程序需要的指定端值]
 * @param   {[string]}        msg                   [提示信息]
 * @param   {[string]}        back_fun              [成功回调方法]
 * @param   {[string]}        back_url              [成功回调跳转地址]
 * @param   {[string]}        back_url_field        [成功回调参数替换字段]
 * @param   {[string]}        back_url_is_new_win   [成功回调参数替换字段]
 * @param   {[string]}        back_win_refresh_type [成功刷新页面类型（空当前页面，parent父级，none不刷新）]
 */
function PackageInstallRequestHandle(params)
{
    // 参数处理
    if ((params || null) == null) {
        Prompt('操作参数有误');
        return false;
    }
    var id = params.id || null;
    var type = params.type || null;
    var terminal = params.terminal || '';
    var url = params.url || null;
    var key = params.key || '';
    var opt = params.opt || 'url';
    var msg = params.msg || window['lang_get_loading_tips'] || '正在获取中...';
    if(id == null || type == null || url == null)
    {
        Prompt(window['lang_operate_params_error'] || '请求参数有误');
        return false;
    }
    // 回调方法
    var back_fun = params.back_fun || null;
    // 回调地址
    var back_url = params.back_url || null;
    // 回调地址替换参数字段
    var back_url_field = params.back_url_field || null;
    // 是否新标签打开
    var back_url_is_new_win = params.back_url_is_new_win || 0;
    // 新标签跳转后刷新原来列表的数据
    var back_url_is_new_win_refresh = params.back_url_is_new_win_refresh || 0;
    // 新标签跳转后重新查询原来列表的数据
    var back_url_is_new_win_data_list_query = params.back_url_is_new_win_data_list_query || 0;
    // 刷新类型（空当前页面，parent父级，none不刷新）
    var back_win_refresh_type = params.back_win_refresh_type || null;

    // 加载提示
    AMUI.dialog.loading({ title: msg });

    // ajax
    $.ajax({
        url: RequestUrlHandle(url),
        type: 'POST',
        dataType: 'json',
        timeout: 305000,
        data: {"id":id, "type":type, "opt":opt, "key":key, "terminal":terminal},
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
                        params['msg'] = window['lang_download_loading_tips'] || '正在下载中...';
                        PackageInstallRequestHandle(params);
                        break;

                    // 下载插件包
                    case 'download' :
                        params['key'] = result.data;
                        params['opt'] = 'install';
                        params['msg'] = window['lang_install_loading_tips'] || '正在安装中...';
                        PackageInstallRequestHandle(params);
                        break;

                    // 安装完成
                    case 'install' :
                        Prompt(result.msg, 'success');
                        setTimeout(function () {
                            if (back_fun != null)
                            {
                                if(IsExitsFunction(back_fun))
                                {
                                    window[back_fun](result.data);
                                } else {
                                    Prompt((window['lang_config_fun_not_exist_tips'] || '配置方法未定义') + '[' + value + ']');
                                }
                            } else if(back_url != null)
                            {
                                if(back_url_field != null)
                                {
                                    back_url = UrlFieldReplace(back_url_field, result.data, back_url);
                                }
                                if(back_url_is_new_win == 1)
                                {
                                    window.open(back_url, '_blank');
                                    if(back_url_is_new_win_refresh == 1)
                                    {
                                        if(back_win_refresh_type != 'none')
                                        {
                                            setTimeout(function () {
                                                if (back_win_refresh_type == 'parent') {
                                                    parent.location.reload();
                                                } else {
                                                    window.location.reload();
                                                }
                                            }, 50);
                                        }
                                    } else {
                                        if(back_url_is_new_win_data_list_query == 1)
                                        {
                                            $('.form-table-operate-top-search-submit').trigger('click');
                                        }
                                    }
                                } else {
                                    window.location.href = back_url;
                                }
                            } else {
                                if(back_win_refresh_type != 'none')
                                {
                                    if (back_win_refresh_type == 'parent') {
                                        parent.location.reload();
                                    } else {
                                        window.location.reload();
                                    }
                                }
                            }

                            AMUI.dialog.loading('close');
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
function PackageUpgradeRequestHandle (params) {
    // 参数处理
    if ((params || null) == null) {
        Prompt('操作参数有误');
        return false;
    }
    var url = params.url || null;
    var type = params.type || null;
    var value = params.value || null;
    var key = params.key || '';
    var terminal = params.terminal || '';
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
        data: { "plugins_type": type, "plugins_value": value, "plugins_terminal": terminal, "opt": opt, "key": key },
        success: function (result) {
            if ((result || null) != null && result.code == 0) {
                switch (opt) {
                    // 获取下载地址
                    case 'url':
                        params['key'] = result.data;
                        params['opt'] = 'download';
                        params['msg'] = window['lang_download_loading_tips'] || '正在下载中...';
                        PackageUpgradeRequestHandle(params);
                        break;

                    // 下载插件包
                    case 'download':
                        params['key'] = result.data;
                        params['opt'] = 'upgrade';
                        params['msg'] = window['lang_update_loading_tips'] || '正在更新中...';
                        PackageUpgradeRequestHandle(params);
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
 * 打开商店帐号绑定弹窗
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-04-24
 * @desc    description
 */
function StoreAccountsPopupOpen (title) {
    $('#store-accounts-popup').modal({
        closeViaDimmer: false
    });
    if (title) {
        $('#store-accounts-popup .am-popup-title').text(title);
    }
}

/**
 * 管理顶部菜单和内容添加处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-04-24
 * @desc    description
 * @param   {[string]}        url       [url地址]
 * @param   {[string]}        name      [展示名称]
 * @param   {[string]}        key       [唯一key值]
 * @param   {[string]}        type      [类型 nav 或 menu]
 * @param   {[boolean]}       is_reload [重新加载]
 */
function AdminTopNavIframeAddHandle (url, name, key, type = 'nav', is_reload = false) {
    if ((url || null) != null) {
        if ($('#ifcontent').length > 0) {
            // 先隐藏所有的iframe
            // 页面未打开则添加iframe并打开
            if ($('#ifcontent .iframe-item-key-' + key).length == 0) {
                var html = `<div class="window-layer am-radius am-nbfc iframe-item-key-` + key + `" data-key="` + key + `">
                                <div class="window-layer-tab-bar">
                                    <span>`+ name + `</span>
                                    <div class="am-fr">
                                        <span class="refresh iconfont icon-refresh"></span>
                                        <span class="recovery iconfont icon-retract"></span>
                                        <span class="close iconfont icon-close-o"></span>
                                    </div>
                                </div>
                                <iframe src="`+ url + `" width="100%" height="100%"></iframe>
                                <div class="window-layer-seat"></div>
                                <div class="window-layer-resize-bar">
                                    <div data-type="left" class="window-layer-resize-item-left"></div>
                                    <div data-type="right" class="window-layer-resize-item-right"></div>
                                    <div data-type="top" class="window-layer-resize-item-top"></div>
                                    <div data-type="bottom" class="window-layer-resize-item-bottom"></div>
                                    <div data-type="left-top" class="window-layer-resize-item-left-top"></div>
                                    <div data-type="right-top" class="window-layer-resize-item-right-top"></div>
                                    <div data-type="left-bottom" class="window-layer-resize-item-left-bottom"></div>
                                    <div data-type="right-bottom" class="window-layer-resize-item-right-bottom"></div>
                                </div>
                            </div>`;
                $('#ifcontent').append(html);
            } else {
                if (is_reload) {
                    var $obj = $('#ifcontent .iframe-item-key-' + key + ' iframe');
                    $obj.attr('src', $obj.attr('src'));
                }
            }

            // 添加快捷导航
            if ($('.header-menu-open-pages-list ul li.nav-item-key-' + key).length == 0) {
                var html = `<li data-url="` + url + `" data-key="` + key + `" data-type="` + type + `" data-name="` + name + `" class="nav-item-key-` + key + `"><span>` + name + `</span>`;
                if (key !== '-') {
                    html += `<a href="javascript:;" class="iconfont icon-close-o"></a>`;
                }
                html += `</li>`;
                $('.header-menu-open-pages-list ul').append(html);
                $('.tabs-tool').removeClass('am-hide')
            }
            // 模拟点击当前元素
            $('.header-menu-open-pages-list ul li.nav-item-key-' + key).trigger('click');

            // 顶部菜单事件，关闭弹层
            if (type == 'nav') {
                if ($(document).width() < 641) {
                    $('.header-nav-submit').trigger('click');
                } else {
                    $(this).parents('.admin-header-right-list').trigger('click');
                }
            }

            // 关闭左侧弹层
            if (type == 'menu') {
                $('#admin-offcanvas').offCanvas('close');
            }
        } else {
            window.location.href = url;
        }
        // 存储tabs标签数据
        AdminMenuNavTabsMemoryHandle();
    }
}

/**
 * 存储菜单tabs标签数据
 * @author  kevin
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-12-06
 * @desc    description
 */
function AdminMenuNavTabsMemoryHandle () {
    var key = AdminMenuNavTabsMemoryKey();
    localStorage.removeItem(key);
    var menu_list = [];
    $('.header-menu-open-pages-list ul li').each((index, item) => {
        var self = $(item);
        var menu_obj = {
            url: self.data('url') || '',
            type: self.data('type') || '',
            key: self.data('key') || '',
            name: self.data('name') || '',
            is_active: $(item).hasClass('am-active') ? true : false
        };
        menu_list.push(menu_obj);
    })
    localStorage.setItem(key, JSON.stringify(menu_list));
}

/**
 * tab缓存key
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-03
 * @desc    description
 */
function AdminMenuNavTabsMemoryKey() {
    return __my_host__.replace(/\./g, '')+'-admin-menu-nav-tabs-memory-data-' + __admin_id__; 
}

/**
 * 获取菜单tabs标签数据记忆展示
 * @author  kevin
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-12-06
 * @desc    description
 */
function AdminMenuNavTabsMemoryView () {

    var menu_list = JSON.parse(localStorage.getItem(AdminMenuNavTabsMemoryKey()));
    $('.header-menu-open-pages-list ul').empty();
    if (menu_list !== null) {
        menu_list.forEach(item => {
            // 添加快捷导航
            var html = `<li data-url="` + item.url + `" data-key="` + item.key + `" data-type="` + item.type + `" data-name="` + item.name + `" class="nav-item-key-` + item.key + ` ` + (item.is_active ? 'am-active' : '') + `"><span>` + item.name + `</span>`;
            if (item.key !== '-') {
                html += `<a href="javascript:;" class="iconfont icon-close-o"></a>`;
            }
            html += `</li>`;
            $('.header-menu-open-pages-list ul').append(html);
            if (item.is_active) {
            }
        });
        $('.tabs-tool').removeClass('am-hide');

        // 如果当前是指定url则不处理记忆选中
        if(parseInt($('#ifcontent').data('is-appoint-to-url') || 0) == 0) {
            $('.header-menu-open-pages-list ul li.am-active').trigger('click');
        }
    } else {
        $('.common-left-menu li:eq(0)>a').trigger('click');
    }
}

$(function () {
    // 插件更新操作事件
    $(document).on('click', '.package-upgrade-event', function () {
        // 参数处理
        var json = $(this).attr('data-json') || null;
        if (json != null) {
            var json = JSON.parse(decodeURIComponent(json)) || null;
        }
        var name = $(this).data('name') || null;
        var type = $(this).data('type') || null;
        var value = $(this).data('value') || null;
        var terminal = $(this).data('terminal') || '';
        if (name == null || type == null || value == null || json == null) {
            Prompt(window['lang_operate_params_error'] || '操作事件参数配置有误');
            return false;
        }

        // 数据处理打开弹窗
        var $modal = $('#package-upgrade-modal');
        $modal.find('.upgrade-name').text(name);
        $modal.find('.upgrade-date').text(' ' + json.add_time);
        $modal.find('.upgrade-version').text(' ' + json.version_new);
        $modal.find('.am-scrollable-vertical').html(json.describe.join('<br />'));
        $modal.find('.package-upgrade-submit').attr('data-type', type).attr('data-value', value).attr('data-terminal', terminal);
        $modal.modal({
            closeViaDimmer: false,
            width: 310
        });
    });

    // 包安装搜索
    var $package = $('.package-install-list-container');
    var $package_list = $package.find('.package-data-list-container');
    var $package_data_list = $package_list.find('> ul');
    var $package_btn = $package.find('.forth-selection-container .search-submit');
    var $package_is_already_buy = $package.find('input[type="checkbox"].is-already-buy');
    $(document).on('click', '.package-install-list-container .forth-selection-container .search-submit, .package-data-page-container .pagelibrary li a', function()
    {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if(is_active == 1)
        {
            return false;
        }
        var page = $(this).data('page') || 1;

        // 请求参数
        var url = $package.find('.forth-selection-container').data('search-url');
        var type = $package.find('.forth-selection-form-goods-type select').val() || '';
        var keywords = $package.find('.forth-selection-form-keywords').val();
        var is_already_buy = $package_is_already_buy.is(':checked') ? 1 : 0;

        // 获取数据
        $package_btn.button('loading');
        $package_is_already_buy.uCheck('disable');
        $package_data_list.html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> '+($package_list.data('loading-msg'))+'</div>');
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'post',
            data: {page: page, type: type, keywords: keywords, is_already_buy: is_already_buy},
            dataType: 'json',
            success:function(res)
            {
                $package_btn.button('reset');
                $package_is_already_buy.uCheck('enable');
                if(res.code == 0)
                {
                    $package_list.attr('data-is-init', 0);
                    $package_data_list.html(res.data.data_list);
                } else {
                    $package_data_list.html('<div class="table-no"><i class="am-icon-warning"></i> '+res.msg+'</div>');
                }
                $('.package-data-page-container').html(PageLibrary(res.data.data_total, res.data.page_size, res.data.page, 4));

                // title组件初始化
                ViewDocumentTitleInit();

                // 下拉组件初始化
                DropdownInit();
            },
            error: function(xhr, type)
            {
                $package_btn.button('reset');
                $package_is_already_buy.uCheck('enable');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                $package_data_list.html('<div class="table-no"><i class="am-icon-warning"></i> '+msg+'</div>');
            }
        });
    });
    // 包数据初始化
    if(($package_data_list.html() || null) == null)
    {
        $package.find('.forth-selection-container .search-submit').trigger('click');
    }
    // 关键字输入搜索回车事件
    $(document).on('keydown', '.package-install-list-container .forth-selection-container .forth-selection-form-keywords', function(event)
    {
        if(event.keyCode == 13)
        {
            $package.find('.forth-selection-container .search-submit').trigger('click');
            return false;
        }
    });
    // 我已购买事件
    $(document).on('change', '.package-install-list-container input[type="checkbox"].is-already-buy', function()
    {
        $package.find('.forth-selection-container .search-submit').trigger('click');
    });
    // 包安装操作事件
    $(document).on('click', '.package-install-list-container .package-install-submit', function()
    {
        var $package = $('.package-install-list-container');
        var url = $package.data('url');
        var back_url = $package.data('back-url') || null;
        var back_url_field = $package.data('back-url-field') || null;
        var back_url_is_new_win = $package.data('back-url-is-new-win') || 0;
        var back_url_is_new_win_refresh = $package.data('back-url-is-new-win-refresh') || 0;
        var back_url_is_new_win_data_list_query = $package.data('back-url-is-new-win-data-list-query') || 0;
        var back_win_refresh_type = $package.data('back-win-refresh-type') || '';
        var back_fun = $package.data('back-fun') || null;
        var type = $package.data('type') || $(this).data('type') || null;
        var id = $(this).data('id') || null;
        PackageInstallRequestHandle({type: type,
            id: id,
            url: url,
            back_url: back_url,
            back_url_field: back_url_field,
            back_url_is_new_win: back_url_is_new_win,
            back_url_is_new_win_refresh: back_url_is_new_win_refresh,
            back_url_is_new_win_data_list_query: back_url_is_new_win_data_list_query,
            back_win_refresh_type: back_win_refresh_type,
            back_fun: back_fun
        });
    });

    // 插件更新操作确认
    $(document).on('click', '.package-upgrade-submit', function () {
        // 基础配置、url、插件类型、标识值、小程序终端类型
        var url = $(this).attr('data-url') || null;
        var type = $(this).attr('data-type') || null;
        var value = $(this).attr('data-value') || null;
        var terminal = $(this).attr('data-terminal') || '';
        if (url == null || type == null || value == null) {
            Prompt(window['lang_operate_params_error'] || '操作参数有误');
            return false;
        }
        $('#package-upgrade-modal').modal('close');
        PackageUpgradeRequestHandle({ "url": url, "type": type, "value": value, "terminal": terminal });
    });

    // 商店帐号绑定事件
    $(document).on('click', '.store-accounts-event', function () {
        StoreAccountsPopupOpen($(this).data('title'));
    });

    // 商品规格和参数拖拽排序
    if ($('table.specifications-table').length > 0) {
        $('table.specifications-table tbody').dragsort({ dragSelector: 'tr' });
    }
    if ($('table.parameters-table').length > 0) {
        var len = $('table.parameters-table tbody tr').length;
        if (len == 0) {
            $('table.parameters-table tbody').html('<tr><td></td></tr>');
        }
        $('table.parameters-table tbody').dragsort({ dragSelector: 'tr' });
        if (len == 0) {
            $('table.parameters-table tbody').html('');
        }
    }

    // 商品规格和参数上下移动
    $(document).on('click', '.specifications-table .line-move, .parameters-table .line-move', function () {
        // 父级table
        var $table = $(this).parents('table');

        // 类型
        var type = $(this).data('type') || null;
        if (type == null) {
            Prompt($table.data('move-type-tips') || window['lang_operate_params_error'] || '操作类型配置有误');
            return false;
        }

        // 索引
        var count = $(this).parents('table').find('tbody tr').length;
        var index = $(this).parents('tr').index() || 0;
        var $parent = $(this).parents('tr');
        switch (type) {
            // 上移
            case 'top':
                if (index == 0) {
                    Prompt($table.data('move-top-tips') || '已到最顶部');
                    return false;
                }
                $parent.prev().insertAfter($parent);
                break;

            // 下移
            case 'bottom':
                if (index >= count - 1) {
                    Prompt($table.data('move-bottom-tips') || '已到最底部');
                    return false;
                }
                $parent.next().insertBefore($parent);
                break;

            // 默认
            default:
                Prompt($table.data('move-type-tips') || '操作类型配置有误');
        }
    });

    // 商品参数添加
    var $parameters_table = $('.parameters-table');
    $(document).on('click', '.parameters-line-add', function () {
        // 追加内容
        ParametersItemHtmlCreated();
    });

    // 商品参数移除
    $parameters_table.on('click', '.line-remove', function () {
        $(this).parents('tr').remove();
    });

    // 商品参数配置信息复制
    var $quick_modal = $('#parameters-quick-copy-modal');
    var clipboard = new ClipboardJS('.parameters-quick-copy',
        {
            text: function () {
                // 获取商品参数配置信息
                var data = [];
                $parameters_table.find('tbody tr').each(function (k, v) {
                    data.push({
                        "type": $(this).find('td:eq(0) select').val(),
                        "name": $(this).find('td:eq(1) input').val(),
                        "value": $(this).find('td:eq(2) input').val(),
                    });
                });
                data = JSON.stringify(data);
                $quick_modal.find('textarea').val(data);
                return data;
            }
        });
    clipboard.on('success', function (e) {
        Prompt($parameters_table.data('copy-success-tips') || '复制成功', 'success');
    });
    clipboard.on('error', function (e) {
        // 复制失败则开启复制窗口，让用户自己复制
        $quick_modal.modal({
            width: 200,
            height: 135
        });
    });
    // 点击选中复制的值
    $quick_modal.find('textarea').on('click', function () {
        $(this).select();
    });

    // 商品参数快捷操作
    var $parameters_quick_config = $('.parameters-quick-config');
    $parameters_quick_config.find('button').on('click', function () {
        // 配置数据
        var data = $parameters_quick_config.find('textarea').val() || null;
        if (data == null) {
            Prompt($parameters_table.data('copy-no-tips') || '请先粘贴配置信息');
            return false;
        }

        // 异常处理、防止json格式错误
        try {
            data = JSON.parse(data);
        } catch (e) {
            Prompt($parameters_table.data('copy-error-tips') || '配置格式错误');
            return false;
        }
        if (data.length <= 0) {
            Prompt($parameters_table.data('copy-empty-tips') || '配置为空');
            return false;
        }

        // 数据生成
        $parameters_table.find('tbody').html('');
        for (var i in data) {
            var type = (data[i]['type'] == undefined) ? 1 : data[i]['type'];
            var name = data[i]['name'] || '';
            var value = data[i]['value'] || '';
            ParametersItemHtmlCreated(type, name, value);
        }
        $('#parameters-quick-container').dropdown('close');
        Prompt($parameters_table.data('created-success-tips') || '生成成功', 'success');
    });

    // 商品参数清空
    $(document).on('click', '.parameters-quick-remove', function () {
        $parameters_table.find('tbody').html('');
    });


    // 浏览器窗口实时事件
    $(window).resize(function () {
        // 动态数据表格高度处理
        FormTableHeightHandle();
    });
    // 动态数据表格高度处理
    FormTableHeightHandle();

    // 点击页面关闭父级的tab右侧工具弹窗
    $('body').on('click', function () {
        if ((parent.IframeCloseNavTabsRightTools || null) != null) {
            parent.IframeCloseNavTabsRightTools();
        }
    })
});