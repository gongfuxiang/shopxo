const app = getApp();
import parse from 'mini-html-parser2';
Page({
  data: {
    price_symbol: app.data.price_symbol,
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    detail: null,
    detail_list: [],
    extension_data: [],
    site_fictitious: null,
  },

  onLoad(params) {
    //params['id'] = 7;
    this.setData({params: params});
    this.init();
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.user_order_detail});
  },

  init() {
    var self = this;
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    my.request({
      url: app.get_request_url("detail", "order"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      headers: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data.data,
            detail_list:[
              {name: "订单号", value: data.data.order_no || ''},
              {name: "订单模式", value: data.data.order_model_name || '' },
              {name: "状态", value: data.data.status_name || ''},
              {name: "支付状态", value: data.data.pay_status_name || ''},
              {name: "单价", value: data.data.price || ''},
              {name: "总价", value: data.data.total_price || ''},
              {name: "优惠金额", value: data.data.preferential_price || ''},
              {name: "增加金额", value: data.data.increase_price || '' },
              {name: "支付金额", value: data.data.pay_price || ''},
              {name: "支付方式", value: (data.data.payment_name || '') + ((data.data.is_under_line_text || null) == null ? '' : '（' + data.data.is_under_line_text +'）')},
              {name: "快递公司", value: data.data.express_name || ''},
              {name: "快递单号", value: data.data.express_number || ''},
              {name: "用户留言", value: data.data.user_note || ''},
              {name: "创建时间", value: data.data.add_time || ''},
              {name: "确认时间", value: data.data.confirm_time || ''},
              {name: "支付时间", value: data.data.pay_time || ''},
              {name: "发货时间", value: data.data.delivery_time || ''},
              {name: "收货时间", value: data.data.collect_time || ''},
              {name: "取消时间", value: data.data.cancel_time || ''},
              {name: "关闭时间", value: data.data.close_time || ''},
            ],
            extension_data: data.data.extension_data || [],
            site_fictitious: data.site_fictitious || null,
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });

          // web内容转化
          if(data.data.order_model == 3 && data.data.pay_status == 1 && (data.data.status == 3 || data.data.status == 4))
          {
            // 密钥信息
            var items = data.data.items;
            for(var i in items)
            {
              var value = items[i]['fictitious_goods_value'] || null;
              if(value != null)
              {
                parse(value, (err, nodes) => {
                  if (!err) {
                    items[i]['fictitious_goods_value'] = nodes;
                  }
                });
              }
            }

            // 提示信息
            var tips = data.site_fictitious.tips || null;
            if(tips != null)
            {
              parse(tips, (err, nodes) => {
                if (!err) {
                  tips = nodes;
                }
              });
            }
            this.setData({
              'detail.items': items,
              'site_fictitious.tips': tips,
            });
          }
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
        my.hideLoading();
        my.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast('服务器请求出错');
      }
    });
  },

  // 地图查看
  address_map_event(e) {
    if ((this.data.detail.address_data || null) == null)
    {
      app.showToast("地址有误");
      return false;
    }

    var ads = this.data.detail.address_data;
    var lng = parseFloat(ads.lng || 0);
    var lat = parseFloat(ads.lat || 0);
    if (lng <= 0 || lat <= 0) {
      app.showToast("坐标有误");
      return false;
    }

    my.openLocation({
      latitude: lat,
      longitude: lng,
      scale: 18,
      name: ads.alias || '',
      address: (ads.province_name || '') + (ads.city_name || '') + (ads.county_name || '') + (ads.address || ''),
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});
