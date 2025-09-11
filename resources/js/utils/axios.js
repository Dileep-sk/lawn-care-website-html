import axios from 'axios'
import router from '@/router'

const instance = axios.create({
    baseURL: import.meta.env.VITE_APP_URL || 'http://erp.atithibhavan.com/api',
    headers: {
        // 'Content-Type': 'application/json',
        Accept: 'application/json',
    },
})


instance.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    error => Promise.reject(error)
)


instance.interceptors.response.use(
    response => response,
    error => {

        if (error.response && error.response.status === 401) {

            localStorage.removeItem('token')


            if (router.currentRoute.value.name !== 'login') {
                router.push({ name: 'login' })
            }
        }

        return Promise.reject(error)
    }
)

export default instance
