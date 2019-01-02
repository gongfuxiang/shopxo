App({
  data: {
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
    },

    // 请求地址
    //request_url: "{{request_url}}",
    //request_url: "http://test.shopxo.net/",
    request_url: 'https://test.shopxo.net/',

    // 基础信息
    application_title: "{{application_title}}",
    application_describe: "{{application_describe}}",
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
  startup_query(options) {
    // 没有启动参数则返回
    if ((options.query || null) == null) {
      return false;
    }

    // 启动处理类型
    var type = options.query.type || null;
    switch (type) {
      // type=page
      case 'page':
        // 页面
        var page = options.query.page || null;

        // 参数值
        var value = options.query.value || null;

        // 附带参数值
        var params = null;

        // 进入逻辑处理
        switch (page) {
          // 进入店铺   page=shop
          case 'shop':

          // 进入项目详情   page=detail
          case 'detail':
            if (value != null) {
              params = 'id=' + value;
            }
            break;

          // 店铺买单   page=shoppay
          case 'shoppay':
            if (value != null) {
              params = 'shop_id=' + value;
            }
            break;

          default:
            break;
        }
        break;

      default:
        break;
    }

    // 是否需要进行页面跳转
    if (params != null) {
      wx.navigateTo({
        url: '/pages/' + page + '/' + page + '?' + params
      });
    }
  },

  /**
   * 获取设备信息
   */
  get_system_info() {
    let system_info = wx.getStorageSync(this.data.cache_system_info_key);
    if ((system_info.data || null) == null) {
      return this.set_system_info();
    }
    return system_info.data;
  },

  /**
   * 设置设备信息
   */
  set_system_info() {
    var system_info = wx.getSystemInfoSync();
    wx.setStorage({
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
    var app_client_user_id = user == false ? "" : user.wechat_openid;
    var user_id = user == false ? 0 : user.id;
    return (
      this.data.request_url +
      "index.php?s=/" + m + "/" + c + "/" + a +
      "&application_client=default&&application=app&application_client_type=wechat&application_user_id=" +
      app_client_user_id +
      "&user_id=" +
      user_id +
      "&ajax=ajax" +
      params
    );
  },

  /**
   * 获取平台用户信息
   * object     回调操作对象
   * method     回调操作对象的函数
   * return     有用户数据直接返回, 则回调调用者
   */
  get_user_info(object, method) {
    var user = this.get_user_cache_info();
    if (user == false) {
      // 唤醒用户授权
      this.user_auth_code(object, method);

      return false;
    }
    return user;
  },

  /**
   * 从缓存获取用户信息
   */
  get_user_cache_info() {
    let user = wx.getStorageSync(this.data.cache_user_info_key);
    if ((user || null) == null) {
      return false;
    }
    return user;
  },

  /**
   * 用户授权
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_auth_code(object, method) {
    // 加载loding
    wx.showLoading({ title: '授权中...' });
    var $this = this;

    // 请求授权接口
    wx.getSetting({
      success(res) {
        if (!res.authSetting['scope.userInfo']) {
          wx.navigateTo({
            url: "/pages/login/login"
          });
          // wx.authorize({
          //   scope: 'scope.userInfo',
          //   success() {
          //     $this.user_auth_login(object, method);
          //   },
          //   fail: (e) => {
          //     wx.hideLoading();
          //     $this.showToast('授权失败');
          //   }
          // });
        } else {
          $this.user_auth_login(object, method);
        }
      },
      fail: (e) => {
        wx.hideLoading();
        $this.showToast('授权校验失败');
      }
    });
  },

  /**
   * 用户登录
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_auth_login(object, method) {
    var $this = this;
    wx.checkSession({
      success: function () {
        var openid = wx.getStorageSync($this.data.cache_user_login_key);
        if ((openid || null) == null)
        {
          $this.user_login(object, method);
        } else {
          $this.get_user_login_info(object, method, openid);
        }
      },
      fail: function () {
        $this.user_login(object, method);
      }
    });
  },

  /**
   * 用户登录
   * object     回调操作对象
   * method     回调操作对象的函数
   */
  user_login(object, method) {
    var $this = this;
    wx.login({
      success: (res) => {
        if (res.code) {
          wx.request({
            url: $this.get_request_url('WechatUserAuth', 'user'),
            method: 'POST',
            data: { authcode: res.code },
            dataType: 'json',
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            success: (res) => {
              if (res.data.code == 0) {
                wx.setStorage({
                  key: $this.data.cache_user_login_key,
                  data: res.data.data
                });
                $this.get_user_login_info(object, method, res.data.data);
              } else {
                wx.hideLoading();
                $this.showToast(res.data.text);
              }
            },
            fail: () => {
              wx.hideLoading();
              $this.showToast('服务器请求出错');
            },
          });
        }
      },
      fail: (e) => {
        wx.hideLoading();
        $this.showToast('授权失败');
      }
    });
  },

  /**
   * 获取用户授权信息
   * object     回调操作对象
   * method     回调操作对象的函数
   * openid     用户openid
   */
  get_user_login_info(object, method, openid) {
    var $this = this;
    wx.getUserInfo({
      withCredentials: true,
      success: function (res) {
        // 远程解密数据
        wx.request({
          url: $this.get_request_url('WechatUserInfo', 'user'),
          method: 'POST',
          data: { encrypted_data: res.encryptedData, iv: res.iv, openid: openid },
          dataType: 'json',
          header: { 'content-type': 'application/x-www-form-urlencoded' },
          success: (res) => {
            wx.hideLoading();
            if (res.data.code == 0) {
              wx.setStorage({
                key: $this.data.cache_user_info_key,
                data: res.data.data,
                success: (res) => {
                  if (typeof object === 'object' && (method || null) != null) {
                    object[method]();
                  }
                },
                fail: () => {
                  $this.showToast('用户信息缓存失败');
                }
              });
            } else {
              $this.showToast(res.data.text);
            }
          },
          fail: () => {
            wx.hideLoading();
            $this.showToast('服务器请求出错');
          },
        });
      },
      fail: (e) => {
        wx.hideLoading();
        $this.showToast('获取用户信息失败');
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
   * 事件操作
   */
  operation_event(e) {
    var value = e.currentTarget.dataset.value || null;
    var type = parseInt(e.currentTarget.dataset.type);
    if (value != null) {
      switch (type) {
        // web
        case 0:
          wx.navigateTo({ url: '/pages/web-view/web-view?url=' + value });
          break;

        // 内部页面
        case 1:
          wx.navigateTo({ url: value });
          break;

        // 跳转到外部小程序
        case 2:
          wx.navigateToMiniProgram({ appId: value });
          break;

        // 跳转到地图查看位置
        case 3:
          var values = value.split('|');
          if (values.length != 4) {
            wx.showToast({ content: '事件值格式有误' });
            return false;
          }

          wx.openLocation({
            name: values[0],
            address: values[1],
            longitude: values[2],
            latitude: values[3],
          });
          break;

        // 拨打电话
        case 4:
          wx.makePhoneCall({ number: value });
          break;
      }
    }
  },

  /**
   * 默认弱提示方法
   * msg    [string]  提示信息
   * status [string]  状态 默认error [正确success, 错误error]
   */
  showToast(msg, status)
  {
    if ((status || 'error') == 'success')
    {
      wx.showToast({
        title: msg,
        duration: 3000
      });
    } else {
      wx.showToast({
        image: '/images/default-toast-error.png',
        title: msg,
        duration: 3000
      });
    }
  }

});