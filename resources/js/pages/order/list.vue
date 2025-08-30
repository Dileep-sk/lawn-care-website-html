<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseTable from '@/components/BaseTable.vue'
import plus from '@/assets/icons/plus.svg'
import search from '@/assets/icons/search.svg'
import edit from '@/assets/icons/edit.svg'
import deletes from '@/assets/icons/delete.svg'
import download from '@/assets/icons/download.svg'
import eye from '@/assets/icons/eye.svg'
import { useOrders } from '@/composables/useOrders'
import { STATUS_OPTIONS } from '@/constants/orderListStatus'
const searchTerm = ref('')
const filters = ref({
    status: ''
})
const STATUS_CANCELLED = 4 // order complete
const {
    orders,
    loading,
    statusLoading,
    error,
    currentPage,
    lastPage,
    loadOrders,
    handleStatusToggle,
    exportOrderPDF,
    handleDelete,
} = useOrders(searchTerm)

onMounted(() => {
    loadOrders(1, searchTerm.value, filters.value.status)
})
watch(() => filters.value.status, () => {
    loadOrders(1, searchTerm.value, filters.value.status)
})

</script>
<template>
    <AuthLayout>
        <div class="inner_contant mt-5 w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">

                <!-- Header -->
                <div class="top_header border-b p-4 border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-xl text-black font-bold">Order List</h1>

                    <div class="flex gap-4 items-center">
                        <!-- search -->
                        <div class="search_box relative">
                            <img :src="search" class="absolute w-5 left-3 top-3" />
                            <input type="text" v-model="searchTerm" placeholder="Search here.."
                                class="input !border-0 !w-[325px] rounded-md !px-10 !h-[45px] bg-[rgba(23,23,23,0.05)]" />
                        </div>
                        <select v-model="filters.status"
                            class="input !w-[250px] !border-0 !mt-[0] !bg-[rgba(23,23,23,0.05)] !h-[45px]">
                            <option value="">All Statuses</option>
                            <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value"
                                :class="opt.class + ' text-left'">
                                {{ opt.text }}
                            </option>
                        </select>
                        <!-- create -->
                        <router-link :to="{ name: 'order-create' }"
                            class="flex gap-2 h-[45px] px-4 font-medium items-center text-white text-[15px] rounded-md bg-[#05C46B]">
                            Create Order
                            <img :src="plus" class="w-[22px]" />
                        </router-link>
                    </div>
                </div>

                <!-- Table -->
                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <BaseTable :data="orders" :columns="[
                        { label: 'Order No', key: 'order_no' },
                        { label: 'Design No', key: 'design_no' },
                        { label: 'Item', key: 'item_name' },
                        { label: 'Quantity', key: 'quantity' },
                        { label: 'Status', key: 'status' },
                        // { label: 'Edit', key: 'edit', thClass: 'w-[100px]' },
                        { label: 'Delete', key: 'delete', thClass: 'w-[100px]' },
                        { label: 'Export', key: 'export', thClass: 'w-[150px]' },
                        { label: 'View Order', key: 'view', thClass: 'w-[130px]' }
                    ]" :loading="loading" :error="error" :currentPage="currentPage" :lastPage="lastPage"
                        @page-changed="(page) => loadOrders(page, searchTerm)">
                        <!-- Status -->
                        <template #status="{ row }">
                            <select :value="row.status" @change="(event) => handleStatusToggle(row, event)"
                                class="px-3 py-2 rounded-md border-2 border-gray-300 w-[150px]"
                                :disabled="row.status === STATUS_CANCELLED">
                                <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </option>
                            </select>
                        </template>


                        <!-- Edit -->
                        <!-- <template #edit="{ row }">
                            <router-link v-if="row && row.id" :to="{ name: 'order-edit', params: { id: row.id } }"
                                class="w-[70px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#1e90ff]">
                                <img :src="edit" class="w-[20px]" />
                                Edit
                            </router-link>
                        </template> -->

                        <!-- Delete -->
                        <template #delete="{ row }">
                            <button :disabled="row.status === STATUS_CANCELLED || statusLoading"
                                @click="row.status !== STATUS_CANCELLED && handleDelete(row.id)"
                                class="w-[90px] gap-[5px] h-[35px] flex justify-center text-[15px] items-center rounded-[5px]"
                                :class="[
                                    'text-white',
                                    row.status === STATUS_CANCELLED || statusLoading
                                        ? 'bg-gray-400 cursor-not-allowed opacity-60'
                                        : 'bg-[#D62925]'
                                ]">
                                <img :src="deletes" class="w-[20px]" />
                                Delete
                            </button>
                        </template>


                        <!-- Export -->
                        <template #export="{ row }">
                            <button v-if="row && row.id" @click="exportOrderPDF(row)"
                                class="bg-[#0A3D62] px-[15px] h-[35px] text-white flex justify-center gap-[5px] items-center rounded-[5px]">
                                <img :src="download" class="w-[20px]" />
                                Export PDF
                            </button>
                        </template>

                        <!-- View -->
                        <template #view="{ row }">
                            <router-link v-if="row && row.id" :to="{ name: 'order-view', params: { id: row.id } }"
                                class="bg-[#3C40C6] cursor-pointer w-[130px] px-[15px] h-[35px] text-white flex justify-center gap-[5px] items-center rounded-[5px]">
                                <img :src="eye" class="w-[20px]" />
                                View Order
                            </router-link>
                        </template>
                    </BaseTable>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
