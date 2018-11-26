const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    buy_submit_disabled_status: false,
    data_list_loding_msg: '',
    params: null,
    payment_list: [],
    goods_list: [],
    address: null,
    address_id: 0,
    total_price: 0,
    user_note_value: '',
    is_first: 1,
    extension_list: [],
    payment_id: 0,
  },
  onLoad(params) {
    if((params.data || null) == null || app.get_length(JSON.parse(params.data)) == 0)
    {
      my.alert({
        title: '温馨提示',
        content: '订单信息有误',
        buttonText: '确认',
        success: () => {
          my.navigateBack();
        },
      });
    } else {
      this.setData({ params: JSON.parse(params.data)});

      // 删除地址缓存
      my.removeStorageSync({key: app.data.cache_buy_user_address_select_key});
    }
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.buy});
    this.init();
    this.setData({is_first: 0});
  },

  // 获取数据列表
  init() {
    // 本地缓存地址
    if(this.data.is_first == 0)
    {
      var cache_address = my.getStorageSync({
        key: app.data.cache_buy_user_address_select_key
      });
      if((cache_address.data || null) != null)
      {
        this.setData({
          address: cache_address.data,
          address_id: cache_address.data.id
        });
      }
    }

    // 加载loding
    my.showLoading({content: '加载中...'});
    this.setData({
      data_list_loding_status: 1
    });

    var data = this.data.params;
    data['address_id'] = this.data.address_id;
    my.httpRequest({
      url: app.get_request_url("Index", "Buy"),
      method: "POST",
      data: data,
      dataType: "json",
      success: res => {
        my.hideLoading();
        if (res.data.code == 0) {
          var data = res.data.data;
          if (data.goods_list.length == 0)
          {
            this.setData({data_list_loding_status: 0});
          } else {
            this.setData({
              goods_list: data.goods_list,
              total_price: data.base.total_price,
              payment_list: data.payment_list || [],
              extension_list: data.extension_list || [],
              data_list_loding_status: 3,
            });
            if (this.data.address == null || this.data.address_id == 0) {
              this.setData({
                address: data.base.address,
                address_id: ((data.base.address || null) == null) ? 0 : data.base.address.id,
              });
            }
          }
        } else {
          this.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.hideLoading();
        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
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

    // 数据验证
    var validation = [
      { fields: 'address_id', msg: '请选择地址' },
      { fields: 'payment_id', msg: '请选择支付方式' }
    ];
    if (app.fields_check(data, validation)) {
      // 加载loding
      my.showLoading({content: '提交中...'});
      this.setData({
        buy_submit_disabled_status: true,
      });

      my.httpRequest({
        url: app.get_request_url("Add", "Buy"),
        method: "POST",
        data: data,
        dataType: "json",
        success: res => {
          my.hideLoading();
          if (res.data.code == 0) {
            if (res.data.data.order.status == 1) {
              my.redirectTo({
                url: '/pages/user-order/user-order?is_pay=1&order_id=' + res.data.data.order.id
              });
            } else {
              my.redirectTo({url: '/pages/user-order/user-order'});
            }
          }
        },
        fail: () => {
          my.hideLoading();
          self.setData({buy_submit_disabled_status: false});
          
          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    }
  },

  // 支付方式选择
  payment_event(e) {
    var payment_id = e.currentTarget.dataset.value || 0;
    var temp_payment_list = this.data.payment_list;
    for(var i in temp_payment_list) {
      if(temp_payment_list[i]['id'] == payment_id) {
        temp_payment_list[i]['selected'] = 'selected';
      } else {
        temp_payment_list[i]['selected'] = '';
      }
    }
    this.setData({
      payment_id: payment_id,
      payment_list: temp_payment_list
    });
  }

});
