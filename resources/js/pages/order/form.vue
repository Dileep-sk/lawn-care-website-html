<script setup>
import { reactive, onMounted, ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrders } from '@/composables/useOrders'
import AuthLayout from '../../layouts/AuthLayout.vue'
import AvailableStocks from '../../components/AvailableStocksTable.vue'
import BaseInput from '@/components/BaseInput.vue'
import SelectDropdown from '@/components/SelectDropdown.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'
import { formatDateToISO } from '../../helpers/dateFormat'


const { createOrderHandler, fetchLatestOrderId, getOrderDetails, updateOrderHandler } = useOrders()
const router = useRouter()
const route = useRoute()


const orderId = ref(route.params.id || null)
const isEditMode = ref(false)
const isLoading = ref(false)

const form = reactive({
    customer_name: '',
    order_no: '',
    date: '',
    broker_name: '',
    transport_company: '',
    design_no: '',
    item_name: '',
    quantity: '',
    rate: '',
    status: '0',
    message: ''
})

const validationErrors = reactive({})
const touched = reactive({})

const validators = {
    customer_name: val => val ? '' : 'Customer name is required',
    order_no: val => val ? '' : 'Order No is required',
    date: val => val ? '' : 'Date is required',
    broker_name: val => val ? '' : 'Broker Name is required',
    transport_company: val => val ? '' : 'Transport Company is required',
    design_no: val => val ? '' : 'Design No is required',
    item_name: val => val ? '' : 'Item name is required',
    quantity: val => val > 0 ? '' : 'Quantity must be greater than 0',
    rate: val => val > 0 ? '' : 'Rate must be greater than 0',
    status: val => ['0', '1', '2', '3'].includes(val) ? '' : 'Status is required'
}

const validateField = field => {
    validationErrors[field] = validators[field]?.(form[field]) || ''
}

const validateForm = () => {
    Object.keys(validators).forEach(validateField)
    return Object.values(validationErrors).every(error => !error)
}

const markAllTouched = () => {
    Object.keys(validators).forEach(key => {
        touched[key] = true
        validateField(key)
    })
}

const handleSubmit = async () => {
    markAllTouched()
    if (!validateForm()) {
        console.log('Validation failed:', JSON.stringify(validationErrors))
        return
    }

    try {
        if (isEditMode.value) {
            console.log('Updating order:', orderId.value, form)
            await updateOrderHandler(orderId.value, { ...form })
            console.log('Update successful')
        } else {
            console.log('Creating order:', form)
            await createOrderHandler({ ...form })
            console.log('Create successful')
        }
        router.push({ name: 'order' })
    } catch (error) {
        console.error('Order submission failed:', error)
    }
}

onMounted(async () => {
    if (orderId.value) {
        isEditMode.value = true
        isLoading.value = true
        try {
            const order = await getOrderDetails(orderId.value)
            console.log('Fetched order:', order)

            Object.assign(form, {
                customer_name: order.customer_name || '',
                order_no: order.order_no || '',
                date: formatDateToISO(order.date) || '',
                broker_name: order.broker_name || '',
                transport_company: order.transport_company || '',
                design_no: order.design_no || '',
                item_name: order.item_name || '',
                quantity: order.quantity || '',
                rate: order.rate || '',
                status: order.status !== undefined ? String(order.status) : '0',
                message: order.message || ''
            })

            console.log('Form after assignment:', JSON.stringify(form, null, 2))


            Object.keys(validators).forEach(key => {
                touched[key] = true
                validateField(key)
            })

        } catch (e) {
            console.error('Failed to fetch order details:', e)
        } finally {
            isLoading.value = false
        }
    } else {
        form.order_no = await fetchLatestOrderId()
        form.date = new Date().toISOString().split('T')[0]
    }
})


Object.keys(form).forEach((key) => {
    watch(
        () => form[key],
        () => {
            if (touched[key]) {
                validateField(key)
            }
        }
    )
})


const statusOptions = [
    { value: '0', text: 'Pending' },
    { value: '1', text: 'Hold' },
    { value: '2', text: 'Completed' },
    { value: '3', text: 'Cancelled' }
]


const title = computed(() => (isEditMode.value ? 'Update Order' : 'Submit Order'))
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] font-bold text-black">{{ title }}</h1>
                    <router-link :to="{ name: 'order' }"
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
                                <BaseInput v-model="form.customer_name" label="Customer Name"
                                    placeholder="Enter Customer Name" @blur="touched.customer_name = true" />
                                <p v-if="touched.customer_name && validationErrors.customer_name"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.customer_name }}
                                </p>
                            </div>

                            <!-- Order No (readonly) -->
                            <div>
                                <BaseInput v-model="form.order_no" label="Order No" readonly
                                    @blur="touched.order_no = true" />
                                <p v-if="touched.order_no && validationErrors.order_no"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.order_no }}
                                </p>
                            </div>

                            <!-- Date -->
                            <div>
                                <BaseInput v-model="form.date" label="Date" type="date" @blur="touched.date = true" />
                                <p v-if="touched.date && validationErrors.date" class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.date }}
                                </p>
                            </div>

                            <!-- Broker Name -->
                            <div>
                                <BaseInput v-model="form.broker_name" label="Broker Name"
                                    placeholder="Enter Broker Name" @blur="touched.broker_name = true" />
                                <p v-if="touched.broker_name && validationErrors.broker_name"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.broker_name }}
                                </p>
                            </div>

                            <!-- Transport Company -->
                            <div>
                                <BaseInput v-model="form.transport_company" label="Transport Company"
                                    placeholder="Transport Company Name" @blur="touched.transport_company = true" />
                                <p v-if="touched.transport_company && validationErrors.transport_company"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.transport_company }}
                                </p>
                            </div>

                            <!-- Design No -->
                            <div>
                                <BaseInput v-model="form.design_no" label="Design No" placeholder="D1002"
                                    @blur="touched.design_no = true" />
                                <p v-if="touched.design_no && validationErrors.design_no"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.design_no }}
                                </p>
                            </div>

                            <!-- Item Name -->
                            <div>
                                <BaseInput v-model="form.item_name" label="Item Name" placeholder="Enter Item Name"
                                    @blur="touched.item_name = true" />
                                <p v-if="touched.item_name && validationErrors.item_name"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.item_name }}
                                </p>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <BaseInput v-model="form.quantity" label="Quantity" type="number" placeholder="00.00"
                                    @blur="touched.quantity = true" />
                                <p v-if="touched.quantity && validationErrors.quantity"
                                    class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.quantity }}
                                </p>
                            </div>

                            <!-- Rate -->
                            <div>
                                <BaseInput v-model="form.rate" label="Rate" type="number" placeholder="00.00"
                                    @blur="touched.rate = true" />
                                <p v-if="touched.rate && validationErrors.rate" class="text-red-600 text-sm mt-1">
                                    {{ validationErrors.rate }}
                                </p>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="input_box">
                                <SelectDropdown v-model="form.status" label="Status" :options="statusOptions"
                                    placeholder="Select Status" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button :disabled="isLoading" type="submit"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center">
                                {{ title }}
                                <img :src="right_arrow_white" alt="" />
                            </button>
                        </div>
                    </form>
                </div>

                <AvailableStocks />
            </div>
        </div>
    </AuthLayout>
</template>
