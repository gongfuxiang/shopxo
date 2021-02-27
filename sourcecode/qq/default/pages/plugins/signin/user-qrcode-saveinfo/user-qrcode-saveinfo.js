const app = getApp();
Page({
  data: {
    params: null,
    form_submit_loading: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    data: null,
  },

  onLoad(params) {
    this.setData({ params: params });
    this.init();
  },

  onShow() {},

  init() {
    var self = this;
    qq.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    qq.request({
      url: app.get_request_url("saveinfo", "userqrcode", "signin"),
      method: "POST",
      data: this.data.params,
      dataType: "json",
      success: res => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            data: data.data || null,
            data_list_loding_status: 0,
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 表单提交
  formSubmit(e)
  {
    var data = e.detail.value;
    if((this.data.data || null) != null)
    {
      data['id'] = this.data.data.id || 0;
    }
    // 数据验证
    var validation = [];
    if((this.data.data_base || null) != null && (this.data.data_base.is_qrcode_must_userinfo || 0) == 1)
    {
      validation.push({fields: 'name', msg: '请填写联系人姓名格式 2~30 个字符之间'});
      validation.push({fields: 'tel', msg: '请填写联系人电话 6~15 个字符'});
      validation.push({fields: 'address', msg: '请填写联系人地址、最多230个字符'});
    }
    if(app.fields_check(data, validation))
    {
      qq.showLoading({title: '提交中...'});
      this.setData({form_submit_loading: true});

      // 网络请求
      var self = this;
      qq.request({
        url: app.get_request_url("save", "userqrcode", "signin"),
        method: 'POST',
        data: data,
        dataType: 'json',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: (res) => {
          qq.hideLoading();

          if(res.data.code == 0)
          {
            app.showToast(res.data.msg, "success");
            setTimeout(function()
            {
              // 是否签到也组队
              if((self.data.params || null) != null && (self.data.params.is_team || 0) == 1)
              {
                qq.redirectTo({
                  url: "/pages/plugins/signin/index-detail/index-detail?id="+res.data.data
                });
              } else {
                qq.navigateBack();
              }
            }, 2000);
          } else {
            this.setData({form_submit_loading: false});
            if (app.is_login_check(res.data)) {
              app.showToast(res.data.msg);
            } else {
              app.showToast('提交失败，请重试！');
            }
          }
        },
        fail: () => {
          qq.hideLoading();
          this.setData({form_submit_loading: false});
          app.showToast('服务器请求出错');
        }
      });
    }
  },
});