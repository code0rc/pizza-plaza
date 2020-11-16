const ArticleTile = {
  template: `
    <div class="card my-3 w-100">
    <div class="card-body pb-0" :class="{'alert-success': !!fullPrice}">
      <h5 class="card-title mb-3">
        Pizza {{ name }}
        <span class="badge badge-pill badge-success float-right">{{ price.toFixed(2) }} €</span>
        <span v-if="fullPrice" class="badge badge-pill badge-danger float-right mr-2"><del>{{ fullPrice.toFixed(2) }} €</del></span>
      </h5>
      <h6 class="card-subtitle mb-2 text-muted" style="height: 3.2rem;" v-if="!!description">
        {{ description }}
      </h6>
    </div>
    <div class="card-body py-3" v-if="extras.length > 0">
      <h6>Extras</h6>
      <article-tile-extras :extras="extras" v-slot:default="{ID: extraId, name, price, index}">
        <article-tile-extras-item :name="name" :checked="orderExtras.indexOf(extraId) > -1"
                                  :article-id="id"
                                  @toggle_extra="toggleExtra(extraId, $event)"
                                  :price="price"/>
      </article-tile-extras>
    </div>
    <div class="card-body pt-0">
      <div class="float-right">
        <a class="btn btn-sm btn-primary" v-on:click="addToCart()" href="#">
          <strong>+</strong>
        </a>
      </div>
    </div>
    <div class="card-footer"><h6 class="card-subtitle my-2">Zutaten:</h6>
      <p class="mb-2" style="height: 3.2em;">{{ extras.join(', ') }}</p></div>
    </div>
  `,
  components: { ArticleTileExtras, ArticleTileExtrasItem },
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
    fullPrice: {
      type: Number,
      required: false,
      default: null
    }
  },
  data () {
    return {
      orderExtras: []
    }
  },
  computed: {
    orderExtrasPlain () {
      return [].concat(this.orderExtras)
    }
  },
  methods: {
    addToCart () {
      this.$emit('add_to_cart', { id: this.id, extras: this.orderExtrasPlain })
      this.orderExtras = []
    },
    toggleExtra (extraId, checked) {
      if (checked) {
        if (this.orderExtras.indexOf(extraId) < 0) {
          this.orderExtras.push(extraId)
        }
      } else {
        const index = this.orderExtras.indexOf(extraId)
        if (index > -1) {
          this.orderExtras.splice(index, 1)
        }
      }
    }
  }
}