const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    user_wallet: null,
    submit_disabled_status: false,

    // 导航
    nav_list: [],
  },

  onLoad(params) { },

  onShow() {
    this.set_nav_list();
    this.init();
  },

  init(e) {
    var user = app.get_user_info(this, "init"),
      self = this;
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.showModal({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmText: '确认',
          cancelText: '暂不',
          success: (result) => {
            wx.stopPullDownRefresh();
            if (result.confirm) {
              wx.navigateTo({
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

  // 导航
  set_nav_list() {
    var nav = [
      {
        icon: "/images/plugins/wallet/user-center-wallet-log-icon.png",
        title: "账户明细",
        url: "/pages/plugins/wallet/wallet-log/wallet-log",
      },
      {
        icon: "/images/plugins/wallet/user-center-recharge-icon.png",
        title: "充值记录",
        url: "/pages/plugins/wallet/user-recharge/user-recharge",
      },
      {
        icon: "/images/plugins/wallet/user-center-cash-icon.png",
        title: "提现记录",
        url: "/pages/plugins/wallet/user-cash/user-cash",
      }
    ];
    this.setData({ nav_list: nav});
  },

  // 获取数据
  get_data() {
    var self = this;
    wx.request({
      url: app.get_request_url("index", "user", "wallet"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            user_wallet: data.user_wallet || null,
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
    this.get_data();
  },
});