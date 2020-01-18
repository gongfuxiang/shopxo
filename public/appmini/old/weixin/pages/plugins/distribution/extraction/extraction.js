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
        data_bottom_line_status: false,
      });
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    wx.request({
      url: app.get_request_url("index", "extraction", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
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
        wx.hideLoading();
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

  // 地图查看
  address_map_event(e) {
    if ((this.data.extraction || null) == null) {
      return false;
    }

    var ads = this.data.extraction;
    var lng = parseFloat(ads.lng || 0);
    var lat = parseFloat(ads.lat || 0);
    if (lng <= 0 || lat <= 0) {
      return false;
    }

    wx.openLocation({
      latitude: lat,
      longitude: lng,
      scale: 18,
      name: ads.alias || '',
      address: (ads.province_name || '') + (ads.city_name || '') + (ads.county_name || '') + (ads.address || ''),
    });
  },

  // 进入取货订单管理
  order_event(e) {
    var value = e.currentTarget.dataset.value || 0;
    wx.navigateTo({
      url: '/pages/plugins/distribution/extraction-order/extraction-order?status='+value,
    });
  },

});