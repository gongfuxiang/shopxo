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
  },

  /**
   * 页面加载初始化
   */
  onLoad(option) {
    // 标题设置
    my.setNavigationBar({title: '手机绑定'});

    // 设置用户信息
    this.setData({params: option, user: app.GetUserCacheInfo()});
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
      var $this = this;
      my.showLoading({content: '发送中...'});
      this.setData({verify_submit_text: '发送中', verify_loading: true, verify_disabled: true});

      my.httpRequest({
        url: app.get_request_url('regverifysend', 'user'),
        method: 'POST',
        data: {mobile: this.data.mobile},
        dataType: 'json',
        success: (res) => {
          my.hideLoading();
          if(res.data.code == 0)
          {
            this.setData({verify_loading: false});
            var temp_time = this.data.verify_time_total;
            this.data.temp_clear_time = setInterval(function()
            {
              if(temp_time <= 1)
              {
                clearInterval($this.data.temp_clear_time);
                $this.setData({verify_submit_text: '获取验证码', verify_disabled: false});
              } else {
                temp_time--;
                $this.setData({verify_submit_text: '剩余 '+temp_time+' 秒'});
              }
            }, 1000);
          } else {
            this.setData({verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false});
            
            my.showToast({
              type: 'fail',
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.hideLoading();
          this.setData({verify_submit_text: '获取验证码', verify_loading: false, verify_disabled: false});

          my.showToast({
            type: 'fail',
            content: '服务器请求出错'
          });
        }
      });
    }
  },

  /**
   * 表单提交
   */
  formSubmit(e)
  {              
    // 数据验证
    var validation = [
      {fields: 'mobile', msg: '请填写手机号码'},
      {fields: 'verify', msg: '请填写验证码'},
      {fields: 'alipay_openid', msg: '授权id不能为空'}
    ];
    e.detail.value['alipay_openid'] = this.data.user.alipay_openid;
    e.detail.value['nickname'] = this.data.user.nickname;
    e.detail.value['avatar'] = this.data.user.avatar;
    e.detail.value['province'] = this.data.user.province;
    e.detail.value['city'] = this.data.user.city;
    e.detail.value['gender'] = this.data.user.gender;
    e.detail.value['referrer'] = this.data.user.referrer;
    e.detail.value['app_type'] = 'alipay';
    if(app.fields_check(e.detail.value, validation))
    {
      my.showLoading({content: '处理中...'});
      this.setData({form_submit_loading: true});

      // 网络请求
      my.httpRequest({
        url: app.get_request_url('reg', 'user'),
        method: 'POST',
        data: e.detail.value,
        dataType: 'json',
        success: (res) => {
          my.hideLoading();

          if(res.data.code == 0 && (res.data.data || null) != null)
          {
            clearInterval(this.data.temp_clear_time);
            my.showToast({
              type: 'success',
              content: res.data.msg
            });
            
            my.setStorage({
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
              my.navigateBack();
            }, 1000);
          } else {
            this.setData({form_submit_loading: false});
            
            my.showToast({
              type: 'fail',
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.hideLoading();
          this.setData({form_submit_loading: false});

          my.showToast({
            type: 'fail',
            content: '服务器请求出错'
          });
        }
      });
    }
  }

});
