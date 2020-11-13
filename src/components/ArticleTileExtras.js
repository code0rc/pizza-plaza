const ArticleTileExtras = {
  template: `
<div class='form-group'>
    <template v-for="(extra, index) in $root.extras" :key="index">
        <slot v-bind="{...extra, index}" />
    </template>
</div>
  `,
  props: {
    extras: {
      type: Array,
      required: true
    }
  }
}