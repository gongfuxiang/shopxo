$(function()
{
  // 在线留言表单初始化
  FromInit('form.form-validation-plugins-commonrightnavigation-answer');
  
  // 回顶部监测
  $(window).scroll(function()
  {
    if($(window).scrollTop() > 100)
    {
      $("#plugins-commonrightnavigation").fadeIn(1000);
    } else {
      $("#plugins-commonrightnavigation").fadeOut(1000);
    }
  });

  // 购物车查询
  $('.commonrightnavigation-cart').on('mouseenter', function()
  {
    // 当前鼠标是否还在元素上，防止鼠标直接进入子级元素导致重复执行事件
    if($(this).attr('data-is-has-mouse') == 1)
    {
      return false;
    } else {
      $(this).attr('data-is-has-mouse', 1);
    }
    
    // url
    var $this = $(this);
    var ajax_url = $this.data('cart-ajax-url');
    var delete_url = $this.data('cart-delete-ajax-url');

    // ajax请求
    $.ajax({
        url: ajax_url,
        type: 'post',
        dataType: "json",
        timeout: 10000,
        data: {},
        success: function(result)
        {
            if(result.code == 0 && result.data.cart_list.length > 0)
            {
              var html = '<table class="am-table">';
              for(var i in result.data.cart_list)
              {
                html += '<tr id="data-list-'+result.data.cart_list[i]['id']+'" data-id="'+result.data.cart_list[i]['id']+'" data-goods-id="'+result.data.cart_list[i]['id']+'" class="'+(result.data.cart_list[i]['is_shelves'] != 1 ? 'am-warning' : '')+(result.data.cart_list[i]['is_delete_time'] != 0 ? 'am-danger' : '')+'">';
                html += '<td class="base">';
                html += '<div class="goods-detail">';
                html += '<a href="'+result.data.cart_list[i]['goods_url']+'" target="_blank">';
                html += '<img src="'+result.data.cart_list[i]['images']+'">';
                html += '</a>';
                html += '<div class="goods-base">';
                html += '<a href="'+result.data.cart_list[i]['goods_url']+'" target="_blank" class="goods-title">'+result.data.cart_list[i]['title']+'</a>';
                if((result.data.cart_list[i]['spec'] || null) != null)
                {
                  html += '<ul class="goods-attr">';
                  for(var s in result.data.cart_list[i]['spec'])
                  {
                    html += '<li>'+result.data.cart_list[i]['spec'][s]['type']+'：'+result.data.cart_list[i]['spec'][s]['value']+'</li>';
                  }
                  html += '</ul>';
                }
                html += '<td class="total-price">';
                html += '<strong class="total-price-content">￥'+result.data.cart_list[i]['total_price']+'</strong>';
                html += '<span class="cart-number"> x'+result.data.cart_list[i]['stock']+'</span>';
                html += '</td>';
                html += '<td class="operation">';
                html += '<a href="javascript:;" class="submit-delete" data-url="'+delete_url+'" data-id="'+result.data.cart_list[i]['id']+'" data-view="fun" data-value="PluginsCartViewDeleteBack" data-is-confirm="0">删除</a>';
                html += '</td>';
                html += '</tr>';
              }
              html += '</table>';
              $this.find('.cart-items').html(html);
              $this.find('.mixed-tips').hide();
              $this.find('.cart-nav').show();
              $this.find('.cart-items').show();
              $this.find('.cart-nav .selected-tips strong').text(result.data.base.cart_count);
              $this.find('.cart-nav .nav-total-price').text('￥'+result.data.base.total_price);
              $this.find('.cart-nav input[name="ids"]').val(result.data.base.ids);
              HomeCartNumberTotalUpdate(result.data.base.cart_count);
            } else {
              $this.find('.mixed-tips').show();
              $this.find('.cart-nav').hide();
              $this.find('.cart-items').hide();
              $this.find('.cart-nav .selected-tips strong').text(0);
              $this.find('.cart-nav .nav-total-price').text('￥0.00');
              $this.find('.cart-nav input[name="ids"]').val('');
              HomeCartNumberTotalUpdate(0);
            }
        },
        error: function(xhr, type)
        {
            Prompt('服务器错误');
        }
    });
  }).mouseleave(function()
  {
    // 鼠标离开元素标记
    $(this).attr('data-is-has-mouse', 0);
  });
});