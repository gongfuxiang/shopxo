const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    extraction: null,
    statistical: null,
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
        my.redirectTo({
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
        data_bottom_line_status: false,
      });
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    my.request({
      url: app.get_request_url("index", "extraction", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            extraction: data.extraction || null,
            statistical: data.statistical || null,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true,
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
        my.hideLoading();
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

  // 地图查看
  address_map_event(e) {
    if ((this.data.extraction || null) == null) {
      return false;
    }
    var data = this.data.extraction;

    // 打开地图
    var name = data.alias || data.name || '';
    var address = (data.province_name || '') + (data.city_name || '') + (data.county_name || '') + (data.address || '');
    app.open_location(data.lng, data.lat, name, address);
  },

  // 进入取货订单管理
  order_event(e) {
    var value = e.currentTarget.dataset.value || 0;
    my.navigateTo({
      url: '/pages/plugins/distribution/extraction-order/extraction-order?status='+value,
    });
  },

});