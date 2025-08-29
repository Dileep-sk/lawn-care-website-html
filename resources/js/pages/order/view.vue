<script setup>
import { ref, onMounted, computed } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import { useOrders } from '@/composables/useOrders'
import { useRoute } from 'vue-router'

import DetailItem from '../../components/DetailItem.vue'
import { STATUS_OPTIONS } from '@/constants/orderListStatus'
const route = useRoute()
const orderId = route.params.id || 1

const { getOrderDetails } = useOrders()

const order = ref(null)
const loading = ref(false)
const error = ref(null)

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
                                class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                                <img :src="left_arrow" class="w-[20px]" alt="Back" />
                                Back
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
                                <DetailItem label="Mark No" :value="order.mark_name" />
                                <DetailItem label="Design No" :value="order.design_no" />
                                <DetailItem label="Item Name" :value="order.item_name" />
                                <DetailItem label="Quantity" :value="order.quantity" />
                                <DetailItem label="Rate" :value="order.rate" />
                                <DetailItem label="Status" :value="orderStatus.label" :extraClass="orderStatus.class" />
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
