import axios from '@/utils/axios'


export const fetchMarkNosApi = async (params = {}) => {
  const response = await axios.get('/mark_no', { params })
  return response.data
}
