const app = getApp();
Component({
  data: {
    indicator_dots: false,
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    autoplay: true,
    circular: true,
  },
  properties: {
    propData: Array
  },
  methods: {
    banner_event(e) {
      app.operation_event(e);
    },
  },
});