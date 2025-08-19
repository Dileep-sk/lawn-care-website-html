<script setup>
import { ref, watch } from 'vue'
import Pagination from './Pagination.vue'
import arrow_black from '@/assets/icons/left-arrow_black.svg'

const props = defineProps({
    data: Array,
    columns: Array,
    loading: Boolean, // parent-controlled loading
    error: String,
    currentPage: Number,
    lastPage: Number
})

const emit = defineEmits(['page-changed'])

const showLoader = ref(false)

watch(
    () => props.loading,
    (newVal) => {
        if (newVal) {
            showLoader.value = true
            setTimeout(() => {
                showLoader.value = false
            }, 500)
        }
    }
)
</script>

<template>
    <div class="table_container relative p-[15px] min-h-[300px]">
        <!-- Loader Overlay -->
        <div v-if="showLoader"
            class="absolute inset-0 flex flex-col justify-center items-center bg-white bg-opacity-70 z-10">
            <svg class="animate-spin h-8 w-8 text-gray-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-sm text-gray-600">Loading...</span>
        </div>

        <!-- Table -->
        <table class="w-full">
            <thead>
                <tr>
                    <th v-for="col in columns" :key="col.key" :class="col.thClass">
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Error -->
                <tr v-if="!showLoader && error">
                    <td :colspan="columns.length" class="text-center text-red-600 py-4">{{ error }}</td>
                </tr>

                <!-- No Records -->
                <tr v-else-if="!showLoader && data.length === 0">
                    <td :colspan="columns.length" class="text-center py-4">No records found.</td>
                </tr>

                <!-- Data Rows -->
                <tr v-else v-for="(row, index) in data" :key="row?.id ?? index">
                    <td v-for="col in columns" :key="col.key">
                        <slot :name="col.key" :row="row">
                            {{ row?.[col.key] ?? '' }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <Pagination v-if="lastPage > 1" :currentPage="currentPage" :lastPage="lastPage"
            @page-changed="(page) => emit('page-changed', page)">
            <template #prev>
                <img :src="arrow_black" />
            </template>
            <template #next>
                <img :src="arrow_black" class="rotate-[-180deg]" />
            </template>
        </Pagination>
    </div>
</template>
