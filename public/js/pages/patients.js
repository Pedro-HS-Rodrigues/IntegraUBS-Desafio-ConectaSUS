import { onRetry, showListState } from '../components/listState.js';
import { renderPatients } from '../components/patientTable.js';
import { initSearchBar } from '../components/searchBar.js';
import { listPatients } from '../services/patientService.js';

const state = {
    search: '',
    requestId: 0,
};

async function loadPatients() {
    const requestId = ++state.requestId;

    showListState('loading');

    try {
        const response = await listPatients(state.search);

        if (requestId !== state.requestId) {
            return;
        }

        renderPatients(response.data);
        showListState(response.data.length === 0 ? 'empty' : 'data');
    } catch {
        if (requestId === state.requestId) {
            showListState('error');
        }
    }
}

export function init() {
    initSearchBar((search) => {
        state.search = search;
        loadPatients();
    });

    onRetry(loadPatients);
    loadPatients();
}
