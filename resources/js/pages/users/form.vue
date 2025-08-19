<script setup>
import { reactive, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUser } from '@/composables/useUser'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import toastr from 'toastr'
import BaseInput from '@/components/BaseInput.vue'

const route = useRoute()
const router = useRouter()
const userId = route.params.id
const isEdit = !!userId

const { handleCreateUser, handleUpdateUser, fetchUserById } = useUser()

const form = reactive({
    name: '',
    email: '',
    mobile_number: '',
    password: '',
    password_confirmation: ''
})

const errors = reactive({})
const touched = reactive({})

// Validators
const validators = {
    name: val => (!val.trim() ? 'Name is required' : ''),
    email: val => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        return !val.trim()
            ? 'Email is required'
            : !emailRegex.test(val)
                ? 'Enter a valid email address'
                : ''
    },
    mobile_number: val => {
        const mobileRegex = /^\+?\d{7,15}$/
        return !val.trim()
            ? 'Mobile number is required'
            : !mobileRegex.test(val)
                ? 'Enter a valid mobile number'
                : ''
    },
    password: val =>
        !isEdit && !val
            ? 'Password is required'
            : val && val.length < 6
                ? 'Password must be at least 6 characters'
                : '',
    password_confirmation: val =>
        form.password && val !== form.password ? 'Passwords do not match' : ''
}

const validateField = field => {
    if (touched[field]) {
        errors[field] = validators[field]?.(form[field]) || ''
    }
}

const validateForm = () => {
    Object.keys(validators).forEach(field => {
        touched[field] = true
        validateField(field)
    })
    return Object.values(errors).every(err => !err)
}

const handleBlur = field => {
    touched[field] = true
    validateField(field)
}

Object.keys(form).forEach(field => {
    watch(() => form[field], () => validateField(field))
})

const onSubmit = async () => {
    if (!validateForm()) return

    try {
        if (isEdit) {
            await handleUpdateUser(userId, { ...form })
            toastr.success('User updated successfully', 'Success')
        } else {
            await handleCreateUser({ ...form })
            toastr.success('User created successfully', 'Success')
        }
        router.push({ name: 'users' })
    } catch {
        toastr.error(isEdit ? 'Failed to update user' : 'Failed to create user', 'Error')
    }
}

onMounted(async () => {
    if (isEdit) {
        try {
            const res = await fetchUserById(userId)
            Object.assign(form, {
                name: res.data.name,
                email: res.data.email,
                mobile_number: res.data.mobile_number,
                password: '',
                password_confirmation: ''
            })
        } catch {
            toastr.error('Failed to fetch user details', 'Error')
        }
    }
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] font-[700]">
                        {{ isEdit ? 'Edit User' : 'Add User' }}
                    </h1>
                    <router-link :to="{ name: 'users' }"
                        class="cursor-pointer h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-[500] rounded-[5px] bg-black">
                        <img :src="left_arrow" class="w-[20px]" alt="Back" /> Back
                    </router-link>
                </div>

                <!-- Form -->
                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="onSubmit">
                        <div class="grid grid-cols-4 gap-[30px]">
                            <!-- Name -->
                            <BaseInput v-model="form.name" label="User Name" placeholder="John Doe" :error="errors.name"
                                @blur="handleBlur('name')" />

                            <!-- Email -->
                            <BaseInput v-model="form.email" label="Email" type="email" placeholder="test@gmail.com"
                                :error="errors.email" @blur="handleBlur('email')" />

                            <!-- Mobile -->
                            <BaseInput v-model="form.mobile_number" label="Mobile Number" placeholder="+911234567895"
                                :error="errors.mobile_number" @blur="handleBlur('mobile_number')" />

                            <!-- Password -->
                            <BaseInput v-if="!isEdit" v-model="form.password" label="Password" type="password"
                                placeholder="*********" :error="errors.password" @blur="handleBlur('password')" />

                            <!-- Confirm Password -->
                            <BaseInput v-if="!isEdit" v-model="form.password_confirmation" label="Confirm Password"
                                type="password" placeholder="*********" :error="errors.password_confirmation"
                                @blur="handleBlur('password_confirmation')" />
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit" class="flex create justify-center gap-[10px] items-center">
                                {{ isEdit ? 'Update User' : 'Add User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
