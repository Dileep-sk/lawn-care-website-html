import { ref } from 'vue'
import { getItems } from '@/services/itemService'

export function useItems() {
  const options = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchItems = async (onlyActive = false) => {
    loading.value = true
    error.value = null

    try {
      const data = await getItems(onlyActive)
      options.value = data.data || []
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to load items'
    } finally {
      loading.value = false
    }
  }

  return {
    options,
    loading,
    error,
    fetchItems
  }
}
