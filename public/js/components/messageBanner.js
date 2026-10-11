const messageBanner = document.getElementById('messageBanner');
const messageText = document.getElementById('messageText');
const messageClose = document.getElementById('messageClose');

const SUCCESS_DISPLAY_TIME = 5000;

let hideTimerId = null;

export function initMessageBanner() {
    messageClose.addEventListener('click', hideMessage);
}

export function showMessage(type, text) {
    clearTimeout(hideTimerId);

    messageText.textContent = text || messageBanner.dataset.fallback;
    messageBanner.classList.toggle('alert-success', type === 'success');
    messageBanner.classList.toggle('alert-danger', type === 'error');
    messageBanner.hidden = false;
    messageBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    if (type === 'success') {
        hideTimerId = setTimeout(hideMessage, SUCCESS_DISPLAY_TIME);
    }
}

export function hideMessage() {
    clearTimeout(hideTimerId);
    messageBanner.hidden = true;
}
