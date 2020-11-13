const OrderSummary = {
  template: `
    <div class="row" v-if="order.length > 0">
    <div class="col col-12 mt-3">
      <div class="alert alert-success">
        <h5>Ihre Bestellung</h5>
        <OrderSummaryList :articles="order"
                          v-slot:default="{article: {id, quantity, price}, index}"
                          @delete="$emit('delete', $event)">
          <OrderSummaryListItem :name="$root.getArticleName(id)" :quantity="quantity"
                                :price="$root.getOrderItemTotal({id, quantity})"
                                @set_quantity="$emit('set_quantity', {quantity: $event, index})"
                                @delete="$emit('delete', index)"/>
        </OrderSummaryList>
        <div>
          <h6 class="my-3"><strong>TOTAL:</strong> {{
              $root.getOrderTotal(order).toFixed(2)
            }} &euro;</h6>
        </div>
      </div>
    </div>
    <div class="col col-12 mb-5">
      <a class="btn btn-primary float-right ml-2" onclick="alert('Coming soon!')">Weiter zum Checkout</a>
      <a href="#" class="btn btn-outline-danger float-right" v-on:click="$emit('clear_order')">Bestellvorgang
        abbrechen</a>
    </div>
    </div>
  `,
  components: { OrderSummaryList, OrderSummaryListItem },

  emits: ['delete', 'set_quantity'],
  props: {
    order: {
      type: Array,
      required: true
    }
  }
}