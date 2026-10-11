import { request } from './http.js';

export function listHealthUnits() {
    return request('GET', '/api/health-units');
}
