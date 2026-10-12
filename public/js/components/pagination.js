const totalInfo = document.getElementById('totalInfo');
const pageInfo = document.getElementById('pageInfo');
const previousButton = document.getElementById('previousButton');
const nextButton = document.getElementById('nextButton');

export function initPagination(onPrevious, onNext) {
    previousButton.addEventListener('click', onPrevious);
    nextButton.addEventListener('click', onNext);
}

export function renderPagination(meta, links) {
    totalInfo.textContent = formatTotal(meta);
    pageInfo.textContent = `${pageInfo.dataset.pageLabel} ${meta.current_page} ${pageInfo.dataset.ofLabel} ${meta.last_page}`;
    previousButton.disabled = links.prev === null;
    nextButton.disabled = links.next === null;
}

export function disablePagination() {
    previousButton.disabled = true;
    nextButton.disabled = true;
}

function formatTotal(meta) {
    const totalLabel = meta.total === 1 ? totalInfo.dataset.singular : totalInfo.dataset.plural;

    if (meta.total === 0) {
        return `0 ${totalLabel}`;
    }

    const shownCount = meta.to - meta.from + 1;

    return `${shownCount} ${totalInfo.dataset.ofLabel} ${meta.total} ${totalLabel}`;
}
