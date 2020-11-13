const initViewOrder = (articles, Vue) => {
  const LOCAL_STORAGE_ORDER_KEY = 'pizza_plaza_order'
  Vue.createApp({
    components: { ArticleTile, ArticleTileList, OrderSummary },
    data: () => {
      return {
        initialized: false,
        articles: articles,
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
          Array.prototype.push.call(this.order, {
            id,
            extras: [],
            quantity: 1,
            name: availableArticle.name,
            price: availableArticle.price
          })
        }
      },
      clearOrder () {
        if (!confirm('Möchten Sie den Bestellvorgang wirklich abbrechen?')) {
          return
        }
        this.order = []
      }
    },
    created () {
      this.loadExistingOrder()
    }
  }).mount('#app')
}