import axios from '@/utils/axios'


export const fetchTransportCompany = async (params = {}) => {
    const response = await axios.get('/transport-company', { params })
    return response.data
}
