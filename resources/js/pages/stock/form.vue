<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useStocks } from '@/composables/useStocks'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'
import MarkNoDropdown from '@/components/MarkNoDropdown.vue'
import DesignNoDropdown from '@/components/DesignNoDropdown.vue'
import ItemDropdown from '@/components/ItemDropdown.vue'

const router = useRouter()
const route = useRoute()
const { handleCreateStock, fetchStockById, handleUpdateStock } = useStocks()

const form = ref({
    mark_no: '',
    design_no: '',
    item_name: '',
    quantity: '',
    status: 1,
    stock_manage: 1,
})

const errors = ref({
    mark_no: '',
    design_no: '',
    item_name: '',
    quantity: '',
})

const isEditMode = ref(false)
const loadingStock = ref(false)

const validateField = (key, value) => {
    const trimmed = value.toString().trim()
    if (['mark_no', 'design_no', 'item_name'].includes(key)) {
        errors.value[key] = trimmed ? '' : 'This field is required'
    } else if (key === 'quantity') {
        const number = Number(value)
        if (!trimmed) {
            errors.value.quantity = 'This field is required'
        } else {
            errors.value.quantity = ''
        }
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
    form.value.mark_no &&
    form.value.design_no &&
    form.value.item_name &&
    form.value.quantity
)

const loadStock = async (id) => {
    loadingStock.value = true
    try {
        const response = await fetchStockById(id)
        if (response.success && response.data) {
            const stock = response.data
            form.value.mark_no = stock.mark_no || ''
            form.value.design_no = stock.design_no || ''
            form.value.item_name = stock.item_name || ''
            form.value.quantity = Number(stock.quantity) || ''
            form.value.status = stock.status ?? 1
            setupValidation()
        } else {
            console.error('Failed to load stock data')
        }
    } catch (err) {
        console.error('Error loading stock:', err)
    } finally {
        loadingStock.value = false
    }
}

const handleSubmit = async () => {
    Object.entries(form.value).forEach(([key, val]) => validateField(key, val))
    if (!isFormValid.value) return

    if (isEditMode.value) {
        await handleUpdateStock(route.params.id, form.value)
        router.push({ name: 'stock' })
    } else {
        await handleCreateStock(form.value)
        router.push({ name: 'stock' })
    }
}

onMounted(() => {
    if (route.params.id) {
        isEditMode.value = true
        loadStock(route.params.id)
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
                        {{ isEditMode.value ? 'Edit Stock' : 'Add Stock' }}
                    </h1>
                    <router-link :to="{ name: 'stock' }"
                        class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                        <img :src="left_arrow" class="w-[20px]" alt="Back" />
                        Back
                    </router-link>
                </div>

                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="handleSubmit">
                        <div class="grid grid-cols-3 gap-[30px]">

                            <div>
                                <MarkNoDropdown v-model="form.mark_no" />
                                <p v-if="errors.mark_no" class="text-red-600 text-sm mt-1">
                                    {{ errors.mark_no }}
                                </p>
                            </div>

                            <div>
                                <DesignNoDropdown v-model="form.design_no" />
                                <p v-if="errors.design_no && errors.design_no" class="text-red-600 text-sm mt-1">
                                    {{ errors.design_no }}
                                </p>
                            </div>

                            <div>
                                <ItemDropdown v-model="form.item_name" />
                                <p v-if="errors.item_name && errors.item_name" class="text-red-600 text-sm mt-1">
                                    {{ errors.item_name }}
                                </p>
                            </div>



                            <BaseInput label="Quantity" type="number" v-model="form.quantity" placeholder="00.00"
                                :error="errors.quantity" />

                            <div class="flex flex-col">
                                <label class="text-sm font-medium mb-1">Action</label>
                                <select v-model="form.stock_manage" class="input w-full">
                                    <option :value="1">Add</option>
                                    <option :value="0">Remove</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center disabled:opacity-50">
                                {{ isEditMode.value ? 'Update Stock' : 'Add Stock' }}
                                <img :src="right_arrow_white" alt="" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
