const OrderSummary = {
  template: `
    <div class="row" v-if="order.length > 0">
    <div class="col col-12 mt-3">
      <div class="alert alert-success">
        <h5>Ihre Bestellung</h5>
        <OrderSummaryList :articles="order" @delete="$emit('delete', $event)">
          <template v-slot:articles="{article: {id, quantity, price, extras}, index}">
            <OrderSummaryListItem :name="$root.getArticleName(id)" :quantity="quantity"
                                  :price="$root.getOrderItemTotal({id, quantity, extras})" :extras="extras"
                                  @set_quantity="$emit('set_quantity', {quantity: $event, index})"
                                  @update_extras="$emit('update_extras', {index, extras: $event})"
                                  @delete="$emit('delete', index)"/>
          </template>
          <template v-slot:delivery v-if="delivery">
            <li class="list-group-item">
              <div class="d-flex align-items-start">
                <div>
                  <div class="form">
                  <span>Lieferservice <span class="text-muted">({{ $root.getDeliveryPrice().toFixed(2) }}
                    &euro;)</span></span>
                  </div>
                </div>
<!--                <a href="#" class="btn btn-sm btn-outline-danger ml-auto" :title="'Lieferservice Entfernen'"-->
<!--                   @click="$root.delivery = false">&#x2715;</a>-->
              </div>
            </li>
          </template>

        </OrderSummaryList>
        <div>
          <h6 class="my-3"><strong>TOTAL:</strong> {{
              ($root.getOrderTotal(order) + $root.getDeliveryPrice()).toFixed(2)
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
    },
    delivery: {
      type: Boolean,
      required: true
    }
  }
}