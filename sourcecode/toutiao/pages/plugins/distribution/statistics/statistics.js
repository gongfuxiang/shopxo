const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '加载中...',
    data_bottom_line_status: false,
    user_total: null,
    user_profit_stay_price: 0.00,
    user_profit_vaild_price: 0.00,
    user_profit_already_price: 0.00,
    user_profit_total_price: 0.00,
    user_data: null,
    profit_data: null,

    // 基础配置
    currency_symbol: app.data.currency_symbol,
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
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  init() {
    var self = this;
    tt.showLoading({
      title: "加载中..."
    });
    this.setData({
      data_list_loding_status: 1
    });
    tt.request({
      url: app.get_request_url("index", "statistics", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            user_total: data.user_total || null,
            user_profit_stay_price: data.user_profit_stay_price || 0.00,
            user_profit_vaild_price: data.user_profit_vaild_price || 0.00,
            user_profit_already_price: data.user_profit_already_price || 0.00,
            user_profit_total_price: data.user_profit_total_price || 0.00,
            user_data: data.user_chart || null,
            profit_data: data.profit_chart || null,
            data_list_loding_status: 3,
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
        tt.hideLoading();
        tt.stopPullDownRefresh();
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