import { ref, watch } from 'vue'
import {
    fetchService,
    createService,
    getServiceById,
    updateService,
    deleteService,
} from '@/services/servicesService'
import toastr from 'toastr'
import { confirmDialog } from '@/utils/confirmDialog'
import { deleteConfirm } from '@/utils/deleteConfirm'

export function useServices(searchTerm = ref('')) {
    const services = ref([])
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

    const loadServices = async (page = 1, search = '', status = '') => {
        loading.value = true
        error.value = null
        try {
            const params = {
                page,
                per_page: perPage.value,
                search: search || undefined,
                status: status !== '' ? status : undefined,
            }
            const response = await fetchService(params)
            services.value = response.data || []
            currentPage.value = response.current_page || 1
            lastPage.value = response.last_page || 1
            totalItems.value = response.total || 0
        } catch (err) {
            handleError(err, 'Failed to fetch services')
        } finally {
            loading.value = false
        }
    }

    const handleStatusToggle = async (id, currentStatus) => {
        try {
            const newStatus = currentStatus === 1 ? 0 : 1
            const statusText = newStatus === 1 ? 'active' : 'inactive'

            const confirmed = await confirmDialog(statusText, `Yes, ${statusText}`)
            if (!confirmed) return

            await updateStockStatus(id, newStatus)

            const stock = stocks.value.find(s => s.id === id)
            if (stock) stock.status = newStatus

            toastr.success(`Stock ${statusText}d successfully`, 'Success')
        } catch (err) {
            error.value = 'Failed to update status.'
            toastr.error('Something went wrong while updating status.', 'Error')
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

    const handleCreateService = (payload) =>
        apiHandler(() => createService(payload), 'Service created successfully!', 'Failed to create service')
            .then(() => loadServices())

    const updateServiceHandler = (id, payload) =>
        apiHandler(() => updateService(id, payload), 'Service updated successfully!', 'Failed to update service')

    const getServiceDetails = async (id) => {
        try {
            const { data } = await getServiceById(id)
            return data
        } catch (err) {
            handleError(err, 'Failed to fetch service')
            throw err
        }
    }

    const handleDelete = async (id) => {
        try {
            if (!(await deleteConfirm('this stock'))) return
            await deleteService(id)
            services.value = services.value.filter(o => o.id !== id)
            toastr.success('Service deleted successfully', 'Success')
        } catch {
            toastr.error('Something went wrong while deleting.', 'Error')
        }
    }

    const fetchServiceById = async () => {
        try {
            const { latest_service_no } = await getServiceById()
            return latest_service_no
        } catch (err) {
            console.error('Failed to fetch latest service ID:', err)
        }
    }


    // Debounced search
    let debounceTimeout
    watch(searchTerm, val => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => loadServices(1, val), 500)
    })

    return {
        services,
        loading,
        statusLoading,
        error,
        currentPage,
        perPage,
        lastPage,
        totalItems,
        loadServices,
        handleStatusToggle,
        handleCreateService,
        updateServiceHandler,
        getServiceDetails,
        handleDelete,
        fetchServiceById,
    }
}
