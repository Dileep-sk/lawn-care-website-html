import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/dashboard.vue'
import Order from '../pages/order/list.vue'
import OrderForm from '../pages/order/form.vue'
import Users from '../pages/users/list.vue'
import UsersForm from '../pages/users/form.vue'
import Jobs from '../pages/jobs/list.vue'
import JobForm from '../pages/jobs/form.vue'
import Stock from '../pages/stock/list.vue'
import StockForm from '../pages/stock/form.vue'
import Reports from '../pages/reports/lits.vue'
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
        name: 'dashboard',
        component: Dashboard,
        meta: { title: 'Home' }
    },

    /// ======================================= order routes =================================================
    {
        path: '/order',
        name: 'order',
        component: Order,
        meta: { title: 'Order' }
    },

    {
        path: '/order/create',
        name: 'order-create',
        component: OrderForm,
        meta: { title: 'Order Create' }
    },

    {
        path: '/order/edit',
        name: 'order-edit',
        component: OrderForm,
        meta: { title: 'Order Edit' }
    },

    /// ======================================= order jobs =================================================
    {
        path: '/jobs',
        name: 'jobs',
        component: Jobs,
        meta: { title: 'Jobs' }
    },

    {
        path: '/jobs/create',
        name: 'jobs-create',
        component: JobForm,
        meta: { title: 'Jobs Create' }
    },

    {
        path: '/jobs/edit',
        name: 'jobs-edit',
        component: JobForm,
        meta: { title: 'Jobs Edit' }
    },
    /// ======================================= order stock =================================================
    {
        path: '/stock',
        name: 'stock',
        component: Stock,
        meta: { title: 'Stock' }
    },
    {
        path: '/stock/create',
        name: 'stock-create',
        component: StockForm,
        meta: { title: 'Stock Create' }
    },

    {
        path: '/stock/edit',
        name: 'stock-edit',
        component: StockForm,
        meta: { title: 'Stock Edit' }
    },

    /// ======================================= order users =================================================
    {
        path: '/users',
        name: 'users',
        component: Users,
        meta: { title: 'User List' }
    },
    {
        path: '/users/create',
        name: 'users-create',
        component: UsersForm,
        meta: { title: 'User Create' }
    },
    {
        path: '/users/edit',
        name: 'users-edit',
        component: UsersForm,
        meta: { title: 'User Edit' }
    },

    /// ======================================= order routes =================================================

    {
        path: '/reports',
        name: 'reports',
        component: Reports,
        meta: { title: 'Reports' }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = `${to.meta.title} | Textile Erp Software`
    } else {
        document.title = 'Textile Erp Software'
    }
})

export default router
