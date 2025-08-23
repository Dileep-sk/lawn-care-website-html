import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Dashboard from "../pages/dashboard.vue";
import Order from "../pages/order/list.vue";
import OrderForm from "../pages/order/form.vue";
import OrderView from "../pages/order/view.vue";
import Users from "../pages/users/list.vue";
import UsersForm from "../pages/users/form.vue";
import Jobs from "../pages/jobs/list.vue";
import JobForm from "../pages/jobs/form.vue";
import JobView from "../pages/jobs/view.vue";
import Stock from "../pages/stock/list.vue";
import StockForm from "../pages/stock/form.vue";
import Reports from "../pages/reports/lits.vue";
import Profile from "../pages/profile/form.vue";
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
        path: "/order/edit/:id",
        name: "order-edit",
        component: OrderForm,
        meta: { title: "Order Edit", requiresAuth: true },
    },
    {
        path: "/order/view/:id",
        name: "order-view",
        component: OrderView,
        meta: { title: "Order View", requiresAuth: true },
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
    {
        path: "/jobs/view:id",
        name: "jobs-view",
        component: JobView,
        meta: { title: "Jobs View", requiresAuth: true },
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
        path: "/stock/edit/:id",
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

    // profile
    {
        path: "/profile",
        name: "profile",
        component: Profile,
        meta: { title: "Profile", requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


router.afterEach((to) => {
    document.title = to.meta?.title
        ? `${to.meta.title} | Textile Erp Software`
        : "Textile Erp Software";
});

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth) {
        if (!token) {
            return next({ name: "login" });
        }

        try {
            await api.get("/user");
            return next();
        } catch (error) {

            if (error.response?.status === 401) {
                localStorage.removeItem("token");
                return next({ name: "login" });
            } else {

                return next({ name: "login" });
            }
        }
    }


    if (to.name === "login" && token) {
        try {
            await api.get("/user");
            return next({ name: "dashboard" });
        } catch {

            localStorage.removeItem("token");
            return next();
        }
    }

    return next();
});

export default router;
