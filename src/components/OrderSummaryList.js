const OrderSummaryList = {
  template: `
    <ul class="list-group list-group-flush">
    <template v-for="(article, index) in articles">
      <li class="list-group-item">
        <slot v-bind="{article, index}" name="articles"></slot>
      </li>
    </template>
    <slot name="delivery"></slot>
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