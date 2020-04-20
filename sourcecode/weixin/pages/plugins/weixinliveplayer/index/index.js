const app = getApp();
Page({
  data: {
    load_status: 0,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list_loding_msg: '',
    data_list: [],
    data_base: null,
    banner_list: [],
  },

  onShow() {
    this.init();
  },

  init() {
    // 获取数据
    this.get_data_list();
  },

  // 获取数据
  get_data_list() {
    var self = this;

    // 加载loding
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1,
    });

    wx.request({
      url: app.get_request_url("index", "index", "weixinliveplayer"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({ load_status: 1 });
        
        if (res.data.code == 0) {
          var data = res.data.data;
          var status = ((data.data || null) == null || data.data.length == 0);
          this.setData({
            data_base: data.base || null,
            banner_list: data.banner_list || [],
            data_list: data.data,
            data_list_loding_status: status ? 0 : 3,
            data_bottom_line_status: !status,
          });

          // 导航名称
          if ((data.base || null) != null && (data.base.application_name || null) != null) {
            wx.setNavigationBarTitle({ title: data.base.application_name });
          }
        } else {
          self.setData({
            data_bottom_line_status: true,
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
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = (user != null && (user.id || null) != null) ? user.id : 0;
    var name = ((this.data.data_base || null) != null && (this.data.data_base.application_name || null) != null) ? this.data.data_base.application_name : app.data.application_title;
    return {
      title: name,
      desc: app.data.application_describe,
      path: '/pages/plugins/weixinliveplayer/index/index?referrer=' + user_id
    };
  },
});