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

    goods_spec_base_price: 0,
    goods_spec_base_original_price: 0,
    goods_spec_base_inventory: 0,
    goods_spec_base_images: '',
  },

  onLoad(params) {
    //params['goods_id']=12;
    this.setData({params: params});
    this.init();
  },

  onShow() {
    wx.setNavigationBarTitle({title: app.data.common_pages_title.goods_detail});
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
      wx.stopPullDownRefresh();
      this.setData({
        data_bottom_line_status: false,
        data_list_loding_status: 2,
        data_list_loding_msg: '商品ID有误',
      });
    } else {
      var self = this;

      // 加载loding
      wx.showLoading({title: '加载中...'});
      this.setData({
        data_list_loding_status: 1
      });

      wx.request({
        url: app.get_request_url("detail", "goods"),
        method: "POST",
        data: {goods_id: this.data.params.goods_id},
        dataType: "json",
        success: res => {
          wx.stopPullDownRefresh();
          wx.hideLoading();
          if (res.data.code == 0) {
            var data = res.data.data;
            self.setData({
              goods: data.goods,
              indicator_dots: (data.goods.photo.length > 1),
              autoplay: (data.goods.photo.length > 1),
              goods_photo: data.goods.photo,
              goods_specifications_choose: data.goods.specifications.choose || [],
              goods_content_app: data.goods.content_app,
              temp_buy_number: data.goods.buy_min_number || 1,
              goods_favor_text: (data.goods.is_favor == 1) ? '已收藏' : '收藏',
              goods_favor_icon: '/images/goods-detail-favor-icon-' + data.goods.is_favor+'.png',
              nav_submit_text: ((data.common_order_is_booking || 0) == 0) ? '立即购买' : '立即预约',
              data_bottom_line_status: true,
              data_list_loding_status: 3,
              nav_submit_is_disabled: (data.goods.is_shelves == 1 && data.goods.inventory > 0) ? false : true,

              goods_spec_base_price: data.goods.price,
              goods_spec_base_original_price: data.goods.original_price,
              goods_spec_base_inventory: data.goods.inventory,
              goods_spec_base_images: data.goods.images
            });

            // 不能选择规格处理
            this.goods_specifications_choose_handle_dont(0);

            if (data.goods.is_shelves != 1) {
              this.setData({
                nav_submit_text: '商品已下架',
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
          wx.stopPullDownRefresh();
          wx.hideLoading();
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: '服务器请求出错',
          });

          app.showToast("服务器请求出错");
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

        // 当只有一个规格的时候
        if(key == 0 && temp_data.length == 1)
        {
          temp_data[i]['value'][k]['is_disabled'] = ((temp_data[i]['value'][k]['is_only_level_one'] || null) != null && (temp_data[i]['value'][k]['inventory'] || 0) <= 0) ? 'spec-items-disabled' : '';
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
    wx.navigateTo({
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
    wx.switchTab({
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
    var user = app.get_user_cache_info(this, 'goods_favor_event');
    // 用户未绑定用户则转到登录页面
    if (user == false || (user.mobile || null) == null) {
      wx.navigateTo({
        url: "/pages/login/login?event_callback=init"
      });
      return false;
    } else {
      wx.showLoading({title: '处理中...'});

      wx.request({
        url: app.get_request_url('favor', 'goods'),
        method: 'POST',
        data: {"id": this.data.goods.id},
        dataType: 'json',
        success: (res) => {
          wx.hideLoading();
          if(res.data.code == 0)
          {
            var status = (this.data.goods.is_favor == 1) ? 0 : 1;
            this.setData({
              'goods.is_favor': status,
              goods_favor_text: (status == 1) ? '已收藏' : '收藏',
              goods_favor_icon: '/images/goods-detail-favor-icon-'+status+'.png'
            });
            app.showToast(res.data.msg, "success");
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();

          app.showToast('服务器请求出错');
        }
      });
    }
  },

  // 加入购物车事件
  goods_cart_event(e, spec) {
    var user = app.get_user_cache_info(this, 'goods_cart_event');
    // 用户未绑定用户则转到登录页面
    if (user == false || (user.mobile || null) == null) {
      wx.navigateTo({
        url: "/pages/login/login?event_callback=init"
      });
      return false;
    } else {
      wx.showLoading({title: '处理中...' });
      wx.request({
        url: app.get_request_url('save', 'cart'),
        method: 'POST',
        data: { "goods_id": this.data.goods.id, "stock": this.data.temp_buy_number, "spec": JSON.stringify(spec) },
        dataType: 'json',
        success: (res) => {
          wx.hideLoading();
          if (res.data.code == 0) {
            this.popup_close_event();
            app.showToast(res.data.msg, "success");
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();

          app.showToast('服务器请求出错');
        }
      });
    }
  },

  // 规格事件
  goods_specifications_event(e) {
    var key = e.currentTarget.dataset.key || 0;
    var keys = e.currentTarget.dataset.keys || 0;
    var temp_data = this.data.goods_specifications_choose;
    var temp_images = this.data.goods_spec_base_images;

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
                if((temp_data[i]['value'][k]['images'] || null) != null)
                {
                  temp_images = temp_data[i]['value'][k]['images'];
                }
              } else {
                temp_data[i]['value'][k]['is_active'] = '';
              }
            }
          }
        }
      }
      this.setData({ goods_specifications_choose: temp_data, goods_spec_base_images: temp_images, temp_buy_number: this.data.goods.buy_min_number || 1});

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
    wx.request({
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
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        app.showToast("服务器请求出错");
      }
    });
  },

  // 获取规格详情
  get_goods_specifications_detail() {
    // 是否全部选中
    var temp_data = this.data.goods_specifications_choose;
    var sku_count = temp_data.length;
    var active_count = 0;

    // 获取规格值
    var spec = [];
    for(var i in temp_data)
    {
      for(var k in temp_data[i]['value'])
      {
        if((temp_data[i]['value'][k]['is_active'] || null) != null)
        {
          active_count++;
          spec.push({"type": temp_data[i]['name'], "value": temp_data[i]['value'][k]['name']});
          break;
        }
      }
    }
    if(spec.length <= 0 || active_count < sku_count)
    {
      this.setData({
        goods_spec_base_price: this.data.goods.price,
        goods_spec_base_original_price: this.data.goods.original_price,
        goods_spec_base_inventory: this.data.goods.inventory,
      });
      return false;
    }

    // 获取数据
    wx.request({
      url: app.get_request_url('specdetail', 'goods'),
      method: 'POST',
      data: { "id": this.data.goods.id, "spec": JSON.stringify(spec) },
      dataType: 'json',
      success: (res) => {
        if (res.data.code == 0) {
          this.setData({
            goods_spec_base_price: res.data.data.price,
            goods_spec_base_original_price: res.data.data.original_price,
            goods_spec_base_inventory: res.data.data.inventory,
          });
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        app.showToast("服务器请求出错");
      }
    });
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
    var inventory = parseInt(this.data.goods_spec_base_inventory);
    var inventory_unit = this.data.goods.inventory_unit;
    if(buy_number < buy_min_number)
    {
      buy_number = buy_min_number;
      if(buy_min_number > 1)
      {
        app.showToast('起购'+buy_min_number+inventory_unit);
      }
    }
    if(buy_max_number > 0 && buy_number > buy_max_number)
    {
      buy_number = buy_max_number;
      app.showToast('限购'+buy_max_number+inventory_unit);
    }
    if(buy_number > inventory)
    {
      buy_number = inventory;
      app.showToast('库存数量'+inventory+inventory_unit);
    }
    this.setData({temp_buy_number: buy_number});
  },

  // 确认
  goods_buy_confirm_event(e) {
    var user = app.get_user_cache_info(this, 'goods_buy_confirm_event');
    // 用户未绑定用户则转到登录页面
    if (user == false || (user.mobile || null) == null) {
      wx.navigateTo({
        url: "/pages/login/login?event_callback=init"
      });
      return false;
    } else {
      // 属性
      var temp_data = this.data.goods_specifications_choose;
      var sku_count = temp_data.length;
      var active_count = 0;
      var spec = [];
      if(sku_count > 0)
      {
        for(var i in temp_data)
        {
          for(var k in temp_data[i]['value'])
          {
            if((temp_data[i]['value'][k]['is_active'] || null) != null)
            {
              active_count++;
              spec.push({"type": temp_data[i]['name'], "value": temp_data[i]['value'][k]['name']});
            }
          }
        }
        if(active_count < sku_count)
        {
          app.showToast('请选择属性');
          return false;
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
            "spec": JSON.stringify(spec)
          };
          wx.navigateTo({
            url: '/pages/buy/buy?data=' + JSON.stringify(data)
          });
          this.popup_close_event();
          break;

        case 'cart' :
          this.goods_cart_event(e, spec);
          break;

        default :
          app.showToast("操作事件类型有误");
      }
    }
  },

  // 详情图片查看
  goods_detail_images_view_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if(value != null)
    {
      wx.previewImage({
        current: 0,
        urls: [value]
      });
    }
  },
  // 商品相册图片查看
  goods_photo_view_event(e) {
    var index = e.currentTarget.dataset.index;
    var all = [];
    for (var i in this.data.goods_photo)
    {
      all.push(this.data.goods_photo[i]['images']);
    }
    wx.previewImage({
      current: index,
      urls: all
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
