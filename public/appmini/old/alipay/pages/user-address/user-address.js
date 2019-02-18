const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    params: null,
    is_default: 0,
  },

  onLoad(params) {
    this.setData({params: params});
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.user_address});
    this.init();
  },

  // 初始化
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

  // 获取数据列表
  get_data_list() {
    // 加载loding
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 获取数据
    my.httpRequest({
      url: app.get_request_url("index", "useraddress"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          if (res.data.data.length > 0) {
            // 获取当前默认地址
            var is_default = 0;
            for(var i in res.data.data)
            {
              if(res.data.data[i]['is_default'] == 1)
              {
                is_default = res.data.data[i]['id'];
              }
            }

            // 设置数据
            this.setData({
              data_list: res.data.data,
              is_default: is_default,
              data_list_loding_status: 3,
              data_bottom_line_status: true,
            });
          } else {
            this.setData({
              data_list_loding_status: 0
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0
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
          data_list_loding_status: 2
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
    this.get_data_list();
  },

  // 删除地址
  address_delete_event(e) {
    var index = e.currentTarget.dataset.index;
    var value = e.currentTarget.dataset.value || null;
    if(value == null)
    {
      my.showToast({
        type: "fail",
        content: '地址ID有误'
      });
      return false;
    }

    var self = this;
    my.confirm({
      title: "温馨提示",
      content: "删除后不可恢复，确定继续吗?",
      confirmButtonText: "确认",
      cancelButtonText: "不了",
      success: result => {
        if (result.confirm) {
          // 加载loding
          my.showLoading({ content: "处理中..." });

          // 获取数据
          my.httpRequest({
            url: app.get_request_url("delete", "useraddress"),
            method: "POST",
            data: {id: value},
            dataType: "json",
            success: res => {
              my.hideLoading();
              if (res.data.code == 0)
              {
                var temp_data = self.data.data_list;
                temp_data.splice(index, 1);
                self.setData({
                  data_list: temp_data,
                  data_list_loding_status: temp_data.length == 0 ? 0 : 3,
                  data_bottom_line_status: temp_data.length == 0 ? false : true,
                });

                my.showToast({
                  type: "success",
                  content: res.data.msg
                });

                // 当前删除是否存在缓存中，存在则删除
                var cache_address = my.getStorageSync({
                  key: app.data.cache_buy_user_address_select_key
                });
                if ((cache_address.data || null) != null) {
                  if (cache_address.data.id == value) {
                    // 删除地址缓存
                    my.removeStorageSync({ key: app.data.cache_buy_user_address_select_key });
                  }
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
        }
      }
    });
  },

  // 默认地址设置
  address_default_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if(value == null)
    {
      my.showToast({
        type: "fail",
        content: '地址ID有误'
      });
      return false;
    }

    var self = this;
    if(value == self.data.is_default)
    {
      my.showToast({
          type: "success",
          content: '设置成功'
        });
      return false;
    }
    
    // 加载loding
    my.showLoading({ content: "处理中..." });

    // 获取数据
    my.httpRequest({
      url: app.get_request_url("setdefault", "useraddress"),
      method: "POST",
      data: {id: value},
      dataType: "json",
      success: res => {
        my.hideLoading();
        if (res.data.code == 0)
        {
          self.setData({is_default: value});

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
  },

  // 地址内容事件
  address_conent_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var is_back = this.data.params.is_back || 0;
    if(is_back == 1)
    {
      my.setStorage({
        key: app.data.cache_buy_user_address_select_key,
        data: this.data.data_list[index]
      });
      my.navigateBack();
    }
  },
  
});
