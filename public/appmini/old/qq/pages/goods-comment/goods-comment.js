const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    goods_score: null,
    params: null,
    progress_class: ['progress-bar-danger', 'progress-bar-warning', 'progress-bar-secondary', '', 'progress-bar-success'],
  },

  onLoad(params) {
    //params['goods_id']=9;
    this.setData({ params: params });
    this.init();
  },

  onShow() {
    qq.setNavigationBarTitle({ title: app.data.common_pages_title.goods_comment });
  },

  // 初始化
  init() {
    // 获取数据
    this.goods_score();
    this.get_data_list();
  },

  // 获取商品评分
  goods_score() {
    qq.request({
      url: app.get_request_url("goodsscore", "goods"),
      method: "POST",
      data: { goods_id: this.data.params.goods_id },
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        if (res.data.code == 0) {
          this.setData({
            goods_score: res.data.data || null,
          });
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        app.showToast("服务器请求出错");
      }
    });
  },

  // 获取数据列表
  get_data_list(is_mandatory) {
    // 参数校验
    if ((this.data.params.goods_id || null) == null) {
      qq.stopPullDownRefresh();
      this.setData({
        data_bottom_line_status: false,
        data_list_loding_status: 2,
      });
    } else {
      var self = this;

      // 分页是否还有数据
      if ((is_mandatory || 0) == 0) {
        if (this.data.data_bottom_line_status == true) {
          return false;
        }
      }

      // 加载loding
      qq.showLoading({ title: "加载中..." });
      this.setData({
        data_list_loding_status: 1
      });

      qq.request({
        url: app.get_request_url("comments", "goods"),
        method: "POST",
        data: { goods_id: this.data.params.goods_id, page: this.data.data_page },
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          qq.hideLoading();
          qq.stopPullDownRefresh();
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
                data_list: temp_data_list,
                data_total: res.data.data.total,
                data_page_total: res.data.data.page_total,
                data_list_loding_status: 3,
                data_page: this.data.data_page + 1
              });

              // 是否还有数据
              if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total) {
                this.setData({ data_bottom_line_status: true });
              } else {
                this.setData({ data_bottom_line_status: false });
              }
            } else {
              this.setData({
                data_list_loding_status: 0,
              });
              if (this.data.data_page <= 1) {
                this.setData({
                  data_list: [],
                  data_bottom_line_status: false,
                });
              }
            }
          } else {
            this.setData({
              data_list_loding_status: 0
            });

            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          qq.hideLoading();
          qq.stopPullDownRefresh();

          this.setData({
            data_list_loding_status: 2
          });
          app.showToast("服务器请求出错");
        }
      });
    }
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

  // 图片预览
  images_show_event(e) {
    var index = e.currentTarget.dataset.index;
    var ix = e.currentTarget.dataset.ix;
    qq.previewImage({
      current: this.data.data_list[index]['images'][ix],
      urls: this.data.data_list[index]['images'],
    });
  },
});
