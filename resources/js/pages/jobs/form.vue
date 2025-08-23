<script setup>
import { reactive, watch } from 'vue'
import { useJobs } from '@/composables/useJobs'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import toastr from 'toastr'
import { STATUS_OPTIONS } from '@/constants/jobStatus'

const { createJobHandler } = useJobs()

// --- Form ---
const form = reactive({
    customer_name: '',
    design_no: '',
    images: [],
    quantity: '',
    order_no: '',
    status: '',
    matchings: Array.from({ length: 8 }, () => ({ text: '' }))
})

// --- Validation ---
const errors = reactive({})
const touched = reactive({})

const validators = {
    design_no: val => !val.trim() ? 'Design No is required' : '',
    customer_name: val => !val.trim() ? 'Customer Name is required' : '',
    quantity: val => !val ? 'Quantity is required' : isNaN(val) || val <= 0 ? 'Enter a valid quantity' : '',
    order_no: val => !val.trim() ? 'Order No is required' : '',
    status: val => (val === '' ? 'Status is required' : ''),
    images: val => (!val || val.length === 0) ? 'At least one image is required' : ''
}

const validateField = (field) => {
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

const handleBlur = (field) => {
    touched[field] = true
    validateField(field)
}

const handleFileUpload = (event) => {
    form.images = event.target.files ? Array.from(event.target.files) : []
    touched.images = true
    validateField('images')
}

// --- Submit ---
const handleSubmit = async () => {
    if (!validateForm()) return

    const formData = new FormData()

    Object.entries(form).forEach(([key, value]) => {
        if (key === 'images') {
            value.forEach(file => {
                formData.append('images[]', file)
            })
        } else if (key === 'matchings') {
            value.forEach((matching, index) => {
                formData.append(`matching_${index + 1}`, matching.text || '')
            })
        } else {
            formData.append(key, value)
        }
    })

    try {
        await createJobHandler(formData)
        toastr.success('Job created successfully', 'Success')
    } catch (err) {
        toastr.error(err.response?.data?.message || 'Failed to create job', 'Error')
    }
}

Object.keys(validators).forEach((field) => {
    watch(
        () => form[field],
        () => validateField(field)
    )
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] text-black font-bold">Create Job</h1>
                    <router-link :to="{ name: 'jobs' }"
                        class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                        <img :src="left_arrow" class="w-[20px]" alt="Back" />
                        Back
                    </router-link>
                </div>

                <!-- Form -->
                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="handleSubmit">
                        <div class="grid grid-cols-3 gap-[30px]">
                            <!-- Customer Name -->
                            <div class="input_box">
                                <BaseInput v-model="form.customer_name" label="Customer Name" placeholder=""
                                    @blur="handleBlur('customer_name')" />
                                <span v-if="errors.customer_name" class="text-red-500">{{ errors.customer_name }}</span>
                            </div>

                            <!-- Design No -->
                            <div class="input_box">
                                <BaseInput v-model="form.design_no" label="Design No" placeholder="D1002"
                                    @blur="handleBlur('design_no')" />
                                <span v-if="errors.design_no" class="text-red-500">{{ errors.design_no }}</span>
                            </div>

                            <!-- Image Upload -->
                            <div class="input_box">
                                <label>Image</label>
                                <input type="file" multiple class="input" @change="handleFileUpload" />
                                <span v-if="errors.images" class="text-red-500">{{ errors.images }}</span>
                            </div>

                            <!-- Quantity -->
                            <div class="input_box">
                                <BaseInput v-model="form.quantity" label="Quantity" type="number" placeholder="00.00"
                                    @blur="handleBlur('quantity')" />
                                <span v-if="errors.quantity" class="text-red-500">{{ errors.quantity }}</span>
                            </div>

                            <!-- Order No -->
                            <div class="input_box">
                                <BaseInput v-model="form.order_no" label="Order No" placeholder="ORD1234"
                                    @blur="handleBlur('order_no')" />
                                <span v-if="errors.order_no" class="text-red-500">{{ errors.order_no }}</span>
                            </div>

                            <!-- Status -->
                            <div class="input_box">
                                <label>Status</label>
                                <select v-model="form.status" class="input" @blur="handleBlur('status')">
                                    <option disabled value="">Select Status</option>
                                    <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                                <span v-if="errors.status" class="text-red-500">{{ errors.status }}</span>
                            </div>

                            <!-- Matchings -->
                            <div v-for="(matching, index) in form.matchings" :key="index" class="input_box">
                                <label>Matching {{ index + 1 }}</label>
                                <input v-model="form.matchings[index].text" type="text" class="input" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit" class="flex create justify-center gap-[10px] items-center">
                                Submit Job
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
