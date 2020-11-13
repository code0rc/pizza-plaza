const OrderSummaryList = {
  template: `
    <span v-for="article in articles" class="d-block">
        <slot v-bind="article"></slot>
    </span>
  `,
  props: {
    articles: {
      type: Array,
      required: true
    }
  }
}