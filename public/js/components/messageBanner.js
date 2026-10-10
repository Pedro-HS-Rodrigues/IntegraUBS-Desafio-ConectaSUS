const messageBanner = document.getElementById('messageBanner');
const messageText = document.getElementById('messageText');
const messageClose = document.getElementById('messageClose');

export function initMessageBanner() {
    messageClose.addEventListener('click', hideMessage);
}

export function showMessage(type, text) {
    messageText.textContent = text || messageBanner.dataset.fallback;
    messageBanner.classList.toggle('alert-success', type === 'success');
    messageBanner.classList.toggle('alert-danger', type === 'error');
    messageBanner.hidden = false;
    messageBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

export function hideMessage() {
    messageBanner.hidden = true;
}
