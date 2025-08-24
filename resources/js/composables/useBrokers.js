import { ref } from "vue"
import { fetchBroker } from "@/services/brokersService"

export function useBrokers() {
  const options = ref([])
  const loading = ref(false)
  const error = ref(null)

  const handleFetchBrokers = async (params = {}) => {
    loading.value = true
    error.value = null
    try {
      const data = await fetchBroker(params)

      // ✅ Normalize response
      if (Array.isArray(data)) {
        options.value = data
      } else if (Array.isArray(data?.data)) {
        options.value = data.data
      } else {
        options.value = []
      }
    } catch (err) {
      error.value = err.message || "Failed to load brokers"
    } finally {
      loading.value = false
    }
  }

  return {
    options,
    loading,
    error,
    handleFetchBrokers,
  }
}
