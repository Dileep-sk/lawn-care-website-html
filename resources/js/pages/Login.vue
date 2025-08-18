<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import logo from '@/assets/images/logo.png'
import arrowIcon from '@/assets/icons/right-arrow_white.svg'

import { useRouter } from 'vue-router'

const router = useRouter()

function handleLogin() {

    router.push({ name: 'dashboard' })
}
</script>

<template>
    <GuestLayout>
        <form @submit.prevent="handleLogin"
            class="relative max-w-[415px] mx-[15px] w-full bg-white p-[20px] rounded-[10px]">
            <img :src="logo" alt="Logo" class="mx-auto mt-[20px] flex w-[130px]" />
            <h1 class="text-[#771D16] text-[30px] font-[700] text-center my-[20px]">
                Log In
            </h1>
            <div class="input_container flex flex-col gap-[20px]">
                <div class="input_box">
                    <label>User ID</label>
                    <input class="input" placeholder="test@user" type="text" />
                </div>
                <div class="input_box">
                    <label>Password</label>
                    <input class="input" type="password" />
                </div>
                <button type="submit"
                    class="h-[45px] flex gap-[10px] justify-center items-center px-[60px] w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
                    Login
                    <img :src="arrowIcon" alt="arrow" />
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
