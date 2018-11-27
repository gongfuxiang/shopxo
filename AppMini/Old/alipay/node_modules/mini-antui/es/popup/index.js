Component({
  props: {
    className: '',
    show: false,
    position: 'bottom',
    mask: true,
    animation: true,
    disableScroll: true
  },
  methods: {
    onMaskTap: function onMaskTap() {
      var onClose = this.props.onClose;


      if (onClose) {
        onClose();
      }
    }
  }
});