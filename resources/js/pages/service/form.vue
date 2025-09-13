<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useServices } from '@/composables/useServices'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import BaseImage from '@/components/BaseImage.vue'
import BaseTextarea from '@/components/BaseTextarea.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'

const router = useRouter()
const route = useRoute()
const { handleCreateService, fetchServiceById, handleUpdateService } = useServices()

const form = ref({
    name: '',
    banner_image: '',
    banner_title: '',
    banner_short_description: '',
    service_title: '',
    service_description: '',
    status: 1,
})

const errors = ref({
    name: '',
    banner_image: '',
    banner_title: '',
    banner_short_description: '',
    service_title: '',
    service_description: '',
})

const isEditMode = ref(false)
const loadingService = ref(false)

const validateField = (key, value) => {
     const trimmed = value != null ? value.toString().trim() : ''
    if (['name', 'banner_image', 'banner_title', 'banner_short_description', 'service_title', 'service_description'].includes(key)) {
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

const isFormValid = computed(() =>
    Object.values(errors.value).every(e => !e) &&    
    form.value.name &&
    form.value.banner_image &&
    form.value.banner_title &&
    form.value.banner_short_description &&
    form.value.service_title &&
    form.value.service_description
)

const loadService = async (id) => {
    loadingService.value = true
    try {
        const response = await fetchServiceById(id)
        if (response.success && response.data) {
            const service = response.data
            form.value.name = service.name || ''
            form.value.banner_image = service.banner_image || ''
            form.value.banner_title = service.banner_title || ''
            form.value.banner_short_description = service.banner_short_description || ''
            form.value.service_title = service.service_title || ''
            form.value.service_description = service.service_description || ''
            form.value.status = service.status ?? 1
            setupValidation()
        } else {
            console.error('Failed to load service data')
        }
    } catch (err) {
        console.error('Error loading service:', err)
    } finally {
        loadingService.value = false
    }
}

const handleSubmit = async () => {
    Object.entries(form.value).forEach(([key, val]) => validateField(key, val))
    if (!isFormValid.value) return

    const formData = new FormData()
    Object.entries(form.value).forEach(([key, val]) => {
        if (val !== null && val !== undefined) {
            formData.append(key, val)
        }
    })

    if (isEditMode.value) {
        await handleUpdateService(route.params.id, formData)
        router.push({ name: 'service' })
    } else {
        await handleCreateService(formData)
        router.push({ name: 'service' })
    }
}

onMounted(() => {
    if (route.params.id) {
        isEditMode.value = true
        loadService(route.params.id)
    } else {
        setupValidation()
    }
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] font-bold text-black">
                        {{ isEditMode.value ? 'Edit Service' : 'Add Service' }}
                    </h1>
                    <router-link :to="{ name: 'service' }"
                        class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                        <img :src="left_arrow" class="w-[20px]" alt="Back" />
                        Back
                    </router-link>
                </div>

                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="handleSubmit" enctype="multipart/form-data">
                        <div class="grid grid-cols-3 gap-[30px]">

                            <div>
                                <BaseInput label="Name" type="text" v-model="form.name" placeholder="Enter Name"
                                :error="errors.name" />
                            </div>

                            <div>
                                <BaseInput label="Banner Title" type="text" v-model="form.banner_title" placeholder="Enter Banner Title"
                                :error="errors.banner_title" />
                            </div>

                            <div>
                                <BaseInput label="Service Title" type="text" v-model="form.service_title" placeholder="Enter Service Title"
                                :error="errors.service_title" />
                            </div>                            
                        </div>
                        <div class="grid grid-cols-2 gap-[30px]">
                            <div class="mt-6">
                                <BaseTextarea label="Banner Description" v-model="form.banner_short_description" placeholder="Enter Banner Description" :error="errors.banner_short_description" />
                            </div>
                            
                            <div class="mt-6">
                                <BaseTextarea label="Service Description" v-model="form.service_description" placeholder="Enter Service Description" :error="errors.service_description" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-[30px]">
                            <div class="mt-6">
                                <BaseImage v-model="form.banner_image" label="Banner Image"  />
                                <p v-if="errors.banner_image" class="text-red-500 text-sm mt-1">{{ errors.banner_image }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center disabled:opacity-50">
                                {{ isEditMode.value ? 'Update Service' : 'Add Service' }}
                                <img :src="right_arrow_white" alt="" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
