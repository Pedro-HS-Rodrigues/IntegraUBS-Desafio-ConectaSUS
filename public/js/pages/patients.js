import { onRetry } from '../components/listState.js';
import { initMessageBanner, showMessage } from '../components/messageBanner.js';
import { initPagination } from '../components/pagination.js';
import { fillHealthUnits, initPatientForm, openPatientForm } from '../components/patientForm.js';
import { initPatientTable } from '../components/patientTable.js';
import { initSearchBar } from '../components/searchBar.js';
import { listHealthUnits } from '../services/healthUnitService.js';
import { savePatient } from './patients/actions.js';
import { getPatient, goToNextPage, goToPreviousPage, loadPatients, searchPatients } from './patients/list.js';

async function loadHealthUnits() {
    try {
        const response = await listHealthUnits();
        fillHealthUnits(response.data);
    } catch (error) {
        showMessage('error', error.message);
    }
}

export function init() {
    initMessageBanner();
    initSearchBar(searchPatients);
    initPagination(goToPreviousPage, goToNextPage);
    initPatientTable((id) => openPatientForm(getPatient(id)));
    initPatientForm(savePatient);
    onRetry(loadPatients);

    loadHealthUnits();
    loadPatients();
}
