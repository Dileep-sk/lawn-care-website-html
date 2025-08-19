import axios from '@/utils/axios'

export const loginApi = async (credentials) => {
    const response = await axios.post('/login', credentials)
    return response.data
}


export const logoutApi = async () => {
    const response = await axios.post('/logout')
    return response.data
}
