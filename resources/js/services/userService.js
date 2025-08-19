import axios from '@/utils/axios'

export async function fetchUsers(page = 1, search = '') {
    try {
        const response = await axios.get(`/users`, {
            params: {
                page,
                search,
            },
        })
        return response.data
    } catch (error) {
        throw error
    }
}


export async function deleteUser(id) {
    try {
        const response = await axios.delete(`/users/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function updateUserStatus(id, status) {
    try {
        const response = await axios.put(`/users/${id}/status`, {
            status: status
        })
        return response.data
    } catch (error) {
        throw error
    }
}


export async function createUser(payload) {
    try {
        const response = await axios.post(`/users`, payload)
        return response.data
    } catch (error) {
        throw error
    }
}

export async function getUserById(id) {
    try {
        const response = await axios.get(`/users/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function updateUser(id, payload) {
    try {
        const response = await axios.put(`/users/${id}`, payload);
        return response.data;
    } catch (error) {
        throw error;
    }
}

