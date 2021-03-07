// 地址表单初始化
FromInit('form.form-validation-address');

/**
 * 地址返回处理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 * @param   {[object]}        data [地址信息]
 */
function AddressModalHandle(data)
{
    $(function()
    {
        // 参数处理
        var logo = data.logo || null;
        var alias = data.alias || null;
        var name = data.name || null;
        var tel = data.tel || null;
        var province = data.province || null;
        var city = data.city || null;
        var county = data.county || null;
        var address = data.address || null;
        var lng = data.lng || null;
        var lat = data.lat || null;
        if(name == null || tel == null || province == null || city == null || county == null || address == null)
        {
            Prompt('数据填写有误');
            return false;
        }

        // 地区名称
        data['province_name'] = $('.region-linkage select[name="province"]').find('option:selected').text();
        data['city_name'] = $('.region-linkage select[name="city"]').find('option:selected').text();
        data['county_name'] = $('.region-linkage select[name="county"]').find('option:selected').text();

        // 数据拼接
        var html = '<li>';
            if(logo != null)
            {
                html += '<img src="'+logo+'" alt="'+name+'" class="am-img-thumbnail am-radius address-logo" /> ';
            }
            html += '<span class="address-content">';
            html += '<span class="address-text">'+data['province_name']+' '+data['city_name']+' '+data['county_name']+' '+address+'（'+name+'-'+tel+'）</span>';
            if(alias != null)
            {
                html += '<span class="am-badge am-radius am-badge-success am-margin-left-xs">'+alias+'</span>';
            }
            html += '</span>';
            html += '<span class="am-badge am-radius am-icon-remove delete-submit"> 移除</span>';
            html += '<span class="am-badge am-radius am-icon-edit edit-submit"> 编辑</span>';
            html += '</li>';

        // 数据处理
        var value = SelfExtractionAddressValue();
        
        // 弹层
        var $popup_address = $('#popup-address-win');

        // 操作类型（add, edit）
        var form_type = $popup_address.attr('data-type') || 'add';
        if(form_type == 'add')
        {
            $('ul.address-list').append(html);
            data['id'] = value.length;
            value.push(data);
        } else {
            var form_index = $popup_address.attr('data-index') || 0;
            data['id'] = form_index;
            value.splice(form_index, 1, data);
            $('ul.address-list').find('li').eq(form_index).replaceWith(html);
        }
        $popup_address.modal('close');
        $('.self-extraction-address-value').val(JSON.stringify(value));
    });
}

/**
 * 获取自提地址
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-11-12
 * @desc    description
 */
function SelfExtractionAddressValue()
{
    var value = $('.self-extraction-address-value').val() || null;
    return (value == null) ? [] : JSON.parse(value);
}

