<script setup>
import { ref, onMounted, computed } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import arrow_black from '@/assets/icons/left-arrow_black.svg'
import { useOrders } from '@/composables/useOrders'
import { useRoute } from 'vue-router'

import DetailItem from '../../components/DetailItem.vue'

const route = useRoute()
const orderId = route.params.id || 1

const { getOrderDetails } = useOrders()

const order = ref(null)
const loading = ref(false)
const error = ref(null)

const STATUS_OPTIONS = [
    { text: 'Pending', value: 0, class: 'bg-yellow-100 text-yellow-600 px-2 py-1 rounded font-semibold' },
    { text: 'Hold', value: 1, class: 'bg-blue-100 text-blue-600 px-2 py-1 rounded font-semibold' },
    { text: 'Completed', value: 2, class: 'bg-green-100 text-green-600 px-2 py-1 rounded font-semibold' },
    { text: 'Cancelled', value: 3, class: 'bg-red-600 text-white px-2 py-1 rounded font-semibold' }
]

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
        <div class="inner_contant mt-[20px] w-[100%]">
            <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
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

                    <div class="detail_box bg-[rgba(56,92,76,0.04)] p-[30px] mx-auto">
                        <template v-if="loading">
                            <p>Loading order details...</p>
                        </template>

                        <template v-else-if="error">
                            <p class="text-red-600">{{ error }}</p>
                        </template>

                        <template v-else-if="order">
                            <ul>
                                <DetailItem label="Order No" :value="order.order_no" />
                                <DetailItem label="Customer Name" :value="order.customer_name" />
                                <DetailItem label="Date" :value="order.date" />
                                <DetailItem label="Broker Name" :value="order.broker_name" />
                                <DetailItem label="Transport Company" :value="order.transport_company" />
                                <DetailItem label="Design No" :value="order.design_no" />
                                <DetailItem label="Item Name" :value="order.item_name" />
                                <DetailItem label="Quantity" :value="order.quantity" />
                                <DetailItem label="Rate" :value="order.rate" />
                                <DetailItem label="Status" :value="orderStatus.text" :extraClass="orderStatus.class" />
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
