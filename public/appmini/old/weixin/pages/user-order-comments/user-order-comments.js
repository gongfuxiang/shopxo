const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,

    detail: null,
    rating_list: [],
    rating_msg: ['非常差', '差', '一般', '好', '非常好'],
  },

  onLoad(params) {
    params['id'] = 3;
    this.setData({ params: params });
    this.init();
  },

  onShow() {
    wx.setNavigationBarTitle({ title: app.data.common_pages_title.user_order_comments });
  },

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("detail", "order"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data,
            data_list_loding_status: 3,
            data_list_loding_msg: '',
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 评分事件
  rating_event(e) {
    console.log(e.currentTarget.dataset)
    var index = e.currentTarget.dataset.index;
    var tx = e.currentTarget.dataset.tx;
    var value = e.currentTarget.dataset.value;
    var temp_list = this.data.rating_list;
    temp_list[index] = value;
    this.setData({ rating_list: temp_list});
  },

  // 表单
  formSubmit(e) {
    console.log(e.detail.value)
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});
