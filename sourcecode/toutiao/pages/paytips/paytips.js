const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
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

      // 支付结果未知（有可能已经支付成功），请查询商户订单列表中订单的支付状态
      case '6004':
        msg = '支付结果未知';
        break;

      // 用户点击忘记密码导致快捷界面退出(only iOS)
      case '99' :
        msg = '用户取消支付';
        break;

      // 默认错误
      default :
        msg = '其它异常错误';
    }
    options['msg'] = msg;
    
    // 设置信息
    this.setData({params: options});
  },

  // 返回
  back_event(e) {
    tt.navigateBack();
  }
});
