const app = getApp();
Page({
  data: {
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    params: null,
    load_status: 0,
    is_show_payment_popup: false,
    payment_list: [],
    payment_id: 0,
    temp_pay_value: 0,
    temp_pay_index: 0,
    nav_status_list: [{
      name: "全部",
      value: "-1"
    }, {
      name: "待支付",
      value: "0"
    }, {
      name: "已支付",
      value: "1"
    }, {
      name: "已取消",
      value: "2"
    }, {
      name: "已关闭",
      value: "3"
    }],
    nav_status_index: 0
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
      nav_status_index: nav_status_index
    });
    this.init();
  },

  onShow() {},

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
        data_bottom_line_status: false
      });
    }
  },

  // 获取数据
  get_data_list(is_mandatory) {
    // 分页是否还有数据
    if ((is_mandatory || 0) == 0) {
      if (this.data.data_bottom_line_status == true) {
        return false;
      }
    } // 加载loding


    tt.showLoading({
      title: "加载中..."
    });
    this.setData({
      data_list_loding_status: 1
    }); // 参数

    var order_status = (this.data.nav_status_list[this.data.nav_status_index] || null) == null ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value']; // 获取数据

    tt.request({
      url: app.get_request_url("index", "order", "membershiplevelvip"),
      method: "POST",
      data: {
        page: this.data.data_page,
        status: order_status,
        is_more: 1
      },
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        if (res.data.code == 0) {
          if (res.data.data.data.length > 0) {
            if (this.data.data_page <= 1) {
              var temp_data_list = res.data.data.data; // 下订单支付处理

              if (this.data.load_status == 0) {
                if ((this.data.params.is_pay || 0) == 1 && (this.data.params.order_id || 0) != 0) {
                  for (var i in temp_data_list) {
                    if (this.data.params.order_id == temp_data_list[i]['id']) {
                      this.setData({
                        is_show_payment_popup: true,
                        temp_pay_value: temp_data_list[i]['id'],
                        temp_pay_index: i
                      });
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
              payment_list: res.data.data.payment_list || []
            }); // 是否还有数据

            if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total) {
              this.setData({
                data_bottom_line_status: true
              });
            } else {
              this.setData({
                data_bottom_line_status: false
              });
            }
          } else {
            this.setData({
              data_list_loding_status: 0,
              load_status: 1,
              data_list: [],
              data_bottom_line_status: false
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
            load_status: 1
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
          load_status: 1
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
      temp_pay_index: e.currentTarget.dataset.index
    });
  },

  // 支付弹窗关闭
  payment_popup_event_close(e) {
    this.setData({
      is_show_payment_popup: false
    });
  },

  // 支付弹窗发起支付
  popup_payment_event(e) {
    var payment_id = e.currentTarget.dataset.value || 0;
    this.setData({
      payment_id: payment_id
    });
    this.payment_popup_event_close();
    this.pay_handle(this.data.temp_pay_value, this.data.temp_pay_index);
  },

  // 支付方法
  pay_handle(order_id, index) {
    var self = this; // 加载loding

    tt.showLoading({
      title: "请求中..."
    });
    tt.request({
      url: app.get_request_url("pay", "toutiao", "membershiplevelvip"),
      method: "POST",
      data: {
        id: order_id,
        payment_id: this.data.payment_id
      },
      dataType: "json",
      success: res => {
        tt.hideLoading();

        if (res.data.code == 0) {
          tt.pay({
            orderInfo: res.data.data.order_info,
            service: res.data.data.service,
            success(res) {
              // if (res.code == 0) {
              //   // 数据设置
              //   self.order_item_pay_success_handle(index); // 跳转支付页面

              //   tt.navigateTo({
              //     url: "/pages/paytips/paytips?code=9000&total_price=" + self.data.data_list[index]['price']
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
              console.log(res, 'pay-fail')
              app.showToast('调起收银台失败-'+res.data.code);
            }
          });
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
    temp_data_list[index]['status'] = 1;
    temp_data_list[index]['status_name'] = '已支付';
    this.setData({
      data_list: temp_data_list
    });
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
          var index = e.currentTarget.dataset.index; // 加载loding

          tt.showLoading({
            title: "处理中..."
          });
          tt.request({
            url: app.get_request_url("cancel", "order", "membershiplevelvip"),
            method: "POST",
            data: {
              id: id
            },
            dataType: "json",
            success: res => {
              tt.hideLoading();

              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 2;
                temp_data_list[index]['status_name'] = '已取消';
                this.setData({
                  data_list: temp_data_list
                });
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

  // 删除
  delete_event(e) {
    tt.showModal({
      title: "温馨提示",
      content: "删除后不可恢复，确定继续吗?",
      confirmText: "确认",
      cancelText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.currentTarget.dataset.value;
          var index = e.currentTarget.dataset.index; // 加载loding

          tt.showLoading({
            title: "处理中..."
          });
          tt.request({
            url: app.get_request_url("delete", "order", "membershiplevelvip"),
            method: "POST",
            data: {
              id: id
            },
            dataType: "json",
            success: res => {
              tt.hideLoading();

              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list.splice(index, 1);
                this.setData({
                  data_list: temp_data_list
                });

                if (temp_data_list.length == 0) {
                  this.setData({
                    data_list_loding_status: 0,
                    data_bottom_line_status: false
                  });
                }

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

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_status_index: e.currentTarget.dataset.index || 0,
      data_page: 1
    });
    this.get_data_list(1);
  }

});