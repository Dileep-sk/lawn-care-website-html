import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { initUI } from './helpers/initUI'
import 'toastr/build/toastr.min.css'

const app = createApp(App)

app.use(router)
app.mount('#app')

// Initial UI setup
initUI()

// Re-initialize UI on route change
router.afterEach(() => {
    setTimeout(() => {
        initUI()
    }, 0) // ensure DOM is fully rendered
})
