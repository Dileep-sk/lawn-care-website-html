import axios from '@/utils/axios'

export const fetchStocks = async (params = {}) => {
  const response = await axios.get('/stocks', { params })
  return response.data
}

export const updateStockStatus = async (id, status) => {
  const response = await axios.put(`/stocks/${id}/status`, { status })
  return response.data
}
