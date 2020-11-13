const OrderSummaryListItem = {
  template: `
    <div class="form">
    <input class="form-control form-control-sm d-inline-block"
           type="number" maxlength="2" minlength="1" min="1" max="20" :value="quantity"
           style="width: 4em" autocomplete="off" v-on:input="updateQuantity($event.target.value)"/>
    <span>&nbsp;&nbsp;Pizza {{ name }} ({{ price.toFixed(2) }} &euro;)</span>
    <a class="btn btn-sm btn-outline-danger float-right" title="Entfernen" @click="deleteOrderItem()">&#x2715;</a>
    </div>
  `,
  emits: ['delete', 'set_quantity'],
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
  },
  setup (props, context) {
    const updateQuantity = (newQuantity) => {
      if (!newQuantity) {
        alert('Bitte geben Sie eine gültige Anzahl ein.')
        return context.emit('set_quantity', props.quantity)
      }
      if (newQuantity < 1) {
        alert('Bitte wählen Sie mindestens eine Pizza.')
        return context.emit('set_quantity', 1)
      }
      if (newQuantity > 20) {
        alert('Sie können maximal 20 Pizzen jeder Art bestellen.')
        return context.emit('set_quantity', 20)
      }

      return context.emit('set_quantity', Number(newQuantity))
    }
    const deleteOrderItem = () => {
      if (confirm('Sind Sie sicher?')) {
        context.emit('delete')
      }
    }

    return { updateQuantity, deleteOrderItem }
  }
}