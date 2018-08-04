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
    goods_attribute_show: [],
    goods_attribute_choose: [],
    goods_content_app: [],

    popup_status: 'dis-none',
    goods_favor_text: '收藏',
    goods_favor_icon: '/images/goods-detail-favor-icon-0.png',
    temp_attribute_active: {},
    temp_buy_number: 1,
  },

  onLoad(params) {
    //params['goods_id']=1;
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
        url: app.get_request_url("Detail", "Goods"),
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
              indicator_dots: (data.photo.length > 1),
              autoplay: (data.photo.length > 1),
              goods_photo: data.photo,
              goods_attribute_show: data.attribute.show || [],
              goods_attribute_choose: data.attribute.choose || [],
              goods_content_app: data.content_app,
              temp_buy_number: (data.goods.buy_min_number) || 1,
              goods_favor_text: (data.goods.is_favor == 1) ? '已收藏' : '收藏',
              goods_favor_icon: '/images/goods-detail-favor-icon-'+data.goods.is_favor+'.png',
              data_bottom_line_status: true,
              data_list_loding_status: 3,
            });
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
    this.setData({popup_status: 'dis-none'});
  },

  // 进入店铺
  shop_event(e)
  {
    my.switchTab({
      url: '/pages/index/index'
    });
  },

  // 立即购买
  buy_submit_event(e) {
    this.setData({popup_status: ''});
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
        var self = this;
        my.showLoading({content: '处理中...'});

        my.httpRequest({
          url: app.get_request_url('Favor', 'Goods'),
          method: 'POST',
          data: {goods_id: self.data.goods.id},
          dataType: 'json',
          success: (res) => {
            my.hideLoading();
            if(res.data.code == 0)
            {
              var status = (self.data.goods.is_favor == 1) ? 0 : 1;
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

  // 属性事件
  goods_attribute_event(e) {
    var key = e.currentTarget.dataset.key || 0;
    var keys = e.currentTarget.dataset.keys || 0;
    var temp_data = this.data.temp_attribute_active;

    // 相同则移除
    if(temp_data[key] == keys)
    {
      delete temp_data[key];
    } else {
      temp_data[key] = keys;
    }
    this.setData({temp_attribute_active: temp_data});
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

  // 购买确认
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
        var goods_attribute_choose = this.data.goods_attribute_choose;
        var temp_attribute_active = this.data.temp_attribute_active;
        var attr_count = goods_attribute_choose.length;
        var attribute_all = [];
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
              attribute_all.push(goods_attribute_choose[i]['id']+':'+goods_attribute_choose[i]['find'][temp_attribute_active[i]]['id']);
            }
          }
        }

        // 进入订单确认页面
        var data = [{
          "goods_id": this.data.goods.id,
          "buy_number": this.data.temp_buy_number,
          "attribute": attribute_all.join(',')
        }]
        my.navigateTo({
          url: '/pages/buy/buy?data='+JSON.stringify(data)
        });
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
