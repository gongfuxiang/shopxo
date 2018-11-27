Component({
  data: {
    bottomIndex: 0
  },
  props: {
    columnNum: 3,
    circular: false,
    list: [],
    onGridItemClick: function onGridItemClick() {},
    hasLine: true
  },
  didMount: function didMount() {
    var _props = this.props,
        list = _props.list,
        columnNum = _props.columnNum;

    var rows = list.length / columnNum;
    this.setData({
      bottomIndex: Math.floor(rows) === rows ? (rows - 1) * columnNum : Math.floor(rows) * columnNum
    });
  },

  methods: {
    onGridItemClick: function onGridItemClick(e) {
      this.props.onGridItemClick({
        detail: {
          index: e.target.dataset.index
        }
      });
    }
  }
});