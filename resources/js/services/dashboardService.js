import axios from '@/utils/axios'

export const fetchDashboardDetails = async (params = {}) => {
    const response = await axios.get('/get-dashboard-details', { params })
    return response.data
}


export const fetchTopDesign = async (params = {}) => {
    const response = await axios.get('/top-designs', { params })
    return response.data
}


export const fetchSalesTrends = async (params = {}) => {
    const response = await axios.get('/sales-trends', { params })
    return response.data
}

