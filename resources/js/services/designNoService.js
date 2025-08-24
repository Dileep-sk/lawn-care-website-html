import axios from '@/utils/axios'

export const getDesignNos = async (params = {}) => {
  const response = await axios.get('/design_no', { params })
  return response.data
}

