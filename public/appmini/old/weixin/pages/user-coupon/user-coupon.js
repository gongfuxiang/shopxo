const app = getApp();
Page({
  data: {
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
    var user = app.get_user_cache_info(this, "init");
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
  },

  get_data_list() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("user", "coupon"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          self.setData({
            data_list: res.data.data || null,
            data_bottom_line_status: true,
            data_list_loding_status: 3,
            data_list_loding_msg: '',
          });
          
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 导航事件
  nav_tabs_event(e) {
    var index = e.currentTarget.dataset.index;
    var value = e.currentTarget.dataset.value;
    this.setData({ nav_tabs_value: value});
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

});
