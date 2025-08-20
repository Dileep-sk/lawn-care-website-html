<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import notification from '@/assets/icons/notification.svg'
import profile from '@/assets/icons/profile.svg'
import logoutIcon from '@/assets/icons/logout.svg'
import { useAuth } from '@/composables/useAuth'

const { logout } = useAuth()

const currentDate = ref('')
const currentTime = ref('')
let intervalId

onMounted(() => {
    const today = new Date()
    currentDate.value = today.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
    updateTime()
    intervalId = setInterval(updateTime, 1000)
})

onBeforeUnmount(() => {
    clearInterval(intervalId)
})

function updateTime() {
    const now = new Date()
    currentTime.value = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    })
}
</script>

<template>
    <div class="navbar bg-white w-[100%] flex px-[20px] justify-between items-center h-[80px] ">
        <div class="flex items-center gap-[15px]">
            <h2 class="text-[#000]"> <span class="text-[20px]">Welcome </span> <b class="text-[18px]">
                    Test user</b> </h2>
        </div>
        <div class="flex gap-[15px] items-center">
            <div class="relative flex flex-col text-right leading-tight text-sm text-gray-800 text-[18px]">
                <span>{{ currentDate }}</span>
                <span>{{ currentTime }}</span>
            </div>
            <router-link :to="{ name: 'profile' }" class="flex gap-2 items-center text-[#385C4C] text-[15px] ">
                <img :src="profile" alt="">
            </router-link>
            <button @click="logout"
                class="cursor-pointer flex btn_hover items-center justify-center gap-[10px] bg-[#EB2F06] h-[40px] w-[110px] rounded-[6px] text-white text-[15px]">
                Logout
                <img class="w-[24px] h-[24px]" :src="logoutIcon" alt="Logout">
            </button>
        </div>
    </div>
</template>
