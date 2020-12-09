const app = getApp();
Page({
  data: {
    params: null,
    form_submit_loading: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    data_base: null,
    apply_type_list: [],
    can_invoice_type_list: [],
    invoice_content_list: [],
    save_base_data: null,
    data: null,

    form_invoice_type_index: 0,
    form_apply_type_index: 0,
    form_invoice_content_index: 0,
    form_apply_type_disabled: false,
    company_container: false,
    company_special_container: false,
    addressee_container: false,
    email_container: true,
  },

  onLoad(params) {
    this.setData({ params: params });
    this.init();
  },

  onShow() {},

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("saveinfo", "user", "invoice"),
      method: "POST",
      data: this.data.params,
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            apply_type_list: data.apply_type_list || [],
            can_invoice_type_list: data.can_invoice_type_list || [],
            invoice_content_list: data.invoice_content_list || [],
            save_base_data: data.save_base_data,
            data: ((data.data || null) == null || data.data.length == 0) ? null : data.data,
            data_list_loding_status: 0,
            data_bottom_line_status: true,
            data_list_loding_msg: (data.save_base_data.total_price <= 0) ? '发票金额必须大于0' : '',
          });

          // 数据容器处理
          this.invoice_container_handle();
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
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

  // 发票类型事件
  form_invoice_type_event(e) {
    this.setData({
      form_invoice_type_index: e.detail.value
    });
    this.invoice_container_handle();
  },

  // 发票类型事件
  form_apply_type_event(e) {
    this.setData({
      form_apply_type_index: e.detail.value
    });
    this.invoice_container_handle();
  },

  // 发票内容事件
  form_invoice_content_event(e) {
    this.setData({
      form_invoice_content_index: e.detail.value
    });
  },

  // 容器显隐处理
  invoice_container_handle() {
    // 发票类型
    var invoice_type = this.data.can_invoice_type_list[this.data.form_invoice_type_index]['id'];
    if(invoice_type == 2)
    {
      // 选择专票的时候申请类型必须是企业
      this.setData({
        form_apply_type_index: 1,
        form_apply_type_disabled: true,
      });
    } else {
      this.setData({
        form_apply_type_disabled: false,
      });
    }

    // 申请类型
    switch(invoice_type)
    {
      // 增值税普通电子发票
      case 0 :
        this.setData({
          company_special_container: false,
          addressee_container: false,
          email_container: true,
        });
        break;

      // 增值税普通纸质发票
      case 1 :
        this.setData({
          company_special_container: false,
          addressee_container: true,
          email_container: false,
        });
        break;

      // 增值税专业纸质发票
      case 2 :
        this.setData({
          company_container: true,
          company_special_container: true,
          addressee_container: true,
          email_container: false,
        });
        break;
    }

    // 增值税专业纸质发票情况下个人类型处理
    if(invoice_type != 2)
    {
      var apply_type = this.data.apply_type_list[this.data.form_apply_type_index]['id'];
      if(apply_type == 0)
      {
        this.setData({
          company_container: false,
        });
      } else {
        this.setData({
          company_container: true,
        });
      }
    }
  },

  // 表单提交
  formSubmit(e)
  {
    var data = e.detail.value;
    if((this.data.data || null) == null)
    {
      data['ids'] = this.data.params.ids || '';
      data['type'] = this.data.params.type || '';
    } else {
      data['id'] = this.data.data.id;
    }

    // 数据验证
    var validation = [
      {fields: 'invoice_type', msg: '请选择发票类型', is_can_zero: 1},
      {fields: 'apply_type', msg: '请选择申请类型', is_can_zero: 1},
      {fields: 'invoice_title', msg: '请填写发票抬头、最多200个字符'}
    ];
    if(app.fields_check(data, validation))
    {
      var invoice_type = this.data.can_invoice_type_list[this.data.form_invoice_type_index]['id'];
      var apply_type = this.data.apply_type_list[this.data.form_apply_type_index]['id'];
      if(apply_type == 1)
      {
        validation.push({fields: 'invoice_code', msg: '请填写企业统一社会信用代码或纳税识别号、最多160个字符'});
      }
      if(invoice_type == 2)
      {
        validation.push({fields: 'invoice_bank', msg: '请填写企业开户行名称、最多200个字符'});
        validation.push({fields: 'invoice_account', msg: '请填写企业开户帐号、最多160个字符'});
        validation.push({fields: 'invoice_tel', msg: '请填写企业联系电话 6~15 个字符'});
        validation.push({fields: 'invoice_address', msg: '请填写企业注册地址、最多230个字符'});
      }
      if(invoice_type != 0)
      {
        validation.push({fields: 'name', msg: '请填写收件人姓名格式 2~30 个字符之间'});
        validation.push({fields: 'tel', msg: '请填写收件人电话 6~15 个字符'});
        validation.push({fields: 'address', msg: '请填写收件人地址、最多230个字符'});
      }
      if(app.fields_check(data, validation))
      {
        // 发票类型
        data['invoice_type'] = this.data.can_invoice_type_list[this.data.form_invoice_type_index]['id'];

        // 发票内容
        if(this.data.invoice_content_list.length > 0 && this.data.invoice_content_list[this.data.form_invoice_content_index] != undefined)
        {
          data['invoice_content'] = this.data.invoice_content_list[this.data.form_invoice_content_index];
        }

        wx.showLoading({title: '提交中...'});
        this.setData({form_submit_loading: true});

        // 网络请求
        var self = this;
        wx.request({
          url: app.get_request_url("save", "user", "invoice"),
          method: 'POST',
          data: data,
          dataType: 'json',
          header: { 'content-type': 'application/x-www-form-urlencoded' },
          success: (res) => {
            wx.hideLoading();

            if(res.data.code == 0)
            {
              app.showToast(res.data.msg, "success");
              setTimeout(function()
              {
                // 是否关闭页面进入我的发票、适合从订单开票中过来提交成功直接进入我的发票列表
                if((self.data.params || null) != null && (self.data.params.is_redirect || 0) == 1)
                {
                  wx.redirectTo({
                    url: "/pages/plugins/invoice/invoice/invoice"
                  });
                } else {
                  wx.navigateBack();
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
            wx.hideLoading();
            this.setData({form_submit_loading: false});
            app.showToast('服务器请求出错');
          }
        });
      }
    }
  },
});