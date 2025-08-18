<script setup>
import Loader from './Loader.vue'
import Pagination from './Pagination.vue'
import arrow_black from '@/assets/icons/left-arrow_black.svg'

const props = defineProps({
    data: Array,
    columns: Array,
    loading: Boolean,
    error: String,
    currentPage: Number,
    lastPage: Number
})

const emit = defineEmits(['page-changed'])
</script>

<template>
    <div class="table_container p-[15px]">
        <table>
            <thead>
                <tr>
                    <th v-for="col in columns" :key="col.key" :class="col.thClass">{{ col.label }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="loading">
                    <td :colspan="columns.length" class="text-center">
                        <!-- <Loader /> -->
                    </td>
                </tr>
                <tr v-else-if="error">
                    <td :colspan="columns.length" class="text-center text-red-600 py-4">{{ error }}</td>
                </tr>
                <tr v-else-if="data.length === 0">
                    <td :colspan="columns.length" class="text-center py-4">No records found.</td>
                </tr>
                <tr v-else v-for="row in data" :key="row.id">
                    <td v-for="col in columns" :key="col.key">
                        <slot :name="col.key" :row="row">
                            {{ row[col.key] }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>

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
