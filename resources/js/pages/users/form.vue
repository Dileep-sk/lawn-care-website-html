<script setup>
import { reactive, watch } from 'vue'
import { useUser } from '@/composables/useUser'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import eye from '@/assets/icons/eye-off.svg'

const { handleCreateUser } = useUser()

const form = reactive({
    name: '',
    email: '',
    mobile_number: '',
    password: '',
    password_confirmation: ''
})

const errors = reactive({})
const touched = reactive({})

// validators
const validators = {
    name: (val) => (!val.trim() ? 'Name is required' : ''),
    email: (val) => {
        if (!val.trim()) return 'Email is required'
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        return !emailRegex.test(val) ? 'Enter a valid email address' : ''
    },
    mobile_number: (val) => {
        if (!val.trim()) return 'Mobile number is required'
        const mobileRegex = /^\+?\d{7,15}$/
        return !mobileRegex.test(val) ? 'Enter a valid mobile number' : ''
    },
    password: (val) => {
        if (!val) return 'Password is required'
        return val.length < 6 ? 'Password must be at least 6 characters' : ''
    },
    password_confirmation: (val) => {
        if (!val) return 'Please confirm your password'
        return val !== form.password ? 'Passwords do not match' : ''
    }
}

const validateField = (field) => {
    if (!touched[field]) return
    errors[field] = validators[field](form[field])
}

const handleBlur = (field) => {
    touched[field] = true
    validateField(field)
}

const validateForm = () => {
    Object.keys(validators).forEach((field) => {
        touched[field] = true
        validateField(field)
    })
    return Object.values(errors).every((err) => !err)
}

const onSubmit = async () => {
    if (!validateForm()) return
    await handleCreateUser({ ...form })
}

const togglePassword = (id) => {
    const input = document.getElementById(id)
    input.type = input.type === 'password' ? 'text' : 'password'
}

Object.keys(form).forEach((field) => {
    watch(
        () => form[field],
        () => {
            if (touched[field]) {
                validateField(field)
            }
        }
    )
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] font-[700]">Add User</h1>
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
                            <div class="input_box">
                                <label>User Name</label>
                                <input type="text" v-model="form.name" @blur="handleBlur('name')" class="input"
                                    :class="errors.name && 'border-red-500'" placeholder="John Doe" />
                                <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>
                            </div>

                            <!-- Email -->
                            <div class="input_box">
                                <label>Email</label>
                                <input type="email" v-model="form.email" @blur="handleBlur('email')" class="input"
                                    :class="errors.email && 'border-red-500'" placeholder="test@gmail.com" />
                                <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</p>
                            </div>

                            <!-- Mobile -->
                            <div class="input_box">
                                <label>Mobile Number</label>
                                <input type="text" v-model="form.mobile_number" @blur="handleBlur('mobile_number')"
                                    class="input" :class="errors.mobile_number && 'border-red-500'"
                                    placeholder="+911234567895" />
                                <p v-if="errors.mobile_number" class="text-red-500 text-sm">{{ errors.mobile_number }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div class="input_box">
                                <label>Password</label>
                                <div class="relative">
                                    <input id="userPass" type="password" v-model="form.password"
                                        @blur="handleBlur('password')" class="input w-full pr-10"
                                        :class="errors.password && 'border-red-500'" placeholder="*********" />
                                    <img @click="togglePassword('userPass')" :src="eye"
                                        class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        alt="Toggle visibility" />
                                </div>
                                <p v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="input_box">
                                <label>Confirm Password</label>
                                <div class="relative">
                                    <input id="userPassConfirm" type="password" v-model="form.password_confirmation"
                                        @blur="handleBlur('password_confirmation')" class="input w-full pr-10"
                                        :class="errors.password_confirmation && 'border-red-500'"
                                        placeholder="*********" />
                                    <img @click="togglePassword('userPassConfirm')" :src="eye"
                                        class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        alt="Toggle visibility" />
                                </div>
                                <p v-if="errors.password_confirmation" class="text-red-500 text-sm">
                                    {{ errors.password_confirmation }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit" class="flex create justify-center gap-[10px] items-center">
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
