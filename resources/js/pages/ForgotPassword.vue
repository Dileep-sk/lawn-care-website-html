<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import logo from '@/assets/images/logo.png'
import { ref, watch, computed } from 'vue'
import toastr from 'toastr'
import { useAuth } from '@/composables/useAuth'
import BaseInput from '@/components/BaseInput.vue'
import arrowIcon from '@/assets/icons/right-arrow_white.svg'

const { forgotPassword, loading } = useAuth()

const form = ref({
    email: '',
})

const errors = ref({
    email: '',
})

const validateField = (key, value) => {
    const trimmed = value.toString().trim()
    if (['email'].includes(key)) {
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
    form.value.email
)

const handleForgotPassword = async () => {
    Object.entries(form.value).forEach(([key, val]) => validateField(key, val))
    if (!isFormValid.value) return
    try {
        await forgotPassword({ email: form.value.email })
        toastr.success('Password reset link sent to your email')
    } catch (err) {
        const errors = err.response?.data?.errors || {}
        if (errors.email) toastr.error(errors.email[0])
        if (!errors.email) {
            toastr.error(err.response?.data?.message || 'Failed to send reset link')
        }
    }
}
</script>

<template>
  <GuestLayout>
    <form @submit.prevent="handleForgotPassword"
          class="relative max-w-[415px] mx-[15px] w-full bg-white p-[20px] rounded-[10px]">
      
      <img :src="logo" alt="Logo" class="mx-auto mt-[20px] flex w-[130px]" />
      <h1 class="text-[#771D16] text-[30px] font-[700] text-center my-[20px]">
        Forgot Password
      </h1>

      <div class="input_container flex flex-col gap-[20px]">        
        <BaseInput v-model="form.email" label="User ID" type="email" placeholder="test@user.com"
                    :error="errors.email" />

        <button type="submit" :disabled="loading"
                class="h-[45px] flex gap-[10px] justify-center items-center w-full my-[20px] rounded-[6px] bg-[linear-gradient(90deg,_#FF7556_0%,_#FF4757_100%)] text-white font-[500] text-[17px]">
          <span v-if="!loading">Send Reset Link</span>
          <span v-else>Sending...</span>
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
