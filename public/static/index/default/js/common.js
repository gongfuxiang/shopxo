console.log("%c\u5b89\u5168\u8b66\u544a\uff01","font-size:50px;color:red;-webkit-text-fill-color:red;-webkit-text-stroke: 1px black;");
console.log("%c\u6b64\u6d4f\u89c8\u5668\u529f\u80fd\u4e13\u4f9b\u5f00\u53d1\u8005\u4f7f\u7528\u3002\u8bf7\u4e0d\u8981\u5728\u6b64\u7c98\u8d34\u6267\u884c\u4efb\u4f55\u5185\u5bb9\uff0c\u8fd9\u53ef\u80fd\u4f1a\u5bfc\u81f4\u60a8\u7684\u8d26\u6237\u53d7\u5230\u653b\u51fb\uff0c\u7ed9\u60a8\u5e26\u6765\u635f\u5931 \uff01","font-size: 20px;color:#333");
console.log("\u6280\u672f\u652f\u6301\uff1a\u0068\u0074\u0074\u0070\u003a\u002f\u002f\u0073\u0068\u006f\u0070\u0078\u006f\u002e\u006e\u0065\u0074\u002f");

var store = $.AMUI.store;
if(!store.enabled)
{
  alert('您的浏览器不支持本地存储。请禁用“专用模式”，或升级到现代浏览器。');
} else {
    // 选择缓存key
    var store_user_menu_key = 'store-user-menu-active-key';
}

// 购物车数量更新
function HomeCartNumberTotalUpdate(number)
{
    var $this = $('.common-cart-total');
    if(number <= 0)
    {
        $this.text(0);
        $('.mobile-navigation .common-cart-total').text('');
        $this.removeClass('am-badge am-badge-danger');
    } else {
        $this.text(number);
        $this.addClass('am-badge am-badge-danger');
    }
}

$(function()
{
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
    $(".category-content li").hover(function() {
        $(".category-content .category-list li.first .menu-in").css("display", "none");
        $(".category-content .category-list li.first").removeClass("hover");
        $(this).addClass("hover");
        $(this).children("div.menu-in").css("display", "block");
    }, function() {
        $(this).removeClass("hover")
        $(this).children("div.menu-in").css("display", "none");
    });

    // 非首页的页面商品分类显示/隐藏
    $('#goods-category').hover(function()
    {
        if($(this).data('controller-name') != 'index')
        {
            if(!$('#goods-category .category-content').is(":visible"))
            {
                $('#goods-category .category-content').slideDown(100);
            }
        }
    }).mouseleave(function() {
        if($(this).data('controller-name') != 'index')
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
    $('.login-event').on('click', function()
    {
        // 是否登录
        if(__user_id__ == 0)
        {
            ModalLoad(__modal_login_url__, '登录', 'common-popup-modal-login', 'common-login-modal');
            return false;
        }
    });
    
    // 用户中心菜单
    $('.user-item-parent').on('click', function()
    {
        $(this).find('.am-icon-angle-down').toggleClass('more-icon-rotate');
    });
    $('.user-sidebar-list li').on('click', function()
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
      $('.common-cropper-popup input[type="file"]').on('change', function () {
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
    
});