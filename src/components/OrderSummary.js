const OrderSummary = {
  template: `
    <div class="row" v-if="order.length > 0">
    <div class="col col-12 mt-3">
      <div class="alert alert-success">
        <h5>Ihre Bestellung</h5>
        <OrderSummaryList :articles="order"
                          v-slot:default="{article: {id, quantity, price, extras}, index}"
                          @delete="$emit('delete', $event)">
          <OrderSummaryListItem :name="$root.getArticleName(id)" :quantity="quantity"
                                :price="$root.getOrderItemTotal({id, quantity, extras})" :extras="extras"
                                @set_quantity="$emit('set_quantity', {quantity: $event, index})"
                                @update_extras="$emit('update_extras', {index, extras: $event})"
                                @delete="$emit('delete', index)"/>
        </OrderSummaryList>
        <div>
          <h6 class="my-3"><strong>TOTAL:</strong> {{
              $root.getOrderTotal(order).toFixed(2)
            }} &euro;</h6>
        </div>
      </div>
    </div>
    </div>
  `,
  components: { OrderSummaryList, OrderSummaryListItem },

  emits: ['delete', 'set_quantity', 'update_extras'],
  props: {
    order: {
      type: Array,
      required: true
    }
  }
}