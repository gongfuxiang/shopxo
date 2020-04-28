const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    params: null,
    input_keyword_value: '',
    load_status: 0,
    is_show_payment_popup: false,
    payment_list: [],
    payment_id: 0,
    temp_pay_value: 0,
    temp_pay_index: 0,
    nav_status_list: [
      { name: "全部", value: "-1" },
      { name: "待付款", value: "1" },
      { name: "待发货", value: "2" },
      { name: "待收货", value: "3" },
      { name: "已完成", value: "4" },
      { name: "已失效", value: "5,6" },
    ],
    nav_status_index: 0,
  },

  onLoad(params) {
    // 是否指定状态
    var nav_status_index = 0;
    if ((params.status || null) != null) {
      for (var i in this.data.nav_status_list) {
        if (this.data.nav_status_list[i]['value'] == params.status) {
          nav_status_index = i;
          break;
        }
      }
    }

    this.setData({
      params: params,
      nav_status_index: nav_status_index,
    });
    this.init();
  },

  onShow() {
    tt.setNavigationBarTitle({title: app.data.common_pages_title.user_order});
  },

  init() {
    var user = app.get_user_info(this, 'init');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        tt.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 获取数据
        this.get_data_list();
      }
    } else {
      this.setData({
        data_list_loding_status: 0,
        data_bottom_line_status: false,
      });
    }
  },

  // 输入框事件
  input_event(e) {
    this.setData({input_keyword_value: e.detail.value});
  },

  // 获取数据
  get_data_list(is_mandatory) {
    // 分页是否还有数据
    if ((is_mandatory || 0) == 0) {
      if (this.data.data_bottom_line_status == true) {
        return false;
      }
    }

    // 加载loding
    tt.showLoading({title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 参数
    var order_status = ((this.data.nav_status_list[this.data.nav_status_index] || null) == null) ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value'];

    // 获取数据
    tt.request({
      url: app.get_request_url("index", "order"),
      method: "POST",
      data: {
        page: this.data.data_page,
        keywords: this.data.input_keyword_value || "",
        status: order_status,
        is_more: 1,
      },
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();
        if (res.data.code == 0) {
          if (res.data.data.data.length > 0) {
            if (this.data.data_page <= 1) {
              var temp_data_list = res.data.data.data;

              // 下订单支付处理
              if(this.data.load_status == 0)
              {
                if((this.data.params.is_pay || 0) == 1 && (this.data.params.order_id || 0) != 0)
                {
                  for(var i in temp_data_list)
                  {
                    if(this.data.params.order_id == temp_data_list[i]['id'])
                    {
                      this.pay_handle(this.data.params.order_id, i);
                      break;
                    }
                  }
                }
              }
            } else {
              var temp_data_list = this.data.data_list;
              var temp_data = res.data.data.data;
              for (var i in temp_data) {
                temp_data_list.push(temp_data[i]);
              }
            }
            this.setData({
              data_list: temp_data_list,
              data_total: res.data.data.total,
              data_page_total: res.data.data.page_total,
              data_list_loding_status: 3,
              data_page: this.data.data_page + 1,
              load_status: 1,
              payment_list: res.data.data.payment_list || [],
            });

            // 是否还有数据
            if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total)
            {
              this.setData({ data_bottom_line_status: true });
            } else {
              this.setData({data_bottom_line_status: false});
            }
          } else {
            this.setData({
              data_list_loding_status: 0,
              load_status: 1,
              data_list: [],
              data_bottom_line_status: false,
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
            load_status: 1,
          });
          if (app.is_login_check(res.data, this, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2,
          load_status: 1,
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.setData({
      data_page: 1
    });
    this.get_data_list(1);
  },

  // 滚动加载
  scroll_lower(e) {
    this.get_data_list();
  },

  // 支付
  pay_event(e) {
    this.setData({
      is_show_payment_popup: true,
      temp_pay_value: e.currentTarget.dataset.value,
      temp_pay_index: e.currentTarget.dataset.index,
    });
  },

  // 支付弹窗关闭
  payment_popup_event_close(e) {
    this.setData({ is_show_payment_popup: false });
  },

  // 支付弹窗发起支付
  popup_payment_event(e) {
    var payment_id = e.currentTarget.dataset.value || 0;
    this.setData({payment_id: payment_id});
    this.payment_popup_event_close();
    this.pay_handle(this.data.temp_pay_value, this.data.temp_pay_index);
  },

  // 支付方法
  pay_handle(order_id, index) {
    var self = this;
    // 加载loding
    tt.showLoading({title: "请求中..." });

    tt.request({
      url: app.get_request_url("pay", "toutiao"),
      method: "POST",
      data: {
        id: order_id,
        payment_id: this.data.payment_id,
      },
      dataType: "json",
      success: res => {
        tt.hideLoading();
        if (res.data.code == 0) {
          // 支付方式类型
          switch (res.data.data.is_payment_type) {
            // 正常线上支付
            case 0 :
              tt.pay({
                orderInfo: res.data.data.order_info,
                service: res.data.data.service,
                success(res) {
                  // if (res.code == 0) {
                  //   // 数据设置
                  //   self.order_item_pay_success_handle(index);

                  //   // 跳转支付页面
                  //   wx.navigateTo({
                  //     url: "/pages/paytips/paytips?code=9000&total_price=" +
                  //       self.data.data_list[index]['total_price']
                  //   });
                  // } else {
                  //   app.showToast('支付失败');
                  // }

                  // 由于头条支付无法监听支付状态，这里就不做接口轮询了，直接刷新页面
                  self.setData({
                    data_page: 1
                  });
                  self.get_data_list(1);
                },
                fail(res) {
                  console.log(res, 'pay-fail');
                  app.showToast('调起收银台失败-'+res.data.code);
                }
              });
              break;

            // 线下支付
            case 1 :
              var temp_data_list = self.data.data_list;
              temp_data_list[index]['is_under_line'] = 1;
              self.setData({ data_list: temp_data_list });
              app.alert({ msg: res.data.msg, is_show_cancel: 0});
              break;

            // 钱包支付
            case 2 :
              self.order_item_pay_success_handle(index);
              app.showToast('支付成功', 'success');
              break;

            // 默认
            default :
              app.showToast('支付类型有误');
          }
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        tt.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 支付成功数据设置
  order_item_pay_success_handle(index) {
    // 数据设置
    var temp_data_list = this.data.data_list;
    switch (parseInt(temp_data_list[index]['order_model'])) {
      // 销售模式
      case 0:
        temp_data_list[index]['status'] = 2;
        temp_data_list[index]['status_name'] = '待发货';
        break;

      // 自提模式
      case 2:
        temp_data_list[index]['status'] = 2;
        temp_data_list[index]['status_name'] = '待取货';
        break;

      // 虚拟模式
      case 3:
        temp_data_list[index]['status'] = 3;
        temp_data_list[index]['status_name'] = '待收货';
        break;
    }
    this.setData({ data_list: temp_data_list });
  },

  // 取消
  cancel_event(e) {
    tt.showModal({
      title: "温馨提示",
      content: "取消后不可恢复，确定继续吗?",
      confirmText: "确认",
      cancelText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.currentTarget.dataset.value;
          var index = e.currentTarget.dataset.index;

          // 加载loding
          tt.showLoading({title: "处理中..." });

          tt.request({
            url: app.get_request_url("cancel", "order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              tt.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 5;
                temp_data_list[index]['status_name'] = '已取消';
                this.setData({data_list: temp_data_list});

                app.showToast(res.data.msg, "success");
              } else {
                app.showToast(res.data.msg);
              }
            },
            fail: () => {
              tt.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      }
    });
  },

  // 收货
  collect_event(e) {
    tt.showModal({
      title: "温馨提示",
      content: "请确认已收到货物或已完成，操作后不可恢复，确定继续吗?",
      confirmText: "确认",
      cancelText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.currentTarget.dataset.value;
          var index = e.currentTarget.dataset.index;

          // 加载loding
          tt.showLoading({title: "处理中..." });

          tt.request({
            url: app.get_request_url("collect", "order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              tt.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 4;
                temp_data_list[index]['status_name'] = '已完成';
                this.setData({data_list: temp_data_list});

                app.showToast(res.data.msg, "success");
              } else {
                app.showToast(res.data.msg);
              }
            },
            fail: () => {
              tt.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      }
    });
  },

  // 催催
  rush_event(e) {
    app.showToast("催促成功", "success");
  },

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_status_index: e.currentTarget.dataset.index || 0,
      data_page: 1,
    });
    this.get_data_list(1);
  },

  // 售后订单事件
  orderaftersale_event(e) {
    var oid = e.currentTarget.dataset.oid || 0;
    var did = e.currentTarget.dataset.did || 0;
    if(oid == 0 || did == 0)
    {
      app.showToast("参数有误");
      return false;
    }
    
    // 进入售后页面
    tt.navigateTo({
      url: "/pages/user-orderaftersale-detail/user-orderaftersale-detail?oid=" + oid+"&did="+did
    });
  },

  // 订单评论
  comments_event(e) {
    tt.navigateTo({
      url: "/pages/user-order-comments/user-order-comments?id=" + e.currentTarget.dataset.value
    });
  },
});
