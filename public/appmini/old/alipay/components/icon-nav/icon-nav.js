const app = getApp();
Component({
  mixins: [],
  data: {},
  props: {
    data: []
  },
  didMount() {},
  didUpdate(){},
  didUnmount(){},
  methods: {
    // 操作事件
    nav_event(e) {
      app.operation_event(e);
    },
  }
});
