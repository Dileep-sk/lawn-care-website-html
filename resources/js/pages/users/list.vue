<script setup>
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseTable from '@/components/BaseTable.vue'
import { ref } from 'vue'
import { useUser } from '@/composables/useUser'
import plus from '@/assets/icons/plus.svg'
import search from '@/assets/icons/search.svg'
import edit from '@/assets/icons/edit.svg'
import deletes from '@/assets/icons/delete.svg'

const searchTerm = ref('')
const {
    users,
    currentPage,
    lastPage,
    loading,
    error,
    loadUsers,
    handleDelete,
    handleStatusToggle
} = useUser(searchTerm)
</script>
<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-[100%] ">
            <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px] border-b-[#E8F0E2] border border-transparent flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">User List</h1>
                    <div class="flex gap-[15px] items-center">
                        <div class="search_box relative">
                            <img :src="search" class="absolute w-[20px] left-[10px] top-[12px]" alt="">
                            <input type="text" v-model="searchTerm" placeholder="Search here.."
                                class="input !border-0 !w-[325px] rounded-[5px] !mt-[0] !px-[40px] !h-[45px] bg-[rgba(23,23,23,0.05)]" />
                        </div>
                        <router-link :to="{ name: 'users-create' }"
                            class="cursor-pointer flex gap-[5px] h-[45px] px-[15px] font-[500] items-center text-[#fff] text-[15px] rounded-[5px] bg-[#05C46B]">
                            Add New User
                            <img :src="plus" class="w-[22px]" alt="">
                        </router-link>
                    </div>
                </div>

                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <BaseTable :data="users" :columns="[
                        { label: 'User Name', key: 'name' },
                        { label: 'Email', key: 'email' },
                        { label: 'Mobile Number', key: 'mobile_number' },
                        { label: 'Status', key: 'status' },
                        { label: 'Edit', key: 'edit', thClass: 'w-[100px]' },
                        { label: 'Delete', key: 'delete', thClass: 'w-[100px]' }
                    ]" :loading="loading" :error="error" :currentPage="currentPage" :lastPage="lastPage"
                        @page-changed="(page) => loadUsers(page, searchTerm)">
                        <template #status="{ row }">
                            <button @click="handleStatusToggle(row.id, row.status)" class=""
                                :class="row.status === 1 ? 'badge full bg-green-500' : 'badge out_of_stock bg-gray-500'">
                                {{ row.status === 1 ? 'Active' : 'Inactive' }}
                            </button>
                        </template>
                        <template #edit="{ row }">
                            <router-link :to="{ name: 'users-edit', params: { id: row.id } }"
                                class="w-[70px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#1e90ff]">
                                <img :src="edit" class="w-[20px]" alt="">
                                Edit
                            </router-link>

                        </template>

                        <template #delete="{ row }">
                            <button @click="handleDelete(row.id)"
                                class="w-[90px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#D62925]">
                                <img :src="deletes" class="w-[20px]" alt="">
                                Delete
                            </button>
                        </template>
                    </BaseTable>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
