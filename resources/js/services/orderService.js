import axios from '@/utils/axios'


export const fetchOrder = async (params = {}) => {
    const response = await axios.get('/orders', { params })
    return response.data
}

export const updateOrderStatus = async (id, status) => {
    const response = await axios.put(`/orders/${id}/status`, { status })
    return response.data
}

export const createOrder = async (payload) => {
    const response = await axios.post('/orders', payload)
    return response.data
}

export const getLatestOrderId = async (payload) => {
    const response = await axios.get('/orders/latest-id', payload)
    return response.data
}

export const updateOrder = async (id, payload) => {
    const response = await axios.put(`/orders/${id}`, payload)
    return response.data
}
export const getOrderById = async (id) => {
    const response = await axios.get(`/orders/${id}`)
    return response.data
}

export const deleteOrder = async (id) => {
    const response = await axios.delete(`/orders/${id}`)
    return response.data
}

export const getOrderId = async (params = {}) => {
    const response = await axios.get('/order_id', { params })
    return response.data
}



