import axios from '@/utils/axios'

export const fetchJobs = async (params = {}) => {
    const response = await axios.get('/jobs', { params })
    return response.data
}

export const updateJobStatus = async (id, status) => {
    const response = await axios.put(`/jobs/${id}/status`, { status })
    return response.data
}


export const deleteJob = async (id) => {
    const response = await axios.delete(`/jobs/${id}`)
    return response.data
}

export const createJob = async (data) => {
    const response = await axios.post('/jobs', data)
    return response.data
}
