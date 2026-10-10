export function debounce(callback, delay) {
    let timerId = null;

    function debounced(...args) {
        clearTimeout(timerId);
        timerId = setTimeout(() => callback(...args), delay);
    }

    debounced.cancel = () => clearTimeout(timerId);

    return debounced;
}
