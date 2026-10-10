import { formatCpf, formatDate, formatPhone } from '../utils/formatters.js';

const tableBody = document.getElementById('patientTableBody');
const rowTemplate = document.getElementById('patientRowTemplate');

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

export function renderPatients(patients) {
    tableBody.replaceChildren(...patients.map(buildRow));
}
