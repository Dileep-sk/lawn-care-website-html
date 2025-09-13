import axios from '@/utils/axios'


export const fetchService = async (params = {}) => {
    const response = await axios.get('/services', { params })
    return response.data
}

export const updateServiceStatus = async (id, status) => {
    const response = await axios.put(`/services/${id}/status`, { status })
    return response.data
}

export const createService = async (payload) => {
    const response = await axios.post('/services', payload)
    return response.data
}

export const updateService = async (id, payload) => {
    const response = await axios.put(`/services/${id}`, payload)
    return response.data
}
export const getServiceById = async (id) => {
    const response = await axios.get(`/services/${id}`)
    return response.data
}

export const deleteService = async (id) => {
    const response = await axios.delete(`/services/${id}`)
    return response.data
}



