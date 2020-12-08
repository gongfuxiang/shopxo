const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    nav_list: [
      {
        icon: "/images/plugins/invoice/user-center-invoice-icon.png",
        title: "我的发票",
        url: "/pages/plugins/invoice/invoice/invoice",
      },
      {
        icon: "/images/plugins/invoice/user-center-order-icon.png",
        title: "订单开票",
        url: "/pages/plugins/invoice/order/order",
      }
    ],
  },

  onLoad(params) {},

  onShow() {
    this.init();
  },

  init(e) {
    var user = app.get_user_info(this, "init"),
      self = this;
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        my.confirm({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmButtonText: '确认',
          cancelButtonText: '暂不',
          success: (result) => {
            my.stopPullDownRefresh();
            if (result.confirm) {
              my.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
          },
        });
      } else {
        self.get_data();
      }
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    my.request({
      url: app.get_request_url("center", "user", "invoice"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: false,
          });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'get_data')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        my.stopPullDownRefresh();
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
    this.get_data();
  },
});