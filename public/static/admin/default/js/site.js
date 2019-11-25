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
            html += '<span>'+data['province_name']+' '+data['city_name']+' '+data['county_name']+' '+address+'（'+name+'-'+tel+'）';
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
        var $popup = $('#popup-address-win');

        // 操作类型（add, edit）
        var form_type = $popup.attr('data-type') || 'add';
        if(form_type == 'add')
        {
            $('ul.address-list').append(html);
            data['id'] = value.length;
            value.push(data);
        } else {
            var form_index = $popup.attr('data-index') || 0;
            data['id'] = form_index;
            value.splice(form_index, 1, data);
            $('ul.address-list').find('li').eq(form_index).replaceWith(html);
        }
        $popup.modal('close');
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
    var $popup = $('#popup-address-win');

    // 地址添加开启
    $('.address-submit-add').on('click', function()
    {
        $popup.modal();
        $popup.attr('data-type', 'add');

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

        // 数据填充
        FormDataFill(item, 'form.form-validation-address');

        // 地区初始化
        RegionNodeData(item['province'], 'city', 'city', item['city']);
        RegionNodeData(item['city'], 'county', 'county', item['county']);

        // 基础数据
        $popup.modal();
        $popup.attr('data-type', 'edit');
        $popup.attr('data-index', index);

        // 地图初始化
        MapInit(item.lng, item.lat);
    });
});