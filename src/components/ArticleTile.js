const ArticleTile = {
  template: `
    <div class="card my-3 w-100">
    <div class="card-body">
      <h5 class="card-title mb-3">
        Pizza {{ name }}
        <span class="badge badge-pill badge-success float-right">{{ price.toFixed(2) }} €</span>
      </h5>
      <h6 class="card-subtitle mb-2 text-muted" style="height: 3.2rem;" v-if="!!description">
        {{ description }}
      </h6>
    </div>
    <div class="card-body">
      <div class="float-right">
        <a class="btn btn-sm btn-primary" v-on:click="$emit('add_to_cart', id)">
          <strong>+</strong>
        </a>
      </div>
    </div>
    <div class="card-footer"><h6 class="card-subtitle my-2">Zutaten:</h6>
      <p class="mb-2" style="height: 3.2em;">{{ extras.join(', ') }}</p></div>
    </div>
  `,
  props: {
    id: {
      type: Number,
      required: true
    },
    price: {
      type: Number,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    extras: {
      type: Array,
      required: true
    },
    description: {
      type: String
    },
    discountPrice: {
      type: Number
    }
  }
}