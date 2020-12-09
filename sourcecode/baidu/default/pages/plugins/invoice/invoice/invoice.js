const app = getApp();
Page({
  data: {
    data_base: null,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    params: null,
    nav_status_list: [{
      name: "全部",
      value: "-1"
    }, {
      name: "待审核",
      value: "0"
    }, {
      name: "待开票",
      value: "1"
    }, {
      name: "已开票",
      value: "2"
    }, {
      name: "已拒绝",
      value: "3"
    }],
    nav_status_index: 0
  },

  onReady() {},

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
  },

  onShow() {
    this.init();
  },

  init() {
    var user = app.get_user_info(this, 'init');

    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        swan.redirectTo({
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


    swan.showLoading({
      title: "加载中..."
    });
    this.setData({
      data_list_loding_status: 1
    }); // 请求数据

    var data = {
      page: this.data.data_page
    }; // 参数

    var status = (this.data.nav_status_list[this.data.nav_status_index] || null) == null ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value'];

    if (status != -1) {
      data['status'] = status;
    } // 获取数据


    swan.request({
      url: app.get_request_url("index", "user", "invoice"),
      method: "POST",
      data: data,
      dataType: "json",
      success: res => {
        swan.hideLoading();
        swan.stopPullDownRefresh();

        if (res.data.code == 0) {
          if (res.data.data.data.length > 0) {
            if (this.data.data_page <= 1) {
              var temp_data_list = res.data.data.data;
            } else {
              var temp_data_list = this.data.data_list;
              var temp_data = res.data.data.data;

              for (var i in temp_data) {
                temp_data_list.push(temp_data[i]);
              }
            }

            this.setData({
              data_base: res.data.data.base || null,
              data_list: temp_data_list,
              data_total: res.data.data.total,
              data_page_total: res.data.data.page_total,
              data_list_loding_status: 3,
              data_page: this.data.data_page + 1
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
              data_list: [],
              data_bottom_line_status: false
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0
          });

          if (app.is_login_check(res.data, this, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        this.setData({
          data_list_loding_status: 2
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

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_status_index: e.currentTarget.dataset.index || 0,
      data_page: 1
    });
    this.get_data_list(1);
  },

  // 编辑事件
  edit_event(e) {
    swan.navigateTo({
      url: '/pages/plugins/invoice/invoice-saveinfo/invoice-saveinfo?id=' + e.currentTarget.dataset.value
    });
  },

  // 删除
  delete_event(e) {
    swan.showModal({
      title: "温馨提示",
      content: "删除后不可恢复，确定继续吗?",
      confirmText: "确认",
      cancelText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var value = e.currentTarget.dataset.value;
          var index = e.currentTarget.dataset.index; // 加载loding

          swan.showLoading({
            title: "处理中..."
          });
          swan.request({
            url: app.get_request_url("delete", "user", "invoice"),
            method: "POST",
            data: {
              ids: value
            },
            dataType: "json",
            success: res => {
              swan.hideLoading();

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
              swan.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      }
    });
  }

});