$(function()
{
    // 弹层
    var $popup_address = $('#popup-address-win');

    // 地址添加开启
    $('.address-submit-add').on('click', function()
    {
        $popup_address.modal();
        $popup_address.attr('data-type', 'add');

        // logo
        $popup_address.find('.sitetype-logo').html('');

        // 清空数据
        FormDataFill({"alias":"", "name":"", "tel":"", "address":"", "province":0, "city":0, "county":0, "lng":"", "lat":""}, 'form.form-validation-address');

        // 地图初始化
        MapInit();
    });

    // 地址移除
    $(document).on('click', '.address-list .delete-submit', function()
    {
        var index = $(this).parents('li').index();
        var value = SelfExtractionAddressValue();
        if(value.length > 0)
        {
            AMUI.dialog.confirm({
                title: '温馨提示',
                content: '移除后保存生效、确认继续吗？',
                onConfirm: function(options)
                {
                    value.splice(index, 1);
                    $('.self-extraction-address-value').val(JSON.stringify(value));
                    $('ul.address-list').find('li').eq(index).remove();
                },
                onCancel: function(){}
            });
        } else {
            $('ul.address-list').find('li').eq(index).remove();
        }
    });

    // 地址编辑
    $(document).on('click', '.address-list .edit-submit', function()
    {
        var index = $(this).parents('li').index();
        var value = SelfExtractionAddressValue();
        if(value.length <= 0)
        {
            Prompt('地址数据为空');
            return false;
        }

        var item = value[index] || null;
        if(item == null)
        {
            Prompt('地址不存在');
            return false;
        }

        // logo
        var html = '';
        if((item.logo || null) != null)
        {
            html += '<li>';
            html += '<input type="text" name="logo" value="'+item.logo+'" data-validation-message="请上传logo图片" required />';
            html += '<img src="'+item.logo+'" alt="'+item.name+'" />';
            html += '<i>×</i>';
            html += '</li>';
        }
        $popup_address.find('.sitetype-logo').html(html);

        // 数据填充
        FormDataFill(item, 'form.form-validation-address');

        // 地区初始化
        RegionNodeData(item['province'], 'city', 'city', item['city']);
        RegionNodeData(item['city'], 'county', 'county', item['county']);

        // 基础数据
        $popup_address.modal();
        $popup_address.attr('data-type', 'edit');
        $popup_address.attr('data-index', index);

        // 地图初始化
        MapInit(item.lng, item.lat);
    });


    // 商品列表拖拽
    $('ul.manual-mode-goods-container').dragsort({ dragSelector: 'li', placeHolderTemplate: '<li class="drag-sort-dotted"></li>'});

    // 商品搜索popup容器   
    var $popup_siteset_goods = $('#siteset-goods-popup');

    // 分页
    $('.goods-page-container').html(PageLibrary());

    // 开启商品弹窗
    $('.goods-popup-add').on('click', function()
    {
        // 操作标记
        $popup_siteset_goods.attr('data-tag', $(this).data('tag') || '');
        $popup_siteset_goods.attr('data-form-name', $(this).data('form-name') || '');

        // 初始化搜索数据
        $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> 请搜索商品</div>');
        $('.goods-page-container').html(PageLibrary());
        $popup_siteset_goods.modal();
    });

    // 搜索商品
    $(document).on('click', '.forth-selection-container .search-submit, .pagelibrary li a', function()
    {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if(is_active == 1)
        {
            return false;
        }
        var page = $(this).data('page') || 1;

        // 请求参数
        var url = $('.forth-selection-container').data('search-url');
        var category_id = $('.forth-selection-form-category').val();
        var keywords = $('.forth-selection-form-keywords').val();
        var goods_ids = [];
        $($popup_siteset_goods.attr('data-tag')).find('input[type="hidden"]').each(function(k, v)
        {
            goods_ids.push($(this).val());
        });

        var $this = $(this);
        $.AMUI.progress.start();
        $this.button('loading');
        $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> '+($('.goods-list-container').data('loading-msg'))+'</div>');
        $.ajax({
            url: url,
            type: 'post',
            data: {"page":page, "category_id":category_id, "keywords":keywords, "goods_ids":goods_ids},
            dataType: 'json',
            success: function(res)
            {
                $.AMUI.progress.done();
                $this.button('reset');
                if(res.code == 0)
                {
                    $('.goods-list-container').attr('data-is-init', 0);
                    $('.goods-list-container ul.am-gallery').html(res.data.data);
                    $('.goods-page-container').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 4));
                } else {
                    Prompt(res.msg);
                    $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> '+res.msg+'</div>');
                }
            },
            error: function(xhr, type)
            {
                $.AMUI.progress.done();
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || '异常错误';
                Prompt(msg, null, 30);
                $('.goods-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> '+msg+'</div>');
            }
        });
    });

    // 删除列表
    $(document).on('click', '.manual-mode-goods-container li button.am-close', function()
    {
        $(this).parent('li').remove();
    });

    // 商品添加/删除
    $(document).on('click', '.goods-list-container .goods-add-submit, .goods-list-container .goods-del-submit', function()
    {
        // 基础参数
        var $this = $(this);
        var type = $this.data('type');
        var icon_html = $this.parents('li').data((type == 'add' ? 'del' : 'add')+'-html');
        var goods_id = $this.parents('li').data('gid');
        var goods_title = $this.parents('li').data('title');
        var goods_url = $this.parents('li').data('url');
        var goods_img = $this.parents('li').data('img');
        var tag = $popup_siteset_goods.attr('data-tag') || '';
        var form_name = $popup_siteset_goods.attr('data-form-name') || '';

        // 商品是否已经添加
        if($(tag).find('.manual-mode-goods-item-'+goods_id).length > 0)
        {
            $(tag).find('.manual-mode-goods-item-'+goods_id).remove();
        } else {
            $(tag).append('<li class="manual-mode-goods-item-'+goods_id+'"><input type="hidden" name="'+form_name+'" value="'+goods_id+'" /><a href="'+goods_url+'" target="_blank" class="am-text-truncate"><img src="'+goods_img+'" alt="'+goods_title+'" class="am-fl am-margin-right-xs" width="20" height="20" /><span>'+goods_title+'</span></a><button type="button" class="am-close am-fr">&times;</button></li>');
        }
        $this.parent().html(icon_html);
    });

    // 弹窗全屏
    $('#siteset-goods-popup').on('click', '.am-popup-hd .am-full', function()
    {
        var width = $(window).width();
        var height = $(window).height();
        if(width >= 630 && height >= 630)
        {
            var $parent = $(this).parents('.am-popup');
            if($parent.hasClass('popup-full'))
            {
                $parent.find('.am-gallery').addClass('am-avg-lg-5').removeClass('am-avg-lg-8');
            } else {
                $parent.find('.am-gallery').addClass('am-avg-lg-8').removeClass('am-avg-lg-5');
            }
        }
    });
});