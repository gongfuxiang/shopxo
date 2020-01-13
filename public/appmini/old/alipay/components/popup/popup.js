// components/popup.js
Component({
  /**
   * 组件的属性列表
   */
  props: {
    propClassname: String,
    propShow: Boolean,
    propPosition: String,
    propMask: Boolean,
    propAnimation: Boolean,
    propDisablescroll: Boolean
  },

  /**
   * 组件的初始数据
   */
  data: {

  },

  /**
   * 组件的方法列表
   */
  methods: {
    onMaskTap: function onMaskTap() {
      var onClose = this.props.onClose;
      if (onClose) {
        onClose();
      }
    }
  }
})
