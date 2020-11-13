const ArticleTileList = {
  template: `
    <div class="row">
      <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" v-for="article in articles" :key="article.ID">
        <slot v-bind="article"></slot>
      </div>
    </div>
  `,
  props: {
    articles: {
      type: Array,
      required: true
    }
  }
}