import { ref } from "vue";
import { useRouter } from "vue-router";
import { loginApi } from "@/services/authService";

export function useAuth() {
    const router = useRouter()
    const loading = ref(false)
    const error = ref(null)

    const login = async ({ email, password }) => {
        loading.value = true
        error.value = null

        try {
            const response = await loginApi({ email, password })
            localStorage.setItem('token', response.data.token)
            router.push({ name: 'dashboard' })
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed'
            throw err
        } finally {
            loading.value = false
        }
    }

    const logout = async () => {
        loading.value = true
        error.value = null

        try {
            await logoutApi()
        } catch (err) {
            console.error('Logout error:', err)
        } finally {
            localStorage.removeItem('token')
            loading.value = false
            router.push({ name: 'login' })
        }
    }

    return {
        login,
        loading,
        error,
        logout,
    }
}
