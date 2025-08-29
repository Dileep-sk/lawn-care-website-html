import axios from '@/utils/axios'

export const fetchDashboardDetails = async (params = {}) => {
    const response = await axios.get('/get-dashboard-details', { params })
    return response.data
}

