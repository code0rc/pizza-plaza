const ArticleTileExtrasItem = {
  template: `
    <div class='form-check'>
    <input type="checkbox" class="form-check-input" :id="'ArticleTileExtrasItem_Article' + articleId + '_' + name"
           @change="$emit('toggle_extra', $event.target.checked)"
           :checked="checked">
    <label :for="'ArticleTileExtrasItem_Article' + articleId + '_' + name" class="form-check-label">{{ name }} <span
        class="text-muted">({{ price.toFixed(2) }} &euro;)</span></label>
    </div>
  `,
  props: {
    articleId: {},
    name: {
      type: String,
      required: true
    },
    price: {
      type: Number,
      required: true
    },
    checked: {
      type: Boolean,
      default: false
    }
  }
}