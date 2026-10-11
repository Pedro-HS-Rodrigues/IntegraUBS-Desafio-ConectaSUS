import { closeDeleteDialog, openDeleteDialog, setDeleteSubmitting } from '../../components/deleteDialog.js';
import { showMessage } from '../../components/messageBanner.js';
import { closePatientForm, setFormSubmitting, showFieldErrors } from '../../components/patientForm.js';
import { createPatient, deletePatient, updatePatient } from '../../services/patientService.js';
import { getPatient, loadPatients } from './list.js';

let patientToDeleteId = null;

export async function savePatient(id, data) {
    setFormSubmitting(true);

    try {
        const response = id ? await updatePatient(id, data) : await createPatient(data);

        closePatientForm();
        showMessage('success', response.message);
        loadPatients();
    } catch (error) {
        if (error.status === 422) {
            showFieldErrors(error.errors);
            return;
        }

        closePatientForm();
        showMessage('error', error.message);
    } finally {
        setFormSubmitting(false);
    }
}

export function requestPatientDeletion(id) {
    patientToDeleteId = id;
    openDeleteDialog(getPatient(id).name);
}

export async function confirmPatientDeletion() {
    setDeleteSubmitting(true);

    try {
        const response = await deletePatient(patientToDeleteId);

        closeDeleteDialog();
        showMessage('success', response.message);
        loadPatients();
    } catch (error) {
        closeDeleteDialog();
        showMessage('error', error.message);
    } finally {
        setDeleteSubmitting(false);
        patientToDeleteId = null;
    }
}
