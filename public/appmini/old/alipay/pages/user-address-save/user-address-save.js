const app = getApp();

Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '处理错误',

    province_list: [],
    city_list: [],
    county_list: [],
    province_id: null,
    city_id: null,
    county_id: null,
    is_default: 0,

    default_province: "请选择省",
    default_city: "请选择市",
    default_county: "请选择区/县",

    params: null,
  },

  onLoad(params) {
    this.setData({params: params});
  },

  onShow() {
    if((this.data.params.id || null) == null)
    {
      var title = app.data.common_pages_title.user_address_save_add;
    } else {
      var title = app.data.common_pages_title.user_address_save_edit;
    }
    my.setNavigationBar({title: title});
    this.init();
  },

  init() {
    var user = app.GetUserInfo(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码',
        });
        return false;
      } else {
        this.setData({
          data_list_loding_status: 0,
          data_list_loding_msg: '',
        });

        // 获取地址数据
        if((this.data.params.id || null) != null)
        {
          this.get_user_address();
        }

        // 获取省
        this.get_province_list();
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息',
      });
    }
  },

  //   获取用户地址
  get_user_address() {
    var self = this;
    // 加载loding
    my.showLoading({ content: "加载中..." });

    my.httpRequest({
      url: app.get_request_url("detail", "useraddress"),
      method: "POST",
      data: self.data.params,
      dataType: "json",
      success: res => {
        my.hideLoading();
        if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              name: data.name,
              tel: data.tel,
              address: data.address,
              province_id: data.province,
              city_id: data.city,
              county_id: data.county,
              is_default: data.is_default || 0,
            });

            self.get_city_list();
            self.get_county_list();

            setTimeout(function() {
              self.init_value();
            }, 500);
        } else {
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.hideLoading();
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

  // 获取选择的省市区
  get_province_list() {
    var self = this;
    my.httpRequest({
      url: app.get_request_url("index", "region"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            province_list: data
          });
        } else {
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

  get_city_list() {
    var self = this;
    if (self.data.province_id) {
      my.httpRequest({
        url: app.get_request_url("index", "region"),
        method: "POST",
        data: {
          pid: self.data.province_id
        },
        dataType: "json",
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              city_list: data
            });
          } else {
            my.showToast({
              type: "fail",
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    }
  },

  get_county_list() {
    var self = this;
    if (self.data.city_id) {
      // 加载loding
      my.httpRequest({
        url: app.get_request_url("index", "region"),
        method: "POST",
        data: {
          pid: self.data.city_id
        },
        dataType: "json",
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              county_list: data
            });
          } else {
            my.showToast({
              type: "fail",
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    }
  },

  select_province(e) {
    var value = e.detail.value,
      data = this.data.province_list[value];
    this.setData({
      province_value: value,
      province_id: data.id,
      city_value: null,
      county_value: null,
      city_id: null,
      county_id: null
    });
    this.get_city_list();
  },

  select_city(e) {
    var value = e.detail.value,
      data = this.data.city_list[value];
    this.setData({
      city_value: value,
      city_id: data.id,
      county_value: null,
      county_id: null
    });
    this.get_county_list();
  },

  select_county(e) {
    var value = e.detail.value,
      data = this.data.county_list[value];
    this.setData({
      county_value: value,
      county_id: data.id
    });
  },

  init_value() {
    var province_value = this.get_init_value("province_list", "province_id"),
      city_value = this.get_init_value("city_list", "city_id"),
      county_value = this.get_init_value("county_list", "county_id");
    this.setData({
      province_value: province_value,
      city_value: city_value,
      county_value: county_value
    });
  },

  get_init_value(list, id) {
    var data = this.data[list],
      data_id = this.data[id],
      value;
    data.forEach((d, i) => {
      if (d.id == data_id) {
        value = i;
        return false;
      }
    });
    return value;
  },

  form_submit(e) {
    var self = this,
      data = self.data;
    // 表单数据
    var form_data = e.detail.value;

    // 数据校验
    var validation = [
      { fields: "name", msg: "请填写姓名" },
      { fields: "tel", msg: "请填写手机号" },
      { fields: "province", msg: "请选择省份" },
      { fields: "city", msg: "请选择城市" },
      { fields: "county", msg: "请选择区县" },
      { fields: "address", msg: "请填写详细地址" }
    ];

    form_data["province"] = data.province_id;
    form_data["city"] = data.city_id;
    form_data["county"] = data.county_id;
    form_data["id"] = self.data.params.id || 0;
    form_data["is_default"] = self.data.is_default || 0;

    if (app.fields_check(form_data, validation)) {
      // 加载loding
      my.showLoading({ content: "处理中..." });

      my.httpRequest({
        url: app.get_request_url("save", "useraddress"),
        method: "POST",
        data: form_data,
        dataType: "json",
        success: res => {
          my.hideLoading();
          if (res.data.code == 0) {
            my.showToast({
              type: "success",
              content: res.data.msg
            });
            setTimeout(function() {
              my.navigateBack();
            }, 1000);
          } else {
            my.showToast({
              type: "fail",
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.hideLoading();
          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    }
  }
});
