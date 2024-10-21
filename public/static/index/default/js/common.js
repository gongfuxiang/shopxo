console.log("%c\u5b89\u5168\u8b66\u544a\uff01","font-size:50px;color:red;-webkit-text-fill-color:red;-webkit-text-stroke: 1px black;");
console.log("%c\u6b64\u6d4f\u89c8\u5668\u529f\u80fd\u4e13\u4f9b\u5f00\u53d1\u8005\u4f7f\u7528\u3002\u8bf7\u4e0d\u8981\u5728\u6b64\u7c98\u8d34\u6267\u884c\u4efb\u4f55\u5185\u5bb9\uff0c\u8fd9\u53ef\u80fd\u4f1a\u5bfc\u81f4\u60a8\u7684\u8d26\u6237\u53d7\u5230\u653b\u51fb\uff0c\u7ed9\u60a8\u5e26\u6765\u635f\u5931 \uff01","font-size: 20px;color:#e04343");
console.log("\u6280\u672f\u652f\u6301\uff1a\u0068\u0074\u0074\u0070\u003a\u002f\u002f\u0073\u0068\u006f\u0070\u0078\u006f\u002e\u006e\u0065\u0074\u002f");

var store = $.AMUI.store;
if(!store.enabled)
{
    alert(lang_store_enabled_tips || '您的浏览器不支持本地存储。请禁用“专用模式”，或升级到现代浏览器。');
} else {
    // 选择缓存key
    var store_user_menu_key = 'store-user-menu-active-key';
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
    // 是否存在数据列表
    if($('.am-table-scrollable-horizontal').length > 0)
    {
        // 内容高度
        if($(window).width() >= 641)
        {
            var pure_top = $('.popup-pure-page').length > 0 ? parseInt($('.popup-pure-page').css('padding-top').replace('px', '') || 0) : 0;
            var height_header_top = $('.header-top').outerHeight(true) || 0;
            var height_nav_search = $('.nav-search').outerHeight(true) || 0;
            var height_nav_shop = $('.shop-navigation').outerHeight(true) || 0;
            var height_forn_content_top = $('.form-table-content-top').outerHeight(true) || 0;
            var height_form_nav = $('.form-table-navigation').outerHeight(true) || 0;
            var height_form_top = $('.form-table-operate-top').outerHeight(true) || 0;
            var height_form_bottom = $('.form-table-operate-bottom').outerHeight(true) || 0;
            var height_form_page = $('.form-table-content .am-pagination').outerHeight(true) || 0;
            var header_nav_simple = $('.header-nav-simple').outerHeight(true) || 0;
            var user_center_main_title = $('.user-center-main-title').outerHeight(true) || 0;
            var user_center_ext = $('#user-offcanvas').length > 0 ? 60 : 0;

            // 数据列表
            $('.am-table-scrollable-horizontal').css('height', 'calc(100vh - '+(pure_top+height_header_top+height_nav_search+height_nav_shop+height_forn_content_top+height_form_nav+height_form_top+height_form_bottom+height_form_page+header_nav_simple+user_center_main_title+user_center_ext+2)+'px)');
        }

        // 用户中心左侧导航
        if($('#user-offcanvas').length > 0)
        {
            if($(window).width() <= 640)
            {
                $('#user-offcanvas').css('height', 'auto');
            } else {
                $('#user-offcanvas').css('height', 'calc(100vh - '+((height_header_top+header_nav_simple)-24)+'px)');
            }
        }
    }
}

/**
 * 购物车数量更新
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-06-14
 * @desc    description
 * @param   {[int]}        number [数量值]
 */
function HomeCartNumberTotalUpdate(number)
{
    var $this = $('.common-cart-total');
    if(number <= 0)
    {
        $this.text(0);
        $('.mobile-navigation .common-cart-total').text('');
    } else {
        $this.text(number);
    }
}

/**
 * 用户购物车成功弹窗展示
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-01-02
 * @desc    description
 * @param   {[int]}        number [购物车数量]
 */
