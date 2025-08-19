import { ref, watch, onMounted } from 'vue'
import { fetchOrder, updateOrderStatus } from '@/services/orderService'
import toastr from 'toastr'
import { confirmDialog } from '@/utils/confirmDialog'


export function useOrders(searchTerm = ref('')) {
    const orders = ref([])
    const loading = ref(false)
    const error = ref(null)
    const currentPage = ref(1)
    const perPage = ref(10)
    const lastPage = ref(1)
    const totalItems = ref(0)

    const loadOrders = async (page = 1, search = '') => {
        loading.value = true
        error.value = null
        try {
            const params = {
                page,
                per_page: perPage.value,
                search: search || undefined,
            }

            const data = await fetchOrder(params)
            console.log("Order API Response:", data)

            orders.value = data.data?.data || []
            currentPage.value = data.data?.current_page || 1
            lastPage.value = data.data?.last_page || 1
            totalItems.value = data.data?.total || 0
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to fetch orders'
            toastr.error(error.value, 'Error')
        } finally {
            loading.value = false
        }
    }

    const handleStatusToggle = async (row, newStatus) => {
        const oldStatus = row.status
        const statusText =
            newStatus === 0 ? 'set Pending' :
                newStatus === 1 ? 'put on Hold' :
                    'mark as Success'

        const confirmed = await confirmDialog(statusText, `Yes, ${statusText}`)
        if (!confirmed) return

        row.status = newStatus

        try {
            await updateOrderStatus(row.id, newStatus)
            toastr.success(`Order successfully ${statusText}`, 'Success')
        } catch (err) {
            row.status = oldStatus
            toastr.error(
                err.response?.data?.message || 'Failed to update order status',
                'Error'
            )
        }
    }

    // 🔹 debounce search
    let debounceTimeout = null
    watch(searchTerm, (val) => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => {
            loadOrders(1, val)
        }, 500)
    })

    // 🔹 initial load
    onMounted(() => {
        loadOrders()
    })

    return {
        orders,
        loading,
        error,
        currentPage,
        perPage,
        lastPage,
        totalItems,
        loadOrders,
        handleStatusToggle,
    }
}
