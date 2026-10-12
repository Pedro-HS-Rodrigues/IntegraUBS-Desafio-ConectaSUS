export function isOutsideDialog(event, dialog) {
    if (event.target !== dialog) {
        return false;
    }

    const rect = dialog.getBoundingClientRect();

    return (
        event.clientX < rect.left ||
        event.clientX > rect.right ||
        event.clientY < rect.top ||
        event.clientY > rect.bottom
    );
}
