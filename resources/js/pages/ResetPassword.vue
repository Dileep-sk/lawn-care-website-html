<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import toastr from 'toastr'

const route = useRoute()
const router = useRouter()
const { resetPassword, loading } = useAuth()

const email = ref(route.query.email || '')
const token = ref(route.query.token || '')
const password = ref('')
const password_confirmation = ref('')

const passwordError = ref('')
const confirmError = ref('')

const handleResetPassword = async () => {
  passwordError.value = ''
  confirmError.value = ''

  if (!password.value) {
    passwordError.value = 'Password is required'
  }
  if (!password_confirmation.value) {
    confirmError.value = 'Please confirm your password'
  }
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

      <img src="@/assets/images/logo.png" alt="Logo" class="mx-auto mt-[20px] flex w-[130px]" />
      <h1 class="text-[#771D16] text-[30px] font-[700] text-center my-[20px]">
        Reset Password
      </h1>

      <div class="flex flex-col gap-[20px]">
        <!-- New Password -->
        <input v-model="password" type="password" placeholder="New Password"
               class="w-full p-3 border rounded" />
        <p v-if="passwordError" class="text-red-500 text-sm">{{ passwordError }}</p>

        <!-- Confirm Password -->
        <input v-model="password_confirmation" type="password" placeholder="Confirm Password"
               class="w-full p-3 border rounded" />
        <p v-if="confirmError" class="text-red-500 text-sm">{{ confirmError }}</p>

        <button type="submit" :disabled="loading"
                class="h-[45px] flex gap-[10px] justify-center items-center w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
          <span v-if="!loading">Reset Password</span>
          <span v-else>Resetting...</span>
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
