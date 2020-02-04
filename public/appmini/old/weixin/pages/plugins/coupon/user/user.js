const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_list: null,

    nav_tabs_list: [
      { name: "未使用", value: "not_use" },
      { name: "已使用", value: "already_use" },
      { name: "已过期", value: "already_expire" },
    ],
    nav_tabs_value: 'not_use',
  },

  onLoad(params) {
    this.init();
  },

  onShow() {
    wx.setNavigationBarTitle({ title: app.data.common_pages_title.user_coupon });
  },

  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 获取数据
        this.get_data_list();
      }
    } else {
      this.setData({
        data_list_loding_status: 0,
        data_bottom_line_status: false,
      });
    }
  },

  // 获取数据
  get_data_list() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    if (this.data.data_list == null || (this.data.data_list[this.data.nav_tabs_value] || null) == null || this.data.data_list[this.data.nav_tabs_value].length <= 0) {
      this.setData({
        data_list_loding_status: 1
      });
    }

    wx.request({
      url: app.get_request_url("index", "coupon", "coupon"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          self.setData({
            data_list: res.data.data || null,
            data_list_loding_msg: '',
          });
          self.data_view_handle();
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 数据处理
  data_view_handle() {
    var status = 0;
    if (this.data.data_list != null && (this.data.data_list[this.data.nav_tabs_value] || null) != null && this.data.data_list[this.data.nav_tabs_value].length > 0) {
      status = 3;
    }
    this.setData({
      data_list_loding_status: status,
      data_bottom_line_status: (status == 3),
    });
  },

  // 导航事件
  nav_tabs_event(e) {
    this.setData({ nav_tabs_value: e.currentTarget.dataset.value});
    this.data_view_handle();
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

});
