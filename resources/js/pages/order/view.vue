<script setup>
import { ref, onMounted, computed } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import arrow_black from '@/assets/icons/left-arrow_black.svg'
import { useOrders } from '@/composables/useOrders'
import { useRoute } from 'vue-router'

const route = useRoute()
const orderId = route.params.id || 1 // fallback if no param

const { getOrderDetails } = useOrders()

const order = ref(null)
const loading = ref(false)
const error = ref(null)

// Define your status options with text and classes
const STATUS_OPTIONS = [
    { text: 'Pending', value: 0, class: 'bg-yellow-100 text-yellow-600' },
    { text: 'Hold', value: 1, class: 'bg-blue-100 text-blue-600' },
    { text: 'Completed', value: 2, class: 'bg-green-100 text-green-600' },
    { text: 'Cancelled', value: 3, class: 'bg-red-600 text-white' }
]

// Computed property to get status object based on order.status value
const orderStatus = computed(() => {
    if (!order.value) return { text: '', class: '' }
    return STATUS_OPTIONS.find(s => s.value === order.value.status) || { text: 'Unknown', class: '' }
})

onMounted(async () => {
    loading.value = true
    try {
        order.value = await getOrderDetails(orderId)
    } catch (err) {
        error.value = err.message || 'Failed to load order details'
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div id="view_order" class="w-full min-h-screen bg-white p-[15px]">
                    <div class="flex justify-between items-center mb-[30px]">
                        <h2 class="font-bold text-[24px]">Order Detail</h2>
                        <div class="flex justify-end mb-6">
                            <router-link :to="{ name: 'order' }"
                                class="flex items-center gap-2 bg-[#1d3f92] text-white font-medium px-4 py-2 rounded hover:bg-[#163273] transition">
                                <img :src="arrow_black" alt="Back" class="w-4 h-4 invert" />
                                Return
                            </router-link>
                        </div>
                    </div>

                    <div class="detail_box bg-[rgba(56,92,76,0.04)] p-[30px]  mx-auto">
                        <template v-if="loading">
                            <p>Loading order details...</p>
                        </template>

                        <template v-else-if="error">
                            <p class="text-red-600">{{ error }}</p>
                        </template>

                        <template v-else-if="order">
                            <ul>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Order No</h6>:
                                    <p class="ml-[20px]">{{ order.order_no }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Customer Name</h6>:
                                    <p class="ml-[20px]">{{ order.customer_name }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Date</h6>:
                                    <p class="ml-[20px]">{{ order.date }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Broker Name</h6>:
                                    <p class="ml-[20px]">{{ order.broker_name }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Transport Company</h6>:
                                    <p class="ml-[20px]">{{ order.transport_company }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Design No</h6>:
                                    <p class="ml-[20px]">{{ order.design_no }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Item Name</h6>:
                                    <p class="ml-[20px]">{{ order.item_name }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Quantity</h6>:
                                    <p class="ml-[20px]">{{ order.quantity }}</p>
                                </li>
                                <li class="flex mb-[12px]">
                                    <h6 class="font-semibold w-[200px]">Rate</h6>:
                                    <p class="ml-[20px]">{{ order.rate }}</p>
                                </li>
                                <li class="flex mb-[12px] items-center">
                                    <h6 class="font-semibold w-[200px]">Status</h6>:
                                    <p class="ml-[20px] px-2 py-1 rounded font-semibold" :class="orderStatus.class">
                                        {{ orderStatus.text }}
                                    </p>
                                </li>
                            </ul>
                        </template>

                        <template v-else>
                            <p>No order data found.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
