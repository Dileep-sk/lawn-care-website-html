import { ref, watch, onMounted } from 'vue'
import Swal from 'sweetalert2'
import toastr from 'toastr'
import { fetchUsers, deleteUser, updateUserStatus } from '@/services/userService'

export function useUser(searchTerm = ref('')) {
    const users = ref([])
    const currentPage = ref(1)
    const lastPage = ref(1)
    const loading = ref(false)
    const error = ref(null)
    let searchTimeout = null

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
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D62925',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, delete it!'
            })

            if (!result.isConfirmed) return

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

            const result = await Swal.fire({
                title: `Are you sure you want to ${statusText} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#05C46B',
                cancelButtonColor: '#aaa',
                confirmButtonText: `Yes, ${statusText} it!`,
            })

            if (!result.isConfirmed) return

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

    // Debounced search with try/catch
    watch(searchTerm, (newValue) => {
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(async () => {
            try {
                await loadUsers(1, newValue)
            } catch (err) {
                error.value = 'Failed to search users.'
                toastr.error('Something went wrong during search.', 'Error')
            }
        }, 500)
    })

    onMounted(() => {
        loadUsers().catch(err => {
            error.value = 'Failed to load users initially.'
            toastr.error('Initial load failed.', 'Error')
        })
    })

    return {
        users,
        currentPage,
        lastPage,
        loading,
        error,
        loadUsers,
        handleDelete,
        handleStatusToggle
    }
}
