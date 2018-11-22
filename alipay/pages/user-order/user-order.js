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
  },

  onLoad(params) {
    this.setData({params: params});
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
    }
  },

  // 输入框事件
  input_event(e) {
    this.setData({input_keyword_value: e.detail.value});
  },

  // 搜索事件
  search_event() {
    this.setData({
      data_page: 1
    });
    this.get_data_list(1);
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

    // 获取数据
    my.httpRequest({
      url: app.get_request_url("Index", "Order"),
      method: "POST",
      data: {
        page: this.data.data_page,
        keywords: this.data.input_keyword_value || ""
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
  pay_event(e, order_id, index) {
    var id = e.target.dataset.value;
    var index = e.target.dataset.index;
    this.pay_handle(id, index);
  },

  // 支付方法
  pay_handle(order_id, index) {
    // 加载loding
    my.showLoading({ content: "请求中..." });

    my.httpRequest({
      url: app.get_request_url("Pay", "Order"),
      method: "POST",
      data: {
        id: order_id
      },
      dataType: "json",
      success: res => {
        my.hideLoading();
        if (res.data.code == 0) {
          // 线下支付成功
          if (res.data.data.is_under_line == 1) {
            var temp_data_list = this.data.data_list;
            temp_data_list[index]["status"] = 2;
            temp_data_list[index]['status_name'] = '待发货';
            this.setData({ data_list: temp_data_list });
          } else {
            my.tradePay({
              orderStr: res.data.data.data,
              success: res => {
                // 数据设置
                if (res.resultCode == "9000") {
                  var temp_data_list = this.data.data_list;
                  temp_data_list[index]["status"] = 2;
                  temp_data_list[index]['status_text'] = '待发货';
                  this.setData({ data_list: temp_data_list });
                }

                // 跳转支付页面
                my.navigateTo({
                  url:
                    "/pages/paytips/paytips?code=" +
                    res.resultCode +
                    "&total_price=" +
                    temp_data_list[index]['total_price']
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
            url: app.get_request_url("Cancel", "Order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              my.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 5;
                temp_data_list[index]['status_text'] = '已取消';
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
            url: app.get_request_url("Collect", "Order"),
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: res => {
              my.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 4;
                temp_data_list[index]['status_text'] = '已完成';
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
});
