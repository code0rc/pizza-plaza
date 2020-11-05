<?php
require_once('./../components/Article.php');
$articles = Article::fetchAll();
$articlesJson = json_encode($articles);
?>
<div class="row">
  <div class="col-12">
    <h1>Wählen Sie Ihre Pizzen aus!</h1>
  </div>
</div>
<div id="app" v-cloak>
  <div class="row" v-if="order.length > 0">
    <div class="col col-12 mt-3">
      <div class="alert alert-success">
        <h5>Ihre Bestellung</h5>
        <p>
          <span v-for="article in order" class="d-block">{{ article.quantity }} x Pizza {{ article.name }} ({{ article.price.toFixed(2) }} &euro;)</span>
        </p>
        <h6><strong>TOTAL:</strong> {{ order.reduce((current, next) => { return current + next.price }, 0).toFixed(2) }} &euro;</h6>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" v-for="article in articles" :key="article.ID">
      <div class="card my-3 w-100">
        <div class="card-body">
          <h5 class="card-title mb-3">
            Pizza {{ article.name }}
            <span class="badge badge-pill badge-success float-right">{{ article.price.toFixed(2) }} €</span>
          </h5>
          <h6 class="card-subtitle mb-2 text-muted" style="height: 3.2rem;" v-if="!!article.description">
            {{ article.description }}
          </h6>
        </div>
        <div class="card-body">
          <div class="float-right">
            <a href="#" class="btn btn-sm btn-primary" v-on:click="addToCart(article.ID)">
              <strong>+</strong>
            </a>
          </div>
        </div>
        <div class="card-footer"><h6 class="card-subtitle my-2">Zutaten:</h6>
          <p class="mb-2" style="height: 3.2em;">{{ article.extras.join(', ') }}</p></div>
      </div>
    </div>
  </div>
</div>
<script src="https://unpkg.com/vue@next"></script>
<script>
  const LOCAL_STORAGE_ORDER_KEY = 'pizza_plaza_order'
  Vue.createApp({
    data: () => {
      return {
        initialized: false,
        articles: <?php echo $articlesJson ?>,
        order: []
      }
    },
    watch: {
      order: {
        handler () {
          if (!this.initialized) return
          this.commitOrderToLocalStorage()
        },
        deep: true
      }
    },
    methods: {
      loadExistingOrder () {
        const orderJson = window.localStorage.getItem(LOCAL_STORAGE_ORDER_KEY)
        if (orderJson != null) {
          let possibleArticles = this.articles.map(art => art.ID)
          let order = JSON.parse(orderJson).filter(art => possibleArticles.indexOf(art.id) > -1)
          order.forEach(article => {
            let availableArticle = this.articles.find(art => art.ID === article.id)
            article.name = availableArticle.name
            article.price = availableArticle.price * article.quantity
          })
          this.order = order
          this.commitOrderToLocalStorage()
        }
        this.initialized = true
      },
      commitOrderToLocalStorage () {
        window.localStorage.setItem(LOCAL_STORAGE_ORDER_KEY, JSON.stringify(this.order))
        window.dispatchEvent(new Event('vue.order.updated'))
      },
      addToCart (id) {
        let availableArticle = this.articles.find(art => art.ID === id)
        if (!availableArticle) {
          return
        }

        let article = Array.prototype.find.call(this.order, item => item.id === id)
        if (!!article) {
          article.quantity++
          article.price += availableArticle.price
        } else {
          Array.prototype.push.call(this.order, { id, extras: [], quantity: 1, name: availableArticle.name, price: availableArticle.price })
        }
      }
    },
    created () {
      this.loadExistingOrder()
    }
  }).mount('#app')
</script>