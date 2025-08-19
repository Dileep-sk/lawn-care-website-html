<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import logo from '@/assets/images/logo.png'
import arrowIcon from '@/assets/icons/right-arrow_white.svg'

import { ref, watch } from 'vue'
import { useAuth } from '@/composables/useAuth'
import toastr from 'toastr'

const email = ref('')
const password = ref('')
const emailError = ref('')
const passwordError = ref('')

const { login, error, loading } = useAuth()

watch(email, (value) => {
    emailError.value = value ? '' : 'Email is required'
})

watch(password, (value) => {
    passwordError.value = value ? '' : 'Password is required'
})

const handleLogin = async () => {

    if (!email.value) {
        emailError.value = 'Email is required'
    }

    if (!password.value) {
        passwordError.value = 'Password is required'
    }

    if (emailError.value || passwordError.value) {
        return
    }

    try {
        await login({ email: email.value, password: password.value })
        toastr.success('Login successful')
    } catch (err) {
        const errors = err.response?.data?.errors || {}
        if (errors.email) toastr.error(errors.email[0])
        if (errors.password) toastr.error(errors.password[0])
        if (!errors.email && !errors.password) {
            toastr.error(err.response?.data?.message || 'Login failed')
        }
    }
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
                    <input v-model="email" class="input" placeholder="test@user.com" type="email" />
                    <span class="text-red-500 text-xs">{{ emailError }}</span>
                </div>

                <div class="input_box">
                    <label>Password</label>
                    <input v-model="password" class="input" type="password" />
                    <span class="text-red-500 text-xs">{{ passwordError }}</span>
                </div>

                <button :disabled="loading" type="submit"
                    class="h-[45px] flex gap-[10px] justify-center items-center px-[60px] w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
                    <span v-if="!loading">Login</span>
                    <span v-else>Logging in...</span>
                    <img v-if="!loading" :src="arrowIcon" alt="arrow" />
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
