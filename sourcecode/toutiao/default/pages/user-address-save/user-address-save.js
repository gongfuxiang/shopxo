const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    editor_path_type: '',
    address_data: null,
    province_list: [],
    city_list: [],
    county_list: [],
    province_id: null,
    city_id: null,
    county_id: null,
    idcard_images_data: {},

    default_province: "请选择省",
    default_city: "请选择市",
    default_county: "请选择区/县",

    province_value: null,
    city_value: null,
    county_value: null,

    user_location_cache_key: app.data.cache_userlocation_key,
    user_location: null,

    form_submit_disabled_status: false,

    // 基础配置
    home_user_address_map_status: 0,
    home_user_address_idcard_status : 0,
  },

  onLoad(params) {
    this.setData({ params: params });
  },

  onReady: function () {
    if((this.data.params.id || null) == null)
    {
      var title = app.data.common_pages_title.user_address_save_add;
    } else {
      var title = app.data.common_pages_title.user_address_save_edit;
    }
    tt.setNavigationBarTitle({title: title});

    // 初始化配置
    this.init_config();

    // 清除位置缓存信息
    tt.removeStorage({key: this.data.user_location_cache_key});
    this.init();
  },

  onShow() {
    this.user_location_init();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        home_user_address_map_status: app.get_config('config.home_user_address_map_status'),
        home_user_address_idcard_status: app.get_config('config.home_user_address_idcard_status')
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  init() {
    var user = app.get_user_info(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        tt.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码',
        });
        return false;
      } else {
        this.get_province_list();
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
    tt.request({
      url: app.get_request_url("detail", "useraddress"),
      method: "POST",
      data: self.data.params,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code == 0) {
          var data = res.data.data || null;
          var ads_data = data.data || null;
          var idcard_images = {
            idcard_front: (ads_data == null) ? '' : ads_data.idcard_front || '',
            idcard_back: (ads_data == null) ? '' : ads_data.idcard_back || '',
          };
          self.setData({
            address_data: ads_data,
            idcard_images_data: idcard_images,
            editor_path_type: data.editor_path_type || '',
          });

          // 数据设置
          if(ads_data != null)
          {
            self.setData({
              province_id: ads_data.province || null,
              city_id: ads_data.city || null,
              county_id: ads_data.county || null,
            });

            // 地理位置
            var lng = ads_data.lng || null;
            var lat = ads_data.lat || null;
            if (lng != null && lat != null)
            {
              self.setData({ user_location: {
                lng: lng,
                lat: lat,
                address: ads_data.address || '',
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
    tt.request({
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
      tt.request({
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
      tt.request({
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
    tt.navigateTo({
      url: '/pages/common/open-setting-location/open-setting-location'
    });
  },

  // 地址信息初始化
  user_location_init() {
    var result = tt.getStorageSync(this.data.user_location_cache_key) || null;
    var data = null;
    if (result != null)
    {
      data = {
        name: result.name || null,
        address: result.address || null,
        lat: result.latitude || null,
        lng: result.longitude || null
      }
    }
    this.setData({user_location: data});
  },

  // 文件上传
  file_upload_event(e) {
    // 调用相机、相册权限
    app.file_upload_authorize(this, 'file_upload_handle', e);
  },

  // 文件上传
  file_upload_handle(e) {
    var form_name = e.currentTarget.dataset.value || null;
    if(form_name == null) {
      app.showToast('表单名称类型有误');
      return false;
    }

    var self = this;
    tt.chooseImage({
      count: 1,
      success(res) {
        var success = 0;
        var fail = 0;
        var length = res.tempFilePaths.length;
        var count = 0;
        self.upload_one_by_one(res.tempFilePaths, success, fail, count, length, form_name);
      }
    });
  },

  // 采用递归的方式上传多张
  upload_one_by_one(img_paths, success, fail, count, length, form_name) {
    var self = this;
    tt.uploadFile({
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
            var temp_idcard_images_data = self.data.idcard_images_data || {};
            temp_idcard_images_data[form_name] = data.data.url;
            self.setData({ idcard_images_data: temp_idcard_images_data });
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
          self.upload_one_by_one(img_paths, success, fail, count, length, form_name);
        }
      }
    });
  },

  // 图片删除
  upload_delete_event(e) {
    var form_name = e.currentTarget.dataset.value || null;
    if(form_name == null) {
      app.showToast('表单名称类型有误');
      return false;
    }

    var self = this;
    tt.showModal({
      title: '温馨提示',
      content: '删除后不可恢复、继续吗？',
      success(res) {
        if (res.confirm) {
          var temp_idcard_images_data = self.data.idcard_images_data || {};
          temp_idcard_images_data[form_name] = '';
          self.setData({ idcard_images_data: temp_idcard_images_data });
        }
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
      { fields: "name", msg: "请填写联系人" },
      { fields: "tel", msg: "请填写联系电话" },
      { fields: "province", msg: "请选择省份" },
      { fields: "city", msg: "请选择城市" },
      { fields: "county", msg: "请选择区县" },
      { fields: "address", msg: "请填写详细地址" }
    ];

    // 是否开启了地理位置选择
    if(self.data.home_user_address_map_status == 1)
    {
      validation.push({ fields: "lng", msg: "请选择地理位置" });
      validation.push({ fields: "lat", msg: "请选择地理位置" });
    }

    // 是否开启了用户身份证信息
    if(self.data.home_user_address_idcard_status == 1)
    {
      // 验证
      validation.push({ fields: "idcard_name", msg: "请填写身份证姓名" });
      validation.push({ fields: "idcard_number", msg: "请填写身份证号码" });
      validation.push({ fields: "idcard_front", msg: "请上传身份证正面照片" });
      validation.push({ fields: "idcard_back", msg: "请上传身份证背面照片" });

      // 数据
      form_data['idcard_front'] = self.data.idcard_images_data.idcard_front || '';
      form_data['idcard_back'] = self.data.idcard_images_data.idcard_back || '';
    }

    form_data['province'] = self.data.province_id;
    form_data['city'] = self.data.city_id;
    form_data['county'] = self.data.county_id;
    form_data['id'] = self.data.params.id || 0;
    form_data['is_default'] = form_data.is_default == true ? 1 : 0;

    // 地理位置
    var lng = 0;
    var lat = 0;
    if((self.data.user_location || null) != null) {
      lng = self.data.user_location.lng || 0;
      lat = self.data.user_location.lat || 0;
    }
    if((self.data.address_data || null) != null) {
      if((lng || null) == null) {
        lng = self.data.address_data.lng || 0;
      }
      if((lat || null) == null) {
        lat = self.data.address_data.lat || 0;
      }
    }
    form_data['lng'] = lng;
    form_data['lat'] = lat;

    // 验证提交表单
    if (app.fields_check(form_data, validation)) {
      // 数据保存
      self.setData({ form_submit_disabled_status: true });
      tt.showLoading({ title: "处理中..." });
      tt.request({
        url: app.get_request_url("save", "useraddress"),
        method: "POST",
        data: form_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          tt.hideLoading();
          if (res.data.code == 0) {
            app.showToast(res.data.msg, "success");
            setTimeout(function () {
              tt.navigateBack();
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
          tt.hideLoading();
          app.showToast("服务器请求出错");
        }
      });
    }
  },
});
