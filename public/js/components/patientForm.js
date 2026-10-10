import { formatCpf, formatPhone } from '../utils/formatters.js';

const patientDialog = document.getElementById('patientDialog');
const patientForm = document.getElementById('patientForm');
const formTitle = document.getElementById('formTitle');
const patientId = document.getElementById('patientId');
const healthUnitSelect = document.getElementById('healthUnit');
const submitButton = document.getElementById('submitButton');
const cancelButton = document.getElementById('cancelButton');
const closeFormButton = document.getElementById('closeFormButton');
const newPatientButton = document.getElementById('newPatientButton');

const fields = {
    name: { input: document.getElementById('name'), error: document.getElementById('nameError') },
    cpf: { input: document.getElementById('cpf'), error: document.getElementById('cpfError') },
    birth_date: { input: document.getElementById('birthDate'), error: document.getElementById('birthDateError') },
    phone: { input: document.getElementById('phone'), error: document.getElementById('phoneError') },
    health_unit_id: { input: healthUnitSelect, error: document.getElementById('healthUnitError') },
};

export function initPatientForm(onSubmit) {
    newPatientButton.addEventListener('click', () => openPatientForm());
    cancelButton.addEventListener('click', closePatientForm);
    closeFormButton.addEventListener('click', closePatientForm);
    patientDialog.addEventListener('close', clearFieldErrors);

    patientForm.addEventListener('submit', (event) => {
        event.preventDefault();
        clearFieldErrors();
        onSubmit(patientId.value, getFormData());
    });

    Object.values(fields).forEach(({ input, error }) => {
        input.addEventListener('input', () => clearFieldError(input, error));
    });
}

export function fillHealthUnits(units) {
    const options = units.map((unit) => {
        const option = document.createElement('option');
        option.value = unit.id;
        option.textContent = unit.name;

        return option;
    });

    healthUnitSelect.append(...options);
}

export function openPatientForm(patient = null) {
    const isEditing = patient !== null;

    patientForm.reset();
    clearFieldErrors();

    patientId.value = isEditing ? patient.id : '';
    formTitle.textContent = isEditing ? formTitle.dataset.titleEdit : formTitle.dataset.titleCreate;
    submitButton.textContent = isEditing ? submitButton.dataset.labelEdit : submitButton.dataset.labelCreate;
    cancelButton.hidden = !isEditing;
    fields.birth_date.input.max = new Date().toLocaleDateString('en-CA');

    if (isEditing) {
        fields.name.input.value = patient.name;
        fields.cpf.input.value = formatCpf(patient.cpf);
        fields.birth_date.input.value = patient.birth_date;
        fields.phone.input.value = formatPhone(patient.phone);
        fields.health_unit_id.input.value = patient.health_unit_id;
    }

    patientDialog.showModal();
    fields.name.input.focus();
}

export function closePatientForm() {
    patientDialog.close();
}

export function setFormSubmitting(isSubmitting) {
    submitButton.disabled = isSubmitting;

    if (isSubmitting) {
        submitButton.textContent = submitButton.dataset.labelSaving;
        return;
    }

    submitButton.textContent = patientId.value ? submitButton.dataset.labelEdit : submitButton.dataset.labelCreate;
}

export function showFieldErrors(errors) {
    let firstInvalidInput = null;

    Object.entries(errors).forEach(([name, messages]) => {
        const field = fields[name];

        if (!field) {
            return;
        }

        field.input.setAttribute('aria-invalid', 'true');
        field.error.textContent = messages[0];
        firstInvalidInput ??= field.input;
    });

    firstInvalidInput?.focus();
}

function getFormData() {
    return {
        name: fields.name.input.value.trim(),
        cpf: fields.cpf.input.value,
        birth_date: fields.birth_date.input.value,
        phone: fields.phone.input.value,
        health_unit_id: fields.health_unit_id.input.value,
    };
}

function clearFieldError(input, error) {
    input.removeAttribute('aria-invalid');
    error.textContent = '';
}

function clearFieldErrors() {
    Object.values(fields).forEach(({ input, error }) => clearFieldError(input, error));
}
