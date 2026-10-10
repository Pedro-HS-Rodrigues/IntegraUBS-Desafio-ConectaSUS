import { request } from './http.js';

export function listPatients(search) {
    const params = new URLSearchParams();

    if (search) {
        params.set('search', search);
    }

    const query = params.toString();

    return request('GET', query ? `/api/patients?${query}` : '/api/patients');
}
