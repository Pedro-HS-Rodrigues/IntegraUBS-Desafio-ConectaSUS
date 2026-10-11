const totalInfo = document.getElementById('totalInfo');
const pageInfo = document.getElementById('pageInfo');
const previousButton = document.getElementById('previousButton');
const nextButton = document.getElementById('nextButton');

export function initPagination(onPrevious, onNext) {
    previousButton.addEventListener('click', onPrevious);
    nextButton.addEventListener('click', onNext);
}

export function renderPagination(meta, links) {
    const totalLabel = meta.total === 1 ? totalInfo.dataset.singular : totalInfo.dataset.plural;

    totalInfo.textContent = `${meta.total} ${totalLabel}`;
    pageInfo.textContent = `${pageInfo.dataset.pageLabel} ${meta.current_page} ${pageInfo.dataset.ofLabel} ${meta.last_page}`;
    previousButton.disabled = links.prev === null;
    nextButton.disabled = links.next === null;
}

export function disablePagination() {
    previousButton.disabled = true;
    nextButton.disabled = true;
}