var home_user_cart_success_modal_timer = null;
function HomeUserCartSuccessModal(number = null)
{
    // 先清除之前的定时任务
    clearInterval(home_user_cart_success_modal_timer);

    // 设置数量并打开弹窗
    var $modal = $('#common-user-cart-success-modal');
    if(number !== null)
    {
        $modal.find('.common-cart-total').text(number > 99 ? '99+' : number);
    }
    $modal.modal({
        closeViaDimmer: 0,
        width: 360,
        height: 200,
        dimmer: false
    });

    // 定时5秒关闭弹窗
    home_user_cart_success_modal_timer = setTimeout(function()
    {
        $modal.modal('close');
    }, 5000);

    // 窗口呗关闭则清除定时任务
    $modal.on('close.modal.amui', function()
    {
        clearInterval(home_user_cart_success_modal_timer);
    });
}

/**
 * 主题数据管理编辑事件初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-04-11
 * @desc    description
 */
function ThemeDataEditEventInit()
{
    if($('.theme-data-admin-container').length > 0) {
        $('.theme-data-edit-event').each(function(k, v) {
            if($(this).find('.theme-data-edit-container').length == 0) {
                $(this).append(`<div class="theme-data-edit-container am-radius">
                        <div class="theme-data-edit-border theme-data-edit-border-top"></div>
                        <div class="theme-data-edit-border theme-data-edit-border-right"></div>
                        <div class="theme-data-edit-border theme-data-edit-border-bottom"></div>
                        <div class="theme-data-edit-border theme-data-edit-border-left"></div>
                        <div class="theme-data-edit-content">
                            <button class="am-btn am-btn-default am-btn-sm am-radius theme-data-edit-submit">
                                <i class="iconfont icon-edit"></i>
                                <span>`+(window['lang_operate_modify_name'] || '修改')+`</span>
                            </button>
                        </div>
                    </div>`);
            }
        });
    }
}

