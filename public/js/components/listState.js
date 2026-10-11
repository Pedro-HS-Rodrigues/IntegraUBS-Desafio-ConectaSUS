const tableWrapper = document.querySelector('.table-wrapper');
const loadingState = document.getElementById('loadingState');
const emptyState = document.getElementById('emptyState');
const errorState = document.getElementById('errorState');
const retryButton = document.getElementById('retryButton');

export function showListState(state) {
    tableWrapper.hidden = state !== 'data';
    loadingState.hidden = state !== 'loading';
    emptyState.hidden = state !== 'empty';
    errorState.hidden = state !== 'error';
}

export function onRetry(handler) {
    retryButton.addEventListener('click', handler);
}
