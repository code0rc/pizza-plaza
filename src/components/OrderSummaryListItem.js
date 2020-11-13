const OrderSummaryListItem = {
  template: `
    <div class="d-flex align-items-start">
    <div>
      <div class="form">
        <input class="form-control form-control-sm d-inline-block"
               type="number" maxlength="2" minlength="1" min="1" max="20" :value="quantity"
               style="width: 4em" autocomplete="off" v-on:input="updateQuantity($event.target.value)"/>
        <span>&nbsp;&nbsp;Pizza {{ name }} ({{ price.toFixed(2) }} &euro;)</span>
      </div>
      <div class="pt-2" style="padding-left: 4.1rem;" v-if="mappedExtras.length > 0">
        <h6>Extras</h6>
        <div v-for="extra in mappedExtras" :key="extra.ID">
          [<a class="btn btn-sm btn-link text-danger" :title="extra.name + ' Entfernen'" @click="removeExtra(extra.ID)">&#x2715;</a>]&nbsp;
          {{ extra.name }} <span class="text-muted">({{ extraTotalPrice(extra.price).toFixed(2) }} &euro;)</span>
        </div>
      </div>
    </div>
    <a class="btn btn-sm btn-outline-danger ml-auto" :title="'Pizza ' + name + ' Entfernen'" @click="deleteOrderItem()">&#x2715;</a>
    </div>
  `,
  emits: ['delete', 'set_quantity', 'update_extras'],
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
    extras: {
      type: Array,
      required: false,
      default: []
    }
  },
  computed: {
    mappedExtras () {
      return this.$root.mapExtras(this.extras)
    }
  },
  methods: {
    extraTotalPrice (price) {
      return Math.round(price * this.quantity * 100) / 100
    },
    updateQuantity (newQuantity) {
      if (!newQuantity) {
        alert('Bitte geben Sie eine gültige Anzahl ein.')
        return this.$emit('set_quantity', this.quantity)
      }
      if (newQuantity < 1) {
        alert('Bitte wählen Sie mindestens eine Pizza.')
        return this.$emit('set_quantity', 1)
      }
      if (newQuantity > 20) {
        alert('Sie können maximal 20 Pizzen jeder Art bestellen.')
        return this.$emit('set_quantity', 20)
      }

      return this.$emit('set_quantity', Number(newQuantity))
    },
    deleteOrderItem () {
      if (confirm('Sind Sie sicher?')) {
        this.$emit('delete')
      }
    },
    removeExtra (extraId) {
      if(confirm('Sind Sie sicher?')) {
        const newExtras = this.extras.filter(id => id !== extraId)
        return this.$emit('update_extras', newExtras)
      }
    }
  }
}