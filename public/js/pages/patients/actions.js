import { showMessage } from '../../components/messageBanner.js';
import { closePatientForm, setFormSubmitting, showFieldErrors } from '../../components/patientForm.js';
import { createPatient, updatePatient } from '../../services/patientService.js';
import { loadPatients } from './list.js';

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
