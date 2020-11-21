const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    default_data: null,
    data_base: null,
    user_wallet: null,
    check_status: null,
    can_cash_max_money: 0.00,

    form_submit_disabled_status: false,
  },

  onLoad(params) {
    this.setData({ params: params });
  },

  onShow() {
    this.init();
  },

  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        my.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码',
        });
        return false;
      } else {
        this.get_data();
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息',
      });
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    self.setData({
      data_list_loding_status: 1
    });

    my.showLoading({ content: "加载中..." });
    my.request({
      url: app.get_request_url("createinit", "cash", "wallet"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data || null;
          self.setData({
            data_list_loding_status: 3,
            data_base: data.base || null,
            check_status: data.check_status || 0,
            default_data: data.default_data || null,
            user_wallet: data.user_wallet || null,
            can_cash_max_money: data.can_cash_max_money || 0.00,
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data)) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        my.hideLoading();
        my.stopPullDownRefresh();
        self.setData({
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

  // 数据提交
  form_submit(e) {
    var self = this;
    // 表单数据
    var form_data = e.detail.value;

    // 数据校验
    var validation = [
      { fields: "money", msg: "请填写提现金额" },
      { fields: "bank_name", msg: "请填写收款平台" },
      { fields: "bank_accounts", msg: "请填写收款账号" },
      { fields: "bank_username", msg: "请填写开户人姓名" }
    ];

    // 验证提交表单
    if (app.fields_check(form_data, validation)) {
      self.setData({ form_submit_disabled_status: true });
      my.showLoading({ content: "处理中..." });
      my.request({
        url: app.get_request_url("create", "cash", "wallet"),
        method: "POST",
        data: form_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          my.hideLoading();
          if (res.data.code == 0) {
            app.showToast(res.data.msg, "success");
            setTimeout(function () {
              my.redirectTo({
                url: '/pages/plugins/wallet/user-cash/user-cash',
              });
            }, 1000);
          } else {
            self.setData({ form_submit_disabled_status: false });
            if (app.is_login_check(res.data)) {
              app.showToast(res.data.msg);
            } else {
              app.showToast('提交失败，请重试！');
            }
          }
        },
        fail: () => {
          self.setData({ form_submit_disabled_status: false });
          my.hideLoading();
          app.showToast("服务器请求出错");
        }
      });
    }
  },
});
