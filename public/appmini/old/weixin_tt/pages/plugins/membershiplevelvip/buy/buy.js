const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_list: [],
    data_base: null,
    selected_tabs_index: 0,
    selected_content_index: null,
    submit_disabled_status: false
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
    tt.showLoading({
      title: "加载中..."
    });

    if (self.data.data_list.length <= 0) {
      self.setData({
        data_list_loding_status: 1
      });
    }

    tt.request({
      url: app.get_request_url("index", "buy", "membershiplevelvip"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        if (res.data.code == 0) {
          var data = res.data.data;
          var status = (data.data || []).length > 0;
          self.setData({
            data_base: data.base || null,
            data_list: data.data || [],
            data_list_loding_msg: '',
            data_list_loding_status: status ? 3 : 0,
            data_bottom_line_status: status
          });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg
          });

          if (app.is_login_check(res.data, self, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        tt.hideLoading();
        tt.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

  // tabs事件
  tabs_event(e) {
    this.setData({
      selected_tabs_index: e.currentTarget.dataset.index || 0,
      selected_content_index: null
    });
  },

  // 时长事件
  content_event(e) {
    this.setData({
      selected_content_index: e.currentTarget.dataset.index || 0
    });
  },

  // 确认支付事件
  submit_event(e) {
    if (this.data.selected_tabs_index < 0 || this.data.selected_content_index === null) {
      app.showToast('请选择开通时长');
      return false;
    } // 请求参数


    var item = this.data.data_list[this.data.selected_tabs_index] || null;

    if (item == null) {
      app.showToast('开通时长有误');
      return false;
    }

    var rules = (item['pay_period_rules'] || null) == null ? null : item['pay_period_rules'][this.data.selected_content_index] || null;

    if (rules == null) {
      app.showToast('开通时长有误');
      return false;
    } // 请求生成支付订单


    var self = this;
    self.setData({
      submit_disabled_status: true
    });
    tt.showLoading({
      title: "处理中..."
    });
    tt.request({
      url: app.get_request_url("create", "buy", "membershiplevelvip"),
      method: "POST",
      data: {
        "opening": item['id'] + '-' + rules['number']
      },
      dataType: "json",
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: res => {
        tt.hideLoading();
        self.setData({
          submit_disabled_status: false
        });

        if (res.data.code == 0) {
          // 进入以后会员中心并发起支付
          tt.redirectTo({
            url: '/pages/plugins/membershiplevelvip/order/order?is_pay=1&order_id=' + res.data.data.id
          });
        } else {
          if (app.is_login_check(res.data, self, 'submit_event')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        self.setData({
          submit_disabled_status: false
        });
        tt.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  }

});