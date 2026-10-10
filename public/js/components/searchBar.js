import { debounce } from '../utils/debounce.js';

const searchInput = document.getElementById('searchInput');
const searchClear = document.getElementById('searchClear');

export function initSearchBar(onSearch) {
    const debouncedSearch = debounce(() => onSearch(searchInput.value.trim()), 300);

    searchInput.addEventListener('input', () => {
        searchClear.hidden = searchInput.value === '';
        debouncedSearch();
    });

    searchClear.addEventListener('click', () => {
        debouncedSearch.cancel();
        searchInput.value = '';
        searchClear.hidden = true;
        searchInput.focus();
        onSearch('');
    });
}
