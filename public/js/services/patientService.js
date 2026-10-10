import { request } from './http.js';

export function listPatients(search, page) {
    const params = new URLSearchParams({ page });

    if (search) {
        params.set('search', search);
    }

    return request('GET', `/api/patients?${params}`);
}

export function createPatient(data) {
    return request('POST', '/api/patients', data);
}

export function updatePatient(id, data) {
    return request('PUT', `/api/patients/${id}`, data);
}

export function deletePatient(id) {
    return request('DELETE', `/api/patients/${id}`);
}
