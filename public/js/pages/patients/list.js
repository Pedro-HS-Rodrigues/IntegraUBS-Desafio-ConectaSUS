import { showListState } from '../../components/listState.js';
import { disablePagination, renderPagination } from '../../components/pagination.js';
import { renderPatients } from '../../components/patientTable.js';
import { listPatients } from '../../services/patientService.js';

const state = {
    search: '',
    page: 1,
    requestId: 0,
    patients: new Map(),
};

export async function loadPatients() {
    const requestId = ++state.requestId;

    showListState('loading');
    disablePagination();

    try {
        const response = await listPatients(state.search, state.page);

        if (requestId !== state.requestId) {
            return;
        }

        if (response.data.length === 0 && response.meta.current_page > response.meta.last_page) {
            state.page = response.meta.last_page;
            loadPatients();
            return;
        }

        state.patients = new Map(response.data.map((patient) => [patient.id, patient]));
        renderPatients(response.data);
        renderPagination(response.meta, response.links);
        showListState(response.data.length === 0 ? 'empty' : 'data');
    } catch {
        if (requestId === state.requestId) {
            showListState('error');
        }
    }
}

export function searchPatients(search) {
    state.search = search;
    state.page = 1;
    loadPatients();
}

export function goToPreviousPage() {
    state.page--;
    loadPatients();
}

export function goToNextPage() {
    state.page++;
    loadPatients();
}

export function getPatient(id) {
    return state.patients.get(id);
}
