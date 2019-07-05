// components/limitedtimediscount.js
Component({
  /**
   * 组件的属性列表
   */
  properties: {

  },

  /**
   * 组件的初始数据
   */
  data: {
    hours: 0,
    minutes: 0,
    seconds: 30,
    timer_title: '距离结束',
    is_show_time: true,
    data_list: [
      {
        goods_id: 1,
        goods_title: '2019新款夏装漂亮的睡衣，性感女士专享',
        images_url: 'https://demo.shopxo.net/static/upload/images/goods/2019/01/14/1547454702543219.jpg',
        min_price: 345.23,
        min_original_price: 9863.98,
      },
      {
        goods_id: 2,
        goods_title: 'MARNI Trunk 女士 中号拼色十字纹小牛皮 斜挎风琴包',
        images_url: 'https://demo.shopxo.net/static/upload/images/goods/2019/01/14/1547454145355962.jpg',
        min_price: 256.00,
        min_original_price: 356.00,
      },
      {
        goods_id: 3,
        goods_title: 'Huawei/华为 H60-L01 荣耀6 移动4G版智能手机 安卓',
        images_url: 'https://demo.shopxo.net/static/upload/images/goods/2019/01/14/1547452474332334.jpg',
        min_price: 1999.99,
        min_original_price: 2300.00,
      }
    ],
  },

  ready: function () {
    this.countdown();
  },

  /**
   * 组件的方法列表
   */
  methods: {
    // 倒计时
    countdown() {
      if (this.data.hours > 0 || this.data.minutes > 0 || this.data.seconds > 0) {
        var hours = this.data.hours;
        var minutes = this.data.minutes;
        var seconds = this.data.seconds;

        // 秒
        var self = this;
        var timer = setInterval(function () {
          if (seconds <= 0) {
            if (minutes > 0) {
              minutes--;
              seconds = 59;
            } else if (hours > 0) {
              hours--;
              minutes = 59;
              seconds = 59;
            }
          } else {
            seconds--;
          }

          self.setData({
            hours: (hours < 10) ? 0 + hours : hours,
            minutes: (minutes < 10) ? 0 + minutes : minutes,
            seconds: (seconds < 10) ? 0 + seconds : seconds,
          });

          if (hours <= 0 && minutes <= 0 && seconds <= 0) {
            // 停止时间
            clearInterval(timer);

            // 活动已结束
            self.setData({
              timer_title: '活动已结束',
              is_show_time: false,
            });
          }
        }, 1000);
      }
    }
  }
})
