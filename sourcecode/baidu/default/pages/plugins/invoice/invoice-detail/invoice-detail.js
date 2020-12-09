const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    detail: null,
    detail_list: [],
    express_data: []
  },

  onReady() {},

  onLoad(params) {
    //params['id'] = 1;
    this.setData({
      params: params
    });
    this.init();
  },

  onShow() {},

  init() {
    var self = this;
    swan.showLoading({
      title: "加载中..."
    });
    this.setData({
      data_list_loding_status: 1
    });
    swan.request({
      url: app.get_request_url("detail", "user", "invoice"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        swan.hideLoading();
        swan.stopPullDownRefresh();

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data.data,
            detail_list: [
              { name: "业务类型", value: data.data.business_type_name || '' },
              { name: "申请类型", value: data.data.apply_type_name || '' },
              { name: "发票类型", value: data.data.invoice_type_name || '' },
              { name: "发票金额", value: data.data.total_price || '' },
              { name: "状态", value: data.data.status_name || '' },
              { name: "发票内容", value: data.data.invoice_content || '' },
              { name: "发票抬头", value: data.data.invoice_title || '' },
              { name: "纳税识别号", value: data.data.invoice_code || '' },
              { name: "企业开户行名称", value: data.data.invoice_bank || '' },
              { name: "企业开户帐号", value: data.data.invoice_account || '' },
              { name: "企业联系电话", value: data.data.invoice_tel || '' },
              { name: "企业注册地址", value: data.data.invoice_address || '' },
              { name: "收件人姓名", value: data.data.name || '' },
              { name: "收件人电话", value: data.data.tel || '' },
              { name: "收件人地址", value: data.data.address || '' },
              { name: "电子邮箱", value: data.data.email || '' },
              { name: "拒绝原因", value: data.data.refuse_reason || '' },
              { name: "用户备注", value: data.data.user_note || '' },
              { name: "创建时间", value: data.data.add_time || '' },
              { name: "更新时间", value: data.data.upd_time || '' },
            ],
            express_data: [
              { name: "快递名称", value: data.data.express_name || '' },
              { name: "快递单号", value: data.data.express_number || '' },
            ],
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: ''
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg
          });

          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 电子发票复制
  electronic_invoice_event(e) {
    var value = e.currentTarget.dataset.value || null;

    if (value != null) {
      swan.setClipboardData({
        data: value,
        success(res) {
          swan.showToast({
            title: '内容已复制'
          });
          app.showToast('复制成功', 'success');
        }
      });
    } else {
      app.showToast('链接地址有误');
    }
  }

});