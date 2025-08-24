import { ref } from 'vue'
import { fetchMarkNosApi } from '@/services/markNoService'

export function useMarkNo() {
  const options = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchMarkNos = async (activeOnly = false) => {
    loading.value = true
    error.value = null

    try {
      const response = await fetchMarkNosApi(activeOnly ? { active: true } : {})
      if (response.success) {
        options.value = response.data
      } else {
        error.value = 'Failed to load Mark Nos'
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Error fetching Mark Nos'
      console.error('useMarkNo error:', err)
    } finally {
      loading.value = false
    }
  }

  return {
    options,
    loading,
    error,
    fetchMarkNos
  }
}
