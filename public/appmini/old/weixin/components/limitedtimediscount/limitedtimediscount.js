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
