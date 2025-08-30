import { ref } from 'vue'
import axios from '@/utils/axios'

export const useDashboard = () => {
  const dashboardData = ref({})
  const isLoading = ref(false)
  const error = ref(null)
  const topDesigns = ref([])

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

  const fetchTopDesigns = async () => {
    try {
      const response = await axios.get('/top-designs')
      topDesigns.value = response.data?.data || []
    } catch (err) {
      console.error('Failed to fetch top designs:', err)
      error.value = err
    }
  }

  const fetchSalesTrends = async (params = {}) => {
    isLoading.value = true
    error.value = null
    try {
      const response = await axios.get('/sales-trends', { params })
      return response.data
    } catch (err) {
      console.error('Failed to fetch sales trends:', err)
      error.value = err
      return null
    } finally {
      isLoading.value = false
    }
  }

  return {
    dashboardData,
    topDesigns,
    isLoading,
    error,
    fetchDashboardDetails,
    fetchSalesTrends,
    fetchTopDesigns
  }
}
