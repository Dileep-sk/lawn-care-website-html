<script setup>
import { reactive, ref, watch, onMounted } from 'vue'
import { useJobs } from '@/composables/useJobs'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import toastr from 'toastr'
import { STATUS_OPTIONS } from '@/constants/jobStatus'
import MarkNoDropdown from '@/components/MarkNoDropdown.vue'
import CustomerDropdown from '@/components/CustomerDropdown.vue'
import DesignNoDropdown from '@/components/DesignNoDropdown.vue'
import OrderNoDropdown from '@/components/OrderNoDropdown.vue'
import ItemDropdown from '@/components/ItemDropdown.vue'
import ImageUploader from '@/components/ImageUploader.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const jobId = route.params.id || null
const { createJobHandler, getJobDetails } = useJobs()

// --- Form ---
const form = reactive({
    customer_name: '',
    mark_no: '',
    design_no: '',
    images: [],
    quantity: '',
    order_no: '',
    status: '',
    item_name: '',
    matchings: Array.from({ length: 8 }, () => ({ text: '' }))
})

// --- Validation ---
const errors = reactive({})
const touched = reactive({})

const validators = {
    design_no: val => val ? '' : 'Design No is required',
    item_name: val => val ? '' : 'Item Name is required',
    customer_name: val => val ? '' : 'Customer Name is required',
    mark_no: val => val ? '' : 'Mark No is required',
    quantity: val => (!val || isNaN(val) || val <= 0) ? 'Enter a valid quantity' : '',
    order_no: val => val ? '' : 'Order No is required',
    status: val => val === '' ? 'Status is required' : '',
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

// --- Prefill form for edit ---
const editJob = async (id) => {
    if (!id) return
    try {
        const data = await getJobDetails(id)

        form.customer_name = data.customer_name || ''
        form.mark_no = data.mark_no || ''
        form.design_no = data.design_no || ''
        form.item_name = data.item_name || ''
        form.quantity = data.quantity || ''
        form.order_no = data.order_no || ''
        form.status = data.status ?? ''
        form.images = data.images || []

        // Populate matchings if present
        if (data.matchings?.length) {
            form.matchings.forEach((m, index) => {
                form.matchings[index].text = data.matchings[index] || ''
            })
        }
    } catch (err) {
        toastr.error(err.response?.data?.message || 'Failed to load job details', 'Error')
    }
}

// --- Submit ---
const handleSubmit = async () => {
    if (!validateForm()) return

    const formData = new FormData()
    Object.entries(form).forEach(([key, value]) => {
        if (key === 'images') {
            value.forEach(file => formData.append('images[]', file))
        } else if (key === 'matchings') {
            value.forEach((matching, index) => formData.append(`matching_${index + 1}`, matching.text || ''))
        } else {
            formData.append(key, value)
        }
    })

    try {
        await createJobHandler(formData)
    } catch (err) {
        toastr.error(err.response?.data?.message || 'Failed to save job', 'Error')
    }
}

// --- Watchers for validation ---
Object.keys(validators).forEach((field) => {
    watch(
        () => form[field],
        () => validateField(field)
    )
})

// --- Load form if editing ---
onMounted(() => {
    if (jobId) editJob(jobId)
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
                            <div>
                                <CustomerDropdown v-model="form.customer_name" :is-edit="!!jobId" />
                                <p v-if="touched.customer_name && errors.customer_name"
                                    class="text-red-600 text-sm mt-1">
                                    {{ errors.customer_name }}
                                </p>
                            </div>

                            <!-- mark No -->
                            <div>
                                <MarkNoDropdown v-model="form.mark_no" :is-edit="!!jobId" />
                                <p v-if="touched.mark_no && errors.mark_no" class="text-red-600 text-sm mt-1">
                                    {{ errors.mark_no }}
                                </p>
                            </div>
                            <!-- Design No -->
                            <div>
                                <DesignNoDropdown v-model="form.design_no" :is-edit="!!jobId" />
                                <p v-if="touched.design_no && errors.design_no" class="text-red-600 text-sm mt-1">
                                    {{ errors.design_no }}
                                </p>
                            </div>
                            <!-- Image Upload -->

                            <div>
                                <ItemDropdown v-model="form.item_name" :is-edit="!!jobId" />
                                <p v-if="touched.item_name && errors.item_name" class="text-red-600 text-sm mt-1">
                                    {{ errors.item_name }}
                                </p>
                            </div>

                            <!-- Quantity -->
                            <div class="input_box">
                                <BaseInput v-model="form.quantity" label="Quantity" type="number" placeholder="00.00"
                                    @blur="handleBlur('quantity')" />
                                <span v-if="errors.quantity" class="text-red-500">{{ errors.quantity }}</span>
                            </div>

                            <!-- Order No -->
                            <div>
                                <OrderNoDropdown v-model="form.order_no" :is-edit="!!jobId" />
                                <p v-if="touched.order_no && errors.order_no" class="text-red-600 text-sm mt-1">
                                    {{ errors.order_no }}
                                </p>
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
                        <div class="input_box mt-[20px]">
                           <ImageUploader v-model="form.images" />

                            <p v-if="touched.images && errors.images" class="text-red-600 text-sm mt-1">
                                {{ errors.images }}
                            </p>
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
