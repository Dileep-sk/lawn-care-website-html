import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Dashboard from "../pages/dashboard.vue";
import Order from "../pages/order/list.vue";
import OrderForm from "../pages/order/form.vue";
import Users from "../pages/users/list.vue";
import UsersForm from "../pages/users/form.vue";
import Jobs from "../pages/jobs/list.vue";
import JobForm from "../pages/jobs/form.vue";
import Stock from "../pages/stock/list.vue";
import StockForm from "../pages/stock/form.vue";
import Reports from "../pages/reports/lits.vue";

import api from '@/utils/axios'

const routes = [
    { path: "/", redirect: "/login" },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: { title: "Login" },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: { title: "Home", requiresAuth: true },
    },

    // Orders
    {
        path: "/order",
        name: "order",
        component: Order,
        meta: { title: "Order", requiresAuth: true },
    },
    {
        path: "/order/create",
        name: "order-create",
        component: OrderForm,
        meta: { title: "Order Create", requiresAuth: true },
    },
    {
        path: "/order/edit",
        name: "order-edit",
        component: OrderForm,
        meta: { title: "Order Edit", requiresAuth: true },
    },

    // Jobs
    {
        path: "/jobs",
        name: "jobs",
        component: Jobs,
        meta: { title: "Jobs", requiresAuth: true },
    },
    {
        path: "/jobs/create",
        name: "jobs-create",
        component: JobForm,
        meta: { title: "Jobs Create", requiresAuth: true },
    },
    {
        path: "/jobs/edit",
        name: "jobs-edit",
        component: JobForm,
        meta: { title: "Jobs Edit", requiresAuth: true },
    },

    // Stock
    {
        path: "/stock",
        name: "stock",
        component: Stock,
        meta: { title: "Stock", requiresAuth: true },
    },
    {
        path: "/stock/create",
        name: "stock-create",
        component: StockForm,
        meta: { title: "Stock Create", requiresAuth: true },
    },
    {
        path: "/stock/edit",
        name: "stock-edit",
        component: StockForm,
        meta: { title: "Stock Edit", requiresAuth: true },
    },

    // Users
    {
        path: "/users",
        name: "users",
        component: Users,
        meta: { title: "User List", requiresAuth: true },
    },
    {
        path: "/users/create",
        name: "users-create",
        component: UsersForm,
        meta: { title: "User Create", requiresAuth: true },
    },
    {
        path: "/users/edit/:id",
        name: "users-edit",
        component: UsersForm,
        meta: { title: "User Edit", requiresAuth: true },
    },

    // Reports
    {
        path: "/reports",
        name: "reports",
        component: Reports,
        meta: { title: "Reports", requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Set page title
router.afterEach((to) => {
    document.title = to.meta?.title
        ? `${to.meta.title} | Textile Erp Software`
        : "Textile Erp Software";
});

// Global Auth Guard with token expiration check
router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem("token");

    // If route requires auth
    if (to.meta.requiresAuth) {
        if (!token) {
            // No token at all
            return next({ name: "login" });
        }

        try {
            // Validate token via backend
            await api.get("/user"); // Laravel returns 401 if expired
            return next(); // Valid token
        } catch (error) {
            // Token expired or invalid
            if (error.response?.status === 401) {
                localStorage.removeItem("token");
                return next({ name: "login" });
            } else {
                // Optional: handle other errors like 500
                return next({ name: "login" });
            }
        }
    }

    // If user already logged in and tries to access login
    if (to.name === "login" && token) {
        try {
            await api.get("/user");
            return next({ name: "dashboard" }); // Redirect to dashboard
        } catch {
            // Token invalid, let them see login page
            localStorage.removeItem("token");
            return next();
        }
    }

    // Open route
    return next();
});

export default router;
