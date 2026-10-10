import { formatCpf, formatDate, formatPhone } from '../utils/formatters.js';

const tableBody = document.getElementById('patientTableBody');
const rowTemplate = document.getElementById('patientRowTemplate');

export function initPatientTable(onEdit, onDelete) {
    tableBody.addEventListener('click', (event) => {
        const button = event.target.closest('.edit-button, .delete-button');

        if (!button) {
            return;
        }

        const id = Number(button.closest('tr').dataset.id);

        if (button.classList.contains('edit-button')) {
            onEdit(id);
            return;
        }

        onDelete(id);
    });
}

export function renderPatients(patients) {
    tableBody.replaceChildren(...patients.map(buildRow));
}

function fillPhoneCell(cell, phone) {
    if (phone) {
        cell.textContent = formatPhone(phone);
        return;
    }

    const emptyLabel = document.createElement('span');
    emptyLabel.className = 'cell-empty';
    emptyLabel.textContent = cell.dataset.emptyText;
    cell.append(emptyLabel);
}

function buildRow(patient) {
    const row = rowTemplate.content.firstElementChild.cloneNode(true);

    row.dataset.id = patient.id;
    row.querySelector('.cell-name').textContent = patient.name;
    row.querySelector('.cell-cpf').textContent = formatCpf(patient.cpf);
    row.querySelector('.cell-birth-date').textContent = formatDate(patient.birth_date);
    row.querySelector('.cell-unit').textContent = patient.health_unit?.name ?? '';
    fillPhoneCell(row.querySelector('.cell-phone'), patient.phone);

    return row;
}
