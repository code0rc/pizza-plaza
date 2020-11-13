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
      addToCart ({ id, extras }) {
        console.log({ extras })
        let availableArticle = this.articles.find(art => art.ID === id)
        if (!availableArticle) {
          return
        }

        const filterSameArticle = (item) => {
          return item.id === id && this.sameExtras(item.extras, extras)
        }
        let article = Array.prototype.find.call(this.order, filterSameArticle)
        if (!!article) {
          if(article.quantity < 20) {
            article.quantity++
          }
        } else {
          Array.prototype.push.call(this.order, {
            id,
            extras: extras,
            quantity: 1
          })
        }
      },
      fixOrderData (order) {
        order.forEach(article => {
          if (typeof article.quantity !== 'number') {
            article.quantity = 1
          }
          if (article.quantity > 20) {
            article.quantity = 20
          }
          if (article.quantity < 1) {
            article.quantity = 1
          }
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
      getArticleName (id) {
        return this.articles.find(art => art.ID === id).name
      },
      getOrderTotal (order) {
        return Math.round(order.reduce((current, next) => {
          return Math.round((current + this.getOrderItemTotal(next)) * 100) / 100
        }, 0) * 100) / 100
      },
      getOrderItemTotal ({ id, quantity, extras }) {
        const mappedExtras = this.mapExtras(extras)
        const extraTotalPrice = mappedExtras.reduce((current, next) => {
          return current + (next.price * quantity)
        }, 0)
        const total = (this.getArticlePrice(id) * quantity) + extraTotalPrice
        return Math.round(total * 100) / 100
      },
      getArticlePrice (id) {
        let availableArticle = this.articles.find(art => art.ID === id)
        return Math.round(availableArticle.price * 100) / 100
      },
      getExtrasCompoundKey (extras) {
        return extras.sort().join(',')
      },
      indexOfIdenticalOrderItem (order, orderItem) {
        let index = -1
        order.forEach((item, idx) => {
          if (item.id === orderItem.id && this.sameExtras(item.extras, orderItem.extras)) {
            index = idx
          }
        })
        return index
      },
      sameExtras (extras1, extras2) {
        return this.getExtrasCompoundKey(extras1) === this.getExtrasCompoundKey(extras2)
      },
      mapExtras (extraIds) {
        return extraIds.map((id) => {
          return this.extras.find((extra) => extra.ID === id)
        }).filter((itm) => { return !!itm }).sort((a, b) => {
          return String(a.name).localeCompare(String(b.name))
        })
      },
      updateExtras ({ index, extras }) {
        const newOrder = { id: this.order[index].id, extras }

        this.addToCart(newOrder)
        if (this.order[index].quantity === 1) {
          this.removeFromCart(index)
        } else {
          this.setCartQuantity({ index, quantity: this.order[index].quantity - 1 })
        }
      }
    },
    created () {
      this.loadExistingOrder()
    }
  }).mount('#app')
}