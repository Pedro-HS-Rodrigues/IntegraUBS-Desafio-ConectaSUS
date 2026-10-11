export function isOutsideDialog(event, dialog) {
    const rect = dialog.getBoundingClientRect();

    return (
        event.clientX < rect.left ||
        event.clientX > rect.right ||
        event.clientY < rect.top ||
        event.clientY > rect.bottom
    );
}
