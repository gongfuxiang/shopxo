Component({
  props: {
    title: '',
    onClick: function onClick() {},
    info: ''
  },
  methods: {
    onCardClick: function onCardClick() {
      var _props = this.props,
          info = _props.info,
          onClick = _props.onClick;

      onClick({ info: info });
    }
  }
});