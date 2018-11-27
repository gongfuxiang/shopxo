export default {
  data: {
    results: [],
    items: [],
    commonProps: {
      max: 10000
    }
  },

  didUnmount: function didUnmount() {
    var _data = this.data,
        items = _data.items,
        results = _data.results;

    results.splice(0, results.length);
    items.splice(0, items.length);
  }
};