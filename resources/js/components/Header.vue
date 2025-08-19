<script setup>

import { useRouter } from 'vue-router'
import notification from '@/assets/icons/notification.svg'
import profile from '@/assets/icons/profile.svg'
import logoutIcon from '@/assets/icons/logout.svg'
import { useAuth } from '@/composables/useAuth'
const { logout } = useAuth()
</script>

<template>
    <div class="navbar bg-white w-[100%] flex px-[20px] justify-between items-center h-[80px] ">
        <div class="flex items-center gap-[15px]">
            <h2 class="text-[#000]"> <span class="text-[20px]">Welcome </span> <b class="text-[18px]">
                    Test user</b> </h2>
        </div>
        <div class="flex gap-[15px] items-center">


            <div class="relative">
                <div
                    class="w-[18px] absolute right-[0] flex justify-center items-center h-[18px] rounded-full bg-[#D62925] text-[#fff] text-[10px] ">
                    10</div>
                <img :src="notification" alt="">
            </div>

            <a href="#" class="flex gap-2 items-center text-[#385C4C] text-[15px] ">
                <img :src="profile" alt="">
            </a>

            <button @click="logout"
                class="cursor-pointer flex btn_hover items-center justify-center gap-[10px] bg-[#EB2F06] h-[40px] w-[110px] rounded-[6px] text-white text-[15px]">
                Logout
                <img class="w-[24px] h-[24px]" :src="logoutIcon" alt="Logout">
            </button>

        </div>
    </div>
</template>
