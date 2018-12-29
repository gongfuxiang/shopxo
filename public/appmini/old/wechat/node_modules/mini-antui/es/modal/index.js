Component({
  props: {
    className: '',
    topImageSize: 'md',
    showClose: true,
    closeType: '0'
  },
  methods: {
    onModalClick: function onModalClick() {
      var onModalClick = this.props.onModalClick;

      if (onModalClick) {
        onModalClick();
      }
    },
    onModalClose: function onModalClose() {
      var onModalClose = this.props.onModalClose;

      if (onModalClose) {
        onModalClose();
      }
    }
  }
});