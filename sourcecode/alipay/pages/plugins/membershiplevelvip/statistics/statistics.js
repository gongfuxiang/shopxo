const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_list_loding_status: 1,
    data_list_loding_msg: '加载中...',
    data_bottom_line_status: false,

    user_total: null,
    user_profit_already_price: 0.00,
    user_profit_stay_price: 0.00,
    user_profit_total_price: 0.00,
    user_data: null,
    profit_data: null,
  },
  
  onShow() {
    app.set_nav_bg_color_main('#1d1611');
    this.init();
  },

  init() {
    var self = this;
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    my.request({
      url: app.get_request_url("index", "statistics", "membershiplevelvip"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            user_total: data.user_total || null,
            user_profit_already_price: data.user_profit_already_price || 0.00,
            user_profit_stay_price: data.user_profit_stay_price || 0.00,
            user_profit_total_price: data.user_profit_total_price || 0.00,
            user_data: data.user_chart || null,
            profit_data: data.profit_chart || null,

            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        my.hideLoading();
        my.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错',
        });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});