import { ref } from 'vue'
import { fetchJobs, updateJobStatus, deleteJob, createJob, getJobById } from '@/services/jobService'
import toastr from 'toastr'
import { deleteConfirm } from '@/utils/deleteConfirm'
import { confirmDialog } from '@/utils/confirmDialog'
import { STATUS_OPTIONS } from '@/constants/jobStatus'
import { useRouter } from 'vue-router'
export function useJobs() {
    const jobs = ref([])
    const loading = ref(false)
    const statusLoading = ref(false)
    const error = ref(null)
    const perPage = ref(10)
    const currentPage = ref(1)
    const lastPage = ref(1)
    const totalItems = ref(0)
    const deleteLoading = ref(false)
    const router = useRouter()

    const loadJobs = async (page = 1, search = '', status = '') => {
        loading.value = true
        error.value = null

        try {
            const params = {
                page,
                per_page: perPage.value,
                search: search || undefined,
                status: status || undefined,
            }

            const data = await fetchJobs(params)

            jobs.value = data.data?.data || []
            currentPage.value = data.data?.current_page || 1
            lastPage.value = data.data?.last_page || 1
            totalItems.value = data.data?.total || 0
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to fetch jobs'
            toastr.error(error.value, 'Error')
        } finally {
            loading.value = false
        }
    }

    const getStatusText = (value) => {
        const found = STATUS_OPTIONS.find(opt => opt.value === Number(value))
        return found ? found.label : ''
    }

    const handleStatusChange = async (row, event) => {
        const newValue = Number(event.target.value)
        const statusText = getStatusText(newValue)

        const confirmed = await confirmDialog(statusText, `Yes, ${statusText}`)
        if (!confirmed) {
            event.target.value = row.status
            return
        }
        await updateStatus(row.id, newValue, statusText)
    }

    const updateStatus = async (id, status, statusText) => {
        statusLoading.value = true
        error.value = null
        try {
            const response = await updateJobStatus(id, status)
            toastr.success(response.message, 'Success')
            const job = jobs.value.find(job => job.id === id)
            if (job) {
                job.status = status
            }
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to update job status'
            toastr.error(error.value, 'Error')
        } finally {
            statusLoading.value = false
        }
    }

    const handleDeleteJob = async (id) => {
        deleteLoading.value = true
        error.value = null
        try {
            const confirmed = await deleteConfirm('this user')
            if (!confirmed) return

            const response = await deleteJob(id)
            toastr.success(response.message || 'Job deleted successfully', 'Success')
            jobs.value = jobs.value.filter(job => job.id !== id)
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to delete job'
            toastr.error(error.value, 'Error')
        } finally {
            deleteLoading.value = false
        }
    }

    const createJobHandler = async (formData) => {
        try {
            if (formData instanceof FormData) {
                console.log("Creating job with FormData:", [...formData.entries()])
            } else {
                console.log("Creating job with JSON:", formData)
            }
            const response = await createJob(formData)
            jobs.value.push(response.data)
            router.push({ name: 'jobs' })

        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to create job'
            toastr.error(error.value, 'Error')
        }
    }
    const getJobDetails = async (id) => {
        try {
            const data = await getJobById(id)

            return data
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch order'
            toastr.error(error.value, 'Error')
            throw err
        }
    }

    return {
        jobs,
        loading,
        statusLoading,
        error,
        currentPage,
        lastPage,
        totalItems,
        loadJobs,
        updateStatus,
        handleDeleteJob,
        getStatusText,
        handleStatusChange,
        createJobHandler,
        getJobDetails,
    }
}
