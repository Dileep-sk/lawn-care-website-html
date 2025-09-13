<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { ref, watch, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import toastr from 'toastr'
import logo from '@/assets/images/logo.png'
import arrowIcon from '@/assets/icons/right-arrow_white.svg'
import BaseInput from '@/components/BaseInput.vue'

const route = useRoute()
const router = useRouter()
const { resetPassword, loading } = useAuth()

const form = ref({
    email: route.query.email || '',
    token: route.query.token || '',
    password: '',
    password_confirmation: '',
})

const errors = ref({
    password: '',
    password_confirmation: '',
})

const validateField = (key, value) => {
    const trimmed = value.toString().trim()
    if (['password', 'password_confirmation'].includes(key)) {
        errors.value[key] = trimmed ? '' : 'This field is required'
    }
}

let validationSetupDone = false
const setupValidation = () => {
    if (validationSetupDone) return
    validationSetupDone = true
    Object.keys(form.value).forEach(key => {
    watch(() => form.value[key], value => validateField(key, value))
    })
}

setupValidation();

const isFormValid = computed(() =>
    Object.values(errors.value).every(e => !e) &&
    form.value.password &&
    form.value.password_confirmation
)

const handleResetPassword = async () => {
    Object.entries(form.value).forEach(([key, val]) => validateField(key, val))
    if (!isFormValid.value) return
    try {
        await resetPassword({
            email: form.value.email,
            token: form.value.token,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation
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
        <BaseInput v-model="form.password" label="Password" type="password" placeholder="Enter your password"
                    :error="errors.password" />

        <!-- Confirm Password -->        
        <BaseInput v-model="form.password_confirmation" label="confirm Password" type="password" placeholder="Confirm Password"
                    :error="errors.password_confirmation" />

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
