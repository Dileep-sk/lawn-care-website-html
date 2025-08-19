<script setup>
import { ref } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import AvailableStocks from '../../components/AvailableStocksTable.vue'
import BaseInput from '@/components/BaseInput.vue'
import SelectDropdown from '@/components/SelectDropdown.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'

// Basic reactive form state
const form = ref({
    customer_name: '',
    order_no: 'ORD1234', // Example, can later generate dynamically
    date: '',
    broker_name: '',
    transport_company: '',
    design_no: 'D1002',
    item_name: '',
    quantity: '',
    rate: '',
    status: 'pending'
})

const statusOptions = [
    { value: 'pending', text: 'Pending' },
    { value: 'completed', text: 'Completed' },
    { value: 'cancelled', text: 'Cancelled' },
]

const handleSubmit = () => {
    console.log('Submitting Order:', form.value)
    // later connect to `useOrders` composable (like you did with useStocks)
}
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] font-bold text-black">Create Order</h1>
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
                            <BaseInput v-model="form.customer_name" label="Customer Name"
                                placeholder="Enter Customer Name" />

                            <BaseInput v-model="form.order_no" label="Order No" readonly />

                            <BaseInput v-model="form.date" label="Date" type="date" />

                            <BaseInput v-model="form.broker_name" label="Broker Name" placeholder="Enter Broker Name" />

                            <BaseInput v-model="form.transport_company" label="Transport Company"
                                placeholder="Transport Company Name" />

                            <BaseInput v-model="form.design_no" label="Design No" placeholder="D1002" />

                            <BaseInput v-model="form.item_name" label="Item Name" placeholder="Enter Item Name" />

                            <BaseInput v-model="form.quantity" label="Quantity" type="number" placeholder="00.00" />

                            <BaseInput v-model="form.rate" label="Rate" type="number" placeholder="00.00" />

                            <!-- Status as select -->
                            <div class="input_box">
                                <SelectDropdown v-model="form.status" label="Status" :options="statusOptions"
                                    placeholder="Select Status" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="submit"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center">
                                Submit Order
                                <img :src="right_arrow_white" alt="" />
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Available Stocks -->
                <AvailableStocks />
            </div>
        </div>
    </AuthLayout>
</template>
