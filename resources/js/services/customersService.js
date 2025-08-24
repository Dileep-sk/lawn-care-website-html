import axios from '@/utils/axios'


export const fetchCustomers = async (params = {}) => {
    const response = await axios.get('/customers', { params })
    return response.data
}
