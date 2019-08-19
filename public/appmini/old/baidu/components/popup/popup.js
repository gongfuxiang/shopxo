// components/popup.js
Component({
  /**
   * 组件的属性列表
   */
  properties: {
    propClassname: String,
    propMask: Boolean,
    propAnimation: Boolean,
    propDisablescroll: Boolean
  },

  /**
   * 组件的初始数据
   */
  data: {
    status: false,
    position: 'bottom',
  },

  /**
   * 组件的方法列表
   */
  methods: {
    onMaskTap: function onMaskTap() {
      this.triggerEvent('onclose', {}, {});
    }
  }
});