import axios from '@/utils/axios'


export const fetchOrder = async (params = {}) => {
    const response = await axios.get('/orders', { params })
    return response.data
}
