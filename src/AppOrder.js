const initAppOrder = ({ payload: { articles, extras } }, Vue) => {
  const LOCAL_STORAGE_ORDER_KEY = 'pizza_plaza_order'
  Vue.createApp({
    components: { ArticleTile, ArticleTileList, OrderSummary },
    data: () => {
      return {
        initialized: false,
        articles: articles,
        extras: extras,
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
          this.fixOrderData(order)
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
        } else {
          Array.prototype.push.call(this.order, {
            id,
            extras: [],
            quantity: 1,
            name: availableArticle.name
          })
        }
      },
      fixOrderData (order) {
        order.forEach(article => {
          let availableArticle = this.articles.find(art => art.ID === article.id)
          if (typeof article.quantity !== 'number') {
            article.quantity = 1
          }
          if (article.quantity > 20) {
            article.quantity = 20
          }
          if (article.quantity < 1) {
            article.quantity = 1
          }
          article.name = availableArticle.name
          //article.price = Math.round(availableArticle.price * article.quantity * 100) / 100;
        })
      },
      removeFromCart (index) {
        console.log(index)
        this.order.splice(index, 1)
      },
      setCartQuantity ({ index, quantity }) {
        this.order[index].quantity = quantity
        this.fixOrderData(this.order)
      },
      clearOrder () {
        if (!confirm('Möchten Sie den Bestellvorgang wirklich abbrechen?')) {
          return
        }
        this.order = []
      },
      getOrderTotal (order) {
        return Math.round(order.reduce((current, next) => {
          return Math.round((current + this.getOrderItemTotal(next)) * 100) / 100
        }, 0) * 100) / 100
      },
      getOrderItemTotal ({ id, quantity }) {
        return Math.round(this.getArticlePrice(id) * quantity * 100) / 100
      },
      getArticlePrice (id) {
        let availableArticle = this.articles.find(art => art.ID === id)
        return Math.round(availableArticle.price * 100) / 100
      }
    },
    created () {
      this.loadExistingOrder()
    }
  }).mount('#app')
}