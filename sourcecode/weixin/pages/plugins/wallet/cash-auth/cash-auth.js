const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    check_account_list: [],

    verify_submit_text: '获取验证码',
    verify_loading: false,
    verify_disabled: false,
    form_submit_loading: false,
    verify_time_total: 60,
    temp_clear_time: null,
    
    check_account_value: null,
    form_submit_disabled_status: false,
  },

  onLoad(params) {},

  onShow() {
    this.init();
  },

  init() {
    var user = app.get_user_info(this, 'init');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 获取数据
        this.get_data();
      }
    } else {
      this.setData({
        data_list_loding_status: 0,
      });
    }
  },

  // 获取数据
  get_data() {
    // 加载loding
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 获取数据
    wx.request({
      url: app.get_request_url("auth", "cash", "wallet"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        this.setData({
          data_list_loding_status: 0,
        });
        if (res.data.code == 0) {
          var data = res.data.data;
          this.setData({
            check_account_list: data.check_account_list || [],
          });
        } else {
          if (app.is_login_check(res.data, this, 'get_data')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        wx.hideLoading();
        this.setData({
          data_list_loding_status: 2,
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 身份认证方式事件
  select_check_account_event(e) {
    this.setData({ check_account_value: e.detail.value || 0});
  },

  // 发送验证码
  verify_send_event() {
    var self = this;
    // 数据验证
    if (self.data.check_account_value == null)
    {
      app.showToast('请选择认证方式');
      return false;
    }

    wx.showLoading({ title: '发送中...' });
    this.setData({ verify_submit_text: '发送中', verify_loading: true, verify_disabled: true });

    wx.request({
      url: app.get_request_url("verifysend", "cash", "wallet"),
      method: 'POST',
      data: { account_type: self.data.check_account_list[self.data.check_account_value]['field'] },
      dataType: 'json',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: (res) => {
        wx.hideLoading();
        if (res.data.code == 0) {
          this.setData({ verify_loading: false });
          var temp_time = this.data.verify_time_total;
          this.data.temp_clear_time = setInterval(function () {
            if (temp_time <= 1) {
              clearInterval(self.data.temp_clear_time);
              self.setData({ verify_submit_text: '获取验证码', verify_disabled: false });
            } else {
              temp_time--;
              self.setData({ verify_submit_text: '剩余 ' + temp_time + ' 秒' });
            }
          }, 1000);
        } else {
          this.setData({ verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false });

          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
        this.setData({ verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 数据提交
  form_submit(e) {
    var self = this;
    // 表单数据
    var form_data = e.detail.value;

    // 数据校验
    var validation = [
      { fields: "account_type", msg: "请选择认证方式", "is_can_zero": 1 },
      { fields: "verify", msg: "请输入验证码" },
    ];
    console.log(form_data);
    
    // 验证提交表单
    if (app.fields_check(form_data, validation)) {
      form_data["account_type"] = self.data.check_account_list[self.data.check_account_value]['field'];
      self.setData({ form_submit_disabled_status: true });
      wx.showLoading({ title: "处理中..." });
      wx.request({
        url: app.get_request_url("verifycheck", "cash", "wallet"),
        method: "POST",
        data: form_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          self.setData({ form_submit_disabled_status: false });
          wx.hideLoading();
          if (res.data.code == 0) {
            wx.redirectTo({
              url: '/pages/plugins/wallet/cash-create/cash-create',
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
    }
  },
});