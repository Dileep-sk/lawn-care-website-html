import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'

import { deleteConfirm } from '@/utils/deleteConfirm'
import toastr from 'toastr'
import { fetchUsers, deleteUser, updateUserStatus, createUser, updateUser, getUserById } from '@/services/userService'
import { confirmDialog } from '@/utils/confirmDialog'

export function useUser(searchTerm = ref('')) {
    const users = ref([])
    const currentPage = ref(1)
    const lastPage = ref(1)
    const loading = ref(false)
    const error = ref(null)
    let searchTimeout = null
    const router = useRouter()
    const handleCreateUser = async (payload) => {
        try {
            await createUser(payload)
            toastr.success('User created successfully', 'Success')
            router.push({ name: 'users' })
            return true
        } catch (error) {
            if (error.response && error.response.data?.errors) {
                const errors = error.response.data.errors
                Object.values(errors).forEach(err => toastr.error(err[0], 'Error'))
                return false
            }
        }
    }
    const loadUsers = async (page = 1, search = '') => {
        loading.value = true
        try {
            const data = await fetchUsers(page, search)
            users.value = data.data
            currentPage.value = data.current_page
            lastPage.value = data.last_page
        } catch (err) {
            error.value = 'Failed to fetch users.'
            toastr.error('Something went wrong while fetching users.', 'Error')
        } finally {
            loading.value = false
        }
    }

    const handleDelete = async (id) => {
        try {
            const confirmed = await deleteConfirm('this user')
            if (!confirmed) return

            await deleteUser(id)
            users.value = users.value.filter(user => user.id !== id)
            toastr.success('User deleted successfully', 'Success')
        } catch (err) {
            error.value = 'Failed to delete user.'
            toastr.error('Something went wrong while deleting.', 'Error')
        }
    }

    const handleStatusToggle = async (id, currentStatus) => {
        try {
            const newStatus = currentStatus === 1 ? 0 : 1
            const statusText = newStatus === 1 ? 'activate' : 'deactivate'

            const confirmed = await confirmDialog(statusText, `Yes, ${statusText}`)
            if (!confirmed) return

            await updateUserStatus(id, newStatus)

            const user = users.value.find(user => user.id === id)
            if (user) {
                user.status = newStatus
            }

            toastr.success(`User ${statusText}d successfully`, 'Success')
        } catch (err) {
            error.value = 'Failed to update status.'
            toastr.error('Something went wrong while updating status.', 'Error')
        }
    }

    const fetchUserById = async (id) => {
        try {
            return await getUserById(id)
        } catch (err) {
            toastr.error('Failed to fetch user details', 'Error')
            throw err
        }
    }

    const handleUpdateUser = async (id, payload) => {
        try {
            await updateUser(id, payload)
            router.push({ name: 'users' })
            return true
        } catch (error) {
            if (error.response && error.response.data?.errors) {
                const errors = error.response.data.errors
                Object.values(errors).forEach(err => toastr.error(err[0], 'Error'))
                return false
            }
        }
    }

    watch(
        searchTerm,
        (newValue) => {
            clearTimeout(searchTimeout)
            searchTimeout = setTimeout(() => {
                loadUsers(1, newValue)
            }, 500)
        },
        { immediate: false }
    )




    return {
        users,
        currentPage,
        lastPage,
        loading,
        error,
        loadUsers,
        handleDelete,
        handleStatusToggle,
        handleCreateUser,
        handleUpdateUser,
        fetchUserById,
    }
}
