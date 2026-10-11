function onlyDigits(value) {
    return String(value ?? '').replace(/\D/g, '');
}

export function formatCpf(value) {
    return onlyDigits(value)
        .slice(0, 11)
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
}

export function formatPhone(value) {
    const digits = onlyDigits(value).slice(0, 11);

    if (digits.length === 0) {
        return '';
    }

    if (digits.length <= 2) {
        return `(${digits}`;
    }

    if (digits.length <= 6) {
        return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
    }

    if (digits.length <= 10) {
        return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`;
    }

    return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
}

export function formatDate(isoDate) {
    const [year, month, day] = String(isoDate).split('-');

    return `${day}/${month}/${year}`;
}
