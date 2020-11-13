const OrderSummaryListItem = {
  template: `<span>{{ quantity }} x Pizza {{ name }} ({{ price.toFixed(2) }} &euro;)</span>`,
  props: {
    name: {
      type: String,
      required: true
    },
    price: {
      type: Number,
      required: true
    },
    quantity: {
      type: Number,
      required: true
    },
  }
}