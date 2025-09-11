<script setup>
import { useStocksLog } from '@/composables/useStocksLog'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseTable from '@/components/BaseTable.vue'

const {
    stocks,
    currentPage,
    lastPage,
    loading,
    error,
    search,
    loadStocksLog,
} = useStocksLog()

const loadStocks = (page) => {
    loadStocksLog(page, search.value)
}

const formatDateTime = (dateStr) => {
    const date = new Date(dateStr)
    return new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    }).format(date)
}
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-[100%]">
            <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px] border-b-[#E8F0E2] border border-transparent flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">Stock Log List</h1>

                    <div class="flex gap-[15px] items-center">
                        <div class="search_box relative">
                            <img :src="searchIcon" class="absolute w-[20px] left-[10px] top-[12px]" alt="" />
                            <input type="text" v-model="search" placeholder="Search here.."
                                class="input !border-0 !w-[325px] rounded-[5px] !mt-[0] !px-[40px] !h-[45px] bg-[rgba(23,23,23,0.05)]" />
                        </div>
                    </div>
                </div>

                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <BaseTable :data="stocks" :columns="[
                        { label: 'Mark No', key: 'mark_no_name' },
                        { label: 'Design No', key: 'design_no_name' },
                        { label: 'Item', key: 'item_name' },
                        { label: 'Quantity', key: 'quantity' },
                        { label: 'Log', key: 'message' },
                        { label: 'Created At', key: 'created_at' }
                    ]" :loading="loading" :error="error" :currentPage="currentPage" :lastPage="lastPage"
                        @page-changed="loadStocks">

                        <template #created_at="{ row }">
                            {{ formatDateTime(row.created_at) }}
                        </template>

                        <template #stock_manage="{ row }">
                            <span :class="[
                                'badge px-3 py-1 rounded-full text-white text-sm',
                                row.stock_manage === 1 ? 'bg-green-500' : 'bg-red-500'
                            ]">
                                {{ row.stock_manage === 1 ? 'Stock Add' : 'Stock Remove' }}
                            </span>
                        </template>
                    </BaseTable>

                </div>
            </div>
        </div>
    </AuthLayout>
</template>
