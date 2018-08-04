const app = getApp();
Page({
  data: {
    indicator_dots: false,
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    autoplay: true,
    circular: true,
    data_list_loding_status: 1,
    data_bottom_line_status: false,

    banner_list: [],
    category_list: [],
    cart_count: 0,
    username: null,
    nickname: null,
    customer_service_tel: null,

    load_status: 0,
  },
  
  onShow() {
    this.init();
  },

  // 获取数据列表
  init() {
    var self = this;

    // 加载loding
    this.setData({
      data_list_loding_status: 1,
    });

    // 加载loding
    my.httpRequest({
      url: app.get_request_url("Index", "Index"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        self.setData({load_status: 1});

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            banner_list: data.banner,
            indicator_dots: (data.banner.length > 1),
            autoplay: (data.banner.length > 1),
            category_list: data.category,
            cart_count: data.cart_count,
            username: data.username,
            nickname: data.nickname,
            customer_service_tel: data.customer_service_tel,
            data_list_loding_status: data.category.length == 0 ? 0 : 3,
            data_bottom_line_status: true,
          });
        } else {
          self.setData({
            data_list_loding_status: 0,
            data_bottom_line_status: true,
          });

          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: true,
          load_status: 1,
        });

        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

  // 轮播图事件
  banner_event(e) {
    var value = e.target.dataset.value || null;
    var type = parseInt(e.target.dataset.type);
    if (value != null) {
      switch(type) {
        // web
        case 0 :
          my.navigateTo({url: '/pages/web-view/web-view?url='+value});
          break;

        // 内部页面
        case 1 :
          my.navigateTo({url: value});
          break;

        // 跳转到外部小程序
        case 2 :
          my.navigateToMiniProgram({appId: value});
          break;
      }
    }
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 客服电话
  call_event() {
    if(this.data.customer_service_tel == null)
    {
      my.showToast({
        type: "fail",
        content: "客服电话有误"
      });
    } else {
      my.makePhoneCall({ number: this.data.customer_service_tel });
    }
  },

  // 自定义分享
  onShareAppMessage() {
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/index/index?share=index'
    };
  },

});
