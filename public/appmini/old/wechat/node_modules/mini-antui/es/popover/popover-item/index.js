Component({
  props: {
    className: ''
  },
  methods: {
    onItemClick: function onItemClick() {
      if (this.props.onItemClick && typeof this.props.onItemClick === 'function') {
        this.props.onItemClick();
      }
    }
  }
});