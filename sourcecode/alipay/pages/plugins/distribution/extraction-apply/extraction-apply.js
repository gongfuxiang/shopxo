const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    extraction_data: null,
    province_list: [],
    city_list: [],
    county_list: [],
    province_id: null,
    city_id: null,
    county_id: null,

    default_province: "请选择省",
    default_city: "请选择市",
    default_county: "请选择区/县",

    province_value: null,
    city_value: null,
    county_value: null,

    user_location_cache_key: 'cache_userlocation_key',
    user_location: null,

    form_submit_disabled_status: false,
  },

  onLoad(params) {
    this.setData({ params: params });
  },

  onReady: function () {
    // 清除位置缓存信息
    my.removeStorage({key: this.data.user_location_cache_key});
    this.init();
  },

  onShow() {
    app.set_nav_bg_color_main('#ff6a80');
    this.user_location_init();
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
        this.get_province_list();
        this.applyinfo_init();
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息',
      });
    }
  },

  // 自提点信息
  applyinfo_init() {
    var self = this;
    my.request({
      url: app.get_request_url("applyinfo", "extraction", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code == 0) {
          var data = res.data.data || null;
          self.setData({
            extraction_data: data,
          });

          // 数据设置
          if(data != null)
          {
            self.setData({
              province_id: data.province || null,
              city_id: data.city || null,
              county_id: data.county || null,
            });

            // 地理位置
            var lng = (data.lng || 0) <= 0 ? null : data.lng;
            var lat = (data.lat || 0) <= 0 ? null : data.lat;
            if (lng != null && lat != null)
            {
              self.setData({ user_location: {
                lng: lng,
                lat: lat,
                address: data.address || '',
              }});
            }
          }
          
          // 获取城市、区县
          self.get_city_list();
          self.get_county_list();

          // 半秒后初始化数据
          setTimeout(function () {
            self.init_region_value();
          }, 500);
        } else {
          if (app.is_login_check(res.data)) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        app.showToast("省份信息失败");
      }
    });
  },

  // 地区数据初始化
  init_region_value() {
     this.setData({
      province_value: this.get_region_value("province_list", "province_id"),
      city_value: this.get_region_value("city_list", "city_id"),
      county_value: this.get_region_value("county_list", "county_id"),
    });
  },

  // 地区初始化匹配索引
  get_region_value(list, id) {
    var data = this.data[list];
    var data_id = this.data[id];
    var value = null;
    data.forEach((d, i) => {
      if (d.id == data_id) {
        value = i;
        return false;
      }
    });
    return value;
  },

  // 获取省份
  get_province_list() {
    var self = this;
    my.request({
      url: app.get_request_url("index", "region"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            province_list: data
          });
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        app.showToast("省份获取失败");
      }
    });
  },

  // 获取市
  get_city_list() {
    var self = this;
    console.log(self.data.province_id)
    if (self.data.province_id) {
      my.request({
        url: app.get_request_url("index", "region"),
        method: "POST",
        data: {
          pid: self.data.province_id
        },
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              city_list: data
            });
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          app.showToast("城市获取失败");
        }
      });
    }
  },

  // 获取区/县
  get_county_list() {
    var self = this;
    if (self.data.city_id) {
      // 加载loding
      my.request({
        url: app.get_request_url("index", "region"),
        method: "POST",
        data: {
          pid: self.data.city_id
        },
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              county_list: data
            });
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          app.showToast("区/县获取失败");
        }
      });
    }
  },

  // 省份事件
  select_province_event(e) {
    var index = e.detail.value || 0;
    if (index >= 0) {
      var data = this.data.province_list[index];
      this.setData({
        province_value: index,
        province_id: data.id,
        city_value: null,
        county_value: null,
        city_id: null,
        county_id: null
      });
      this.get_city_list();
    }
  },

  // 市事件
  select_city_event(e) {
    var index = e.detail.value || 0;
    if (index >= 0) {
      var data = this.data.city_list[index];
      this.setData({
        city_value: index,
        city_id: data.id,
        county_value: null,
        county_id: null
      });
      this.get_county_list();
    }
  },

  // 区/县事件
  select_county_event(e) {
    var index = e.detail.value || 0;
    if (index >= 0) {
      var data = this.data.county_list[index];
      this.setData({
        county_value: index,
        county_id: data.id
      });
    }
  },

  // 省市区未按照顺序选择提示
  region_select_error_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if (value != null) {
      app.showToast(value);
    }
  },

  // 选择地理位置
  choose_location_event(e) {
    my.navigateTo({
      url: '/pages/common/open-setting-location/open-setting-location'
    });
  },

  // 地址信息初始化
  user_location_init() {
    var result = my.getStorageSync({key: this.data.user_location_cache_key}) || null;
    var data = null;
    if (result != null && (result.data || null) != null)
    {
      data = {
        name: result.data.name || null,
        address: result.data.address || null,
        lat: result.data.latitude || null,
        lng: result.data.longitude || null
      }
    }
    this.setData({user_location: data});
  },

  // 数据提交
  form_submit(e) {
    var self = this;
    // 表单数据
    var form_data = e.detail.value;

    // 数据校验
    var validation = [
      { fields: "name", msg: "请填写联系人" },
      { fields: "tel", msg: "请填写联系电话" },
      { fields: "province", msg: "请选择省份" },
      { fields: "city", msg: "请选择城市" },
      { fields: "county", msg: "请选择区县" },
      { fields: "address", msg: "请填写详细地址" },
      { fields: "lng", msg: "请选择地理位置" },
      { fields: "lat", msg: "请选择地理位置" }
    ];

    form_data["province"] = self.data.province_id;
    form_data["city"] = self.data.city_id;
    form_data["county"] = self.data.county_id;

    // 地理位置
    if ((self.data.user_location || null) != null)
    {
      form_data["lng"] = self.data.user_location.lng || 0;
      form_data["lat"] = self.data.user_location.lat || 0;
    }

    // 验证提交表单
    if (app.fields_check(form_data, validation)) {
      if ((self.data.extraction_data || null) != null && (self.data.extraction_data.status || 0) == 1)
      {
        my.confirm({
          title: '温馨提示',
          content: '数据需重新审核后方可生效',
          confirmButtonText: '确认',
          cancelButtonText: '暂不',
          success: (result) => {
            if (result.confirm) {
              self.request_data_save(form_data);
            }
          },
        });
      } else {
        self.request_data_save(form_data);
      }
    }
  },

  // 数据保存
  request_data_save(data) {
    var self = this;
    self.setData({ form_submit_disabled_status: true });
    my.showLoading({ content: "处理中..." });
    my.request({
      url: app.get_request_url("applysave", "extraction", "distribution"),
      method: "POST",
      data: data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        my.hideLoading();
        if (res.data.code == 0) {
          app.showToast(res.data.msg, "success");
          setTimeout(function () {
            my.navigateBack();
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
  },
});
