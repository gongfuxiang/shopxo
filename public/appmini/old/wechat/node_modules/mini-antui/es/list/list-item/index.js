Component({
  props: {
    className: '',
    align: false,
    disabled: false,
    multipleLine: false,
    wrap: false
  },
  didMount: function didMount() {
    this.dataset = {};
    for (var key in this.props) {
      if (/data-/gi.test(key)) {
        this.dataset[key.replace(/data-/gi, '')] = this.props[key];
      }
    }
  },

  methods: {
    onItemTap: function onItemTap(ev) {
      var _props = this.props,
          onClick = _props.onClick,
          disabled = _props.disabled;

      if (onClick && !disabled) {
        onClick({
          index: ev.target.dataset.index,
          target: { dataset: this.dataset }
        });
      }
    }
  }
});