$(function()
{
    // 自动开启退货窗口
    if($('.orderaftersale-delivery-submit').length > 0)
    {
        if($('.orderaftersale-delivery-submit').data('is-auto-delivery') == 1)
        {
            $('#popup-orderaftersale-delivery').modal('open');
        }
    }

    // 表单面板
    var $form_panel = $('.aftersale-form-panel');

    // 类型切换
    $('.aftersale-type .items-align').on('click', function()
    {
        $('.aftersale-type .items-align').removeClass('selected');
        $(this).addClass('selected');

        // 表单处理
        var type = $(this).data('type');
        if(type != undefined)
        {
            $form_panel.removeClass('none');
            $form_panel.find('input[name="type"]').val(type);
        }

        var json = [];
        switch(type)
        {
            // 仅退款
            case 0 :
                json = $form_panel.find('select[name="reason"]').data('only-json') || null;
                $form_panel.find('.form-number').addClass('none');
                $('.return-only-money-step').removeClass('none');
                $('.return-money-goods-step').addClass('none');
                break;

            // 退款退货
            case 1 :
                json = $form_panel.find('select[name="reason"]').data('goods-json') || null;
                $form_panel.find('.form-number').removeClass('none');
                $('.return-only-money-step').addClass('none');
                $('.return-money-goods-step').removeClass('none');
                break;
        }

        // 退款原因
        if(json.length > 0)
        {
            var html = '';
            for(var i in json)
            {
                html += '<option value="'+json[i]+'">'+json[i]+'</option>';
            }
            $form_panel.find('select[name="reason"]').html(html);
        } else {
            $form_panel.find('select[name="reason"]').html('');
            Prompt('退款原因数据为空');
        }
        $form_panel.find('.chosen-select').val('').trigger('chosen:updated');
    });

    // 数量加减
    $('.number-container .am-input-group-label').on('click', function()
    {
        var number = $('.number-container input').val();
        var max = $('.number-container input').attr('max') || 1;
        if(($(this).data('type') || 0) == 0)
        {
            number--;
        } else {
            number++;
        }
        if(number <= 0)
        {
            number = 1;
        }
        if(number > max)
        {
            number = max;
        }
        $('.number-container input').val(number);
    });
});