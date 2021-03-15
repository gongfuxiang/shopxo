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
    editor_path_type: '',

    default_province: "请选择省",
    default_city: "请选择市",
    default_county: "请选择区/县",

    province_value: null,
    city_value: null,
    county_value: null,

    user_location_cache_key: app.data.cache_userlocation_key,
    user_location: null,

    form_submit_disabled_status: false
  },

  onLoad(params) {
    this.setData({ params: params });
  },

  onReady: function () {
    // 清除位置缓存信息
    swan.removeStorage({ key: this.data.user_location_cache_key });
    this.init();
  },

  onShow() {
    this.user_location_init();
  },

  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        swan.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码'
        });
        return false;
      } else {
        this.get_province_list();
        this.applyinfo_init();
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息'
      });
    }
  },

  // 自提点信息
  applyinfo_init() {
    var self = this;
    swan.request({
      url: app.get_request_url("applyinfo", "extraction", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code == 0) {
          var data = res.data.data;
          var extraction_data = data.extraction_data || null;
          self.setData({
            extraction_data: extraction_data || null,
            editor_path_type: data.editor_path_type || '',
          });

          // 数据设置
          if(extraction_data != null)
          {
            self.setData({
              province_id: extraction_data.province || null,
              city_id: extraction_data.city || null,
              county_id: extraction_data.county || null,
            });

            // 地理位置
            var lng = extraction_data.lng || null;
            var lat = extraction_data.lat || null;
            if (lng != null && lat != null)
            {
              self.setData({ user_location: {
                lng: lng,
                lat: lat,
                address: extraction_data.address || '',
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
      county_value: this.get_region_value("county_list", "county_id")
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
    swan.request({
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
    if (self.data.province_id) {
      swan.request({
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
      swan.request({
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
    swan.navigateTo({
      url: '/pages/common/open-setting-location/open-setting-location'
    });
  },

  // 地址信息初始化
  user_location_init() {
    var result = swan.getStorageSync(this.data.user_location_cache_key) || null;
    var data = null;
    if (result != null) {
      data = {
        name: result.name || null,
        address: result.address || null,
        lat: result.latitude || null,
        lng: result.longitude || null
      };
    }
    this.setData({ user_location: data });
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

    // logo
    form_data['logo'] = (this.data.extraction_data || null) != null ? (this.data.extraction_data.logo || '') : '';

    // 地区
    form_data["province"] = self.data.province_id;
    form_data["city"] = self.data.city_id;
    form_data["county"] = self.data.county_id;

    // 地理位置
    var lng = 0;
    var lat = 0;
    if((self.data.user_location || null) != null) {
      lng = self.data.user_location.lng || 0;
      lat = self.data.user_location.lat || 0;
    }
    if((self.data.extraction_data || null) != null) {
      if((lng || null) == null) {
        lng = self.data.extraction_data.lng || 0;
      }
      if((lat || null) == null) {
        lat = self.data.extraction_data.lat || 0;
      }
    }
    form_data["lng"] = lng;
    form_data["lat"] = lat;

    // 验证提交表单
    if (app.fields_check(form_data, validation)) {
      if ((self.data.extraction_data || null) != null && (self.data.extraction_data.status || 0) == 1) {
        swan.showModal({
          title: '温馨提示',
          content: '数据需重新审核后方可生效',
          confirmText: '确认',
          cancelText: '暂不',
          success: result => {
            if (result.confirm) {
              self.request_data_save(form_data);
            }
          }
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
    swan.showLoading({ title: "处理中..." });
    swan.request({
      url: app.get_request_url("applysave", "extraction", "distribution"),
      method: "POST",
      data: data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        swan.hideLoading();
        if (res.data.code == 0) {
          app.showToast(res.data.msg, "success");
          setTimeout(function () {
            swan.navigateBack();
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
        swan.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 上传图片预览
  upload_show_event(e) {
    swan.previewImage({
      current: this.data.extraction_data.logo,
      urls: [this.data.extraction_data.logo],
    });
  },

  // 图片删除
  upload_delete_event(e) {
    var self = this;
    swan.showModal({
      title: '温馨提示',
      content: '删除后不可恢复、继续吗？',
      success(res) {
        if (res.confirm) {
          var temp_data = self.data.extraction_data || {};
          temp_data['logo'] = '';
          self.setData({
            extraction_data: temp_data,
          });
        }
      }
    });
  },

  // 文件上传
  file_upload_event(e) {
    var self = this;
    swan.chooseImage({
      count: 1,
      success(res) {
        var success = 0;
        var fail = 0;
        var length = res.tempFilePaths.length;
        var count = 0;
        self.upload_one_by_one(res.tempFilePaths, success, fail, count, length);
      }
    });
  },

  // 采用递归的方式上传多张
  upload_one_by_one(img_paths, success, fail, count, length) {
    var self = this;
    swan.uploadFile({
      url: app.get_request_url("index", "ueditor"),
      filePath: img_paths[count],
      name: 'upfile',
      formData: {
        action: 'uploadimage',
        path_type: self.data.editor_path_type
      },
      success: function (res) {
        success++;
        if (res.statusCode == 200) {
          var data = (typeof (res.data) == 'object') ? res.data : JSON.parse(res.data);
          if (data.code == 0 && (data.data.url || null) != null) {
            var temp_data = self.data.extraction_data || {};
            temp_data['logo'] = data.data.url;
            self.setData({ extraction_data: temp_data });
          } else {
            app.showToast(data.msg);
          }
        }
      },
      fail: function (e) {
        fail++;
      },
      complete: function (e) {
        count++; // 下一张
        if (count >= length) {
          // 上传完毕，作一下提示
          //app.showToast('上传成功' + success +'张', 'success');
        } else {
          // 递归调用，上传下一张
          self.upload_one_by_one(img_paths, success, fail, count, length);
        }
      }
    });
  },
});