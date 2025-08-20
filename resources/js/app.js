import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { isLoading } from './stores/loadingStore'
import { initUI } from './helpers/initUI'
import 'toastr/build/toastr.min.css'

const app = createApp(App)

app.use(router)
app.mount('#app')


router.beforeEach((to, from, next) => {
    isLoading.value = true
    next()
})


router.afterEach(() => {
    setTimeout(() => {
        isLoading.value = false
        initUI()
    }, 300)
})
