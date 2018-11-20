const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    data_list: [],
    value: 8,
  },

  onShow() {
    my.setNavigationBar({ title: app.data.common_pages_title.cart });
    this.init();
  },

  init() {
    this.setData({
      data_list_loding_status: 1
    });

    my.httpRequest({
      url: app.get_request_url("Index", "Cart"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {  
          this.setData({
            data_list: res.data.data,
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错',
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
    this.init();
  },

  // 数量输入事件
  goods_buy_number_blur(e) {
    var index = e.currentTarget.dataset.index || 0;
    var buy_number = parseInt(e.detail.value) || 1;
    this.goods_buy_number_func(index, buy_number);
  },

  // 数量操作事件
  goods_buy_number_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var type = parseInt(e.currentTarget.dataset.type) || 0;
    var temp_buy_number = parseInt(this.data.data_list[index]['stock']);
    if (type == 0) {
      var buy_number = temp_buy_number - 1;
    } else {
      var buy_number = temp_buy_number + 1;
    }
    this.goods_buy_number_func(index, buy_number);
  },

  // 数量处理方法
  goods_buy_number_func(index, buy_number) {
    var temp_data_list = this.data.data_list;
    var buy_min_number = parseInt(temp_data_list['buy_min_number']) || 1;
    var buy_max_number = parseInt(temp_data_list['buy_max_number']) || 0;
    var inventory = parseInt(temp_data_list['inventory']);
    var inventory_unit = temp_data_list['inventory_unit'];
    if (buy_number < buy_min_number) {
      buy_number = buy_min_number;
      if (buy_min_number > 1) {
        my.showToast({ content: '起购' + buy_min_number + inventory_unit });
        return false;
      }
    }
    if (buy_max_number > 0 && buy_number > buy_max_number) {
      buy_number = buy_max_number;
      my.showToast({ content: '限购' + buy_max_number + inventory_unit });
      return false;
    }
    if (buy_number > inventory) {
      buy_number = inventory;
      my.showToast({ content: '库存数量' + inventory + inventory_unit });
      return false;
    }

    if (temp_data_list[index]['stock'] == 1 && buy_number == 1)
    {
      return false;
    }

    // 更新数据库
    my.httpRequest({
      url: app.get_request_url("Stock", "Cart"),
      method: "POST",
      data: { "id": temp_data_list[index]['id'], "goods_id": temp_data_list[index]['goods_id'], "stock": buy_number},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          temp_data_list[index]['stock'] = buy_number;
          this.setData({ data_list: temp_data_list });
        } else {
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

});
