import { ref, watch, onMounted } from 'vue'
import { fetchStocksLog } from '@/services/stockLogService'
import toastr from 'toastr'

export function useStocksLog() {
    const stocks = ref([])
    const loading = ref(false)
    const error = ref(null)
    const currentPage = ref(1)
    const perPage = ref(10)
    const search = ref('')
    const lastPage = ref(1)
    const totalItems = ref(0)

    const loadStocksLog = async (page = 1, searchTerm = '') => {
        loading.value = true
        error.value = null
        try {
            const params = {
                page,
                per_page: perPage.value,
                search: searchTerm || undefined,
            }
            const data = await fetchStocksLog(params)

            stocks.value = data.data || data // In case backend sends unpaginated array
            currentPage.value = data.current_page || 1
            lastPage.value = data.last_page || 1
            totalItems.value = data.total || data.data?.length || 0
        } catch (err) {
            error.value = err.message || 'Failed to fetch stocks'
            toastr.error('Something went wrong while fetching stocks.', 'Error')
        } finally {
            loading.value = false
        }
    }

    // Debounce search
    let debounceTimeout = null
    watch(search, (val) => {
        clearTimeout(debounceTimeout)
        debounceTimeout = setTimeout(() => {
            loadStocksLog(1, val)
        }, 500)
    })

    onMounted(() => {
        loadStocksLog()
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
        loadStocksLog,
    }
}
