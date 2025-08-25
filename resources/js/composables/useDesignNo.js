import { ref } from 'vue'
import { getDesignNos } from '@/services/designNoService'

export function useDesignNo() {
  const options = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchDesignNos = async (activeOnly = false) => {
    loading.value = true
    error.value = null

    try {
      const data = await getDesignNos(activeOnly ? { active: true } : {})

      options.value = data.data || []
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to load design numbers'
    } finally {
      loading.value = false
    }
  }

  return {
    options,
    loading,
    error,
    fetchDesignNos
  }
}
