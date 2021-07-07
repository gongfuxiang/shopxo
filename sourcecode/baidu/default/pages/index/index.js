const app = getApp();
Page({
  data: {
    load_status: 0,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    banner_list: [],
    navigation: [],

    // 基础配置
    currency_symbol: app.data.currency_symbol,
    common_shop_notice: null,
    home_index_floor_data_type: 0,
    common_app_is_enable_search: 0,
    common_app_is_enable_answer: 0,
    common_app_is_header_nav_fixed: 0,
    common_app_is_online_service: 0,

    // 限时秒杀插件
    plugins_limitedtimediscount_is_valid: 0,
    plugins_limitedtimediscount_data: null,
    plugins_limitedtimediscount_timer_title: '距离结束',
    plugins_limitedtimediscount_is_show_time: true,
    plugins_limitedtimediscount_timer: null,

    // 购买记录插件
    plugins_salerecords_data: null,
  },

  onShow() {
    // 数据加载
    this.init();

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        currency_symbol: app.get_config('currency_symbol'),
        common_shop_notice: app.get_config('config.common_shop_notice'),
        home_index_floor_data_type: app.get_config('config.home_index_floor_data_type'),
        common_app_is_enable_search: app.get_config('config.common_app_is_enable_search'),
        common_app_is_enable_answer: app.get_config('config.common_app_is_enable_answer'),
        common_app_is_header_nav_fixed: app.get_config('config.common_app_is_header_nav_fixed'),
        common_app_is_online_service: app.get_config('config.common_app_is_online_service'),
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  init() {
    var self = this;

    // 加载loding
    this.setData({
      data_list_loding_status: 1
    });

    // 加载loding
    swan.request({
      url: app.get_request_url("index", "index"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        swan.stopPullDownRefresh();
        
        // 获取最新缓存
        if(this.data.load_status == 0) {
          self.init_config(true);
        }

        // 设置首次加载状态
        self.setData({load_status: 1});

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_bottom_line_status: true,
            banner_list: data.banner_list || [],
            navigation: data.navigation || [],
            data_list: data.data_list,
            data_list_loding_status: data.data_list.length == 0 ? 0 : 3,
            plugins_limitedtimediscount_data: data.plugins_limitedtimediscount_data || null,
            plugins_limitedtimediscount_is_valid: ((data.plugins_limitedtimediscount_data || null) != null && (data.plugins_limitedtimediscount_data.is_valid || 0) == 1) ? 1 : 0,
            plugins_salerecords_data: ((data.plugins_salerecords_data || null) == null || data.plugins_salerecords_data.length <= 0) ? null : data.plugins_salerecords_data,
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
          if (this.data.plugins_limitedtimediscount_is_valid == 1) {
            this.plugins_limitedtimediscount_countdown();
          }

          // 页面信息设置
          this.set_page_info();
        } else {
          self.setData({
            data_list_loding_status: 0,
            data_bottom_line_status: true
          });

          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        swan.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: true,
          load_status: 1
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
    swan.navigateTo({
      url: '/pages/goods-search/goods-search?keywords=' + keywords
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
            plugins_limitedtimediscount_is_show_time: false
          });
        }
      }, 1000);
    } else {
      // 活动已结束
      self.setData({
        plugins_limitedtimediscount_timer_title: msg,
        plugins_limitedtimediscount_is_show_time: false
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
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/index/index?referrer=' + user_id
    };
  },

  // web页面信息设置
  set_page_info() {
    swan.setPageInfo({
      title: app.data.application_title,
      keywords: app.data.application_describe,
      description: app.data.application_describe,
      image: (this.data.banner_list.length == 0) ? [] : this.data.banner_list.map(function (v) { return v.images_url;}).slice(0,3)
    });
  },

});