<script setup>
import { onMounted, computed } from 'vue'
import { useStocks } from '@/composables/useStocks'

const { outOfStocks, loading, loadOutOfStocks } = useStocks()

onMounted(loadOutOfStocks)

const hasStocks = computed(() => outOfStocks?.value?.length > 0)
</script>

<template>
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)]
           h-[calc(50vh_-_132px)] rounded-[10px] p-[15px]">
        <h1 class="text-[18px] font-[600] text-[#EB2F06]">Out of Stock</h1>

        <div class="table_container pr-[10px] h-[calc(90%_-_60px)] overflow-y-auto mt-[15px]">
            <table>
                <thead class="!bg-[#eb2f060f]">
                    <tr>
                        <th class="rounded-tl-[5px]">Mark No</th>
                        <th>Design No</th>
                        <th>Item</th>
                        <th class="rounded-tr-[5px]">Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Loading -->
                    <tr v-if="loading">
                        <td colspan="4" class="text-center py-3">Loading...</td>
                    </tr>

                    <!-- Empty -->
                    <tr v-else-if="!hasStocks">
                        <td colspan="4" class="text-center py-3">No out of stock items found</td>
                    </tr>

                    <!-- Data -->
                    <tr v-else v-for="(stock, index) in outOfStocks" :key="index" class="!h-[40px]">
                        <td>{{ stock?.mark_no || '-' }}</td>
                        <td>{{ stock?.design_no || '-' }}</td>
                        <td>{{ stock?.item_name || '-' }}</td>
                        <td>{{ stock?.quantity ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
