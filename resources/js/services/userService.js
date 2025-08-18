import axios from 'axios';
import API_BASE_URL from '@/config/api';

export async function fetchUsers(page = 1, search = '') {
    try {
        const response = await axios.get(`${API_BASE_URL}/users`, {
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
        const response = await axios.delete(`${API_BASE_URL}/users/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function updateUserStatus(id, status) {
    try {
        const response = await axios.put(`${API_BASE_URL}/users/${id}/status`, {
            status: status
        })
        return response.data
    } catch (error) {
        throw error
    }
}


export async function createUser(payload) {
    try {
        const response = await axios.post(`${API_BASE_URL}/users`, payload)
        return response.data
    } catch (error) {
        throw error
    }
}

export async function getUserById(id) {
    try {
        const response = await axios.get(`${API_BASE_URL}/users/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
}

export async function updateUser(id, payload) {
    try {
        const response = await axios.put(`${API_BASE_URL}/users/${id}`, payload);
        return response.data;
    } catch (error) {
        throw error;
    }
}