$(function()
{
    // 浏览器窗口实时事件
    $(window).resize(function()
    {
        // 动态数据表格高度处理
        FormTableHeightHandle();
    });
    // 动态数据表格高度处理
    FormTableHeightHandle();

    // 选中处理
    if(store.enabled)
    {
        // 用户菜单选中
        if($('.user-sidebar-list li.am-active').length == 0)
        {
            var user_menu_value = store.get(store_user_menu_key);
            if(user_menu_value !== undefined)
            {
                $('.user-sidebar-list li').removeClass('am-active')
                $('.user-sidebar-list').find('.'+user_menu_value).addClass('am-active');
            }
        }
    }

    // 商品分类子级内容显示/隐藏
    $('.category-content li').hover(function()
    {
        $('.category-content .category-list li.first .menu-in').css('display', 'none');
        $('.category-content .category-list li.first').removeClass('hover');
        $(this).addClass('hover');
        $(this).children('div.menu-in').css('display', 'block');
        $(this).children('.category-fillet-top').removeClass('am-hide');
        $(this).children('.category-fillet-bottom').removeClass('am-hide');
        if($(this).data('index') == 0)
        {
            $(this).children('.category-fillet-top').addClass('am-hide');
        }
        if($(this).data('index') == $(this).parent().children().length - 1) {
            $(this).children('.category-fillet-bottom').addClass('am-hide');
        }
    }, function() {
        $(this).removeClass('hover')
        $(this).children('div.menu-in').css('display', 'none');
        $(this).children('.category-fillet-top').addClass('am-hide');
        $(this).children('.category-fillet-bottom').addClass('am-hide');
    });

    // 全局商品分类显示/隐藏
    var goods_category_display = $('#goods-category .category-content').css('display');
    $('#goods-category').hover(function()
    {
        if(goods_category_display == 'none')
        {
            if(!$('#goods-category .category-content').is(":visible"))
            {
                $('#goods-category .category-content').slideDown(100);
            }
        }
    }).mouseleave(function() {
        if(goods_category_display == 'none')
        {
            $('#goods-category .category-content').slideUp(100);
        }
    });

    // 搜索导航固定
    $(window).scroll(function()
    {
        if($(window).width() <= 625)
        {
            if($('.nav-search').length > 0 && ($('.nav-search').css('display') || null) == 'block')
            {
                if($(document).scrollTop() > 0)
                {
                    $('.nav-search').css('position','fixed');
                    $('body').css('padding-top','45px');
                } else {
                    $('.nav-search').css('position','relative');
                    $('body').css('padding-top','0');
                }
            }
        }
    });
    $(window).resize(function()
    {
        if($(window).width() > 625)
        {
            $('.nav-search').css('position','relative');
            $('body').css('padding-top','0');
        }
    });

    // 登录事件
    $(document).on('click', '.login-event', function()
    {
        // 是否登录
        if((__user_id__ || 0) == 0)
        {
            // 是否进入登录页面
            if(parseInt($(this).data('login-info') || 0) == 1)
            {
                window.location.href = __user_login_info_url__;
            } else {
                ModalLoad(__modal_login_url__, '', 'common-login-modal');
            }
            return false;
        }
    });
    
    // 用户中心菜单
    $(document).on('click', '.user-item-parent', function()
    {
        $(this).find('.am-icon-angle-down').toggleClass('more-icon-rotate');
    });
    $(document).on('click', '.user-sidebar-list li', function()
    {
        var value = $(this).data('value') || null;
        if(value != null)
        {
            store.set(store_user_menu_key, value);
        }
    });

    
    /**
     * 用户头像上传插件初始化
     */
    var $image = $('.user-avatar-img-container > img'),
        $dataX = $('#user-avatar-img_x'),
        $dataY = $('#user-avatar-img_y'),
        $dataHeight = $('#user-avatar-img_height'),
        $dataWidth = $('#user-avatar-img_width'),
        $dataRotate = $('#user-avatar-img_rotate'),
        options = {
            // strict: false,
            // responsive: false,
            // checkImageOrigin: false

            // modal: false,
            // guides: false,
            // highlight: false,
            // background: false,

            // autoCrop: false,
            // autoCropArea: 0.5,
            // dragCrop: false,
            // movable: false,
            // resizable: false,
            // rotatable: false,
            // zoomable: false,
            // touchDragZoom: false,
            // mouseWheelZoom: false,

            // minCanvasWidth: 320,
            // minCanvasHeight: 180,
            // minCropBoxWidth: 160,
            // minCropBoxHeight: 90,
            // minContainerWidth: 320,
            // minContainerHeight: 180,

            // build: null,
            // built: null,
            // dragstart: null,
            // dragmove: null,
            // dragend: null,
            // zoomin: null,
            // zoomout: null,

            aspectRatio: 1 / 1,
            preview: '.user-avatar-img-preview',
            crop: function (data) {
                $dataX.val(Math.round(data.x));
                $dataY.val(Math.round(data.y));
                $dataHeight.val(Math.round(data.height));
                $dataWidth.val(Math.round(data.width));
                $dataRotate.val(Math.round(data.rotate));
            }
        };
    $image.on({}).cropper(options);

    // 缩放操作
    $(document.body).on('click', '.common-cropper-popup [data-method]', function () {
      var data = $(this).data(),
          $target,
          result;
      if (data.method) {
        data = $.extend({}, data); // Clone a new one
        if (typeof data.target !== 'undefined') {
          $target = $(data.target);

          if (typeof data.option === 'undefined') {
            try {
              data.option = JSON.parse($target.val());
            } catch (e) {
              Prompt(e.message);
            }
          }
        }

        result = $image.cropper(data.method, data.option);

        if (data.method === 'getCroppedCanvas') {
          $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
        }

        if ($.isPlainObject(result) && $target) {
          try {
            $target.val(JSON.stringify(result));
          } catch (e) {
            Prompt(e.message);
          }
        }
      }
    }).on('keydown', function (e) {});


    // 头像图片上传
    var URL = window.URL || window.webkitURL, blobURL;
    if (URL) {
      $(document).on('change', '.common-cropper-popup input[type="file"]', function () {
        var files = this.files,
            file;

        if (files && files.length) {
          file = files[0];

          if (/^image\/\w+$/.test(file.type)) {
            blobURL = URL.createObjectURL(file);
            $image.one('built.cropper', function () {
              URL.revokeObjectURL(blobURL); // Revoke when load complete
            }).cropper('reset', true).cropper('replace', blobURL);
          } else {
            Prompt('Please choose an image file.');
          }
        } else {
          $image.cropper('reset', true).cropper('replace', $image.attr('src'));
        }
      });
    }

    // 头像表单初始化
    if($('form.form-validation-user-avatar').length > 0)
    {
      FromInit('form.form-validation-user-avatar');
    }

    // 公共商品收藏
    $(document).on('click', '.common-goods-favor-submit-event, .buy-nav-left-favor-submit', function()
    {
        // 是否登录
        if((__user_id__ || 0) == 0)
        {
            // 是否已指定登录class事件
            if(!$(this).hasClass('login-event'))
            {
                ModalLoad(__modal_login_url__, '', 'common-login-modal');
            }
            return false;
        }

        // 收藏处理
        var goods_id = $(this).data('gid') || null;
        if(goods_id != null)
        {
            var $this = $(this);
            $.AMUI.progress.start();
            $.ajax({
                url: RequestUrlHandle(__goods_favor_url__),
                type: 'post',
                dataType: "json",
                timeout: 10000,
                data: {"id": goods_id},
                success: function(res)
                {
                    $.AMUI.progress.done();
                    if(res.code == 0)
                    {
                        if($this.find('.goods-favor-text').length > 0)
                        {
                            $this.find('.goods-favor-text').text(res.data.text);
                        } else {
                            if($this.find('.name').length > 0)
                            {
                                $this.find('.name').text(res.data.text);
                            }
                        }
                        if(res.data.status == 1)
                        {
                            $this.addClass('am-active');
                            if($this.find('i').length > 0)
                            {
                                $this.find('i').addClass('icon-heart').removeClass('icon-heart-o');
                            } else if($this.hasClass('icon-heart-o')) {
                                $this.addClass('icon-heart').removeClass('icon-heart-o');
                            }
                        } else {
                            $this.removeClass('am-active');
                            if($this.find('i').length > 0)
                            {
                                $this.find('i').addClass('icon-heart-o').removeClass('icon-heart');
                            } else if($this.hasClass('icon-heart')) {
                                $this.addClass('icon-heart-o').removeClass('icon-heart');
                            }
                        }
                        Prompt(res.msg, 'success');
                    } else {
                        Prompt(res.msg);
                    }
                },
                error: function(xhr, type)
                {
                    $.AMUI.progress.done();
                    Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                }
            });
        }
    });
    
    // 公共商品加入购物车
    $(document).on('click', '.common-goods-cart-submit-event', function()
    {
        // 是否登录
        if((__user_id__ || 0) == 0)
        {
            // 是否已指定登录class事件
            if(!$(this).hasClass('login-event'))
            {
                ModalLoad(__modal_login_url__, '', 'common-login-modal');
            }
            return false;
        }

        // 加入购物车处理
        var $this = $(this);
        var goods_id = $this.attr('data-gid');
        var stock = parseInt($this.attr('data-stock') || 0);
        // 是否强制数量
        if(parseInt($this.attr('data-is-force-stock') || 0) == 1 && stock == 0)
        {
            Prompt(window['lang_goods_stock_empty_tips'] || '请输入购买数量');
            return false;
        }
        // 指定规格、先获取指定原始数据字段
        var spec = $this.attr('data-original-spec') || $this.attr('data-spec') || '';
        if(spec !== '')
        {
            spec = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(spec)).toString(CryptoJS.enc.Utf8));
        }
        // 已确定非多规格 或 存在指定多规格数据则直接加入购物车操作
        if(parseInt($(this).data('is-many-spec') || 0) == 0 || spec !== '')
        {
            $.AMUI.progress.start();
            $.ajax({
                url: RequestUrlHandle(__goods_cart_save_url__),
                type: 'post',
                dataType: "json",
                timeout: 10000,
                data: {
                    goods_id: goods_id,
                    stock: stock || 1,
                    spec: spec,
                },
                success: function(res)
                {
                    $.AMUI.progress.done();
                    if(res.code == 0)
                    {
                        // 更新公共购物车数量
                        HomeCartNumberTotalUpdate(res.data.buy_number);

                        // 是否展示购物车成功提示弹窗
                        if(parseInt($this.attr('data-is-cart-success-modal') || 0) == 1)
                        {
                            // 展示购物车成功提示弹窗
                            HomeUserCartSuccessModal(res.data.buy_number);
                        } else {
                            // 提示成功
                            Prompt(res.msg, 'success');
                        }
                    } else {
                        Prompt(res.msg);
                    }
                },
                error: function(xhr, type)
                {
                    $.AMUI.progress.done();
                    Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                }
            });
        } else {
            // 开启规格选择弹窗
            ModalLoad(UrlFieldReplace('id', goods_id, __goods_cart_info_url__), (window['lang_goods_cart_title'] || '加入购物车'), 'common-goods-cart-popup');
        }
    });

    // 商品规格选择
    var $common_goods_spec_choice_submit_event_obj = null;
    $(document).on('click', '.common-goods-spec-choice-submit-event', function()
    {
        // 基础参数
        var goods_id = $(this).data('goods-id') || null;
        if(goods_id == null)
        {
            Prompt(window['lang_goods_id_empty_tips'] || '商品ID数据');
            return false;
        }

        var spec = $(this).data('spec') || null;
        if(spec == null)
        {
            Prompt(window['lang_goods_spec_empty_tips'] || '无规格数据');
            return false;
        }
        spec = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(spec)).toString(CryptoJS.enc.Utf8));

        // 规格处理
        var html = `<div class="common-goods-spec-choice-container am-padding-sm">`;
        html += `<div class="common-goods-spec-choice-content am-scrollable-vertical" data-id="`+goods_id+`">`;
        for(var i in spec)
        {
        html += `<div class="spec-options sku-items am-radius">
                    <div class="spec-title">`+spec[i]['name']+`</div>
                    <ul>`;
            for(var t in spec[i]['value'])
            {
              var temp = spec[i]['value'][t];
                html += `<li class="am-radius sku-line `+((temp['images'] || null) != null ? ' sku-line-images' : '')+` `+((i > 0) ? ' sku-dont-choose' : '')+` `+((parseInt(temp['is_only_level_one'] || 0) == 1 && parseInt(temp['inventory'] || 0) <= 0) ? ' sku-items-disabled' : '')+`" data-type-value="`+spec[i]['name']+`" data-value="`+temp['name']+`" data-type-images="`+((temp['images'] || null) != null ? temp['images'] : '')+`">`;
                        if((temp['images'] || null) != null)
                        {
                          html += `<img src="`+temp['images']+`" class="am-radius am-margin-right-xs" />`;
                        }
                        html += temp['name']+`<i></i>
                    </li>`;
            }
        html += `</ul>`;
        html += `</div>`;
        }
        html += `</div>`;
        html += `<div class="am-text-right am-margin-top-lg">
                <button type="button" class="am-btn am-btn-warning am-radius am-btn-xs am-margin-right-lg" data-am-modal-close>
                    <i class="am-icon-paint-brush"></i>
                    <span>`+(window['lang_cancel_name'] || '取消')+`
                </button>
                <button type="button" class="am-btn am-btn-primary am-radius am-btn-xs common-goods-spec-choice-confirm-submit">
                    <i class="am-icon-check"></i>
                    <span>`+(window['lang_confirm_name'] || '确认')+`
                </button>
            </div>
        </div>`;

        // 调用弹窗组件
        AMUI.dialog.alert({
            isClose: true,
            content: html
        });
        // 当前对象赋值
        $common_goods_spec_choice_submit_event_obj = $(this);
    });

    // 商品规格选择
    $(document).on('click', '.common-goods-spec-choice-content .spec-options ul>li', function()
    {
        // 规格处理
        var $parent = $(this).parents('.common-goods-spec-choice-content');
        var length = $parent.find('.sku-items').length;
        var index = $(this).parents('.sku-items').index();
        if($(this).hasClass('selected'))
        {
            $(this).removeClass('selected');

            // 去掉元素之后的禁止
            $parent.find('.sku-items').each(function(k, v)
            {
                if(k > index)
                {
                    $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                }
            });
        } else {
            if($(this).hasClass('sku-items-disabled') || $(this).hasClass('sku-dont-choose'))
            {
                return false;
            }
            $(this).addClass('selected').siblings('li').removeClass('selected');
            $(this).parents('.sku-items').removeClass('sku-not-active');

            // 去掉元素之后的禁止
            if(index < length)
            {
                $parent.find('.sku-items').each(function(k, v)
                {
                    if(k > index)
                    {
                        $(this).find('li').removeClass('sku-items-disabled').removeClass('selected').addClass('sku-dont-choose');
                    }
                });
            }

            // 获取下一个规格类型
            CommonGoodsChoiceSpecType();

            // 获取规格详情
            CommonGoodsChoiceSpecDetail();
        }
    });

    // 商品规格选择确认
    $(document).on('click', '.common-goods-spec-choice-confirm-submit', function()
    {
        var $parent = $(this).parents('.common-goods-spec-choice-container');
        // 是否存在规格数据
        var sku_count = $parent.find('.sku-items').length;
        if(sku_count <= 0)
        {
            Prompt(window['lang_goods_spec_empty_tips'] || '无规格数据');
            return false;
        }

        // 是否已选择处理
        var spec_count = $('.sku-line.selected').length;
        if(spec_count < sku_count)
        {
            $parent.find('.sku-items').each(function(k, v)
            {
                if($(this).find('.sku-line.selected').length == 0)
                {
                    $(this).addClass('sku-not-active');
                }
            });
            Prompt(window['lang_goods_no_choice_spec_tips'] || '请选择规格');
            return false;
        }

        // 已选规格
        var spec = [];
        var value = [];
        $('.sku-items li.selected').each(function(k, v)
        {
            spec.push({"type": $(this).data('type-value'), "value": $(this).data('value')});
            value.push($(this).data('value'));
        });

        // 属性赋值
        $common_goods_spec_choice_submit_event_obj.attr('data-value', encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(spec)))))

        // 赋值已选
        var is_text_value = $common_goods_spec_choice_submit_event_obj.data('is-text-value');
        if(is_text_value == undefined || is_text_value == 1)
        {
            $common_goods_spec_choice_submit_event_obj.text(value.join(' / '));
        }

        // 关闭弹窗
        $(this).parents('.am-modal-dialog').find('.am-modal-hd .am-close').trigger('click');

        // 回调方法
        var method = $common_goods_spec_choice_submit_event_obj.data('back-method') || null;
        if(method != null)
        {
            if(IsExitsFunction(method))
            {
                window[method]({obj: $common_goods_spec_choice_submit_event_obj, spec: spec, value: value});
            }
        }
    });

    // tabs选项卡，底部边框动态效果 - 初始化
    $('.am-tabs-border>ul.am-tabs-nav').each(function(k, v)
    {
        $(this).append('<p class="bar"></p>');
        if($(this).find('> li.am-active').length > 0)
        {
            var $this = $(this);
            setTimeout(function() {
                $this.find('> li.am-active').trigger('click');
            }, 0);
        } else {
            $(this).find('> .bar').css({width: $(this).find(' >li:eq(0)').width(), transform: 'translateX(0%)'});
        }
    });
    // tabs选项卡，底部边框动态效果 - 点击切换
    $(document).on('click', '.am-tabs-border>ul.am-tabs-nav > li', function(e)
    {   
        var $parent = $(this).parent();
        var $sub = $(this).find('> a');
        var index = $(this).index();
        var padding_left = parseInt($sub.css('padding-left').replace('px', ''));
        var width = $(this).position().left+padding_left;
        $parent.find('> .bar').css({width: $sub.width(), transform: 'translateX('+width+'px)'});
    });

    // 查看密码
    $(document).on('click', '.eye-submit', function()
    {
        var $obj = $(this).parent().parent().find('input');
        if($obj.attr('type') == 'password')
        {
            $(this).removeClass('am-icon-eye').addClass('am-icon-eye-slash');
            $obj.attr('type', 'text');
        } else {
            $(this).removeClass('am-icon-eye-slash').addClass('am-icon-eye');
            $obj.attr('type', 'password');
        }
    });
    // 用户验证码获取
    $(document).on('click', '.user-form-content-container .user-verify-submit, #user-verify-win .user-verify-win-submit', function()
    {
        // 表单发送按钮
        var form_tag = $(this).data('form-tag') || null;
        if(form_tag != null)
        {
            $('body').attr('data-form-tag', form_tag);
        }
        
        // 验证账户
        var $this = $(this);
        var $form_tag = $($('body').attr('data-form-tag'));
        var $form_verify_submit = $form_tag.find('.user-verify-submit');
        var $accounts = $form_tag.find('input[name="accounts"]');
        var $user_verify_win = $('#user-verify-win');
        var $user_verify_win_submit = $user_verify_win.find('.user-verify-win-submit');
        var $verify = $user_verify_win.find('#user-verify-win-img-value');
        var $verify_img = $user_verify_win.find('#user-verify-win-img');
        var verify = '';
        if($accounts.hasClass('am-field-valid'))
        {
            // 是否需要先校验图片验证码
            if($this.data('verify') == 1)
            {
                // 开启图片验证码窗口
                $user_verify_win.modal({closeViaDimmer:false});
                $verify_img.trigger("click");
                $verify.val('');
                $verify.focus();
                return false;
            }

            // 验证码窗口操作按钮则更新按钮对象
            var is_win = $(this).data('win');
            if(is_win == 1)
            {
                $this = $form_verify_submit;

                // 验证码参数处理
                verify = $verify.val().replace(/\s+/g, '');

                if(verify.length != 4)
                {
                    Prompt($verify.data('validation-message'));
                    $verify.focus();
                    return false;
                }
            }

            // 验证码时间间隔
            var time_count = parseInt($this.data('time'));
            
            // 按钮交互
            $this.button('loading');
            if(is_win == 1)
            {
                $user_verify_win_submit.button('loading');
            }

            // 发送验证码
            $.ajax({
                url: RequestUrlHandle($form_verify_submit.data('url')),
                type: 'POST',
                data: {"accounts":$accounts.val(), "verify":verify, "type":$form_tag.find('input[name="type"]').val()},
                dataType: 'json',
                success: function(result)
                {
                    if(result.code == 0)
                    {
                        var intervalid = setInterval(function()
                        {
                            if(time_count == 0)
                            {
                                $this.button('reset');
                                if(is_win == 1)
                                {
                                    $user_verify_win_submit.button('reset');
                                }
                                $this.text($this.data('text'));
                                $verify.val('');
                                clearInterval(intervalid);
                            } else {
                                var send_msg = $this.data('send-text').replace(/{time}/, time_count--);
                                $this.text(send_msg);
                            }
                        }, 1000);
                        if($user_verify_win.length > 0)
                        {
                            $user_verify_win.modal('close');
                        }
                    } else {
                        $this.button('reset');
                        if(is_win == 1)
                        {
                            $user_verify_win_submit.button('reset');
                            $verify_img.trigger("click");
                        }
                        Prompt(result.msg);
                    }
                },
                error: function(xhr, type)
                {
                    $this.button('reset');
                    if(is_win == 1)
                    {
                        $user_verify_win_submit.button('reset');
                    }
                    Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
                }
            });         
        } else {
            if($user_verify_win.length > 0)
            {
                $user_verify_win.modal('close');
            }
            $accounts.focus();
        }
    });

    // 主题数据修改初始化
    ThemeDataEditEventInit();

    // 主题数据修改事件
    $(document).on('click', '.theme-data-edit-container .theme-data-edit-content button.theme-data-edit-submit', function() {
        var $parent = $(this).parents('.theme-data-edit-event');
        var id = $parent.data('id') || null;
        var module = $parent.data('module') || 'themedata';
        var url = window['__theme_data_admin_'+module+'_url__'] || null;
        if(url == null) {
            Prompt((window['lang_themedata_admin_url_error_tips'] || '主题数据管理url地址有误')+'('+module+')');
            return false;
        }
        // 存在id则增加id参数
        if(id !== null) {
            url = UrlFieldReplace('id', id, url);
        }
        // 打开弹窗
        ModalLoad(url, (window['themedata_admin_title'] || '主题数据管理'), '', 1, 1, 'lg', null, function() {
            // 关闭窗口则刷新页面
            window.location.reload();
        });
    });
});