import { ref } from "vue"
import { useRouter } from "vue-router"
import { loginApi, logoutApi, profile } from "@/services/authService"
import toastr from 'toastr'
export function useAuth() {
    const router = useRouter()
    const loading = ref(false)
    const error = ref(null)
    const success = ref(null)

    const login = async ({ email, password }) => {
        loading.value = true
        error.value = null

        try {
            const response = await loginApi({ email, password })
            localStorage.setItem('token', response.data.token)
            localStorage.setItem('user', JSON.stringify(response.data.user))
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
            localStorage.removeItem('user')
            loading.value = false
            router.push({ name: 'login' })
        }
    }

    const updateProfile = async (data) => {
        loading.value = true
        error.value = null
        success.value = null

        try {
            const res = await profile(data)
            success.value = res.message || 'Profile updated successfully.'
            toastr.success(success.value)
            const existingUser = JSON.parse(localStorage.getItem('user'))
            localStorage.setItem('user', JSON.stringify({ ...existingUser, email: data.email }))

            // Return true + whether password was updated
            const passwordUpdated = !!(data.old_password && data.new_password && data.new_password_confirmation)
            return { success: true, passwordUpdated }
        } catch (err) {
            if (err.response?.status === 422) {
                error.value = err.response.data.errors
                toastr.error(Object.values(error.value).flat().join(', '))
            } else {
                const message = err.response?.data?.error || 'Something went wrong'
                error.value = { general: message }
                toastr.error(message)
            }
            return { success: false, passwordUpdated: false }
        } finally {
            loading.value = false
        }
    }



    return {
        login,
        logout,
        updateProfile,
        loading,
        error,
        success
    }
}
