<script setup>
import { ref, watch } from 'vue'
import { useStocks } from '@/composables/useStocks'

const props = defineProps({
  designNo: {
    type: [String, Number],
    default: ''
  }
})

const { getAvailableStockByDesignNo } = useStocks()
const availableStocks = ref([])
const loading = ref(false)

watch(() => props.designNo, async (newDesignNo) => {
  if (newDesignNo) {
    loading.value = true
    try {
      const response = await getAvailableStockByDesignNo(newDesignNo)
      availableStocks.value = response || []
    } catch (e) {
      availableStocks.value = []
      console.error('Failed to fetch available stocks:', e)
    } finally {
      loading.value = false
    }
  } else {
    availableStocks.value = []
  }
}, { immediate: true })
</script>

<template>
  <div class="mt-[15px]">
    <h2 class="px-[20px] font-[600]">Available Stocks</h2>

    <div class="table_container p-[15px]">
      <table>
        <thead>
          <tr>
            <th>Mark No</th>
            <th>Design No</th>
            <th>Item</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td colspan="4" class="text-center">Loading...</td>
          </tr>
          <tr v-else-if="availableStocks.length === 0">
            <td colspan="4" class="text-center text-gray-500">No available stock for selected design.</td>
          </tr>
          <tr v-for="(stock, index) in availableStocks" :key="stock.id || index">
            <td>{{ stock.mark_no }}</td>
            <td>{{ stock.design_no }}</td>
            <td>{{ stock.item_name }}</td>
            <td>{{ stock.quantity }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
