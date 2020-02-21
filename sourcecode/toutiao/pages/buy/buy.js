const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_list_loding_status: 1,
    buy_submit_disabled_status: false,
    data_list_loding_msg: '',
    params: null,
    payment_list: [],
    goods_list: [],
    address: null,
    address_id: null,
    total_price: 0,
    user_note_value: '',
    is_first: 1,
    extension_data: [],
    payment_id: 0,
    common_order_is_booking: 0,
    common_site_type: 0,
    extraction_address: [],
    site_model: 0,
    buy_header_nav: [
      { name: "快递邮寄", value: 0 },
      { name: "自提点取货", value: 2 }
    ],

    // 优惠劵
    plugins_coupon_data: null,
    plugins_use_coupon_id: 0,
    plugins_choice_coupon_value: '选择优惠劵',
    popup_plugins_coupon_status: false,
  },
  onLoad(params) {
    //params['data'] = '{"buy_type":"goods","goods_id":"1","stock":"1","spec":"[]"}';
    if((params.data || null) != null && app.get_length(JSON.parse(params.data)) > 0)
    {
      this.setData({ params: JSON.parse(params.data)});

      // 删除地址缓存
      tt.removeStorageSync(app.data.cache_buy_user_address_select_key);
    }
  },

  onShow() {
    this.init();
    this.setData({ is_first: 0 });
  },

  // 获取数据列表
  init() {
    // 订单参数信息是否正确
    if (this.data.params == null) {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '订单信息有误',
      });
      tt.stopPullDownRefresh();
      return false;
    }

    // 本地缓存地址
    if(this.data.is_first == 0)
    {
      var cache_address = tt.getStorageSync(app.data.cache_buy_user_address_select_key);
      if((cache_address || null) != null)
      {
        this.setData({
          address: cache_address,
          address_id: cache_address.id,
        });
      }
    }

    // 加载loding
    tt.showLoading({title: '加载中...'});
    this.setData({
      data_list_loding_status: 1
    });

    var data = this.data.params;
    data['address_id'] = this.data.address_id;
    data['payment_id'] = this.data.payment_id;
    data['coupon_id'] = this.data.plugins_use_coupon_id;
    data['site_model'] = this.data.site_model;
    tt.request({
      url: app.get_request_url("index", "buy"),
      method: "POST",
      data: data,
      dataType: "json",
      success: res => {
        tt.stopPullDownRefresh();
        tt.hideLoading();
        if (res.data.code == 0) {
          var data = res.data.data;
          if (data.goods_list.length == 0)
          {
            this.setData({data_list_loding_status: 0});
          } else {
            this.setData({
              goods_list: data.goods_list,
              total_price: data.base.actual_price,
              extension_data: data.extension_data || [],
              data_list_loding_status: 3,
              common_order_is_booking: data.common_order_is_booking || 0,
              common_site_type: data.common_site_type || 0,
              extraction_address: data.base.extraction_address || [],
              plugins_coupon_data: data.plugins_coupon_data || null,
            });

            // 优惠劵选择处理
            if ((data.plugins_coupon_data || null) != null)
            {
              if ((data.plugins_coupon_data.coupon_choice || null) != null)
              {
                this.setData({ plugins_choice_coupon_value: data.plugins_coupon_data.coupon_choice.coupon.desc });
              } else {
                var coupon_count = ((data.plugins_coupon_data.coupon_list || null) != null) ? data.plugins_coupon_data.coupon_list.length : 0;
                this.setData({ plugins_choice_coupon_value: (coupon_count > 0) ? '可选优惠劵' + coupon_count + '张' : '暂无可用优惠劵' });
              }
            }

            // 地址
            this.setData({
              address: data.base.address || null,
              address_id: ((data.base.address || null) != null) ? data.base.address.id : null,
            });
            tt.setStorage({
              key: app.data.cache_buy_user_address_select_key,
              data: data.base.address || null,
            });

            // 支付方式
            this.payment_list_data(data.payment_list);
          }
        } else {
          this.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, this, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        tt.stopPullDownRefresh();
        tt.hideLoading();
        this.setData({
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

  // 用户留言事件
  bind_user_note_event(e) {
    this.setData({user_note_value: e.detail.value});
  },

  // 提交订单
  buy_submit_event(e) {
    // 表单数据
    var data = this.data.params;
    data['address_id'] = this.data.address_id;
    data['payment_id'] = this.data.payment_id;
    data['user_note'] = this.data.user_note_value;
    data['coupon_id'] = this.data.plugins_use_coupon_id;
    data['site_model'] = this.data.site_model;

    // 数据验证
    var validation = [];
    if (this.data.common_site_type == 0 || this.data.common_site_type == 2 || this.data.common_site_type == 4)
    {
      validation.push({ fields: 'address_id', msg: '请选择地址', is_can_zero: 1 });
    }
    if (this.data.common_order_is_booking != 1) {
      validation.push({ fields: 'payment_id', msg: '请选择支付方式' });
    }
    if (app.fields_check(data, validation)) {
      // 加载loding
      tt.showLoading({title: '提交中...'});
      this.setData({ buy_submit_disabled_status: true });

      tt.request({
        url: app.get_request_url("add", "buy"),
        method: "POST",
        data: data,
        dataType: "json",
        success: res => {
          tt.hideLoading();
          if (res.data.code == 0) {
            if (res.data.data.order.status == 1) {
              tt.redirectTo({
                url: '/pages/user-order/user-order?is_pay=1&order_id=' + res.data.data.order.id
              });
            } else {
              tt.redirectTo({url: '/pages/user-order/user-order'});
            }
          } else {
            app.showToast(res.data.msg);
            this.setData({ buy_submit_disabled_status: false });
          }
        },
        fail: () => {
          tt.hideLoading();
          this.setData({buy_submit_disabled_status: false});
          
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 支付方式选择
  payment_event(e) {
    this.setData({ payment_id: e.currentTarget.dataset.value});
    this.payment_list_data(this.data.payment_list);
    this.init();
  },

  // 支付方式数据处理
  payment_list_data(data) {
    if (this.data.payment_id != 0) {
      for (var i in data) {
        if (data[i]['id'] == this.data.payment_id) {
          data[i]['selected'] = 'selected';
        } else {
          data[i]['selected'] = '';
        }
      }
    }
    this.setData({payment_list: data || []});
  },

  // 优惠劵弹层开启
  plugins_coupon_open_event(e) {
    this.setData({ popup_plugins_coupon_status: true});
  },

  // 优惠劵弹层关闭
  plugins_coupon_close_event(e) {
    this.setData({ popup_plugins_coupon_status: false });
  },

  // 优惠劵选择
  plugins_coupon_use_event(e) {
    var index = e.currentTarget.dataset.index;
    var value = e.currentTarget.dataset.value;
    this.setData({
      plugins_use_coupon_id: value,
      popup_plugins_coupon_status: false,
    });
    this.init();
  },

  // 不使用优惠劵
  plugins_coupon_not_use_event(e) {
    this.setData({
      plugins_use_coupon_id: 0,
      popup_plugins_coupon_status: false,
    });
    this.init();
  },

  // 地址选择事件
  address_event(e) {
    if (this.data.common_site_type == 0 || (this.data.common_site_type == 4 && this.data.site_model == 0))
    {
      tt.navigateTo({
        url: '/pages/user-address/user-address?is_back=1'
      });
    } else if (this.data.common_site_type == 2 || (this.data.common_site_type == 4 && this.data.site_model == 2))
    {
      tt.navigateTo({
        url: '/pages/extraction-address/extraction-address?is_back=1'
      });
    } else {
      app.showToast('当前模式不允许使用地址');
    }
  },

  // 销售+自提 模式选择事件
  buy_header_nav_event(e) {
    var value = e.currentTarget.dataset.value || 0;
    if (value != this.data.site_model)
    {
      // 数据设置
      this.setData({
        address: null,
        address_id: null,
        site_model: value,
      });

      // 删除地址缓存
      tt.removeStorageSync(app.data.cache_buy_user_address_select_key);

      // 数据初始化
      this.init();
    }
  },
});
