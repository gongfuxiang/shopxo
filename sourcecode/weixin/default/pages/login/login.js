const app = getApp();
Page({
  data: {
    params: null,
    user: null,
    mobile: null,
    verify_submit_text: '获取验证码',
    verify_loading: false,
    verify_disabled: false,
    form_submit_loading: false,
    verify_time_total: 60,
    temp_clear_time: null,

    // 基础配置
    // 0 确认绑定方式, 1 验证码绑定
    login_type_status: 0,
    common_user_is_onekey_bind_mobile: 0,
  },

  // 页面加载初始化
  onLoad(option) {
    this.setData({
      params: option,
      user: app.get_user_cache_info() || null
    });
  },

  // 页面显示
  onShow() {
    wx.setNavigationBarTitle({ title: (this.data.user == null) ? '授权用户信息' : '手机绑定' });

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        common_user_is_onekey_bind_mobile: app.get_config('config.common_user_is_onekey_bind_mobile'),
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  /**
   * 登录授权事件
   */
  get_user_info_event(e) {
    wx.getUserProfile({
      desc: '注册使用',
      lang: 'zh_CN',
      success: (res) => {
        this.user_auth_code(res.userInfo);
      }
    });
  },

  /**
   * 用户授权
   * auth_data  授权数据
   */
  user_auth_code(auth_data) {
    if ((auth_data || null) != null) {
      app.user_auth_login(this, 'user_auth_back_event', auth_data);
    } else {
      app.showToast("授权失败");
    }
  },

  /**
   * 授权返回事件
   */
  user_auth_back_event() {
    var user = app.get_user_cache_info();
    this.setData({user: user || null});
    if (app.user_is_need_login(user) == false)
    {
      wx.navigateBack();
    }
  },

  /**
   * 输入手机号码事件
   */
  bind_key_input(e)
  {
    this.setData({mobile: e.detail.value});
  },

  /**
   * 短信验证码发送
   */
  verify_send()
  {
    // 数据验证
    var validation = [{fields: 'mobile', msg: '请填写手机号码'}];
    if(app.fields_check(this.data, validation))
    {
      // 网络请求
      var self = this;
      wx.showLoading({title: '发送中...'});
      this.setData({verify_submit_text: '发送中', verify_loading: true, verify_disabled: true});

      wx.request({
        url: app.get_request_url('appmobilebindverifysend', 'user'),
        method: 'POST',
        data: {mobile: this.data.mobile},
        dataType: 'json',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: (res) => {
          wx.hideLoading();
          if(res.data.code == 0)
          {
            this.setData({verify_loading: false});
            var temp_time = this.data.verify_time_total;
            this.data.temp_clear_time = setInterval(function()
            {
              if(temp_time <= 1)
              {
                clearInterval(self.data.temp_clear_time);
                self.setData({verify_submit_text: '获取验证码', verify_disabled: false});
              } else {
                temp_time--;
                self.setData({verify_submit_text: '剩余 '+temp_time+' 秒'});
              }
            }, 1000);
          } else {
            this.setData({verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false});
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();
          this.setData({verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false});
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  /**
   * 表单提交
   */
  formSubmit(e)
  {
    // 邀请人参数
    var params = wx.getStorageSync(app.data.cache_launch_info_key) || null;  

    // 数据验证
    var validation = [
      {fields: 'mobile', msg: '请填写手机号码'},
      {fields: 'verify', msg: '请填写验证码'},
      {fields: 'weixin_openid', msg: '授权id不能为空'}
    ];
    e.detail.value['weixin_openid'] = this.data.user.weixin_openid;
    e.detail.value['nickname'] = this.data.user.nickname;
    e.detail.value['avatar'] = this.data.user.avatar;
    e.detail.value['province'] = this.data.user.province;
    e.detail.value['city'] = this.data.user.city;
    e.detail.value['gender'] = this.data.user.gender;
    e.detail.value['weixin_unionid'] = this.data.user.weixin_unionid || '';
    e.detail.value['referrer'] = (params == null) ? (this.data.user.referrer || 0) : (params.referrer || 0);
    if(app.fields_check(e.detail.value, validation))
    {
      wx.showLoading({title: '处理中...'});
      this.setData({form_submit_loading: true});

      // 网络请求
      wx.request({
        url: app.get_request_url('appmobilebind', 'user'),
        method: 'POST',
        data: e.detail.value,
        dataType: 'json',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: (res) => {
          wx.hideLoading();

          if(res.data.code == 0 && (res.data.data || null) != null)
          {
            clearInterval(this.data.temp_clear_time);
            app.showToast(res.data.msg, 'success');
            
            wx.setStorage({
              key: app.data.cache_user_info_key,
              data: res.data.data
            });
            
            var event_callback = this.data.params.event_callback || null;
            setTimeout(function()
            {
              // 触发回调函数
              if(event_callback != null)
              {
                getCurrentPages()[getCurrentPages().length-2][event_callback]();
              }
              wx.navigateBack();
            }, 1000);
          } else {
            this.setData({form_submit_loading: false});
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();
          this.setData({form_submit_loading: false});
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 获取手机号码一键登录
  confirm_phone_number_event(e) {
    var encrypted_data = e.detail.encryptedData || null;
    var iv = e.detail.iv || null;
    if(encrypted_data != null && iv != null) {
      // 邀请人参数
      var params = wx.getStorageSync(app.data.cache_launch_info_key) || null;
      var referrer = (params == null) ? (this.data.user.referrer || 0) : (params.referrer || 0);

      // 解密数据并绑定手机
      var data = {
        "encrypted_data": encrypted_data,
        "iv": iv,
        "openid": this.data.user.weixin_openid,
        "nickname": this.data.user.nickname || '',
        "avatar": this.data.user.avatar || '',
        "province": this.data.user.province || '',
        "city": this.data.user.city || '',
        "gender": this.data.user.gender || 0,
        "weixin_unionid": this.data.user.weixin_unionid || '',
        "referrer": referrer || 0
      };

      wx.showLoading({ title: "处理中..." });
      var self = this;
      wx.request({
        url: app.get_request_url('onekeyusermobilebind', 'user'),
        method: 'POST',
        data: data,
        dataType: 'json',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: (res) => {
          wx.hideLoading();
          if (res.data.code == 0 && (res.data.data || null) != null) {
            app.showToast(res.data.msg, 'success');

            wx.setStorage({
              key: app.data.cache_user_info_key,
              data: res.data.data
            });
            
            var event_callback = this.data.params.event_callback || null;
            setTimeout(function()
            {
              // 触发回调函数
              if(event_callback != null)
              {
                getCurrentPages()[getCurrentPages().length-2][event_callback]();
              }
              wx.navigateBack();
            }, 1000);
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();
          self.showToast('服务器请求出错');
        },
      });
    }
  },

  // 确认使用验证码
  confirm_verify_event(e) {
    this.setData({login_type_status: 1});
  },

  // 协议事件
  agreement_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if(value == null)
    {
      app.showToast('协议类型有误');
      return false;
    }

    // 是否存在协议 url 地址
    var key = 'agreement_'+value+'_url';
    var url = app.get_config('config.'+key) || null;
    if(url == null)
    {
      app.showToast('协议url地址有误');
      return false;
    }

    // 打开 webview
    app.open_web_view(url);
  },

});
