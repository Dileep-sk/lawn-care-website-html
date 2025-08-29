<script setup>
import { reactive, onMounted, watch } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import search from '@/assets/icons/search.svg'
import plus from '@/assets/icons/plus.svg'
import edit from '@/assets/icons/edit.svg'
import deletes from '@/assets/icons/delete.svg'
import eye from '@/assets/icons/eye.svg'
import BaseTable from '@/components/BaseTable.vue'
import { useJobs } from '@/composables/useJobs'
import { STATUS_OPTIONS } from '@/constants/jobStatus'

const MOVE_JOB_IN_STOCK = 4 // Stocked


const filters = reactive({
    search: '',
    status: ''
})

const {
    loadJobs,
    jobs,
    loading,
    error,
    currentPage,
    lastPage,
    handleDeleteJob,
    handleStatusChange,
} = useJobs()

watch(filters, ({ search, status }) => {
    loadJobs(1, search, status)
})

onMounted(() => {
    loadJobs()
})

</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div
                    class="top_header border-b p-[15px] border-b-[#E8F0E2] border border-transparent flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">Job List</h1>

                    <div class="flex gap-[15px] items-center">
                        <!-- Search Box -->
                        <div class="search_box relative">
                            <img :src="search" class="absolute w-[20px] left-[10px] top-[12px]" alt="Search Icon" />
                            <input v-model="filters.search" type="text" placeholder="Search here.."
                                class="input !border-0 !w-[325px] rounded-[5px] !mt-[0] !px-[40px] !h-[45px] bg-[rgba(23,23,23,0.05)]" />
                        </div>
                        <!-- Status Dropdown -->

                        <select v-model="filters.status"
                            class="input !w-[250px] !border-0 !mt-[0] !bg-[rgba(23,23,23,0.05)] !h-[45px]">
                            <option value="">All Status</option>
                            <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>


                        <!-- Create Job Button -->
                        <router-link :to="{ name: 'jobs-create' }"
                            class="flex items-center gap-[5px] h-[45px] px-[15px] text-[15px] font-[500] text-white rounded-[5px] bg-[#05C46B]">
                            Create Job
                            <img :src="plus" class="w-[22px]" alt="Plus Icon" />
                        </router-link>
                    </div>
                </div>

                <!-- Table -->
                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <BaseTable :data="jobs" :columns="[
                        { label: 'Job No', key: 'id' },
                        { label: 'Mark No', key: 'mark_no' },
                        { label: 'Design No', key: 'design_no' },
                        { label: 'Item Name', key: 'item_name' },
                        { label: 'Order No', key: 'order_no' },
                        { label: 'Quantity', key: 'quantity' },
                        { label: 'Status', key: 'status' },
                        { label: 'Edit', key: 'edit', thClass: 'w-[100px]' },
                        { label: 'Delete', key: 'delete', thClass: 'w-[100px]' },
                        { label: 'View Order', key: 'view_order', thClass: 'w-[130px]' }
                    ]" :loading="loading" :error="error" :currentPage="currentPage" :lastPage="lastPage"
                        @page-changed="(page) => loadJobs(page, filters.search, filters.status)">

                        <!-- Status Column -->
                        <template #status="{ row }">
                            <select :value="row.status" @change="(event) => handleStatusChange(row, event)"
                                class="px-3 py-2 rounded-md border-2 border-gray-300 w-[150px]"
                                :disabled="row.status === MOVE_JOB_IN_STOCK">
                                <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </option>
                            </select>
                        </template>

                        <!-- Edit Column -->
                        <template #edit="{ row }">
                            <router-link :to="{ name: 'jobs-edit', params: { id: row.id } }"
                                class="w-[70px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#1e90ff]">
                                <img :src="edit" class="w-[20px]" alt="Edit Icon" />
                                Edit
                            </router-link>
                        </template>


                        <!-- Delete Column -->

                        <template #delete="{ row }">
                            <button @click="handleDeleteJob(row.id)" :disabled="row.status === MOVE_JOB_IN_STOCK"
                                class="w-[90px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px]"
                                :class="row.status === MOVE_JOB_IN_STOCK ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#D62925] cursor-pointer'">
                                <img :src="deletes" class="w-[20px]" alt="" />Delete
                            </button>
                        </template>



                        <!-- View Order Column -->
                        <template #view_order="{ row }">
                            <router-link v-if="row && row.id" :to="{ name: 'jobs-view', params: { id: row.id } }"
                                class="bg-[#3C40C6] cursor-pointer w-[130px] h-[35px] text-white flex justify-center text-[15px] items-center gap-[5px] rounded-[5px]">
                                <img :src="eye" class="w-[20px]" alt="">View Job
                            </router-link>
                        </template>
                    </BaseTable>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
