// resources/js/composables/useCustomers.js
import { ref } from "vue"
import { fetchCustomers } from "@/services/customersService"

export function useCustomers() {
  const options = ref([])
  const loading = ref(false)
  const error = ref(null)

  const handleFetchCustomers = async (activeOnly = false) => {
    loading.value = true
    error.value = null

    try {
      const response = await fetchCustomers(activeOnly ? { active: true } : {})
      if (response.success) {
        options.value = response.data
      } else {
        error.value = "Failed to load customers"
      }
    } catch (err) {
      error.value = err.response?.data?.message || "Error fetching customers"
      console.error("useCustomers error:", err)
    } finally {
      loading.value = false
    }
  }

  return {
    options,
    loading,
    error,
    handleFetchCustomers
  }
}
