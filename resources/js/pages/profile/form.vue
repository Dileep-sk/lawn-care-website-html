<script setup>
import { ref, onMounted, watchEffect } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const { updateProfile, logout, loading, error: apiError } = useAuth()
const router = useRouter()

const user = ref({
    email: '',
    oldPassword: '',
    newPassword: '',
    reNewPassword: ''
})

const validationErrors = ref({
    oldPassword: '',
    newPassword: '',
    reNewPassword: ''
})


onMounted(() => {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
        const parsedUser = JSON.parse(storedUser)
        user.value.email = parsedUser?.email || ''
    }
})

const validate = () => {
    validationErrors.value = { oldPassword: '', newPassword: '', reNewPassword: '' }

    if (!user.value.oldPassword) validationErrors.value.oldPassword = 'Old password is required.'
    if (!user.value.newPassword) validationErrors.value.newPassword = 'New password is required.'
    else if (user.value.newPassword.length < 6) validationErrors.value.newPassword = 'New password must be at least 6 characters.'
    if (user.value.reNewPassword !== user.value.newPassword) validationErrors.value.reNewPassword = 'Passwords do not match.'

    return Object.values(validationErrors.value).every(err => !err)
}


watchEffect(() => {
    if (user.value.oldPassword) validationErrors.value.oldPassword = ''
    if (user.value.newPassword.length >= 6) validationErrors.value.newPassword = ''
    if (user.value.reNewPassword === user.value.newPassword) validationErrors.value.reNewPassword = ''
})

const handleSubmit = async () => {
    if (!validate()) return

    const payload = {
        email: user.value.email,
        old_password: user.value.oldPassword,
        new_password: user.value.newPassword,
        new_password_confirmation: user.value.reNewPassword
    }

    const { success, passwordUpdated } = await updateProfile(payload)
    if (success) {
        if (passwordUpdated) {
            await logout()
        } else {
            router.push({ name: 'profile' })
        }
    }
}
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">Profile</h1>
                </div>

                <div class="form_box p-[15px]">
                    <form @submit.prevent="handleSubmit"
                        class="p-[15px] bg-[rgba(56,92,76,0.04)] max-w-[500px] mx-auto grid gap-[20px]">

                        <!-- Email -->
                        <div>
                            <BaseInput label="Email" v-model="user.email" type="text" placeholder="Email" readonly />
                        </div>

                        <!-- Old Password -->
                        <div>
                            <BaseInput label="Old Password" v-model="user.oldPassword" type="password"
                                placeholder="********" />
                            <p v-if="validationErrors.oldPassword" class="text-red-500 text-sm mt-1">
                                {{ validationErrors.oldPassword }}
                            </p>
                            <p v-if="apiError?.old_password" class="text-red-500 text-sm mt-1">
                                {{ apiError.old_password[0] }}
                            </p>
                        </div>

                        <!-- New Password -->
                        <div>
                            <BaseInput label="New Password" v-model="user.newPassword" type="password"
                                placeholder="********" />
                            <p v-if="validationErrors.newPassword" class="text-red-500 text-sm mt-1">
                                {{ validationErrors.newPassword }}
                            </p>
                            <p v-if="apiError?.new_password" class="text-red-500 text-sm mt-1">
                                {{ apiError.new_password[0] }}
                            </p>
                        </div>

                        <!-- Re-entry New Password -->
                        <div>
                            <BaseInput label="Re-entry New Password" v-model="user.reNewPassword" type="password"
                                placeholder="********" />
                            <p v-if="validationErrors.reNewPassword" class="text-red-500 text-sm mt-1">
                                {{ validationErrors.reNewPassword }}
                            </p>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="flex create justify-center gap-[10px] px-6 py-2 bg-[#385C4C] text-white rounded-md cursor-pointer items-center"
                                :disabled="loading">
                                <span v-if="loading">Updating...</span>
                                <span v-else>Update</span>
                            </button>
                        </div>

                        <!-- General API Error -->
                        <p v-if="apiError?.general" class="text-red-600 text-sm mt-2 text-center">
                            {{ apiError.general }}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
