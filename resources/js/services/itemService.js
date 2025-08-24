import axios from '@/utils/axios'

export const getItems = async (onlyActive = false) => {
  const response = await axios.get('/items', {
    params: { active: onlyActive }
  })
  return response.data
}
