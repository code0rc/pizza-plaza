const OrderSummaryList = {
  template: `
    <ul class="list-group list-group-flush" v-for="(article, index) in articles">
    <li class="list-group-item">
      <slot v-bind="{article, index}"></slot>
    </li>
    </ul>
  `,
  emits: ['delete'],
  props: {
    articles: {
      type: Array,
      required: true
    }
  }
}