import { isOutsideDialog } from '../utils/dialog.js';

const deleteDialog = document.getElementById('deleteDialog');
const deleteName = document.getElementById('deleteName');
const cancelDeleteButton = document.getElementById('cancelDeleteButton');
const confirmDeleteButton = document.getElementById('confirmDeleteButton');

export function initDeleteDialog(onConfirm) {
    cancelDeleteButton.addEventListener('click', closeDeleteDialog);
    confirmDeleteButton.addEventListener('click', onConfirm);
    deleteDialog.addEventListener('click', (event) => {
        if (isOutsideDialog(event, deleteDialog) && !cancelDeleteButton.disabled) {
            closeDeleteDialog();
        }
    });
}

export function openDeleteDialog(patientName) {
    deleteName.textContent = patientName;
    confirmDeleteButton.disabled = false;
    deleteDialog.showModal();
    cancelDeleteButton.focus();
}

export function closeDeleteDialog() {
    deleteDialog.close();
}

export function setDeleteSubmitting(isSubmitting) {
    confirmDeleteButton.disabled = isSubmitting;
    cancelDeleteButton.disabled = isSubmitting;
}
