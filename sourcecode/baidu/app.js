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
    tabbar_pages: [
        "/pages/index/index",
        "/pages/goods-category/goods-category",
        "/pages/cart/cart",
        "/pages/user/user",
    ],

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
      "goods_comment": "商品评论",
      "user_orderaftersale": "退款/售后",
      "user_orderaftersale_detail": "订单售后",
      "user_order_comments": "订单评论",
      "coupon": "领劵中心",
      "user_coupon": "优惠劵",
      "extraction_address": "自提地址",
    },

    // 请求地址
    request_url: "{{request_url}}",
    // request_url: 'http://shopxo.com/',
    // request_url: 'https://dev.shopxo.net/',

    // 基础信息
    application_title: "{{application_title}}",
    application_describe: "{{application_describe}}",

    // 价格符号
    price_symbol: "{{price_symbol}}"
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
   * 请求地址生成
   * a              方法
   * c              控制器
   * plugins        插件标记（传参则表示为插件请求）
   * params         url请求参数
   */
  get_request_url(a, c, plugins, params) {
    a = a || "index";
    c = c || "index";

    // 是否插件请求
    var plugins_params = "";
    if ((plugins || null) != null)
    {
      plugins_params = "&pluginsname=" + plugins + "&pluginscontrol=" + c + "&pluginsaction=" + a;

      // 走api统一插件调用控制器
      c = "plugins"
      a = "index"
    }

    // 参数处理
    params = params || "";
    if (params != "" && params.substr(0, 1) != "&") {
      params = "&" + params;
    }

    // 用户信息
    var user = this.get_user_cache_info();
    var token = (user == false) ? '' : user.token || '';
    return this.data.request_url +
      "index.php?s=/api/" + c + "/" + a + plugins_params+
      "&application=app&application_client_type=baidu" +
      "&token=" +
      token +
      "&ajax=ajax" +
      params;
  },

  /**
   * 获取用户信息,信息不存在则唤醒授权
   * object     回调操作对象
   * method     回调操作对象的函数
   * return     有用户数据直接返回, 则回调调用者
   */
  get_user_info(object, method) {
    var user = this.get_user_cache_info();
    if (user == false) {
      // 唤醒用户授权
      this.user_login(object, method);

      return false;
    } else {
      return user;
    }
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
   * auth_data  授权数据
   */
  user_auth_login(object, method, auth_data) {
    var self = this;
    swan.checkSession({
      success: function () {
        var openid = swan.getStorageSync(self.data.cache_user_login_key) || null;
        if (openid == null)
        {
          self.user_login(object, method);
        } else {
          self.get_user_login_info(object, method, openid, auth_data);
        }
      },
      fail: function () {
        self.user_login(object, method);
      }
    });
  },

  /**
   * 用户登录
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_login(object, method) {
    var openid = swan.getStorageSync(this.data.cache_user_login_key) || null;
    if (openid == null)
    {
      var self = this;
      // 加载loding
      swan.showLoading({ title: "授权中..." });

      swan.login({
        success: function (res) {
          swan.request({
            url: self.get_request_url("baiduuserauth", "user"),
            method: "POST",
            data: {
              authcode: res.code
            },
            header: {'content-type': 'application/x-www-form-urlencoded'},
            dataType: "json",
            success: res => {
              swan.hideLoading();
              if (res.data.code == 0) {
                var data = res.data.data;
                if ((data.is_user_exist || 0) == 1) {
                  swan.setStorage({
                    key: self.data.cache_user_info_key,
                    data: data,
                    success: (res) => {
                      if (typeof object === 'object' && (method || null) != null) {
                        object[method]();
                      }
                    },
                    fail: () => {
                      self.showToast('用户信息缓存失败');
                    }
                  });
                } else {
                  swan.setStorage({
                    key: self.data.cache_user_login_key,
                    data: data.openid
                  });
                  self.login_to_auth();
                }
              } else {
                swan.hideLoading();
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
    } else {
      this.login_to_auth();
    }
  },

  /**
   * 跳转到登录页面授权
   */
  login_to_auth() {
    swan.showModal({
        title: '温馨提示',
        content: '授权用户信息',
        confirmText: '确认',
        cancelText: '暂不',
        success: (result) => {
          if (result.confirm) {
            swan.navigateTo({
              url: "/pages/login/login"
            });
          }
        }
      });
  },

  /**
   * 获取用户授权信息
   * object     回调操作对象
   * method     回调操作对象的函数
   * openid     用户openid
   * auth_data  授权数据
   */
  get_user_login_info(object, method, openid, auth_data) {
    // 邀请人参数
    var params = swan.getStorageSync(this.data.cache_launch_info_key) || null;
    var referrer = (params == null) ? 0 : (params.referrer || 0);

    // 远程解密数据
    swan.showLoading({ title: "授权中..." });
    var self = this;
    swan.request({
      url: self.get_request_url('baiduuserinfo', 'user'),
      method: 'POST',
      data: {
        "encrypted_data": auth_data.encryptedData,
        "iv": auth_data.iv,
        "openid": openid,
        "referrer": referrer
      },
      dataType: 'json',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: (res) => {
        swan.hideLoading();
        if (res.data.code == 0) {
          swan.setStorage({
            key: self.data.cache_user_info_key,
            data: res.data.data,
            success: (res) => {
              if (typeof object === 'object' && (method || null) != null) {
                object[method]();
              }
            },
            fail: () => {
              self.showToast('用户信息缓存失败');
            }
          });
        } else {
          self.showToast(res.data.msg);
        }
      },
      fail: () => {
        swan.hideLoading();
        self.showToast('服务器请求出错');
      },
    });
  },

  /**
   * 字段数据校验
   * data           待校验的数据, 一维json对象
   * validation     待校验的字段, 格式 [{fields: 'mobile', msg: '请填写手机号码', is_can_zero: 1(是否可以为0)}, ...]
  */
  fields_check(data, validation) {
    for (var i in validation) {
      var temp_value = data[validation[i]["fields"]];
      var temp_is_can_zero = validation[i]["is_can_zero"] || null;

      if ((temp_value == undefined || temp_value.length == 0 || temp_value == -1) || (temp_is_can_zero == null && temp_value == 0)
      ) {
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
    if (url.indexOf("?") == -1)
    {
      var value = url;
    } else {
      var temp_str = url.split("?");
      var value = temp_str[0];
    }
    if ((value || null) == null)
    {
      return false;
    }

    var temp_tabbar_pages = this.data.tabbar_pages;
    for (var i in temp_tabbar_pages)
    {
      if (temp_tabbar_pages[i] == value)
      {
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
            longitude: parseFloat(values[2]),
            latitude: parseFloat(values[3])
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
   * alert确认框
   * title              [string]    标题（默认空）
   * msg                [string]    提示信息，必传
   * is_show_cancel     [int]       是否显示取消按钮（默认显示 0否, 1|undefined是）
   * cancel_text        [string]    取消按钮文字（默认 取消）
   * cancel_color       [string]    取消按钮的文字颜色，必须是 16 进制格式的颜色字符串（默认 #000000）
   * confirm_text       [string]    确认按钮文字（默认 确认）
   * confirm_color      [string]    确认按钮的文字颜色，必须是 16 进制格式的颜色字符串（默认 #000000）
   * object             [boject]    回调操作对象，点击确认回调参数1，取消回调0
   * method             [string]    回调操作对象的函数
   */
  alert(e)
  {
    var msg = e.msg || null;
    if (msg != null)
    {
      var title = e.title || '';
      var is_show_cancel = (e.is_show_cancel == 0) ? false : true;
      var cancel_text = e.cancel_text || '取消';
      var confirm_text = e.confirm_text || '确认';
      var cancel_color = e.cancel_color || '';
      var confirm_color = e.confirm_color || '';

      swan.showModal({
        title: title,
        content: msg,
        showCancel: is_show_cancel,
        cancelText: cancel_text,
        cancelColor: cancel_color,
        confirmText: confirm_text,
        confirmColor: confirm_color,
        success(res) {
          if ((e.object || null) != null && typeof e.object === 'object' && (e.method || null) != null) {
            e.object[e.method](res.confirm ? 1 : 0);
          }
        }
      });
    } else {
      self.showToast('提示信息为空 alert');
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
  },

  // 拨打电话
  call_tel(value) {
    if ((value || null) != null) {
      swan.makePhoneCall({ phoneNumber: value });
    }
  },

  /**
   * 登录校验
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  is_login_check(res, object, method) {
    if(res.code == -400)
    {
      swan.clearStorage();
      this.get_user_info(object, method);
      return false;
    }
    return true;
  },

  /**
   * 设置导航reddot
   * index     tabBar 的哪一项，从左边算起（0开始）
   * type      0 移出, 1 添加 （默认 0 移出）
   */
  set_tab_bar_reddot(index, type) {
    if (index !== undefined && index !== null)
    {
      if ((type || 0) == 0)
      {
        swan.hideTabBarRedDot({ index: Number(index) });
      } else {
        swan.showTabBarRedDot({ index: Number(index) });
      }
    }
  },

  /**
   * 设置导航车badge
   * index     tabBar 的哪一项，从左边算起（0开始）
   * type      0 移出, 1 添加 （默认 0 移出）
   * value     显示的文本，超过 4 个字符则显示成 ...（type参数为1的情况下有效）
   */
  set_tab_bar_badge(index, type, value) {
    if (index !== undefined && index !== null)
    {
      if ((type || 0) == 0) {
        swan.removeTabBarBadge({ index: Number(index) });
      } else {
        swan.setTabBarBadge({ index: Number(index), "text": value.toString() });
      }
    }
  },

});