import axios from '@/utils/axios'

export const fetchStocks = async (params = {}) => {
    const response = await axios.get('/stocks', { params })
    return response.data
}

export const updateStockStatus = async (id, status) => {
    const response = await axios.put(`/stocks/${id}/status`, { status })
    return response.data
}

export const createStock = async (payload) => {
    const response = await axios.post('/stocks', payload)
    return response.data
}


export async function getStockById(id) {
    try {
        const response = await axios.get(`/stocks/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function updateStock(id, payload) {
    try {
        const response = await axios.put(`/stocks/${id}`, payload);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function deleteStock(id) {
    try {
        const response = await axios.delete(`/stocks/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}
