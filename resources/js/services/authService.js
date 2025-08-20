import axios from '@/utils/axios'

export const loginApi = async (payload) => {
    const response = await axios.post('/login', payload)
    return response.data
}


export const logoutApi = async () => {
    const response = await axios.post('/logout')
    return response.data
}


export const profile = async (payload) => {
    const response = await axios.post('/profile/update', payload)
    return response.data
}
