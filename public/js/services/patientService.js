import { request } from './http.js';

export function listPatients(search, page) {
    const params = new URLSearchParams({ page });

    if (search) {
        params.set('search', search);
    }

    return request('GET', `/api/patients?${params}`);
}
