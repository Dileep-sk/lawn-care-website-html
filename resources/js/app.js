import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { isLoading } from './stores/loadingStore'  // import reactive loading ref
import { initUI } from './helpers/initUI'
import 'toastr/build/toastr.min.css'

const app = createApp(App)

app.use(router)
app.mount('#app')

// Show loader before route changes
router.beforeEach((to, from, next) => {
  isLoading.value = true
  next()
})

// Hide loader after route changes
router.afterEach(() => {
  setTimeout(() => {
    isLoading.value = false
    initUI()
  }, 300) // small delay to avoid flicker
})
