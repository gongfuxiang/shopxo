const app = getApp();
Component({
  data: {
    navigation: [],
  },
  properties: {
  },
  methods: {
    navigation_event(e) {
      app.operation_event(e);
    }
  }
});