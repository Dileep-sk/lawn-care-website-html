import { ref } from "vue"
import { fetchTransportCompany } from "@/services/transportCompanyService"

export function useTransportCompanies() {
    const options = ref([])
    const loading = ref(false)
    const error = ref(null)

    const handleFetchTransportCompanies = async (params = {}) => {
        loading.value = true
        error.value = null
        try {
            const data = await fetchTransportCompany(params)

            // ✅ Ensure we always store an array
            options.value = Array.isArray(data)
                ? data
                : (data?.data || [])   // adjust if API response is wrapped in { data: [...] }
        } catch (err) {
            error.value = err.message || "Failed to load transport companies"
        } finally {
            loading.value = false
        }
    }

    return {
        options,
        loading,
        error,
        handleFetchTransportCompanies,
    }
}
