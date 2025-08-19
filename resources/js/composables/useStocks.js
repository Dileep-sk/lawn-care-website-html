import { ref, watch, onMounted } from 'vue'
import { fetchStocks, updateStockStatus } from '@/services/stockService'
import Swal from 'sweetalert2'
import toastr from 'toastr'

export function useStocks() {
    const stocks = ref([])
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

            const result = await Swal.fire({
                title: `Are you sure you want to ${statusText} this stock?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#05C46B',
                cancelButtonColor: '#aaa',
                confirmButtonText: `Yes, ${statusText} it!`,
            })

            if (!result.isConfirmed) return

            await updateStockStatus(id, newStatus)

            const stock = stocks.value.find(s => s.id === id)
            if (stock) stock.status = newStatus

            toastr.success(`Stock ${statusText}d successfully`, 'Success')
        } catch (err) {
            error.value = 'Failed to update status.'
            toastr.error('Something went wrong while updating status.', 'Error')
        }
    }


    let debounceTimeout = null
    watch(search, (val) => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => {
            loadStocks(1, val)
        }, 500)
    })

    onMounted(() => {
        loadStocks()
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
    }
}
