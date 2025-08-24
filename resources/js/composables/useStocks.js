import { ref, watch, onMounted } from 'vue'
import { fetchStocks, updateStockStatus, createStock, getStockById, updateStock, deleteStock, availableStock } from '@/services/stockService'
import { deleteConfirm } from '@/utils/deleteConfirm'
import toastr from 'toastr'
import { confirmDialog } from '@/utils/confirmDialog'


export function useStocks() {
    const stocks = ref([])
    const availableStocks = ref([])
    const loading = ref(false)
    const error = ref(null)
    const currentPage = ref(1)
    const perPage = ref(10)
    const search = ref('')
    const lastPage = ref(1)
    const totalItems = ref(0)

    const loadStocks = async (page = 1, searchTerm = '') => {
        loading.value = true
        error.value = null
        try {
            const params = {
                page,
                per_page: perPage.value,
                search: searchTerm || undefined,
            }
            const data = await fetchStocks(params)

            stocks.value = data.data
            currentPage.value = data.current_page
            lastPage.value = data.last_page
            totalItems.value = data.total
        } catch (err) {
            error.value = err.message || 'Failed to fetch stocks'
            toastr.error('Something went wrong while fetching stocks.', 'Error')
        } finally {
            loading.value = false
        }
    }

    const handleStatusToggle = async (id, currentStatus) => {
        try {
            const newStatus = currentStatus === 1 ? 0 : 1
            const statusText = newStatus === 1 ? 'activate' : 'deactivate'

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

    const handleCreateStock = async (payload) => {
        loading.value = true
        try {
            const response = await createStock(payload)
            toastr.success('Stock created successfully', 'Success')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to create stock.'
            toastr.error(error.value, 'Error')
            throw err
        } finally {
            loading.value = false
        }
    }


    const fetchStockById = async (id) => {
        try {

            return await getStockById(id)
        } catch (err) {
            toastr.error('Failed to fetch user details', 'Error')
            throw err
        }
    }

    const handleUpdateStock = async (id, payload) => {
        loading.value = true
        try {
            const response = await updateStock(id, payload)
            toastr.success('Stock updated successfully', 'Success')
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update stock.'
            toastr.error(error.value, 'Error')
            throw err
        } finally {
            loading.value = false
        }
    }


    const handleDelete = async (id) => {
        try {
            const confirmed = await deleteConfirm('this stock')
            if (!confirmed) return

            await deleteStock(id)
            stocks.value = stocks.value.filter(stocks => stocks.id !== id)
            toastr.success('Stock deleted successfully', 'Success')
        } catch (err) {
            error.value = 'Failed to delete user.'
            toastr.error('Something went wrong while deleting.', 'Error')
        }
    }


    const getAvailableStockByDesignNo = async (designNoId) => {
        if (!designNoId) return
        loading.value = true
        error.value = null
        try {
            const response = await availableStock(designNoId)
            return response?.data?.original || []
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch available stock.'
            toastr.error(error.value, 'Error')
        } finally {
            loading.value = false
        }
    }

    let debounceTimeout = null
    watch(search, (val) => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => {
            loadStocks(1, val)
        }, 500)
    })


    return {
        stocks,
        loading,
        error,
        currentPage,
        perPage,
        search,
        lastPage,
        totalItems,
        loadStocks,
        handleStatusToggle,
        handleCreateStock,
        fetchStockById,
        handleUpdateStock,
        handleDelete,
        getAvailableStockByDesignNo,
    }
}
