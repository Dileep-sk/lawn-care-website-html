import axios from '@/utils/axios'

export const fetchStocksLog = async (params = {}) => {
    const response = await axios.get('/stocks-log', { params })
    return response.data
}
