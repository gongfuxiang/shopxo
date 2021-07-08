const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,
    user: null,
    data_base: null,
    shop: null,
    shop_favor_user: [],
    shop_navigation: [],
    shop_goods_category: [],
    data: null,
    goods_list: [],
    // 基础配置
    currency_symbol: app.data.currency_symbol,
    search_keywords_value: '',
    header_service_status: false,
    nav_category_status: false,
    shop_category_tab_value: 0,
    shop_favor_info: {
      "text": "收藏",
      "status": 0,
      "count": 0
    }
  },

  onReady() {},

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);
    this.setData({
      params: params,
      user: app.get_user_cache_info()
    });
  },

  onShow() {
    this.get_data();

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if ((status || false) == true) {
      this.setData({
        currency_symbol: app.get_config('currency_symbol')
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    swan.request({
      url: app.get_request_url("detail", "index", "shop"),
      method: "POST",
      data: {
        "id": this.data.params.id || 0
      },
      dataType: "json",
      success: res => {
        swan.stopPullDownRefresh();

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            shop: (data.shop || null) == null || data.shop.length <= 0 ? null : data.shop,
            shop_favor_user: data.shop_favor_user || [],
            shop_navigation: data.shop_navigation || [],
            shop_goods_category: data.shop_goods_category || [],
            data: data.data || null,
            goods_list: (data.data || null) != null && (data.data.goods || null) != null && data.data.goods.length > 0 ? data.data.goods : [],
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true
          }); // 自动模式数据、商品列表切换处理

          if ((this.data.shop || null) != null && (this.data.shop.data_model || 0) == 0) {
            this.shop_category_tab_handle();
          } // 收藏信息


          if ((this.data.shop || null) != null) {
            var status = this.data.shop_favor_user.indexOf(this.data.shop.id) != -1 ? 1 : 0;
            this.setData({
              shop_favor_info: {
                "count": this.data.shop.shop_favor_count || 0,
                "status": status,
                "text": (status == 1 ? '已' : '') + '收藏'
              }
            });
          } // 标题名称


          if ((this.data.shop || null) != null) {
            swan.setNavigationBarTitle({
              title: this.data.shop.name
            });
          }
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg
          });
        }
      },
      fail: () => {
        swan.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data();
  },

  // 店铺收藏事件
  shop_favor_event(e) {
    var user = app.get_user_info(this, 'shop_favor_event');

    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        swan.navigateTo({
          url: "/pages/login/login?event_callback=shop_favor_event"
        });
        return false;
      } else {
        swan.showLoading({
          title: '处理中...'
        });
        swan.request({
          url: app.get_request_url("favor", "shopfavor", "shop"),
          method: 'POST',
          data: {
            "id": this.data.shop.id
          },
          dataType: 'json',
          success: res => {
            swan.hideLoading();

            if (res.data.code == 0) {
              this.setData({
                shop_favor_info: res.data.data
              });
              app.showToast(res.data.msg, "success");
            } else {
              if (app.is_login_check(res.data, this, 'shop_favor_event')) {
                app.showToast(res.data.msg);
              }
            }
          },
          fail: () => {
            swan.hideLoading();
            app.showToast('服务器请求出错');
          }
        });
      }
    }
  },

  // 搜索输入事件
  search_keywords_event(e) {
    this.setData({
      search_keywords_value: e.detail.value || ''
    });
  },

  // 搜索事件
  search_button_event(e) {
    var value = e.currentTarget.dataset.value || null;
    swan.navigateTo({
      url: value + 'keywords=' + this.data.search_keywords_value || ''
    });
  },

  // 导航分类事件
  header_service_event(e) {
    this.setData({
      header_service_status: !this.data.header_service_status
    });
  },

  // 导航分类事件
  nav_shop_category_event(e) {
    this.setData({
      nav_category_status: !this.data.nav_category_status
    });
  },

  // 导航分类事件
  shop_category_event(e) {
    var value = e.currentTarget.dataset.value || null;
    swan.navigateTo({
      url: '/pages/plugins/shop/search/search?shop_id=' + this.data.shop.id + '&category_id=' + value
    });
  },

  // 导航事件
  nav_event(e) {
    app.url_event(e);
  },

  // 剪切板
  text_copy_event(e) {
    app.text_copy_event(e);
  },

  // 电话
  tel_event(e) {
    app.call_tel(e.currentTarget.dataset.value || null);
  },

  // 图片预览
  image_show_event(e) {
    app.image_show_event(e);
  },

  // 分类切换事件
  shop_category_tab_event(e) {
    this.setData({
      shop_category_tab_value: e.currentTarget.dataset.value || 0
    });
    this.shop_category_tab_handle();
  },

  // 分类切换处理
  shop_category_tab_handle() {
    var value = this.data.shop_category_tab_value || 0;
    var temp = this.data.data;
    var goods = [];

    if (temp.goods.length > 0) {
      for (var i in temp.goods) {
        if (temp.goods[i]['shop_category_id'] == value || value == 0) {
          goods.push(temp.goods[i]);
        }
      }
    }

    this.setData({
      goods_list: goods,
      data_bottom_line_status: goods.length > 0
    });
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.shop.name || this.data.shop.seo_title || app.data.application_title,
      desc: this.data.shop.describe || this.data.shop.seo_desc || app.data.application_describe,
      path: '/pages/plugins/shop/detail/detail?id=' + this.data.shop.id + '&referrer=' + user_id
    };
  }

});