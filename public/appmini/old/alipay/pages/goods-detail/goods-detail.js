const app = getApp();
Page({
  data: {
    indicator_dots: false,
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    autoplay: true,
    circular: true,
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,

    goods: null,
    goods_photo: [],
    goods_specifications_choose: [],
    goods_content_app: [],

    popup_status: false,
    goods_favor_text: '收藏',
    goods_favor_icon: '/images/goods-detail-favor-icon-0.png',
    temp_buy_number: 1,
    buy_event_type: 'buy',
    nav_submit_text: '立即购买',
    nav_submit_is_disabled: true,
  },

  onLoad(params) {
    //params['goods_id']=16;
    this.setData({params: params});
    this.init();
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.goods_detail});
  },

  // 获取数据列表
  init() {
    // 数据初始化
    this.setData({
      temp_attribute_active: {}
    });

    // 参数校验
    if((this.data.params.goods_id || null) == null)
    {
      my.stopPullDownRefresh();
      this.setData({
        data_bottom_line_status: false,
        data_list_loding_status: 2,
        data_list_loding_msg: '商品ID有误',
      });
    } else {
      var self = this;

      // 加载loding
      my.showLoading({content: '加载中...'});
      this.setData({
        data_list_loding_status: 1
      });

      my.httpRequest({
        url: app.get_request_url("detail", "goods"),
        method: "POST",
        data: {goods_id: this.data.params.goods_id},
        dataType: "json",
        success: res => {
          my.stopPullDownRefresh();
          my.hideLoading();
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              goods: data.goods,
              indicator_dots: (data.goods.photo.length > 1),
              autoplay: (data.goods.photo.length > 1),
              goods_photo: data.goods.photo,
              goods_specifications_choose: data.goods.specifications.choose || [],
              goods_content_app: data.goods.content_app,
              temp_buy_number: (data.goods.buy_min_number) || 1,
              goods_favor_text: (data.goods.is_favor == 1) ? '已收藏' : '收藏',
              goods_favor_icon: '/images/goods-detail-favor-icon-' + data.goods.is_favor+'.png',
              nav_submit_text: ((data.common_order_is_booking || 0) == 0) ? '立即购买' : '立即预约',
              data_bottom_line_status: true,
              data_list_loding_status: 3,
              nav_submit_is_disabled: (data.goods.is_shelves == 1 && data.goods.inventory > 0) ? false : true,
            });

            // 不能选择规格处理
            this.goods_specifications_choose_handle_dont(0);

            if (data.goods.is_shelves != 1) {
              this.setData({
                nav_submit_text: '商品已下架',
                nav_submit_is_disabled: true,
              });
            } else {
              if(data.goods.inventory <= 0) {
                this.setData({
                  nav_submit_text: '商品卖光了',
                  nav_submit_is_disabled: true,
                });
              }
            }
          } else {
            self.setData({
              data_bottom_line_status: false,
              data_list_loding_status: 0,
              data_list_loding_msg: res.data.msg,
            });
          }
        },
        fail: () => {
          my.stopPullDownRefresh();
          my.hideLoading();
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: '服务器请求出错',
          });

          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    }
  },

  // 不能选择规格处理
  goods_specifications_choose_handle_dont(key) {
    var temp_data = this.data.goods_specifications_choose || [];
    if(temp_data.length <= 0)
    {
      return false;
    }

    // 是否不能选择
    for(var i in temp_data)
    {
      for(var k in temp_data[i]['value'])
      {
        if(i > key)
        {
          temp_data[i]['value'][k]['is_dont'] = 'spec-dont-choose',
          temp_data[i]['value'][k]['is_disabled'] = '';
          temp_data[i]['value'][k]['is_active'] = '';
        }
      }
    }
    this.setData({goods_specifications_choose: temp_data});
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 进入商品属性事件
  good_attribute_nav_event(e) {
    my.navigateTo({
      url: "/pages/goods-attribute/goods-attribute?data="+JSON.stringify(this.data.goods_attribute_show)
    });
  },

  // 弹层关闭
  popup_close_event(e) {
    this.setData({popup_status: false});
  },

  // 进入店铺
  shop_event(e)
  {
    my.switchTab({
      url: '/pages/index/index'
    });
  },

  // 加入购物车
  cart_submit_event(e) {
    this.setData({ popup_status: true, buy_event_type: 'cart' });
  },

  // 立即购买
  buy_submit_event(e) {
    this.setData({ popup_status: true, buy_event_type: 'buy'});
  },

  // 收藏事件
  goods_favor_event(e)
  {
    var user = app.GetUserInfo(this, 'goods_favor_event');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.navigateTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        my.showLoading({content: '处理中...'});

        my.httpRequest({
          url: app.get_request_url('favor', 'goods'),
          method: 'POST',
          data: {"id": this.data.goods.id},
          dataType: 'json',
          success: (res) => {
            my.hideLoading();
            if(res.data.code == 0)
            {
              var status = (this.data.goods.is_favor == 1) ? 0 : 1;
              this.setData({
                'goods.is_favor': status,
                goods_favor_text: (status == 1) ? '已收藏' : '收藏',
                goods_favor_icon: '/images/goods-detail-favor-icon-'+status+'.png'
              });
              my.showToast({
                type: 'success',
                content: res.data.msg
              });
            } else {
              my.showToast({
                type: 'fail',
                content: res.data.msg
              });
            }
          },
          fail: () => {
            my.hideLoading();

            my.showToast({
              type: 'fail',
              content: '服务器请求出错'
            });
          }
        });
      }
    }
  },

  // 加入购物车事件
  goods_cart_event(e) {
    var user = app.GetUserInfo(this, 'goods_cart_event');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.navigateTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        var attribute_all_cart = {};
        var temp_attribute_active = this.data.temp_attribute_active;
        if (app.get_length(temp_attribute_active) > 0)
        {
          var goods_specifications_choose = this.data.goods_specifications_choose;
          for (var i in temp_attribute_active) {
            attribute_all_cart[goods_specifications_choose[i]['id']] = goods_specifications_choose[i]['find'][temp_attribute_active[i]]['id'];
          }
        }
        my.showLoading({ content: '处理中...' });

        my.httpRequest({
          url: app.get_request_url('save', 'cart'),
          method: 'POST',
          data: { "goods_id": this.data.goods.id, "stock": this.data.temp_buy_number, "attr": JSON.stringify(attribute_all_cart) },
          dataType: 'json',
          success: (res) => {
            my.hideLoading();
            if (res.data.code == 0) {
              this.popup_close_event();
              my.showToast({
                type: 'success',
                content: res.data.msg
              });
            } else {
              my.showToast({
                type: 'fail',
                content: res.data.msg
              });
            }
          },
          fail: () => {
            my.hideLoading();

            my.showToast({
              type: 'fail',
              content: '服务器请求出错'
            });
          }
        });
      }
    }
  },

  // 规格事件
  goods_specifications_event(e) {
    var key = e.currentTarget.dataset.key || 0;
    var keys = e.currentTarget.dataset.keys || 0;
    var temp_data = this.data.goods_specifications_choose;

    // 不能选择和禁止选择跳过
    if((temp_data[key]['value'][keys]['is_dont'] || null) == null && (temp_data[key]['value'][keys]['is_disabled'] || null) == null)
    {
      // 规格选择
      for(var i in temp_data)
      {
        for(var k in temp_data[i]['value'])
        {
          if((temp_data[i]['value'][k]['is_dont'] || null) == null && (temp_data[i]['value'][k]['is_disabled'] || null) == null)
          {
            if(key == i)
            {
              if(keys == k && (temp_data[i]['value'][k]['is_active'] || null) == null)
              {
                temp_data[i]['value'][k]['is_active'] = 'spec-active';
              } else {
                temp_data[i]['value'][k]['is_active'] = '';
              }
            }
          }
        }
      }
      this.setData({goods_specifications_choose: temp_data});

      // 不能选择规格处理
      this.goods_specifications_choose_handle_dont(key);

      // 获取下一个规格类型
      this.get_goods_specifications_type(key);

      // 获取规格详情
      this.get_goods_specifications_detail();
    }
  },

  // 获取下一个规格类型
  get_goods_specifications_type(key) {
    var temp_data = this.data.goods_specifications_choose;
    var active_index = key+1;
    var sku_count = temp_data.length;

    if(active_index <= 0 || active_index >= sku_count)
    {
      return false;
    }

    // 获取规格值
    var spec = [];
    for(var i in temp_data)
    {
      for(var k in temp_data[i]['value'])
      {
        if((temp_data[i]['value'][k]['is_active'] || null) != null)
        {
          spec.push({"type": temp_data[i]['name'], "value": temp_data[i]['value'][k]['name']});
          break;
        }
      }
    }
    if(spec.length <= 0)
    {
      return false;
    }

    // 获取数据
    my.httpRequest({
      url: app.get_request_url('spectype', 'goods'),
      method: 'POST',
      data: { "id": this.data.goods.id, "spec": JSON.stringify(spec) },
      dataType: 'json',
      success: (res) => {
        if (res.data.code == 0) {
          var spec_count = spec.length;
          var index = (spec_count > 0) ? spec_count : 0;
          if(index < sku_count)
          {
            for(var i in temp_data)
            {
              for(var k in temp_data[i]['value'])
              {
                if(index == i)
                {
                  temp_data[i]['value'][k]['is_dont'] = '';
                  var temp_value = temp_data[i]['value'][k]['name'];
                  var temp_status = false;
                  for(var t in res.data.data)
                  {
                    if(res.data.data[t] == temp_value)
                    {
                      temp_status = true;
                      break;
                    }
                  }
                  console.log(temp_value, temp_status, res.data)
                  if(temp_status == true)
                  {
                    temp_data[i]['value'][k]['is_disabled'] = '';
                  } else {
                    temp_data[i]['value'][k]['is_disabled'] = 'spec-items-disabled';
                  }
                }
              }
            }
            this.setData({goods_specifications_choose: temp_data});
          }
        } else {
          my.showToast({
            type: 'fail',
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.showToast({
          type: 'fail',
          content: '服务器请求出错'
        });
      }
    });
  },

  // 获取规格详情
  get_goods_specifications_detail() {

  },

  // 数量输入事件
  goods_buy_number_blur(e) {
    var buy_number = parseInt(e.detail.value) || 1;
    this.setData({temp_buy_number: buy_number});
    this.goods_buy_number_func(buy_number);
  },

  // 数量操作事件
  goods_buy_number_event(e) {
    var type = parseInt(e.currentTarget.dataset.type) || 0;
    var temp_buy_number = parseInt(this.data.temp_buy_number);
    if(type == 0)
    {
      var buy_number = temp_buy_number - 1;
    } else {
      var buy_number = temp_buy_number + 1;
    }
    this.goods_buy_number_func(buy_number);
  },

  // 数量处理方法
  goods_buy_number_func(buy_number) {
    var buy_min_number = parseInt(this.data.goods.buy_min_number) || 1;
    var buy_max_number = parseInt(this.data.goods.buy_max_number) || 0;
    var inventory = parseInt(this.data.goods.inventory);
    var inventory_unit = this.data.goods.inventory_unit;
    if(buy_number < buy_min_number)
    {
      buy_number = buy_min_number;
      if(buy_min_number > 1)
      {
        my.showToast({content: '起购'+buy_min_number+inventory_unit});
      }
    }
    if(buy_max_number > 0 && buy_number > buy_max_number)
    {
      buy_number = buy_max_number;
      my.showToast({content: '限购'+buy_max_number+inventory_unit});
    }
    if(buy_number > inventory)
    {
      buy_number = inventory;
      my.showToast({content: '库存数量'+inventory+inventory_unit});
    }
    this.setData({temp_buy_number: buy_number});
  },

  // 确认
  goods_buy_confirm_event(e) {
    var user = app.GetUserInfo(this, 'goods_buy_confirm_event');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.navigateTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 属性
        var goods_specifications_choose = this.data.goods_specifications_choose;
        var temp_attribute_active = this.data.temp_attribute_active;
        var attr_count = goods_specifications_choose.length;
        var attribute_all = {};
        if(attr_count > 0)
        {
          var attr_active_count = app.get_length(temp_attribute_active);
          if(attr_active_count < attr_count)
          {
            my.showToast({
              type: 'fail',
              content: '请选择属性'
            });
            return false;
          } else {
            for(var i in temp_attribute_active)
            {
              attribute_all[goods_specifications_choose[i]['id']] = goods_specifications_choose[i]['find'][temp_attribute_active[i]]['id'];
            }
          }
        }
        
        // 操作类型
        switch (this.data.buy_event_type) {
          case 'buy' :
            // 进入订单确认页面
            var data = {
              "buy_type": "goods",
              "goods_id": this.data.goods.id,
              "stock": this.data.temp_buy_number,
              "attr": JSON.stringify(attribute_all)
            };
            my.navigateTo({
              url: '/pages/buy/buy?data=' + JSON.stringify(data)
            });
            this.popup_close_event();
            break;

          case 'cart' :
            this.goods_cart_event();
            break;

          default :
            my.showToast({
              type: "fail",
              content: "操作事件类型有误"
            });
        }
      }
    }
  },

  // 详情图片查看
  goods_detail_images_view_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if(value != null)
    {
      my.previewImage({
        current: 0,
        urls: [value]
      });
    }
  },
  // 商品相册图片查看
  goods_photo_view_event(e) {
    var index = e.currentTarget.dataset.index;
    my.previewImage({
      current: index,
      urls: this.data.goods_photo
    });
  },

  // 自定义分享
  onShareAppMessage() {
    return {
      title: app.data.application_title +'-'+ this.data.goods.title,
      desc: app.data.application_describe,
      path: '/pages/goods-detail/goods-detail?share=goods-detail&goods_id='+this.data.goods.id
    };
  },
  
});
