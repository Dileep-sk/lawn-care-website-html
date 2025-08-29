import { ref, watch } from 'vue'
import {
    fetchOrder,
    updateOrderStatus,
    createOrder,
    getLatestOrderId,
    getOrderById,
    updateOrder,
    deleteOrder,
    getOrderId
} from '@/services/orderService'
import toastr from 'toastr'
import { confirmDialog } from '@/utils/confirmDialog'
import { deleteConfirm } from '@/utils/deleteConfirm'
import jsPDF from 'jspdf'
import { STATUS_OPTIONS } from '@/constants/orderListStatus'

export function useOrders(searchTerm = ref('')) {
    const STATUS_CANCELLED = 4 // order complete
    const orders = ref([])
    const loading = ref(false)
    const statusLoading = ref(false)
    const error = ref(null)
    const currentPage = ref(1)
    const perPage = ref(10)
    const lastPage = ref(1)
    const totalItems = ref(0)

    const handleError = (err, fallbackMsg = 'Something went wrong') => {
        const msg = err?.response?.data?.message || err.message || fallbackMsg
        toastr.error(msg, 'Error')
        error.value = msg
    }

    const loadOrders = async (page = 1, search = '', status = '') => {
        loading.value = true
        error.value = null
        try {
            const params = {
                page,
                per_page: perPage.value,
                search: search || undefined,
                status: status !== '' ? status : undefined,
            }
            const { data } = await fetchOrder(params)
            orders.value = data?.data || []
            currentPage.value = data?.current_page || 1
            lastPage.value = data?.last_page || 1
            totalItems.value = data?.total || 0
        } catch (err) {
            handleError(err, 'Failed to fetch orders')
        } finally {
            loading.value = false
        }
    }

    const getStatusText = (value) =>
        STATUS_OPTIONS.find(opt => opt.value === Number(value))?.label || ''

    const updateStatus = async (id, status, statusText) => {
        statusLoading.value = true
        try {
            const { message } = await updateOrderStatus(id, status)
            toastr.success(message, 'Success')
        } catch (err) {
            handleError(err, 'Failed to update order status')
        } finally {
            statusLoading.value = false
        }
    }

    const handleStatusToggle = async (row, event) => {
        const newValue = Number(event.target.value)
        const statusText = getStatusText(newValue)

        const confirmed = await confirmDialog(statusText, `Yes, ${statusText}`)
        if (!confirmed) {
            event.target.value = row.status
            return
        }

        await updateStatus(row.id, newValue, statusText)

        if (newValue === STATUS_CANCELLED) {
            await loadOrders(currentPage.value, searchTerm.value, STATUS_CANCELLED)
        }
    }

    const apiHandler = async (apiCall, successMsg, fallbackMsg) => {
        loading.value = true
        try {
            const { data } = await apiCall()
            toastr.success(successMsg, 'Success')
            return data
        } catch (err) {
            handleError(err, fallbackMsg)
            throw err
        } finally {
            loading.value = false
        }
    }

    const createOrderHandler = (payload) =>
        apiHandler(() => createOrder(payload), 'Order created successfully!', 'Failed to create order')
            .then(() => loadOrders())

    const updateOrderHandler = (id, payload) =>
        apiHandler(() => updateOrder(id, payload), 'Order updated successfully!', 'Failed to update order')

    const getOrderDetails = async (id) => {
        try {
            const { data } = await getOrderById(id)
            return data
        } catch (err) {
            handleError(err, 'Failed to fetch order')
            throw err
        }
    }

    const handleDelete = async (id) => {
        try {
            if (!(await deleteConfirm('this stock'))) return
            await deleteOrder(id)
            orders.value = orders.value.filter(o => o.id !== id)
            toastr.success('Order deleted successfully', 'Success')
        } catch {
            toastr.error('Something went wrong while deleting.', 'Error')
        }
    }

    const fetchLatestOrderId = async () => {
        try {
            const { latest_order_no } = await getLatestOrderId()
            return latest_order_no
        } catch (err) {
            console.error('Failed to fetch latest order ID:', err)
        }
    }

    const fetchAllOrderNos = async () => {
        try {
            const { data } = await getOrderId()
            return data || []
        } catch {
            toastr.error('Failed to fetch order numbers', 'Error')
            return []
        }
    }

    const exportOrderPDF = (order) => {
        if (!order) return

        const doc = new jsPDF()

        doc.setFontSize(18)
        doc.text('Order Details', 14, 20)

        doc.setFontSize(12)
        doc.text(`Order No: ${order.order_no}`, 14, 30)
        doc.text(`Design No: ${order.design_no}`, 14, 40)
        doc.text(`Item: ${order.item_name}`, 14, 50)
        doc.text(`Quantity: ${order.quantity}`, 14, 60)
        doc.text(`Status: ${order.status}`, 14, 70)

        doc.save(`Order_${order.order_no}.pdf`)
    }


    // Debounced search
    let debounceTimeout
    watch(searchTerm, val => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => loadOrders(1, val), 500)
    })

    return {
        orders,
        loading,
        statusLoading,
        error,
        currentPage,
        perPage,
        lastPage,
        totalItems,
        loadOrders,
        handleStatusToggle,
        createOrderHandler,
        updateOrderHandler,
        getOrderDetails,
        handleDelete,
        fetchLatestOrderId,
        fetchAllOrderNos,
        exportOrderPDF,
    }
}
