const app = getApp();
Page({
  data: {
    params: {},
    default_round_success_icon: app.data.default_round_success_icon,
    default_round_error_icon: app.data.default_round_error_icon,
  },

  /**
   * 页面加载初始化
   */
  onLoad(options)
  {
    var msg = null;
    switch(options.code)
    {
      // 支付成功
      case '9000' :
        msg = '支付成功';
        break;

      // 正在处理中
      case '8000' :
        msg = '正在处理中';
        break;

      // 支付失败
      case '4000' :
        msg = '支付失败';
        break;

      // 用户中途取消
      case '6001' :
        msg = '已取消支付';
        break;

      // 网络连接出错
      case '6002' :
        msg = '网络连接出错';
        break;

      // 用户点击忘记密码导致快捷界面退出(only iOS)
      case '99' :
        msg = '支付异常错误';
        break;

      // 默认错误
      default :
        msg = '其它异常错误';
    }
    options['msg'] = msg;

    // 支付成功返回的信息
    var pay = ((options.result || null) == null || options.result == 'undefined') ? {} : JSON.parse(options.result);
    options['pay'] = pay.alipay_trade_app_pay_response;

    // 设置信息
    this.setData({params: options});
  },

  // 返回
  back_event(e) {
    my.navigateBack();
  }
});
