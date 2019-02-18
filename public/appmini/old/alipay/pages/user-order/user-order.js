const app = getApp();
Page({
  data: {
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
    my.setNavigationBar({title: app.data.common_pages_title.user_order});
  },

  init() {
    var user = app.GetUserInfo(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.redirectTo({
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
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 参数
    var order_status = ((this.data.nav_status_list[this.data.nav_status_index] || null) == null) ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value'];

    // 获取数据
    my.httpRequest({
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
        my.hideLoading();
        my.stopPullDownRefresh();
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

          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.hideLoading();
        my.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2,
          load_status: 1,
        });
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
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
      temp_pay_value: e.target.dataset.value,
      temp_pay_index: e.target.dataset.index,
    });
  },

  // 支付弹窗关闭
  payment_popup_event_close(e) {
    this.setData({ is_show_payment_popup: false });
  },

  // 支付弹窗发起支付
  popup_payment_event(e) {
    var payment_id = e.target.dataset.value || 0;
    this.setData({payment_id: payment_id});
    this.payment_popup_event_close();
    this.pay_handle(this.data.temp_pay_value, this.data.temp_pay_index);
  },

  // 支付方法
  pay_handle(order_id, index) {
    // 加载loding
    my.showLoading({ content: "请求中..." });

    my.httpRequest({
      url: app.get_request_url("pay", "order"),
      method: "POST",
      data: {
        id: order_id,
        payment_id: this.data.payment_id,
      },
      dataType: "json",
      success: res => {
        my.hideLoading();
        if (res.data.code == 0) {
          // 线下支付成功
          if (res.data.data.is_under_line == 1) {
            var temp_data_list = this.data.data_list;
            temp_data_list[index]['status'] = 2;
            temp_data_list[index]['status_name'] = '待发货';
            this.setData({ data_list: temp_data_list });

            my.showToast({
              type: "success",
              content: '支付成功'
            });
          } else {
            my.tradePay({
              tradeNO: res.data.data.data,
              success: res => {
                // 数据设置
                if (res.resultCode == 9000) {
                  var temp_data_list = this.data.data_list;
                  temp_data_list[index]['status'] = 2;
                  temp_data_list[index]['status_name'] = '待发货';
                  this.setData({ data_list: temp_data_list });
                }

                // 跳转支付页面
                my.navigateTo({
                  url:
                    "/pages/paytips/paytips?code=" +
                    res.resultCode +
                    "&total_price=" +
                    this.data.data_list[index]['total_price']
                });
              },
              fail: res => {
                my.showToast({
                  type: "fail",
                  content: "唤起支付模块失败"
                });
              }
            });
          }
        } else {
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.hideLoading();
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

  // 取消
  cancel_event(e) {
    my.confirm({
      title: "温馨提示",
      content: "取消后不可恢复，确定继续吗?",
      confirmButtonText: "确认",
      cancelButtonText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.target.dataset.value;
          var index = e.target.dataset.index;

          // 加载loding
          my.showLoading({ content: "处理中..." });

          my.httpRequest({
            url: app.get_request_url("cancel", "order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              my.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 5;
                temp_data_list[index]['status_name'] = '已取消';
                this.setData({data_list: temp_data_list});

                my.showToast({
                  type: "success",
                  content: res.data.msg
                });
              } else {
                my.showToast({
                  type: "fail",
                  content: res.data.msg
                });
              }
            },
            fail: () => {
              my.hideLoading();
              my.showToast({
                type: "fail",
                content: "服务器请求出错"
              });
            }
          });
        }
      }
    });
  },

  // 收货
  collect_event(e) {
    my.confirm({
      title: "温馨提示",
      content: "请确认已收到货物或已完成，操作后不可恢复，确定继续吗?",
      confirmButtonText: "确认",
      cancelButtonText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.target.dataset.value;
          var index = e.target.dataset.index;

          // 加载loding
          my.showLoading({ content: "处理中..." });

          my.httpRequest({
            url: app.get_request_url("collect", "order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              my.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 4;
                temp_data_list[index]['status_name'] = '已完成';
                this.setData({data_list: temp_data_list});

                my.showToast({
                  type: "success",
                  content: res.data.msg
                });
              } else {
                my.showToast({
                  type: "fail",
                  content: res.data.msg
                });
              }
            },
            fail: () => {
              my.hideLoading();
              my.showToast({
                type: "fail",
                content: "服务器请求出错"
              });
            }
          });
        }
      }
    });
  },

  // 催催
  rush_event(e) {
    my.showToast({
      type: "success",
      content: "催促成功"
    });
  },

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_status_index: e.target.dataset.index || 0,
      data_page: 1,
    });
    this.get_data_list(1);
  },
});
