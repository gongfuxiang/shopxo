const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    data_base: null,
    level_list: []
  },

  onLoad(params) {
    this.setData({ params: params });
    this.init();
  },

  onShow() {},

  init() {
    var self = this;
    swan.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    swan.request({
      url: app.get_request_url("index", "introduce", "distribution"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          var data_base = data.base || null;
          var level_list = (data.level_list || null) != null && data.level_list.length > 0 ? data.level_list : [];
          self.setData({
            data_base: data_base,
            level_list: level_list,
            data_list_loding_status: data_base == null || level_list.length <= 0 ? 0 : 3,
            data_bottom_line_status: true,
            data_list_loding_msg: ''
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错'
        });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  }

});