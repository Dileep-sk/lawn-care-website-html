<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseTable from '@/components/BaseTable.vue'
import plus from '@/assets/icons/plus.svg'
import search from '@/assets/icons/search.svg'
import edit from '@/assets/icons/edit.svg'
import deletes from '@/assets/icons/delete.svg'
import eye from '@/assets/icons/eye.svg'
import { useServices } from '@/composables/useServices'

const searchTerm = ref('')
const filters = ref({
    status: ''
})

const {
    services,
    loading,
    error,
    currentPage,
    lastPage,
    loadServices,
    handleStatusToggle,
    handleDelete,
} = useServices(searchTerm)

onMounted(() => {
    loadServices(1, searchTerm.value, filters.value.status)
})
watch(() => filters.value.status, () => {
    loadServices(1, searchTerm.value, filters.value.status)
})

</script>
<template>
    <AuthLayout>
        <div class="inner_contant mt-5 w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">

                <!-- Header -->
                <div class="top_header bservice-b p-4 bservice-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-xl text-black font-bold">Service List</h1>

                    <div class="flex gap-4 items-center">
                        <!-- search -->
                        <div class="search_box relative">
                            <img :src="search" class="absolute w-5 left-3 top-3" />
                            <input type="text" v-model="searchTerm" placeholder="Search here.."
                                class="input !bservice-0 !w-[325px] rounded-md !px-10 !h-[45px] bg-[rgba(23,23,23,0.05)]" />
                        </div>
                        <!-- create -->
                        <router-link :to="{ name: 'service-create' }"
                            class="flex gap-2 h-[45px] px-4 font-medium items-center text-white text-[15px] rounded-md bg-[#05C46B]">
                            Create Service
                            <img :src="plus" class="w-[22px]" />
                        </router-link>
                    </div>
                </div>

                <!-- Table -->
                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <BaseTable :data="services" :columns="[
                        { label: 'Service No', key: 'id' },
                        { label: 'Name', key: 'name' },
                        { label: 'Slug', key: 'slug' },
                        { label: 'Banner Title', key: 'banner_title' },
                        { label: 'Status', key: 'status' },
                        { label: 'Edit', key: 'edit', thClass: 'w-[100px]' },
                        { label: 'Delete', key: 'delete', thClass: 'w-[100px]' },
                        { label: 'View', key: 'view', thClass: 'w-[130px]' }
                    ]" :loading="loading" :error="error" :currentPage="currentPage" :lastPage="lastPage"
                        @page-changed="(page) => loadServices(page, searchTerm)">
                        <!-- Status -->
                        <template #status="{ row }">
                            <button @click="handleStatusToggle(row.id, row.status)" :class="[                            
                                'badge',
                                row.status === 1 ? 'full bg-green-500' : 'out_of_stock bg-gray-500'
                            ]">
                                {{ row.status === 1 ? 'Active' : 'Inactive' }}
                            </button>
                        </template>

                        <!-- Edit -->
                        <template #edit="{ row }">
                            <router-link v-if="row && row.id" :to="{ name: 'service-edit', params: { id: row.id } }"
                                class="w-[70px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#1e90ff]">
                                <img :src="edit" class="w-[20px]" />
                                Edit
                            </router-link>
                        </template>

                        <!-- Delete -->
                        <template #delete="{ row }">
                            <button
                                @click="handleDelete(row.id)"
                                class="w-[90px] gap-[5px] h-[35px] flex justify-center items-center text-[15px] rounded-[5px] text-white bg-[#D62925] cursor-pointer">
                                <img :src="deletes" class="w-[20px]" alt="Delete icon" />
                                Delete
                            </button>
                        </template>

                        <!-- View -->
                        <template #view="{ row }">
                            <router-link v-if="row && row.id" :to="{ name: 'service-view', params: { id: row.id } }"
                                class="bg-[#3C40C6] cursor-pointer w-[130px] px-[15px] h-[35px] text-white flex justify-center gap-[5px] items-center rounded-[5px]">
                                <img :src="eye" class="w-[20px]" />
                                View
                            </router-link>
                        </template>
                    </BaseTable>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
