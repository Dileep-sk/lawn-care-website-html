import { ref } from 'vue'
import axios from '@/utils/axios'

export const useDashboard = () => {
    const dashboardData = ref({})
    const isLoading = ref(false)
    const error = ref(null)

    const fetchDashboardDetails = async (params = {}) => {
        isLoading.value = true
        error.value = null
        try {
            const response = await axios.get('/get-dashboard-details', { params })
            dashboardData.value = response.data
        } catch (err) {
            console.error('Failed to load dashboard data:', err)
            error.value = err
        } finally {
            isLoading.value = false
        }
    }

    return {
        dashboardData,
        isLoading,
        error,
        fetchDashboardDetails
    }
}
