const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    load_status: 0,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    banner_list: [],
    navigation: [],
    common_shop_notice: null,
    common_app_is_enable_search: 1,
    common_app_is_enable_answer: 1,
    common_app_is_header_nav_fixed: 0,
    common_app_is_online_service: 0,

    // 限时秒杀插件
    common_app_is_limitedtimediscount : 0,
    plugins_limitedtimediscount_data: null,
    plugins_limitedtimediscount_timer_title: '距离结束',
    plugins_limitedtimediscount_is_show_time: true,
    plugins_limitedtimediscount_timer: null,
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
    wx.request({
      url: app.get_request_url("index", "index"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.stopPullDownRefresh();
        self.setData({load_status: 1});

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_bottom_line_status: true,
            banner_list: data.banner_list || [],
            navigation: data.navigation || [],
            data_list: data.data_list,
            common_shop_notice: data.common_shop_notice || null,
            common_app_is_enable_search: data.common_app_is_enable_search,
            common_app_is_enable_answer: data.common_app_is_enable_answer,
            common_app_is_header_nav_fixed: data.common_app_is_header_nav_fixed,
            data_list_loding_status: data.data_list.length == 0 ? 0 : 3,
            common_app_is_online_service: data.common_app_is_online_service || 0,
            common_app_is_limitedtimediscount: data.common_app_is_limitedtimediscount || 0,
            plugins_limitedtimediscount_data: data.plugins_limitedtimediscount_data || null,
          });

          // 导航购物车处理
          var cart_total = data.common_cart_total || 0;
          if (cart_total <= 0)
          {
            app.set_tab_bar_badge(2, 0);
          } else {
            app.set_tab_bar_badge(2, 1, cart_total);
          }

          // 限时秒杀倒计时
          if (this.data.common_app_is_limitedtimediscount == 1 && this.data.plugins_limitedtimediscount_data != null)
          {
            this.plugins_limitedtimediscount_countdown();
          }
        } else {
          self.setData({
            data_list_loding_status: 0,
            data_bottom_line_status: true,
          });

          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: true,
          load_status: 1,
        });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 搜索事件
  search_input_event(e) {
    var keywords = e.detail.value || null;
    if (keywords == null) {
      app.showToast("请输入搜索关键字");
      return false;
    }

    // 进入搜索页面
    wx.navigateTo({
      url: '/pages/goods-search/goods-search?keywords='+keywords
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 显示秒杀插件-倒计时
  plugins_limitedtimediscount_countdown() {
    // 销毁之前的任务
    clearInterval(this.data.plugins_limitedtimediscount_timer);

    // 定时参数
    var status = this.data.plugins_limitedtimediscount_data.time.status || 0;
    var msg = this.data.plugins_limitedtimediscount_data.time.msg || '';
    var hours = parseInt(this.data.plugins_limitedtimediscount_data.time.hours) || 0;
    var minutes = parseInt(this.data.plugins_limitedtimediscount_data.time.minutes) || 0;
    var seconds = parseInt(this.data.plugins_limitedtimediscount_data.time.seconds) || 0;
    var self = this;
    if (status == 1) {
      // 秒
      var timer = setInterval(function () {
        if (seconds <= 0) {
          if (minutes > 0) {
            minutes--;
            seconds = 59;
          } else if (hours > 0) {
            hours--;
            minutes = 59;
            seconds = 59;
          }
        } else {
          seconds--;
        }

        self.setData({
          'plugins_limitedtimediscount_data.time.hours': (hours < 10) ? '0' + hours : hours,
          'plugins_limitedtimediscount_data.time.minutes': (minutes < 10) ? '0' + minutes : minutes,
          'plugins_limitedtimediscount_data.time.seconds': (seconds < 10) ? '0' + seconds : seconds,
          plugins_limitedtimediscount_timer: timer,
        });

        if (hours <= 0 && minutes <= 0 && seconds <= 0) {
          // 停止时间
          clearInterval(timer);

          // 活动已结束
          self.setData({
            plugins_limitedtimediscount_timer_title: '活动已结束',
            plugins_limitedtimediscount_is_show_time: false,
          });
        }
      }, 1000);
    } else {
      // 活动已结束
      self.setData({
        plugins_limitedtimediscount_timer_title: msg,
        plugins_limitedtimediscount_is_show_time: false,
      });
    }
  },

  // 页面从前台变为后台时执行
  onHide: function () {
    clearInterval(this.data.plugins_limitedtimediscount_timer);
  },

  // 页面销毁时执行
  onUnload: function () {
    clearInterval(this.data.plugins_limitedtimediscount_timer);
  },

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = (user != null && (user.id || null) != null) ? user.id : 0;
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/index/index?referrer=' + user_id
    };
  },

});
