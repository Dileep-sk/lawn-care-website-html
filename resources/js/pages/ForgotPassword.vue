<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { ref } from 'vue'
import toastr from 'toastr'
import { useAuth } from '@/composables/useAuth'

const email = ref('')
const emailError = ref('')
const { forgotPassword, loading } = useAuth()

const handleForgotPassword = async () => {
  if (!email.value) {
    emailError.value = 'Email is required'
    return
  }
  emailError.value = ''
  try {
    await forgotPassword({ email: email.value })
    toastr.success('Password reset link sent to your email')
  } catch (err) {
    toastr.error(err.response?.data?.message || 'Failed to send reset link')
  }
}
</script>

<template>
  <GuestLayout>
    <form @submit.prevent="handleForgotPassword"
          class="relative max-w-[415px] mx-[15px] w-full bg-white p-[20px] rounded-[10px]">
      
      <img src="@/assets/images/logo.png" alt="Logo" class="mx-auto mt-[20px] flex w-[130px]" />
      <h1 class="text-[#771D16] text-[30px] font-[700] text-center my-[20px]">
        Forgot Password
      </h1>

      <div class="flex flex-col gap-[20px]">
        <input v-model="email" type="email" placeholder="Enter your email"
               class="w-full p-3 border rounded" />
        <p v-if="emailError" class="text-red-500 text-sm">{{ emailError }}</p>

        <button type="submit" :disabled="loading"
                class="h-[45px] flex gap-[10px] justify-center items-center w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
          <span v-if="!loading">Send Reset Link</span>
          <span v-else>Sending...</span>
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
