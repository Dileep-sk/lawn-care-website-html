import axios from '@/utils/axios'


export const fetchBroker = async (params = {}) => {
    const response = await axios.get('/broker', { params })
    return response.data
}
