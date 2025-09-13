<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import toastr from 'toastr'
import logo from '@/assets/images/logo.png'
import arrowIcon from '@/assets/icons/right-arrow_white.svg'
import BaseInput from '@/components/BaseInput.vue'

const route = useRoute()
const router = useRouter()
const { resetPassword, loading } = useAuth()

const email = ref(route.query.email || '')
const token = ref(route.query.token || '')
const password = ref('')
const password_confirmation = ref('')

watch(password, (value) => {
    passwordError.value = value ? '' : 'Password is required'
})

watch(password_confirmation, (value) => {
    confirmError.value = value ? '' : 'Please confirm your password'
})

const passwordError = ref('')
const confirmError = ref('')

const handleResetPassword = async () => {
    passwordError.value = ''
    confirmError.value = ''

    if (!password.value) passwordError.value = 'Password is required'    
    if (!password_confirmation.value) confirmError.value = 'Please confirm your password'    

    if (password.value && password_confirmation.value && password.value !== password_confirmation.value) {
        confirmError.value = 'Passwords do not match'
        return
    }
    if (!password.value || !password_confirmation.value) return

    try {
        await resetPassword({
        email: email.value,
        token: token.value,
        password: password.value,
        password_confirmation: password_confirmation.value
        })
        toastr.success('Password has been reset successfully')
        router.push('/login')
    } catch (err) {
        toastr.error(err.response?.data?.message || 'Failed to reset password')
    }
}
</script>

<template>
  <GuestLayout>
    <form @submit.prevent="handleResetPassword"
          class="relative max-w-[415px] mx-[15px] w-full bg-white p-[20px] rounded-[10px]">

      <img :src="logo" alt="Logo" class="mx-auto mt-[20px] flex w-[130px]" />
      <h1 class="text-[#771D16] text-[30px] font-[700] text-center my-[20px]">
        Reset Password
      </h1>

      <div class="flex flex-col gap-[20px]">
        <!-- New Password -->        
        <BaseInput v-model="password" label="Password" type="password" placeholder="Enter your password"
                    :error="passwordError" />

        <!-- Confirm Password -->        
        <BaseInput v-model="password_confirmation" label="Password" type="password" placeholder="Confirm Password"
                    :error="confirmError" />

        <button type="submit" :disabled="loading"
                class="h-[45px] flex gap-[10px] justify-center items-center w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
          <span v-if="!loading">Reset Password</span>
          <span v-else>Resetting...</span>
          <img v-if="!loading" :src="arrowIcon" alt="arrow" />
        </button>
      </div>

      <div class="text-center text-sm">
        <router-link to="/login" class="text-[#FF4757] hover:underline">
          Back to Login
        </router-link>
      </div>

    </form>
  </GuestLayout>
</template>
