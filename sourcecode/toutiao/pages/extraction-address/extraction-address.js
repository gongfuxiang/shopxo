const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    params: null,
    is_default: 0,
  },

  onLoad(params) {
    this.setData({ params: params });
  },

  onShow() {
    tt.setNavigationBarTitle({ title: app.data.common_pages_title.extraction_address });
    this.init();
  },

  // 初始化
  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        tt.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 获取数据
        this.get_data_list();
      }
    } else {
      this.setData({
        data_list_loding_status: 0,
        data_bottom_line_status: false,
      });
    }
  },

  // 获取数据列表
  get_data_list() {
    // 加载loding
    tt.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 获取数据
    tt.request({
      url: app.get_request_url("extraction", "useraddress"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();
        if (res.data.code == 0) {
          if (res.data.data.length > 0) {
            // 获取当前默认地址
            var is_default = 0;
            for (var i in res.data.data) {
              if (res.data.data[i]['is_default'] == 1) {
                is_default = res.data.data[i]['id'];
              }
            }

            // 设置数据
            this.setData({
              data_list: res.data.data,
              is_default: is_default,
              data_list_loding_status: 3,
              data_bottom_line_status: true,
            });
          } else {
            this.setData({
              data_list_loding_status: 0
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0
          });
          if (app.is_login_check(res.data, this, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

  // 地图查看
  address_map_event(e) {
    app.location_authorize(this, 'address_map_handle', e);
  },

  // 地图查看处理
  address_map_handle(e) {
    var index = e.currentTarget.dataset.index || 0;
    var ads = this.data.data_list[index] || null;
    if (ads == null)
    {
      app.showToast("地址有误");
      return false;
    }

    var lng = parseFloat(ads.lng || 0);
    var lat = parseFloat(ads.lat || 0);
    if (lng <= 0 || lat <= 0) {
      app.showToast("坐标有误");
      return false;
    }

    tt.openLocation({
      latitude: lat,
      longitude: lng,
      scale: 18,
      name: ads.alias || '',
      address: (ads.province_name || '') + (ads.city_name || '') + (ads.county_name || '') + (ads.address || ''),
    });
  },

  // 地址内容事件
  address_conent_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var is_back = this.data.params.is_back || 0;
    if (is_back == 1) {
      tt.setStorage({
        key: app.data.cache_buy_user_address_select_key,
        data: this.data.data_list[index]
      });
      tt.navigateBack();
    }
  },

});
