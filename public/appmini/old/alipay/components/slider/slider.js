const app = getApp();
Component({
  mixins: [],
  data: {
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    circular: true,
  },
  props: {
    data: []
  },
  didMount() {},
  didUpdate() {},
  didUnmount() {},
  methods: {
    // 操作事件
    banner_event(e) {
      app.operation_event(e);
    },
  }
});
