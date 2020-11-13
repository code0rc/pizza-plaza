const OrderSummary = {
  template: `
    <div class="row" v-if="order.length > 0">
      <div class="col col-12 mt-3">
        <div class="alert alert-success">
          <h5>Ihre Bestellung</h5>
          <p>
            <OrderSummaryList :articles="order" v-slot:default="{name, quantity, price}">
              <OrderSummaryListItem :name="name" :quantity="quantity" :price="price"/>
            </OrderSummaryList>
          </p>
          <h6><strong>TOTAL:</strong> {{
              order.reduce((current, next) => { return current + next.price }, 0).toFixed(2)
            }} &euro;</h6>
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
  props: {
    order: {
      type: Array,
      required: true
    }
  }
}