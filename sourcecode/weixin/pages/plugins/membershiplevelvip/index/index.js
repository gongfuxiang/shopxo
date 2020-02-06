const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_list: [],
    data_base: null,
  },

  onLoad(params) {
    this.init();
  },

  onShow() {},

  init() {
    // 获取数据
    this.get_data_list();
  },

  // 获取数据
  get_data_list() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    if (self.data.data_list.length <= 0) {
      self.setData({
        data_list_loding_status: 1
      });
    }

    wx.request({
      url: app.get_request_url("index", "index", "membershiplevelvip"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            data_list: data.data || [],
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true,
          });

          // 导航名称
          if ((data.base || null) != null && (data.base.application_name || null) != null) {
            wx.setNavigationBarTitle({ title: data.base.application_name });
          }
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

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

});