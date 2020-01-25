const app = getApp();
Page({
  data: {
    params: null,
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    recharge_money_value: '',
    form_submit_disabled_status: false,
  },

  onLoad(params) {
    this.setData({
      params: params,
      recharge_money_value: params.money || '',
    });
  },

  onShow() {
    this.init();
  },

  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码',
        });
        return false;
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息',
      });
    }
  },

  // 充值金额输入事件
  recharge_money_value_input_event(e) {
    this.setData({ recharge_money_value: e.detail.value || '' });
  },

  // 数据提交
  form_submit_event(e) {
    var self = this;
    // 参数
    if ((self.data.recharge_money_value || null) == null) {
      app.showToast('请输入充值金额');
      return false;
    }

    self.setData({ form_submit_disabled_status: true });
    wx.showLoading({ title: "处理中..." });
    wx.request({
      url: app.get_request_url("create", "recharge", "wallet"),
      method: "POST",
      data: { money: self.data.recharge_money_value},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        self.setData({ form_submit_disabled_status: false });
        wx.hideLoading();
        if (res.data.code == 0) {
          wx.redirectTo({
            url: '/pages/plugins/wallet/user-recharge/user-recharge?is_pay=1&recharge_id=' + res.data.data.recharge_id,
          });
        } else {
          if (app.is_login_check(res.data)) {
            app.showToast(res.data.msg);
          } else {
            app.showToast('提交失败，请重试！');
          }
        }
      },
      fail: () => {
        self.setData({ form_submit_disabled_status: false });
        wx.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  },
});
