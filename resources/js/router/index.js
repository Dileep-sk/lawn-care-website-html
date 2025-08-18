import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/dashboard.vue'
import Order from '../pages/order.vue'
import Users from '../pages/users/list.vue'

const routes = [
    {
        path: '/',
        redirect: '/login',
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { title: 'Login' }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { title: 'Home' }
    },
    {
        path: '/order',
        name: 'Order',
        component: Order,
        meta: { title: 'Order' }
    },
    {
        path: '/users',
        name: 'Users',
        component: Users,
        meta: { title: 'User List' }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

// dynamic page titles like: Home | ERP
router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = `${to.meta.title} | Textile Erp Software`
    } else {
        document.title = 'Textile Erp Software'
    }
})

export default router
