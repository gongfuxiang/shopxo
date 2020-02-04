const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_list: [],
    data_base: null,

    // 优惠劵领取
    temp_coupon_receive_index: null,
    temp_coupon_receive_value: null
  },

  onLoad(params) {
    this.init();
  },

  onShow() {
    swan.setNavigationBarTitle({ title: app.data.common_pages_title.coupon });
  },

  init() {
    // 获取数据
    this.get_data_list();
  },

  // 获取数据
  get_data_list() {
    var self = this;
    swan.showLoading({ title: "加载中..." });
    if (self.data.data_list.length <= 0) {
      self.setData({
        data_list_loding_status: 1
      });
    }

    swan.request({
      url: app.get_request_url("index", "index", "coupon"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          var status = (data.data || []).length > 0;
          this.setData({
            data_base: data.base || null,
            data_list: data.data || [],
            data_list_loding_msg: '',
            data_list_loding_status: status ? 3 : 0,
            data_bottom_line_status: status
          });

          // 导航名称
          if ((data.base || null) != null && (data.base.application_name || null) != null) {
            swan.setNavigationBarTitle({ title: data.base.application_name });
          }
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 优惠劵领取事件
  coupon_receive_event(e) {
    // 参数处理
    if ((e || null) == null) {
      var index = this.data.temp_coupon_receive_index;
      var value = this.data.temp_coupon_receive_value;
    } else {
      var index = e.currentTarget.dataset.index;
      var value = e.currentTarget.dataset.value;
      this.setData({ temp_coupon_receive_index: index, temp_coupon_receive_value: value });
    }

    // 登录校验
    var user = app.get_user_info(this, 'coupon_receive_event');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        swan.navigateTo({
          url: "/pages/login/login?event_callback=coupon_receive_event"
        });
        return false;
      } else {
        var self = this;
        var temp_list = this.data.data_list;
        if (temp_list[index]['is_operable'] != 0) {
          swan.showLoading({ title: "处理中..." });
          swan.request({
            url: app.get_request_url("receive", "coupon", "coupon"),
            method: "POST",
            data: { "coupon_id": value },
            dataType: "json",
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            success: res => {
              swan.hideLoading();
              if (res.data.code == 0) {
                app.showToast(res.data.msg, "success");
                if (self.data.data_base != null && self.data.data_base.is_repeat_receive != 1) {
                  temp_list[index]['is_operable'] = 0;
                  temp_list[index]['is_operable_name'] = '已领取';
                  self.setData({ data_list: temp_list });
                }
              } else {
                if (app.is_login_check(res.data, self, 'coupon_receive_event')) {
                  app.showToast(res.data.msg);
                }
              }
            },
            fail: () => {
              swan.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      }
    }
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  }

});