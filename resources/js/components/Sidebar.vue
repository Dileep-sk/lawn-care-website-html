<script setup>
import { useRoute } from 'vue-router'
import logo from '@/assets/images/logo.png'
import leftarrow from '@/assets/icons/left-arrow.svg'
import dashboard from '@/assets/icons/dashboard.svg'
import cart from '@/assets/icons/cart.svg'
import jobs from '@/assets/icons/jobs.svg'
import stocks from '@/assets/icons/stock.svg'
import users from '@/assets/icons/users.svg'
import doc from '@/assets/icons/doc.svg'

const route = useRoute()
</script>

<template>
    <div id="sidebar" class="sidebar fixed py-[30px] px-[15px] w-[300px] h-[100vh] bg-[#FEFDE0]">
        <div class="sidebar_logo flex justify-center">
            <img class="w-[150px]" :src="logo" alt="">
        </div>

        <button id="collapseToggle"
            class="w-[30px] absolute top-[20px] right-[0] cursor-pointer flex items-center justify-center h-[30px] bg-[#781E16] rounded-l-[6px]">
            <img :src="leftarrow" class="w-[20px]" alt="">
        </button>

        <ul class="flex flex-col mt-[50px] gap-[18px]">
            <li :class="{ 'active': route.path === '/dashboard' }">
                <router-link :to="{ name: 'dashboard' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="dashboard" alt="">
                    <span>Dashboard</span>
                </router-link>
            </li>

            <li :class="{ 'active': route.path.startsWith('/order') }">
                <router-link :to="{ name: 'order' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="cart" alt="">
                    <span>Orders</span>
                </router-link>
            </li>

            <li :class="{ 'active': route.path.startsWith('/jobs') }">
                <router-link :to="{ name: 'jobs' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="jobs" alt="">
                    <span>Jobs</span>
                </router-link>
            </li>

            <li :class="{ 'active': route.path.startsWith('/stock') }">
                <router-link :to="{ name: 'stock' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="stocks" alt="">
                    <span>Stocks</span>
                </router-link>
            </li>

            <li :class="{ 'active': route.path.startsWith('/users') }">
                <router-link :to="{ name: 'users' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="users" alt="">
                    <span>Users</span>
                </router-link>
            </li>

            <li :class="{ 'active': route.path.startsWith('/reports') }">
                <router-link :to="{ name: 'reports' }"
                    class="h-[45px] rounded-[5px] flex gap-[10px] items-center px-[15px] text-[#000]">
                    <img :src="doc" alt="">
                    <span>Reports</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>
