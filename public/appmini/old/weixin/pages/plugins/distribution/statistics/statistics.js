import * as echarts from '../../../../components/ec-canvas/echarts';
const app = getApp();

// 近15日收益走势
let profit_chart_obj = null;
function init_profit_chart(canvas, width, height) {
  profit_chart_obj = echarts.init(canvas, null, {
    width: width,
    height: height
  });
  canvas.setChart(profit_chart_obj);
  return profit_chart_obj;
};

// 近15日推广用户数
let user_chart_obj = null;
function init_user_chart(canvas, width, height) {
  user_chart_obj = echarts.init(canvas, null, {
    width: width,
    height: height
  });
  canvas.setChart(user_chart_obj);
  return user_chart_obj;
};

Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_list_loding_status: 1,
    data_list_loding_msg: '加载中...',
    data_bottom_line_status: false,

    user_total: null,
    user_profit_stay_price: 0.00,
    user_profit_vaild_price: 0.00,
    user_profit_already_price: 0.00,
    user_profit_total_price: 0.00,
    user_data: null,
    profit_data: null,

    // 图表
    profit_chart: {
      onInit: init_profit_chart,
    },
    user_chart: {
      onInit: init_user_chart,
    },
  },
  
  onShow() {
    this.init();
  },

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("index", "statistics", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            user_total: data.user_total || null,
            user_profit_stay_price: data.user_profit_stay_price || 0.00,
            user_profit_vaild_price: data.user_profit_vaild_price || 0.00,
            user_profit_already_price: data.user_profit_already_price || 0.00,
            user_profit_total_price: data.user_profit_total_price || 0.00,
            user_data: data.user_chart || null,
            profit_data: data.profit_chart || null,

            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });

          // 图表
          setTimeout(function()
          {
            // 近15日收益走势
            self.set_profit_chart(data.profit_chart);

            // 近15日推广用户数
            self.set_profit_user(data.user_chart);
          }, 200);
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错',
        });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 近15日推广用户数
  set_profit_user(data) {
    if ((data || null) != null) {
      var option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            label: {
              backgroundColor: '#6a7985'
            }
          }
        },
        grid: {
          top: '10%',
          left: '5%',
          right: '5%',
          bottom: '15%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: data.name_arr
        },
        yAxis: {
          type: 'value'
        },
        series: [{
          data: data.data,
          type: 'bar',
          areaStyle: {}
        }]
      };
      if (typeof (user_chart_obj) == 'object') {
        user_chart_obj.setOption(option);
      }
    }
  },

  // 近15日收益走势图表
  set_profit_chart(data) {
    if ((data || null) != null)
    {
      var option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            label: {
              backgroundColor: '#6a7985'
            }
          }
        },
        grid: {
          top: '10%',
          left: '5%',
          right: '5%',
          bottom: '15%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: data.name_arr
        },
        yAxis: {
          type: 'value'
        },
        series: [{
          data: data.data,
          type: 'line'
        }]
      };
      if (typeof (profit_chart_obj) == 'object') {
        profit_chart_obj.setOption(option);
      }
    }
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});