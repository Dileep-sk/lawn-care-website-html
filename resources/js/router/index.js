import { createRouter, createWebHistory } from "vue-router";
import api from '@/utils/axios';

const routes = [
    { path: "/", redirect: "/login" },

    {
        path: "/login",
        name: "login",
        component: () => import("../pages/Login.vue"),
        meta: { title: "Login" },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("../pages/dashboard.vue"),
        meta: { title: "Home", requiresAuth: true },
    },

    // Orders
    {
        path: "/order",
        name: "order",
        component: () => import("../pages/order/list.vue"),
        meta: { title: "Order", requiresAuth: true },
    },
    {
        path: "/order/create",
        name: "order-create",
        component: () => import("../pages/order/form.vue"),
        meta: { title: "Order Create", requiresAuth: true },
    },
    {
        path: "/order/edit/:id",
        name: "order-edit",
        component: () => import("../pages/order/form.vue"),
        meta: { title: "Order Edit", requiresAuth: true },
    },
    {
        path: "/order/view/:id",
        name: "order-view",
        component: () => import("../pages/order/view.vue"),
        meta: { title: "Order View", requiresAuth: true },
    },

    // Jobs
    {
        path: "/jobs",
        name: "jobs",
        component: () => import("../pages/jobs/list.vue"),
        meta: { title: "Jobs", requiresAuth: true },
    },
    {
        path: "/jobs/create",
        name: "jobs-create",
        component: () => import("../pages/jobs/form.vue"),
        meta: { title: "Jobs Create", requiresAuth: true },
    },
    {
        path: "/jobs/edit",
        name: "jobs-edit",
        component: () => import("../pages/jobs/form.vue"),
        meta: { title: "Jobs Edit", requiresAuth: true },
    },
    {
        path: "/jobs/view/:id",  // <-- fixed missing colon here
        name: "jobs-view",
        component: () => import("../pages/jobs/view.vue"),
        meta: { title: "Jobs View", requiresAuth: true },
    },

    // Stock
    {
        path: "/stock",
        name: "stock",
        component: () => import("../pages/stock/list.vue"),
        meta: { title: "Stock", requiresAuth: true },
    },
    {
        path: "/stock/create",
        name: "stock-create",
        component: () => import("../pages/stock/form.vue"),
        meta: { title: "Stock Create", requiresAuth: true },
    },
    {
        path: "/stock/edit/:id",
        name: "stock-edit",
        component: () => import("../pages/stock/form.vue"),
        meta: { title: "Stock Edit", requiresAuth: true },
    },

    // Users
    {
        path: "/users",
        name: "users",
        component: () => import("../pages/users/list.vue"),
        meta: { title: "User List", requiresAuth: true },
    },
    {
        path: "/users/create",
        name: "users-create",
        component: () => import("../pages/users/form.vue"),
        meta: { title: "User Create", requiresAuth: true },
    },
    {
        path: "/users/edit/:id",
        name: "users-edit",
        component: () => import("../pages/users/form.vue"),
        meta: { title: "User Edit", requiresAuth: true },
    },

    // Reports
    {
        path: "/reports",
        name: "reports",
        component: () => import("../pages/reports/lits.vue"),
        meta: { title: "Reports", requiresAuth: true },
    },

    // Profile
    {
        path: "/profile",
        name: "profile",
        component: () => import("../pages/profile/form.vue"),
        meta: { title: "Profile", requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Update document title after each route change
router.afterEach((to) => {
    document.title = to.meta?.title
        ? `${to.meta.title} | Textile Erp Software`
        : "Textile Erp Software";
});

// Navigation guard for authentication
router.beforeEach(async (to) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth) {
        if (!token) {
            return { name: "login" };
        }

        try {
            await api.get("/user");
            return true;
        } catch (error) {
            localStorage.removeItem("token");
            return { name: "login" };
        }
    }

    if (to.name === "login" && token) {
        try {
            await api.get("/user");
            return { name: "dashboard" };
        } catch {
            localStorage.removeItem("token");
            return true;
        }
    }

    return true;
});

export default router;
