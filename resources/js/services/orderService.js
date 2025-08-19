import axios from '@/utils/axios'


export const fetchOrder = async (params = {}) => {
    const response = await axios.get('/orders', { params })
    return response.data
}

export const updateOrderStatus = async (id, status) => {
    const response = await axios.put(`/orders/${id}/status`, { status })
    return response.data
}
