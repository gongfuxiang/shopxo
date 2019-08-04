App({
  data: {
    // 用户登录缓存key
    cache_user_login_key: "cache_user_login_key",

    // 用户信息缓存key
    cache_user_info_key: "cache_shop_user_info_key",

    // 用户站点信息缓存key
    cache_user_merchant_key: "cache_shop_user_merchant_key",

    // 设备信息缓存key
    cache_system_info_key: "cache_shop_system_info_key",

    // 用户地址选择缓存key
    cache_buy_user_address_select_key: "cache_buy_user_address_select_key",

    // 用户传入信息缓存key
    cache_launch_info_key: "cache_shop_launch_info_key",

    // 默认用户头像
    default_user_head_src: "/images/default-user.png",

    // 成功圆形提示图片
    default_round_success_icon: "/images/default-round-success-icon.png",

    // 错误圆形提示图片
    default_round_error_icon: "/images/default-round-error-icon.png",

    // tabbar页面
    tabbar_pages: ["index", "goods-category", "cart", "user"],

    // 页面标题
    common_pages_title: {
      "goods_search": "商品搜索",
      "goods_detail": "商品详情",
      "goods_attribute": "属性",
      "user_address": "我的地址",
      "user_address_save_add": "新增地址",
      "user_address_save_edit": "编辑地址",
      "buy": "订单确认",
      "user_order": "我的订单",
      "user_order_detail": "订单详情",
      "user_favor": "我的收藏",
      "answer_form": "留言",
      "answer_list": "问答",
      "user_answer_list": "我的留言",
      "user": "用户中心",
      "goods_category": "分类",
      "cart": "购物车",
      "message": "消息",
      "user_integral": "我的积分",
      "user_goods_browse": "我的足迹",
      "goods_comment": "商品评论"
    },

    // 请求地址
    request_url: "{{request_url}}",
    // request_url: 'http://tp5-dev.com/',
    // request_url: 'https://test.shopxo.net/',

    // 基础信息
    application_title: "{{application_title}}",
    application_describe: "{{application_describe}}"
  },

  /**
   * 小程序初始化
   */
  onLaunch(options) {
    // 设置设备信息
    this.set_system_info();

    // 启动query参数处理
    this.startup_query(options);
  },

  /**
   * 启动query参数处理
   */
  startup_query(params) {
    // 没有启动参数则返回
    if ((params || null) == null) {
      return false;
    }

    // 启动处理类型
    var type = params.type || null;
    switch (type) {
      // type=page
      case "page":
        // 页面
        var page = params.page || null;

        // 参数名
        var params_field = params.params_field || null;

        // 参数值
        var params_value = params.params_value || null;

        // 页面跳转
        if (page != null) {
          swan.navigateTo({
            url: "/pages/" + page + "/" + page + "?" + params_field + "=" + params_value
          });
        }
        break;

      // type=view
      case "view":
        var url = params.url || null;

        // 页面跳转
        if (url != null) {
          swan.navigateTo({
            url: '/pages/web-view/web-view?url=' + url
          });
        }
        break;

      // 默认
      default:
        break;
    }
  },

  /**
   * 获取设备信息
   */
  get_system_info() {
    let system_info = swan.getStorageSync(this.data.cache_system_info_key) || null;
    if (system_info == null) {
      return this.set_system_info();
    }
    return system_info;
  },

  /**
   * 设置设备信息
   */
  set_system_info() {
    var system_info = swan.getSystemInfoSync();
    swan.setStorage({
      key: this.data.cache_system_info_key,
      data: system_info
    });
    return system_info;
  },

  /**
  /**
   * 请求地址生成
   */
  get_request_url(a, c, m, params) {
    a = a || "index";
    c = c || "index";
    m = m || "api";
    params = params || "";
    if (params != "" && params.substr(0, 1) != "&") {
      params = "&" + params;
    }
    var user = this.get_user_cache_info();
    var token = user == false ? '' : user.token || '';
    return this.data.request_url + "index.php?s=/" + m + "/" + c + "/" + a + "&application=app&application_client_type=baidu" + "&token=" + token + "&ajax=ajax" + params;
  },

  /**
   * 从缓存获取用户信息
   */
  get_user_cache_info() {
    let user = swan.getStorageSync(this.data.cache_user_info_key) || null;
    if (user == null) {
      return false;
    }
    return user;
  },

  /**
   * 用户登录
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_auth_login(object, method) {
    var self = this;
    // 请求授权接口
    swan.authorize({
      scope: "scope.userInfo",
      success: res => {
        swan.checkSession({
          success: function (res) {
            self.user_login(object, method);
          },
          fail: function (err) {
            self.user_login(object, method);
          }
        });
      },
      fail: e => {
        self.showToast("调用授权失败");
      }
    });

  },

  /**
   * 用户登录
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_login(object, method) {
    var self = this;
    // 加载loding
    swan.showLoading({ title: "授权中..." });

    // 邀请人参数
    var params = swan.getStorageSync(this.data.cache_launch_info_key) || null;
    var referrer = (params == null) ? 0 : (params.referrer || 0);

    // 请求登录
    swan.login({
      success: function (res) {
        swan.request({
          url: self.get_request_url("baiduuserauth", "user"),
          method: "POST",
          data: {
            authcode: res.code,
            referrer: referrer
          },
          header: {'content-type': 'application/x-www-form-urlencoded'},
          dataType: "json",
          success: res => {
            swan.hideLoading();
            if (res.data.code == 0) {
              swan.getUserInfo({
                success: function (user) {
                    res.data.data['nickname'] = user.userInfo.nickName;
                    res.data.data['avatar'] = user.userInfo.avatarUrl;
                    res.data.data['gender'] = parseInt(user.userInfo.gender)+1;
                    res.data.data['referrer'] = referrer;
                    swan.setStorage({
                      key: self.data.cache_user_info_key,
                      data: res.data.data
                    });

                    if (typeof object === "object" && (method || null) != null) {
                      object[method]();
                    }
                },
                fail: function(res) {
                  self.showToast("调用用户信息授权失败");
                }
              });
            } else {
              self.showToast(res.data.msg);
            }
          },
          fail: () => {
            swan.hideLoading();
            self.showToast("服务器请求出错");
          }
        });
      },
      fail: function (err) {
        swan.hideLoading();
        self.showToast(err);
      }
    });
  },

  /**
   * 字段数据校验
   * data           待校验的数据, 一维json对象
   * validation     待校验的字段, 格式 [{fields: 'mobile', msg: '请填写手机号码'}, ...]
  */
  fields_check(data, validation) {
    for (var i in validation) {
      if ((data[validation[i]['fields']] || null) == null) {
        this.showToast(validation[i]['msg']);
        return false;
      }
    }
    return true;
  },

  /**
   * 获取当前时间戳
   */
  get_timestamp() {
    return parseInt(new Date().getTime() / 1000);
  },

  /**
   * 获取日期
   * format       日期格式（默认 yyyy-MM-dd h:m:s）
   * timestamp    时间戳（默认当前时间戳）
   */
  get_date(format, timestamp) {
    var d = new Date((timestamp || this.get_timestamp()) * 1000);
    var date = {
      "M+": d.getMonth() + 1,
      "d+": d.getDate(),
      "h+": d.getHours(),
      "m+": d.getMinutes(),
      "s+": d.getSeconds(),
      "q+": Math.floor((d.getMonth() + 3) / 3),
      "S+": d.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
      format = format.replace(RegExp.$1, (d.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
      if (new RegExp("(" + k + ")").test(format)) {
        format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
      }
    }
    return format;
  },

  /**
   * 获取对象、数组的长度、元素个数
   * obj      要计算长度的元素（object、array、string）
   */
  get_length(obj) {
    var obj_type = typeof obj;
    if (obj_type == "string") {
      return obj.length;
    } else if (obj_type == "object") {
      var obj_len = 0;
      for (var i in obj) {
        obj_len++;
      }
      return obj_len;
    }
    return false;
  },

  /**
   * 价格保留两位小数
   * price      价格保留两位小数
   */
  price_two_decimal(x) {
    var f_x = parseFloat(x);
    if (isNaN(f_x)) {
      return 0;
    }
    var f_x = Math.round(x * 100) / 100;
    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');
    if (pos_decimal < 0) {
      pos_decimal = s_x.length;
      s_x += '.';
    }
    while (s_x.length <= pos_decimal + 2) {
      s_x += '0';
    }
    return s_x;
  },

  /**
   * 当前地址是否存在tabbar中
   */
  is_tabbar_pages(url) {
    if (url.indexOf("?") == -1) {
      var all = url.split("/");
    } else {
      var temp_str = url.split("?");
      var all = temp_str[0].split("/");
    }
    if (all.length <= 0) {
      return false;
    }

    var temp_tabbar_pages = this.data.tabbar_pages;
    for (var i in temp_tabbar_pages) {
      if (temp_tabbar_pages[i] == all[all.length - 1]) {
        return true;
      }
    }
    return false;
  },

  /**
   * 事件操作
   */
  operation_event(e) {
    var value = e.currentTarget.dataset.value || null;
    var type = parseInt(e.currentTarget.dataset.type);
    if (value != null) {
      switch (type) {
        // web
        case 0:
          swan.navigateTo({ url: '/pages/web-view/web-view?url=' + encodeURIComponent(value) });
          break;

        // 内部页面
        case 1:
          if (this.is_tabbar_pages(value)) {
            swan.switchTab({ url: value });
          } else {
            swan.navigateTo({ url: value });
          }
          break;

        // 跳转到外部小程序
        case 2:
          swan.navigateToSmartProgram({ appId: value });
          break;

        // 跳转到地图查看位置
        case 3:
          var values = value.split('|');
          if (values.length != 4) {
            this.showToast('事件值格式有误');
            return false;
          }

          swan.openLocation({
            name: values[0],
            address: values[1],
            longitude: values[2],
            latitude: values[3]
          });
          break;

        // 拨打电话
        case 4:
          swan.makePhoneCall({ phoneNumber: value });
          break;
      }
    }
  },

  /**
   * 默认弱提示方法
   * msg    [string]  提示信息
   * status [string]  状态 默认error [正确success, 错误error]
   */
  showToast(msg, status) {
    if ((status || 'error') == 'success') {
      swan.showToast({
        title: msg,
        duration: 3000
      });
    } else {
      swan.showToast({
        image: '/images/default-toast-error.png',
        title: msg,
        duration: 3000
      });
    }
  },

  /**
   * 是否需要登录
   * 是否需要绑定手机号码
   */
  user_is_need_login(user) {
    // 用户信息是否正确
    if (user == false) {
      return true;
    }

    // 是否需要绑定手机号码
    if ((user.is_mandatory_bind_mobile || 0) == 1) {
      if ((user.mobile || null) == null) {
        return true;
      }
    }

    return false;
  },

  /**
   * 删除数组中空元素
   */
  array_notempty(data) {
    var arr = [];
    data.map(function(val, index) {
        if (val !== "" && val != undefined) {
            arr.push(val);
        }
    });
    return arr;
  }

});