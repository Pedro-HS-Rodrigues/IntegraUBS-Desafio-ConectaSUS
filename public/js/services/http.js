export async function request(method, url, body = null) {
    const options = {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    };

    if (body !== null) {
        options.body = JSON.stringify(body);
    }

    let response;

    try {
        response = await fetch(url, options);
    } catch {
        throw buildError(0, '', {});
    }

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw buildError(response.status, data.message ?? '', data.errors ?? {});
    }

    return data;
}

function buildError(status, message, errors) {
    const error = new Error(message);
    error.status = status;
    error.errors = errors;

    return error;
}
