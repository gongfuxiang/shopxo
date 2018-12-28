Component({
  props: {
    className: "",
    type: "success",
    title: "",
    onTapMain: function onTapMain() {},
    onTapSub: function onTapSub() {}
  },
  methods: {
    tapMain: function tapMain() {
      this.props.onTapMain();
    },
    tapSub: function tapSub() {
      this.props.onTapSub();
    }
  }
});