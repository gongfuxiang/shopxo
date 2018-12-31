const app = getApp();
Component({
  data: {},
  properties: {
    propData: Array
  },
  methods: {
    nav_event(e) {
      app.operation_event(e);
    },
  },
});
